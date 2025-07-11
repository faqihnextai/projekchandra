<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use App\Models\User; // Pastikan ini ada, jika tidak pakai model User, gunakan DB

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cara 1: Menggunakan Model User (lebih disarankan)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Password-nya "password"
            'email_verified_at' => now(), // Untuk menandakan email sudah diverifikasi
        ]);

        // Cara 2: Menggunakan DB Facade (opsional, jika tidak ingin pakai Model User)
        // DB::table('users')->insert([
        //     'name' => 'Admin User 2',
        //     'email' => 'admin2@example.com',
        //     'password' => Hash::make('password'),
        //     'email_verified_at' => now(),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);
    }
}