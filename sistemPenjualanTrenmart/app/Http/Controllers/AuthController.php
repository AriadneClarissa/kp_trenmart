<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * 1. PENDAFTARAN MANUAL (One-Stop Registration)
     * Langsung meminta data lengkap agar tidak muncul layar 'Pilih Jenis' lagi.
     */
    public function register(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        // Tambahkan regex: wajib angka dan diawali 08
        'phone_number' => ['required', 'string', 'min:10', 'max:13', 'regex:/^08[0-9]{8,11}$/'],
        'home_address' => 'required|string|max:500',
    ];

    if ($request->customer_type === 'langganan') {
        $rules['organization_name'] = 'required|string|max:255';
        $rules['organization_type'] = 'required|string|max:255';
    }

    $validated = $request->validate($rules, [
        'phone_number.regex' => 'Nomor WhatsApp harus diawali dengan 08.',
    ]);

        $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'customer',
        'phone_number' => $validated['phone_number'],
        'home_address' => $validated['home_address'],
        'is_active' => true,
    ]);
        // Log the user in or redirect to login with success
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk.');
    }

    /**
     * 2. LOGIN MANUAL
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login'    => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['login'])
            ->orWhere('kd_pelanggan', $credentials['login'])
            ->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (!$user->isActive()) {
                return back()->withErrors(['login' => 'Akun Anda telah dinonaktifkan oleh pemilik sistem.'])->onlyInput('login');
            }

            Auth::login($user, $request->boolean('remember'));

            if ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda ditolak oleh admin.');
            }

            if ($user->isCashier()) {
                return redirect()->route('admin.orders.index');
            }

            return redirect()->intended(route('beranda'));
        }

        return back()->withErrors(['login' => 'Email atau kode pelanggan tidak sesuai.'])->onlyInput('login');
    }

    /**
     * 3. GOOGLE AUTHENTICATION
     */
    public function redirectToGoogle()
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            return redirect()->route('login')->with('error', 'Login Google belum dikonfigurasi. Isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET terlebih dahulu.');
        }

        return Socialite::driver('google')
            ->redirectUrl(url('/auth/google/callback'))
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(url('/auth/google/callback'))
                ->user();
                
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Ini eksekusi jika dia mendaftar pertama kali pakai Google
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(str()->random(16)), 
                    'role' => 'customer',
                    'google_id' => $googleUser->id,
                    'is_approved' => true, 
                    'is_active' => true,
                ]);
            }

            if (!$user->isActive()) {
                return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan oleh pemilik sistem.');
            }

            Auth::login($user);
            
            // PENTING: Arahkan ke fungsi ini untuk di-screening
            return $this->handleRedirectAfterLogin($user);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal masuk menggunakan Google.']);
        }
    }

    public function handlePilihJenis(Request $request)
    {
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Jika data profil belum lengkap (misal: user baru dari Google belum isi nomor HP)
        if (empty($user->phone_number)) {
            // Langsung arahkan ke form umum
            return redirect()->route('form.umum');
        }

        // Default: Ke Beranda (User yang profilnya sudah lengkap)
        return redirect()->route('beranda');
    }
    /**
     * 4. CENTRALIZED REDIRECT LOGIC
     * Fungsi kunci untuk menghilangkan layar ganda.
     */
    private function handleRedirectAfterLogin($user)
    {
        // Jika Pemilik toko
        if ($user->isOwner()) {
            return redirect()->route('beranda');
        }

        if (method_exists($user, 'isCashier') && $user->isCashier()) {
            return redirect()->route('admin.orders.index');
        }

        // Jika Admin biasa
        if ($user->isAdmin()) {
            return redirect()->route('beranda');
        }

        // Jika data profil belum lengkap (misal: user baru dari Google belum isi nomor HP)
        if (empty($user->phone_number)) {
            // Langsung arahkan ke form umum
            return redirect()->route('form.umum');
        }
        // Default: Ke Beranda (Regular atau Langganan yang sudah aktif)
        return redirect()->route('beranda');
    }

    /**
     * 5. UPDATE PROFIL (Khusus Alur Google / Lengkapi Data)
     */
    public function updateProfileAfterGoogle(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Sesuaikan pengecekan dengan name input dari form Anda
        $isLangganan = ($user->customer_type === 'langganan'); 

        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|min:10|max:15',
            'home_address' => 'required|string',
        ];

        $validated = $request->validate($rules);
        
        // Update data profil dan setujui akun
        $user->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'home_address' => $validated['home_address'],
            'is_approved' => true, 
        ]);
        return redirect()->route('beranda');
    }

    /**
     * 6. PROFIL UMUM (Untuk halaman edit profil mandiri)
     */
    public function profile()
    {
        return view('auth.profil', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if (!$user->isAdmin()) {
            $rules['phone_number'] = 'required|string|min:10|max:15';
            $rules['home_address'] = 'required|string|max:500';
        }

        if ($user->customer_type === 'langganan' && !$user->isAdmin()) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
            $rules['organization_name'] = 'required|string|max:255';
            $rules['organization_type'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $user->update($validated);
        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Update user's password from profile
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }

    /**
     * 7. ADMIN FEATURES
     */
    public function adminDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Sekarang $user->isAdmin() tidak akan merah lagi
        if (!$user || !$user->isAdmin()) { 
            abort(403); 
        }

        // Fetch sales data for the last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $salesData = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as order_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Convert to format for chart
        $chartLabels = [];
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::now()->subDays($i)->format('d/m');
            $revenue = $salesData->firstWhere('date', $date)?->revenue ?? 0;
            $chartData[] = floatval($revenue);
        }

        // Calculate metrics
        $totalRevenue = Order::where('created_at', '>=', $thirtyDaysAgo)->sum('total');
        $totalOrders = Order::where('created_at', '>=', $thirtyDaysAgo)->count();
        $averageOrderValue = $totalOrders > 0 ? round($totalRevenue / $totalOrders, 2) : 0;

        // Order status breakdown
        $statusBreakdown = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        $pendingUsers = User::where('customer_type', 'langganan')
                            ->where('is_approved', false)
                            ->get();
        $allUsers = User::all();
        $methods = PaymentMethod::orderBy('created_at', 'desc')->get();

        return view('admin.dashboard', compact(
            'pendingUsers', 
            'allUsers', 
            'methods',
            'chartLabels',
            'chartData',
            'totalRevenue',
            'totalOrders',
            'averageOrderValue',
            'statusBreakdown'
        ));
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);

        $this->sendReviewStatusEmail($user, 'accepted');

        return back()->with('success', 'User telah disetujui.');
    }

    public function reject($id)
    {
        $user = \App\Models\User::findOrFail($id);

        $this->sendReviewStatusEmail($user, 'rejected');
        
        // Hapus permanen agar tidak bisa login Google lagi dengan akun yang sama
        $user->delete(); 

        return redirect()->back()->with('success', 'Pendaftaran pelanggan telah ditolak.');
    }
    
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_detail', compact('user'));
    }

    /**
     * 8. OTHER HELPERS
     */
    public function statusTinjau()
    {
        /** @var \App\Models\User $user */ 
        $user = Auth::user();

        if (!$user || !$user->isPendingMember()) {
            return redirect()->route('beranda');
        }
        return view('auth.status_tinjau', compact('user'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function sendReviewStatusEmail(User $user, string $status): void
    {
        $isAccepted = $status === 'accepted';

        $subject = $isAccepted
            ? 'Admin Trenmart: Akun Anda Diterima'
            : 'Admin Trenmart: Akun Anda Ditolak';

        $loginUrl = url('/login');

        $message = $isAccepted
            ? "Halo {$user->name},\n\nPendaftaran akun pelanggan langganan Anda telah kami tinjau dan dinyatakan DITERIMA.\nAkun Anda sekarang sudah aktif dan dapat digunakan untuk login ke sistem Trenmart.\n\nSilakan masuk melalui tautan berikut:\n{$loginUrl}\n\nTerima kasih telah bergabung bersama Trenmart.\n\nSalam,\nAdmin Trenmart"
            : "Halo {$user->name},\n\nPendaftaran akun pelanggan langganan Anda telah kami tinjau dan untuk saat ini dinyatakan DITOLAK.\nJika Anda ingin mengajukan ulang, silakan cek informasi akun atau hubungi CS Trenmart.\n\nAnda dapat mengunjungi halaman login di:\n{$loginUrl}\n\nSalam,\nAdmin Trenmart";

        try {
            Mail::raw($message, function ($mail) use ($user, $subject) {
                $mail->to($user->email)
                    ->subject($subject);
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function updateBanner(Request $request)
    {
        $request->validate([
            'tentang_banner' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('tentang_banner')) {
            $path = $request->file('tentang_banner')->store('banners', 'public');
            $user->tentang_banner = $path;
            $user->save();

            return back()->with('success', 'Banner berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal upload.');
    }

    // Alur penunjang view pilih jenis
    public function showPilihJenis() { return view('auth.pilih_jenis'); }
    public function formUmum() { return view('auth.form_umum', ['user' => Auth::user()]); }
    public function formLangganan() { return view('auth.form_langganan', ['user' => Auth::user()]); }
}