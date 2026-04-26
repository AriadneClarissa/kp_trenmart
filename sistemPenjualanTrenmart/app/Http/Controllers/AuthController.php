<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite; // Tambahkan ini untuk Google Auth

class AuthController extends Controller
{
    /**
     * Menangani Pendaftaran (Sign Up)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
            'customer_type' => 'required|in:regular,langganan',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', 
            'customer_type' => $request->customer_type,
            'is_approved' => ($request->customer_type === 'regular') ? true : false,
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk.');
    }

    /**
     * Menangani Login Manual
     */
    public function login(Request $request)
    {
    // 1. Validasi menggunakan email
    $credentials = $request->validate([
        'email'    => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // 2. Proteksi Langganan (Tetap ada)
        if ($user->customer_type === 'langganan' && !$user->is_approved) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun langganan Anda sedang menunggu verifikasi admin.']);
        }

        $request->session()->regenerate();

        // 3. LOGIKA REDIRECT: Jika admin, ke dashboard internal
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Jika user biasa, ke beranda
        return redirect()->route('beranda'); 
    }

    // Jika gagal, kembalikan ke input email
    return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
    }

    // Tambahkan fungsi untuk menampilkan halaman login admin
    public function login_admin()
    {
        return view('auth.login_admin'); // Pastikan nama file blade sesuai
    }

    /**
     * --- FITUR GOOGLE AUTH ---
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan email, jika tidak ada maka buat baru
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make('password_google_default'), // Password dummy
                    'role' => 'customer',
                    'customer_type' => 'regular', // User google defaultnya regular
                    'is_approved' => true,
                    'google_id' => $googleUser->id,
                ]);
            }

            Auth::login($user);
            return redirect()->route('beranda');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['name' => 'Gagal masuk menggunakan Google.']);
        }
    }

    /**
     * Dashboard Admin: Menampilkan Persetujuan & Daftar User
     */
    public function adminDashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        // 1. Ambil data langganan yang butuh approval
        $pendingUsers = User::where('customer_type', 'langganan')
                            ->where('is_approved', false)
                            ->get();

        // 2. Ambil semua user untuk dikelola rolenya
        $allUsers = User::all();

        return view('admin.dashboard', compact('pendingUsers', 'allUsers'));
    }

    /**
     * Mengubah User biasa menjadi Admin lewat UI
     */
    public function promoteToAdmin($id)
    {
        if (Auth::user()->role !== 'admin') { abort(403); }

        $user = User::findOrFail($id);
        $user->update(['role' => 'admin']);

        return back()->with('success', 'Berhasil! ' . $user->name . ' sekarang memiliki akses Admin.');
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);
        return back()->with('success', 'User ' . $user->name . ' telah disetujui.');
    }

    public function profile()
    {
        return view('auth.profil', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'home_address' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail(Auth::id());
        $user->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'] ?? null,
            'home_address' => $validated['home_address'] ?? null,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function editJudul() 
    {
    return view('admin.judul_edit'); 
}
}