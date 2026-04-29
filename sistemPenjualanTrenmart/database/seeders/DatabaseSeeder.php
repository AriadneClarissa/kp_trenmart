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
        User::updateOrCreate(
            ['email' => 'admintrenmart@gmail.com'],
            [
                'name' => 'Admin Trenmart',
                'password' => bcrypt('TAS102^&'), // Gunakan password ini
                'role' => 'admin',
                'is_approved' => true,
            ]
        );
    }
}
