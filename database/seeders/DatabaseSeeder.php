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
     *
     * Akun Demo (hanya untuk testing/development):
     * ┌─────────────┬──────────────────────┬──────────┐
     * │ Role        │ Email                │ Password │
     * ├─────────────┼──────────────────────┼──────────┤
     * │ Admin       │ admin@gmail.com      │ password │
     * │ User        │ user@gmail.com       │ password │
     * │ Pemilik     │ pemilik@toko.com     │ password │
     * └─────────────┴──────────────────────┴──────────┘
     */
    public function run(): void
    {
        // Sample Admin & User
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin SPK',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Biasa',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        User::firstOrCreate(
            ['email' => 'pemilik@toko.com'],
            [
                'name' => 'Pemilik Demo',
                'password' => bcrypt('password'),
                'role' => 'pemilik_toko',
            ]
        );

        $this->call([
            AdminSeeder::class,
            KriteriaSeeder::class,
            TokoSeeder::class,
            NilaiAlternatifSeeder::class,
        ]);
    }
}
