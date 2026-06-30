<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Administrator
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        // BK (Bimbingan Konseling)
        User::updateOrCreate(
            ['email' => 'bk@gmail.com'],
            [
                'name' => 'BK User',
                'password' => Hash::make('bk'),
                'role' => 'bk',
                'status' => 'aktif',
            ]
        );

        // BKK (Bursa Kerja Khusus)
        User::updateOrCreate(
            ['email' => 'bkk@gmail.com'],
            [
                'name' => 'BKK User',
                'password' => Hash::make('bkk'),
                'role' => 'bkk',
                'status' => 'aktif',
            ]
        );

        // Admin Jurusan (Khusus Jurusan RPL)
        User::updateOrCreate(
            ['email' => 'adminjurusan@gmail.com'],
            [
                'name' => 'Admin Jurusan',
                'password' => Hash::make('adminjurusan'),
                'role' => 'admin_jurusan',
                'status' => 'aktif',
            ]
        );
    }
}
