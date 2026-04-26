<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    \App\Models\User::create([
        'name' => 'Admin Trenmart',
        'email' => 'admintrenmart@gmail.com', // Gunakan email ini untuk login
        'password' => bcrypt('TAS102^&'), // Gunakan password ini
        'role' => 'admin',
        'is_approved' => true,
    ]);
    }
}
