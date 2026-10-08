<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call(UserSeeder::class);

        // Alumni seeders
        Alumnus::truncate();
        Alumnus::create([
            'name' => 'Dr. Irfan Mahendra',
            'title' => 'Sp.JP',
            'level' => 'sma',
            'class_year' => "'04",
            'full_year' => '2004',
            'profession' => 'Dokter Spesialis Jantung & Pembuluh Darah',
            'institution' => 'RSUD Kanujoso Djatiwibowo Balikpapan',
            'domicile' => 'Balikpapan',
            'summary' => 'Dokter Spesialis Jantung & Pembuluh Darah di RSUD Kanujoso Djatiwibowo Balikpapan. Aktif dalam program kesehatan masyarakat.',
            'email' => 'irfan.mahendra@rsud-kanujoso.id',
            'phone' => '0811540104',
            'location' => 'Balikpapan Kota',
            'linkedin_url' => 'https://linkedin.com/in/irfan-mahendra',
            'instagram_handle' => '@dr.irfan_jantung',
            'avatar_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAaaUkrtqS254yDgkaO_AoRxwMOA4y01WrLtUw2YCgLOZCfHyHrUjduJcR4481gHAT_a1xUeDcF0hzNDpphM055Nqv8jGSvzcc2vxhA7lWowKb5JCKS1JGf2FfHe2j7OPCC43zlwFJV9xnoX-WRmEZEW158D_YEwZu6FI2mjLcCWTRBj0quUHlfWSvRP-GvyL9SAsqGYO5GWNvVbcqF4hMTj4a7yixdFBeG2_LScy_mFm5m6fuFaXTrww',
            'is_verified' => true,
        ]);

        Alumnus::create([
            'name' => 'Dian Sastrowijaya',
            'title' => 'S.T., M.Sc.',
            'level' => 'sma',
            'class_year' => "'11",
            'full_year' => '2011',
            'profession' => 'VP of Operations di PetroEnergy Nusantara',
            'institution' => 'PetroEnergy Nusantara',
            'domicile' => 'Balikpapan',
            'summary' => 'VP of Operations di PetroEnergy Nusantara & Konsultan Rekayasa IKN Nusantara. Spesialisasi energi hijau & konstruksi.',
            'email' => 'dian.sastrowijaya@petroenergy.co.id',
            'phone' => '0812540111',
            'location' => 'Balikpapan Kota',
            'linkedin_url' => 'https://linkedin.com/in/dian-sastrowijaya',
            'instagram_handle' => '@diansastro_energy',
            'avatar_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDNzI7pmelBb08ZmlEDx1awi1FspwDRo_fbXdNgsoxkCZQVPv2PJE5qsTx6I0slWtmjNZ4aoCuY8yQ-2sbYGnEvtKIf9jO3haKdJUrEk5FBgq1QFERhdTd3zF-OwNz4pRgdM0IbbDKpLghvmNojgCovkj2UyaP048yCqv6g6Zz8cYkOqMirQDn4gReGxn17w_LOCymBShZzQS-dvdAiCvQY68jR_OlYxUZp3yupMo-hQalxlG8wGxRGTQ',
            'is_verified' => true,
        ]);

        Alumnus::create([
            'name' => 'Reza Raditya',
            'title' => 'B.A.',
            'level' => 'sma',
            'class_year' => "'17",
            'full_year' => '2017',
            'profession' => 'Founder & CEO Borneo Tech Studio',
            'institution' => 'Borneo Tech Studio',
            'domicile' => 'Balikpapan',
            'summary' => 'Founder & CEO Borneo Tech Studio. Memberdayakan ratusan UMKM Balikpapan melalui transformasi digital & software branding.',
            'email' => 'reza@borneotech.id',
            'phone' => '0813540117',
            'location' => 'Balikpapan Kota',
            'linkedin_url' => 'https://linkedin.com/in/reza-raditya',
            'instagram_handle' => '@rezaraditya.id',
            'avatar_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuChWBNWEFHtYbB7lP8lMhAPwaJLS9KhvzRCy9ATiObxR5aJDVDlxilIvA0ZLFYu9odPgE8cfP6zSdZVw5MwdeW79PzwYYGJE5gA35Xc5BiMNKCNGVemnLfOTmB976PJ808NRoVqLc8iMOcAmAW7Qg9HnUrTCi0a3ePyZwOxsi3Yg-0j3bhWTZMngkhmXfefiMuYWUoNTKLYQdZ7RDs7UyiQsqzuQ6NApEHHVucV-uh3HXYGy5Z86u79uQ',
            'is_verified' => true,
        ]);

        // Businesses seeders
        $this->call(BusinessSeeder::class);

        // Job Vacancies seeder
        $this->call(JobVacancySeeder::class);

        // Programs
        $this->call(ProgramSeeder::class);

        // Articles
        $this->call(ArticleSeeder::class);

        // Events & Agenda
        $this->call(EventSeeder::class);

        // Galleries & Photo Documentation
        $this->call(GallerySeeder::class);

        // Contacts & Inbox
        $this->call(ContactSeeder::class);

        // General Website Settings
        $this->call(SettingSeeder::class);

        $this->call(AlumnusSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
