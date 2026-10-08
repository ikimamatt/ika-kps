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
        // 1. Akun Super Administrator
        User::updateOrCreate(
            ['email' => 'admin@ikakps.org'],
            [
                'name' => 'Super Administrator IKA KPS',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0811540001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Pengurus Organisasi
        User::updateOrCreate(
            ['email' => 'pengurus@ikakps.org'],
            [
                'name' => 'Sekretariat IKA KPS',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
                'phone' => '0811540002',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Sampel Alumni Biasa
        User::updateOrCreate(
            ['email' => 'alumni@ikakps.org'],
            [
                'name' => 'Rangga Perkasa',
                'password' => Hash::make('password'),
                'role' => 'alumni',
                'phone' => '08125000003',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
