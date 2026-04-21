<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
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
    }
}