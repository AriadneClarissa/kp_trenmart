<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menangani Pendaftaran (Sign Up)
    public function register(Request $request)
    {
        // 1. Validasi Input (Menambahkan 'confirmed' untuk keamanan)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Mencari input 'password_confirmation'
            'customer_type' => 'required|in:regular,langganan',
        ]);

        // 2. Buat User Baru
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', 
            'customer_type' => $request->customer_type,
            'is_approved' => ($request->customer_type === 'regular') ? true : false,
        ]);

        // 3. JANGAN Auth::login($user); agar user tidak otomatis masuk.

        // 4. Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk menggunakan akun Anda.');
    }

    // Menangani Login
    public function login(Request $request)
        {
    // Validasi menggunakan 'name'
    $credentials = $request->validate([
        'name' => ['required', 'string'], // Berubah dari email ke name
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // JIKA ADMIN: Ke Dashboard Admin
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        // JIKA CUSTOMER: Ke Beranda (/)
        return redirect()->intended('/'); 
    }

    // Jika gagal, kembali ke login dengan pesan error
    return back()->withErrors([
        'email' => 'Email atau password tidak sesuai.',
    ])->onlyInput('email');
    }

    // Menangani Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Tampilan Dashboard Admin (List Approval)
    public function adminDashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        // Ambil customer langganan yang belum di-approve
        $pendingUsers = User::where('customer_type', 'langganan')
                            ->where('is_approved', false)
                            ->get();

        return view('admin.dashboard', compact('pendingUsers'));
    }

    // Proses Approval User Langganan oleh Admin
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);

        return back()->with('success', 'User ' . $user->name . ' sekarang mendapatkan harga khusus.');
    }

    public function up()
    {
    Schema::table('users', function (Blueprint $table) {
        $table->string('no_telp')->nullable()->after('email');
        $table->text('alamat')->nullable()->after('no_telp');
        $table->string('kode_pos', 10)->nullable()->after('alamat');
    });
    }
}