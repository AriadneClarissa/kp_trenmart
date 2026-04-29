<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
            'customer_type' => 'required|in:regular,langganan',
            'phone_number' => 'required|string|min:10|max:15',
            'home_address' => 'required|string|max:500',
        ];

        // Jika memilih langganan, wajib isi data organisasi
        if ($request->customer_type === 'langganan') {
            $rules['organization_name'] = 'required|string|max:255';
            $rules['organization_type'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'customer_type' => $validated['customer_type'],
            'phone_number' => $validated['phone_number'],
            'home_address' => $validated['home_address'],
            'organization_name' => $validated['organization_name'] ?? null,
            'organization_type' => $validated['organization_type'] ?? null,
            'is_approved' => ($validated['customer_type'] === 'regular'), // Regular langsung aktif
        ]);

        if ($user->customer_type === 'langganan') {
            Auth::login($user);

            return redirect()->route('status.tinjau')
                ->with('info', 'Pendaftaran berhasil. Akun Anda sedang ditinjau oleh admin.');
        }

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk.');
    }

    /**
     * 2. LOGIN MANUAL
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Di logic Login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->status === 'rejected') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Akun Anda ditolak oleh admin.');
            }

            if ($user->isPendingMember()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Akun Anda sedang ditinjau oleh admin.');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
    }

    /**
     * 3. GOOGLE AUTHENTICATION
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->with(['prompt' => 'select_account']) 
        ->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(str()->random(16)), 
                    'role' => 'customer',
                    'google_id' => $googleUser->id,
                    'is_approved' => false, 
                ]);
            }

            // PERBAIKAN: Jika tipe pelanggan masih kosong, paksa pilih jenis dlu
            if (empty($user->customer_type)) {
                Auth::login($user);
                return redirect()->route('pilih.jenis');
            }

            // Jika dia langganan tapi belum disetujui, jangan biarkan login
            if ($user->customer_type === 'langganan' && !$user->is_approved) {
                return redirect()->route('status.tinjau')->with('info', 'Akun Anda sedang ditinjau.');
            }

            Auth::login($user);
            return $this->handleRedirectAfterLogin($user);

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal masuk menggunakan Google.']);
        }
    }

    public function handlePilihJenis(Request $request)
    {
        // Sesuaikan validasi dengan name="jenis" dari Blade
        $request->validate([
            'jenis' => 'required|in:regular,langganan',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Update data user di database
        $user->update([
            'customer_type' => $request->jenis,
            // Regular langsung aktif (true), Langganan butuh persetujuan (false)
            'is_approved' => ($request->jenis === 'regular')
        ]);

        // Redirect berdasarkan pilihan
        if ($request->jenis === 'langganan') {
            return redirect()->route('form.langganan');
        }

        return redirect()->route('form.umum');
    }
    /**
     * 4. CENTRALIZED REDIRECT LOGIC
     * Fungsi kunci untuk menghilangkan layar ganda.
     */
    private function handleRedirectAfterLogin($user)
    {
        // Jika Admin
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Jika data profil kosong (Biasanya user baru dari Google)
        if (empty($user->customer_type) || empty($user->phone_number)) {
            return redirect()->route('pilih.jenis');
        }

        // Jika Member Langganan tapi belum disetujui Admin
        if ($user->isPendingMember()) {
            return redirect()->route('status.tinjau');
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

        if ($isLangganan) {
            $rules['organization_name'] = 'required|string|max:255';
            $rules['organization_type'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Update profil dan status persetujuan
        $user->update(array_merge($validated, [
            'is_approved' => !$isLangganan, 
        ]));

        if ($isLangganan) {
            // JANGAN logout di sini agar data di halaman status.tinjau bisa terbaca
            return redirect()->route('status.tinjau');
        }

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

        $rules = ['name' => 'required|string|max:255'];

        if (!$user->isAdmin()) {
            $rules['phone_number'] = 'required|string|min:10|max:15';
            $rules['home_address'] = 'required|string|max:500';
        }

        if ($user->customer_type === 'langganan' && !$user->isAdmin()) {
            $rules['organization_name'] = 'required|string|max:255';
            $rules['organization_type'] = 'required|string|max:255';
        }

        $user->update($request->validate($rules));
        return back()->with('success', 'Profil berhasil diperbarui!');
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

        $pendingUsers = User::where('customer_type', 'langganan')
                            ->where('is_approved', false)
                            ->get();
        $allUsers = User::all();

        return view('admin.dashboard', compact('pendingUsers', 'allUsers'));
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

    // Alur penunjang view pilih jenis
    public function showPilihJenis() { return view('auth.pilih_jenis'); }
    public function formUmum() { return view('auth.form_umum', ['user' => Auth::user()]); }
    public function formLangganan() { return view('auth.form_langganan', ['user' => Auth::user()]); }
}