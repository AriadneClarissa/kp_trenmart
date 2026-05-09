<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * AKUN LOGIN UNTUK DEV/TEST
     * 
     * Pemilik (Owner) - Akses Penuh:
     *   Email: owner@trenmart.com
     *   Password: owner123
     * 
     * Admin - Akses Terbatas:
     *   Email: admin@trenmart.com
     *   Password: admin123
     */
    public function run(): void
    {
        // Membuat akun Admin utama
        User::create([
            'name' => 'Admin Trenmart',
            'email' => 'admin@trenmart.com',
            'password' => Hash::make('admin123'), // Ini password untuk login
            'role' => 'admin',
            'customer_type' => 'regular',
            'is_approved' => true,
        ]);

        // Membuat akun Pemilik (owner)
        User::create([
            'name' => 'Pemilik Trenmart',
            'email' => 'owner@trenmart.com',
            'password' => Hash::make('owner123'),
            'role' => 'owner',
            'customer_type' => 'regular',
            'is_approved' => true,
        ]);
    }
}