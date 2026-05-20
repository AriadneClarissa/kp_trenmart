<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')->orderBy('created_at', 'desc')->get();
        return view('admin.customers.index', compact('customers'));
    }

    // Pelanggan
    public function create()
    {
        return view('admin.users.create');
    }

    // Admin
    public function createAdmin()
    {
        return view('admin.admins.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'kd_pelanggan' => 'required|string|max:50|unique:users,kd_pelanggan',
            'customer_type' => 'required|in:regular,langganan',
            'organization_name' => 'nullable|string|max:255',
            'organization_type' => 'nullable|string|max:255',
        ]);

        // Default password generated or provided
        $defaultPassword = $request->input('default_password') ?: \Illuminate\Support\Str::random(10);
        $generatedEmail = strtolower(trim($data['kd_pelanggan'])) . '@trenmart.local';

        $user = User::create([
            'name' => $data['name'],
            'email' => $generatedEmail,
            'kd_pelanggan' => $data['kd_pelanggan'],
            'password' => \Illuminate\Support\Facades\Hash::make($defaultPassword),
            'role' => 'customer',
            'customer_type' => $data['customer_type'],
            'organization_name' => $data['organization_name'] ?? null,
            'organization_type' => $data['organization_type'] ?? null,
            'is_approved' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pelanggan berhasil dibuat.');
    }

    // Store Admin Account
    public function storeAdmin(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,kasir',
            'send_email' => 'nullable|boolean',
        ]);

        // Default password generated or provided
        $defaultPassword = $request->input('default_password') ?: \Illuminate\Support\Str::random(10);

        $admin = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($defaultPassword),
            'role' => $data['role'],
            'is_approved' => true,
        ]);

        try {
            ActivityLog::create([
                'actor_id' => Auth::id(),
                'action' => 'create_internal_user',
                'details' => 'Created user ' . $admin->email . ' with role ' . $data['role'],
                'ip_address' => $request->ip(),
                'subject_type' => 'user',
                'subject_id' => $admin->id,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }

        // Optional: send email with credentials
        if ($request->boolean('send_email')) {
            try {
                $roleLabel = $data['role'] === 'kasir' ? 'Kasir' : 'Admin';
                \Illuminate\Support\Facades\Mail::raw("Halo {$admin->name},\n\nAkun {$roleLabel} Anda telah dibuat.\nEmail: {$admin->email}\nPassword: {$defaultPassword}\n\nSilakan masuk di: " . url('/login') . " dan segera ganti kata sandi melalui halaman profil.", function ($m) use ($admin, $roleLabel) {
                    $m->to($admin->email)->subject('Akun ' . $roleLabel . ' Trenmart');
                });
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Akun ' . $data['role'] . ' berhasil dibuat.');
    }
}
