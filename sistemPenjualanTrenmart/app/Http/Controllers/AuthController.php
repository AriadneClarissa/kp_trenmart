<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Menangani Pendaftaran Manual
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
            'is_approved' => ($request->customer_type === 'regular'),
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan masuk.');
    }

    /**
     * Menangani Login Manual
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $request->session()->regenerate();

            // Prioritas 1: Jika Admin, langsung ke Dashboard
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Prioritas 2: Cek kelengkapan data Profil (WhatsApp)
            if (empty($user->phone_number)) {
                return redirect()->route('pilih.jenis'); 
            }

            // Prioritas 3: Cek jika status masih pending (Khusus Langganan)
            if ($user->isPendingMember()) {
                return redirect()->route('status.tinjau');
            }

            return redirect()->route('beranda'); 
        }

        return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
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

            Auth::login($user);
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if (empty($user->phone_number)) {
                return redirect()->route('pilih.jenis');
            }

            if ($user->isPendingMember()) {
                return redirect()->route('status.tinjau');
            }

            return redirect()->route('beranda');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal masuk menggunakan Google.']);
        }
    }

    /**
     * Menangani Update Profil Berdasarkan Form (Lengkapi Data)
     */
    public function updateProfileAfterGoogle(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->customer_type === 'langganan') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|min:10|max:13', // Revisi format Indonesia
                'home_address' => 'required|string',
                'organization_name' => 'required|string|max:255',
                'organization_type' => 'required|string',
            ]);

            $user->update(array_merge($validated, [
                'customer_type' => 'langganan',
                'is_approved' => false, 
            ]));

            return redirect()->route('status.tinjau');
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|min:10|max:13', // Revisi format Indonesia
                'home_address' => 'required|string',
            ]);

            $user->update(array_merge($validated, [
                'customer_type' => 'regular',
                'is_approved' => true, 
            ]));

            return redirect()->route('beranda');
        }
    }

    /**
     * Halaman Status Tinjauan 
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

    /**
     * Alur Pemilihan Jenis & Form
     */
    public function showPilihJenis()
    {
        return view('auth.pilih_jenis');
    }

    public function handlePilihJenis(Request $request)
    {
        $request->validate(['jenis' => 'required|in:regular,langganan']);

        return ($request->jenis === 'langganan') 
            ? redirect()->route('form.langganan') 
            : redirect()->route('form.umum');
    }

    public function formUmum()
    {
        return view('auth.form_umum', ['user' => Auth::user()]);
    }

    public function formLangganan()
    {
        return view('auth.form_langganan', ['user' => Auth::user()]);
    }

    /**
     * Fitur Admin Dashboard
     */
    public function adminDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak.');
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
        return back()->with('success', 'User ' . $user->name . ' telah disetujui.');
    }

    public function promoteToAdmin($id)
    {
        /** @var \App\Models\User $me */
        $me = Auth::user();
        if (!$me || $me->role !== 'admin') { abort(403); }

        $user = User::findOrFail($id);
        $user->update(['role' => 'admin']);
        return back()->with('success', 'Berhasil mempromosikan Admin.');
    }

    /**
     * Profil & Logout
     */
    public function profile()
    {
        return view('auth.profil', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
    /** @var \App\Models\User $user */
    $user = Auth::user();

    // 1. Aturan Dasar untuk SEMUA
    $rules = ['name' => 'required|string|max:255'];

    // 2. Tambah Aturan jika BUKAN Admin
    if (!$user->isAdmin()) {
        $rules['phone_number'] = 'required|string|min:10|max:13';
        $rules['home_address'] = 'required|string|max:500';
    }

    // 3. Tambah Aturan khusus LANGGANAN
    if ($user->customer_type === 'langganan' && !$user->isAdmin()) {
        $rules['organization_name'] = 'required|string|max:255';
        $rules['organization_type'] = 'required|string|max:255';
    }

    $validated = $request->validate($rules);
    $user->update($validated);

    return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}