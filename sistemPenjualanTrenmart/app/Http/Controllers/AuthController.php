<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Menangani Pendaftaran (Sign Up)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
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

        // 3. Arahkan ke login (user tidak otomatis login sebelum disetujui jika tipe langganan)
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk menggunakan akun Anda.');
    }

    /**
     * Menangani Login
     */
    public function login(Request $request)
    {
        // Validasi menggunakan 'name' sesuai kebutuhan form Anda
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Admin diarahkan ke 'beranda' agar Panel Kontrol Admin muncul
            return redirect()->route('beranda'); 
        }

        // Jika gagal, kembali dengan error pada input 'name'
        return back()->withErrors([
            'name' => 'Nama atau password tidak sesuai.',
        ])->onlyInput('name');
    }

    /**
     * Menangani Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Tampilan Dashboard Admin (List Approval)
     */
    public function adminDashboard()
    {
        // Proteksi tambahan jika middleware belum terpasang sempurna
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }

        // Ambil customer langganan yang belum di-approve
        $pendingUsers = User::where('customer_type', 'langganan')
                            ->where('is_approved', false)
                            ->get();

        return view('admin.dashboard', compact('pendingUsers'));
    }

    /**
     * Proses Approval User Langganan oleh Admin
     */
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_approved' => true]);

        return back()->with('success', 'User ' . $user->name . ' sekarang mendapatkan harga khusus.');
    }
}