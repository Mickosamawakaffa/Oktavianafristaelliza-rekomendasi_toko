<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin default untuk production.
     * Jalankan dengan: php artisan db:seed --class=AdminSeeder
     *
     * PENTING: Ganti password ini setelah pertama kali login!
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@thriftjember.com'],
            [
                'name'     => 'Admin Thrift Jember',
                'password' => Hash::make('Adm!n@Th3Jmb#2026'),
                'role'     => 'admin',
            ]
        );

        $this->command->info('✅ Akun admin berhasil dibuat:');
        $this->command->info('   Email    : admin@thriftjember.com');
        $this->command->info('   Password : Adm!n@Th3Jmb#2026');
        $this->command->warn('   ⚠️  Segera ganti password setelah login pertama!');
    }
}
