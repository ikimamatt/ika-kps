<?php

namespace Database\Seeders;

use App\Models\Alumnus;
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
        $alumniUser = User::updateOrCreate(
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

        Alumnus::updateOrCreate(
            ['user_id' => $alumniUser->id],
            [
                'name' => 'Rangga Perkasa',
                'slug' => 'rangga-perkasa-2015',
                'title' => 'S.Kom.',
                'level' => 'sma',
                'class_year' => "'15",
                'full_year' => '2015',
                'profession' => 'Senior Software Engineer',
                'institution' => 'Tech Nusantara',
                'domicile' => 'Balikpapan',
                'summary' => 'Alumni SMA KPS angkatan 2015. Aktif berkarir di bidang rekayasa perangkat lunak dan ekosistem digital Kaltim.',
                'email' => 'alumni@ikakps.org',
                'phone' => '08125000003',
                'location' => 'Balikpapan Kota',
                'linkedin_url' => 'https://linkedin.com/in/rangga-perkasa',
                'instagram_handle' => '@ranggaperkasa',
                'avatar_url' => 'https://ui-avatars.com/api/?name=Rangga+Perkasa',
                'is_verified' => true,
                'verified_at' => now(),
            ]
        );
    }
}
