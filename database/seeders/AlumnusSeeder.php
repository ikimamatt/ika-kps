<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use Illuminate\Database\Seeder;

class AlumnusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curatedAlumni = [
            [
                'name' => 'Ir. Bambang Trihatmojo',
                'slug' => 'bambang-trihatmojo-1988',
                'title' => 'M.T.',
                'level' => 'sma',
                'class_year' => "'88",
                'full_year' => '1988',
                'profession' => 'Senior Vice President - Pertamina Hulu Mahakam',
                'institution' => 'PT Pertamina Hulu Mahakam',
                'domicile' => 'Balikpapan',
                'summary' => 'Lebih dari 30 tahun mengabdi di industri migas Kalimantan Timur dan aktif dalam pembinaan almamater KPS.',
                'email' => 'bambang.tm@phm.co.id',
                'phone' => '0811540988',
                'location' => 'Balikpapan Kota',
                'linkedin_url' => 'https://linkedin.com/in/bambang-trihatmojo',
                'instagram_handle' => '@bambang_tm',
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300',
                'is_verified' => true,
            ],
            [
                'name' => 'dr. Nabila Rahmadani',
                'slug' => 'nabila-rahmadani-2012',
                'title' => 'Sp.A.',
                'level' => 'sma',
                'class_year' => "'12",
                'full_year' => '2012',
                'profession' => 'Dokter Spesialis Anak - RS Pertamina Balikpapan',
                'institution' => 'RS Pertamina Balikpapan',
                'domicile' => 'Balikpapan',
                'summary' => 'Praktisi kesehatan anak dan koordinator program bakti kesehatan alumni IKA KPS.',
                'email' => 'dr.nabila@gmail.com',
                'phone' => '08125433211',
                'location' => 'Balikpapan Selatan',
                'linkedin_url' => 'https://linkedin.com/in/nabila-rahmadani',
                'instagram_handle' => '@dr.nabila_sp.a',
                'avatar_url' => 'https://images.unsplash.com/photo-1594824813511-209a25b30030?w=300',
                'is_verified' => true,
            ],
            [
                'name' => 'Fajar Aditya Pratama',
                'slug' => 'fajar-aditya-2016',
                'title' => 'S.Kom.',
                'level' => 'smp',
                'class_year' => "'16",
                'full_year' => '2016',
                'profession' => 'Lead Software Architect - GovTech Edu',
                'institution' => 'GovTech Edu',
                'domicile' => 'Jakarta / Remote Balikpapan',
                'summary' => 'Membangun platform pendidikan nasional skala puluhan juta pengguna. Sering mengadakan mentoring karir bagi alumni muda.',
                'email' => 'fajar.aditya@tech.org',
                'phone' => '082155667788',
                'location' => 'Jakarta / Remote Balikpapan',
                'linkedin_url' => 'https://linkedin.com/in/fajar-aditya',
                'instagram_handle' => '@fajaraditya',
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300',
                'is_verified' => true,
            ],
            [
                'name' => 'Hj. Siti Rahmawati',
                'slug' => 'siti-rahmawati-1995',
                'title' => 'S.E., M.M.',
                'level' => 'sma',
                'class_year' => "'95",
                'full_year' => '1995',
                'profession' => 'Owner & Founder RM Kepiting Kenari Balikpapan',
                'institution' => 'RM Kepiting Kenari',
                'domicile' => 'Balikpapan',
                'summary' => 'Pengusaha kuliner khas Balikpapan dan pendukung aktif UMKM alumni sekolah KPS.',
                'email' => 'siti.rahmawati@kenari.id',
                'phone' => '081347000112',
                'location' => 'Balikpapan Baru',
                'linkedin_url' => 'https://linkedin.com/in/siti-rahmawati',
                'instagram_handle' => '@siti_kenari',
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300',
                'is_verified' => true,
            ],
        ];

        foreach ($curatedAlumni as $item) {
            Alumnus::updateOrCreate(['slug' => $item['slug']], $item);
        }

        // Tambahkan 20 data sampel dengan Factory
        Alumnus::factory()->count(20)->create();
    }
}
