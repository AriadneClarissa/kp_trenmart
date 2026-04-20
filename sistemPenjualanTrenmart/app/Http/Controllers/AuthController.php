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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'customer_type' => 'required|in:regular,langganan',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', // Default pendaftar adalah customer
            'customer_type' => $request->customer_type,
            // Jika pilih regular langsung aktif, jika langganan harus di-approve (false)
            'is_approved' => ($request->customer_type === 'regular') ? true : false,
        ]);

        Auth::login($user);

        return redirect()->route('beranda')->with('success', 'Pendaftaran berhasil!');
    }

    // Menangani Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika Admin, arahkan ke Dashboard Admin
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

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
}