<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use App\Models\Article;
use App\Models\Business;
use App\Models\JobVacancy;
use App\Models\Program;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Alumni seeders
        Alumnus::truncate();
        Alumnus::create([
            'name' => 'Dr. Irfan Mahendra',
            'title' => 'Sp.JP',
            'level' => 'sma',
            'class_year' => "'04",
            'full_year' => '2004',
            'profession' => 'Dokter Spesialis Jantung & Pembuluh Darah',
            'summary' => 'Dokter Spesialis Jantung & Pembuluh Darah di RSUD Kanujoso Djatiwibowo Balikpapan. Aktif dalam program kesehatan masyarakat.',
            'tags' => ['Kesehatan', 'Prapatan Balikpapan'],
            'badge' => 'Mentor Medis',
            'location' => 'Balikpapan Kota',
            'avatar_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAaaUkrtqS254yDgkaO_AoRxwMOA4y01WrLtUw2YCgLOZCfHyHrUjduJcR4481gHAT_a1xUeDcF0hzNDpphM055Nqv8jGSvzcc2vxhA7lWowKb5JCKS1JGf2FfHe2j7OPCC43zlwFJV9xnoX-WRmEZEW158D_YEwZu6FI2mjLcCWTRBj0quUHlfWSvRP-GvyL9SAsqGYO5GWNvVbcqF4hMTj4a7yixdFBeG2_LScy_mFm5m6fuFaXTrww',
            'is_verified' => true,
        ]);

        Alumnus::create([
            'name' => 'Dian Sastrowijaya',
            'title' => 'S.T., M.Sc',
            'level' => 'sma',
            'class_year' => "'11",
            'full_year' => '2011',
            'profession' => 'VP of Operations di PetroEnergy Nusantara',
            'summary' => 'VP of Operations di PetroEnergy Nusantara & Konsultan Rekayasa IKN Nusantara. Spesialisasi energi hijau & konstruksi.',
            'tags' => ['Energi & EPC', 'Balikpapan Kota'],
            'badge' => 'Narasumber Tamu',
            'location' => 'Balikpapan Kota',
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
            'summary' => 'Founder & CEO Borneo Tech Studio. Memberdayakan ratusan UMKM Balikpapan melalui transformasi digital & software branding.',
            'tags' => ['Digital Creative', 'UMKM Kaltim'],
            'badge' => 'Startup Founder',
            'location' => 'Balikpapan Kota',
            'avatar_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuChWBNWEFHtYbB7lP8lMhAPwaJLS9KhvzRCy9ATiObxR5aJDVDlxilIvA0ZLFYu9odPgE8cfP6zSdZVw5MwdeW79PzwYYGJE5gA35Xc5BiMNKCNGVemnLfOTmB976PJ808NRoVqLc8iMOcAmAW7Qg9HnUrTCi0a3ePyZwOxsi3Yg-0j3bhWTZMngkhmXfefiMuYWUoNTKLYQdZ7RDs7UyiQsqzuQ6NApEHHVucV-uh3HXYGy5Z86u79uQ',
            'is_verified' => true,
        ]);

        // Businesses seeders
        Business::truncate();
        Business::create([
            'name' => 'Kopi Prapatan Roastery',
            'category' => 'Kuliner & Cafe',
            'owner_info' => "Owner: Alumni KPS '08",
            'description' => 'Cafe artisan kopi dengan beans khas Kalimantan. Terletak 200m dari gerbang lama KPS Prapatan. Diskon 15% untuk sesama alumni KPS!',
            'action_type' => 'whatsapp',
            'action_label' => 'Order via WhatsApp',
            'action_link' => 'https://wa.me/',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDRAOUGKBw9Yu8ePNmfmuAKuI1LAW4Db2kON7qj5rTW-qj__EzZ74virpHX99yXY1NIiWalpTF76wihlLIns7bpcWiSbRMdklciz_dhxG4fwzWggwi42LzMewxluw8upiRa40DX7QBYx-ob-mS9dcwjVpUavaX2wlL54_jthiPtpT4t7_Nr8MQFPXZHZGKNFvM5gLaxAIzSo6QP3cnBbjJMxMl6_9XWaYc3CWRRiPqTe6hTmTGZh54cmg',
        ]);

        Business::create([
            'name' => 'Borneo Legal & Tax Consulting',
            'category' => 'Legal & Keuangan',
            'owner_info' => "Owner: Alumni KPS '99",
            'description' => 'Layanan pendampingan kepatuhan perpajakan perusahaan, audit internal, serta perizinan bisnis penunjang proyek IKN dan korporasi Kaltim.',
            'action_type' => 'phone',
            'action_label' => 'Konsultasi Bisnis',
            'action_link' => '#',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBg-tgVRMNw2SsY6PQxcgEa2wal_xSHKdzaeaxjB2DBrw58kq90eT2fLPaq5XCYSarmZITjHOJgQ-3uUHm-byDG6mhyw_bGLlnT77fAjOAa6d-gDZUj6ayOox6BfgidXNn-NSp1WbNamPqWdcjLoxoIcnM9NynVjnqtmPsS7r2N7AXtsHjoTnhEnbKdKZDSSunvmTVsmMtEAl9Wf4fJTWEhzGGiu9ef3vZUZsu6O2i_3sAnM-KYTcinhg',
        ]);

        Business::create([
            'name' => 'Mitra Mandiri Logistik Kaltim',
            'category' => 'Supply Chain',
            'owner_info' => "Owner: Alumni KPS '02",
            'description' => 'Ekspedisi kargo darat dan laut melayani rute Pelabuhan Semayang Balikpapan - Samarinda - IKN. Prioritas armada untuk mitra alumni.',
            'action_type' => 'download',
            'action_label' => 'Unduh Katalog Layanan',
            'action_link' => '#',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuASWLn9ST1_3k9Ug9k0HLutKFTiGJvwPAaYJTTANRK0yPsORx8p2ridEDOc-SMaCZNrq0kEWDf37BoJREFyTcNv7xFjE_s-O2yovVXq0DEYUrTCg0dmsPqFMCqghXMs1vw7jMrmboLXEcOX7AblY17ccZo6UlC9sCvbmfDt00co4zLypIvdL5Yf3O21T4RPcIfivdMwTFiyi1Z0jqmLEEzgivqcl5HSALHPGzP_v_77YQ4nr0I0jQOK3g',
        ]);

        // Job Vacancies
        JobVacancy::truncate();
        JobVacancy::create([
            'title' => 'Senior HSE Specialist (Migas & Konstruksi)',
            'company' => 'PT Mahakam Energi Persada',
            'company_alumni_info' => "PT Mahakam Energi Persada (Alumni KPS '96)",
            'job_type' => 'Full Time • Balikpapan',
            'type_badge_class' => 'bg-emerald-100 text-emerald-800',
            'posted_time_info' => 'Diposting 2 hari lalu',
            'description' => 'Membutuhkan sarjana teknik berpengalaman minimal 4 tahun untuk pengawasan keselamatan proyek fasilitas kilang Balikpapan.',
            'cta_label' => 'Lamar via Jalur Rekomendasi IKA',
            'cta_link' => '#',
        ]);

        JobVacancy::create([
            'title' => 'UI/UX Designer & Web Developer Intern',
            'company' => 'Borneo Tech Studio',
            'company_alumni_info' => "Borneo Tech Studio (Alumni KPS '17)",
            'job_type' => 'Magang / Internship',
            'type_badge_class' => 'bg-sky-100 text-sky-800',
            'posted_time_info' => 'Khusus Mahasiswa Alumni KPS',
            'description' => 'Program mentorship magang 3 bulan dengan tunjangan kompetitif. Terbuka bagi alumni KPS yang sedang kuliah jurusan IT / Desain.',
            'cta_label' => 'Kirim Portofolio Magang',
            'cta_link' => '#',
        ]);

        // Programs
        Program::truncate();
        Program::create([
            'title' => 'Beasiswa Anak Almamater',
            'description' => 'Bantuan SPP & perlengkapan sekolah untuk 45+ siswa berprestasi putra-putri KPS dari keluarga pra-sejahtera.',
            'icon' => 'school',
            'icon_bg_class' => 'bg-secondary/15 text-secondary',
            'progress_label' => 'Target Realisasi 2025',
            'progress_status' => '85% Terpenuhi',
            'progress_percent' => 85,
            'bar_color_class' => 'bg-secondary',
            'achievement_text' => 'Rp 128.500.000 tersalurkan',
        ]);

        Program::create([
            'title' => 'KPS Mentorship PTN & Karier',
            'description' => 'Bimbingan masuk PTN favorit (ITB, UI, UGM) dan simulasi wawancara kerja yang dimentori alumni senior.',
            'icon' => 'psychology',
            'icon_bg_class' => 'bg-primary text-secondary-fixed-dim',
            'progress_label' => 'Adik Asuh Aktif',
            'progress_status' => '120 Siswa SMA',
            'progress_percent' => 92,
            'bar_color_class' => 'bg-primary',
            'achievement_text' => '34 Mentor Alumni Lulusan Top Campus',
        ]);

        Program::create([
            'title' => 'Reuni Akbar & KPS Sport Cup',
            'description' => 'Turnamen basket, mini soccer, senam pagi di Lapangan Merdeka, dan panggung apresiasi nostalgia tahunan.',
            'icon' => 'sports_basketball',
            'icon_bg_class' => 'bg-red-50 text-tertiary',
            'progress_label' => 'Partisipasi Angkatan',
            'progress_status' => '38 Tim Terdaftar',
            'progress_percent' => 75,
            'bar_color_class' => 'bg-tertiary',
            'achievement_text' => 'Agenda Rutin Tiap Akhir Tahun',
        ]);

        Program::create([
            'title' => 'Bakti Sosial & Tanggap Bencana',
            'description' => 'Pemeriksaan kesehatan gratis, donor darah, dan bantuan paket sembako tanggap bencana untuk warga Balikpapan.',
            'icon' => 'emergency',
            'icon_bg_class' => 'bg-primary text-secondary-fixed-dim',
            'progress_label' => 'Penerima Manfaat',
            'progress_status' => '1.250 Warga',
            'progress_percent' => 100,
            'bar_color_class' => 'bg-primary',
            'achievement_text' => '6 Kali Aksi Lapangan per Tahun',
        ]);

        // Articles
        Article::truncate();
        Article::create([
            'title' => 'Mengenang Lapangan Basket Legendaris & Kantin Mbak Prapatan',
            'slug' => 'mengenang-lapangan-basket-legendaris-kps',
            'category' => 'Nostalgia',
            'badge_bg_class' => 'bg-secondary text-on-secondary',
            'author_and_date' => '14 November 2024 • Oleh Tim Redaksi Nostalgia',
            'summary' => 'Bagi siapa pun yang pernah bersekolah di KPS, denting bola basket di sore hari dan es teh manis kantin belakang adalah memori tak tergantikan yang merekatkan kita hingga hari ini.',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZa5SsGjPGXlrUgrV6zPzPuQIV1HPOnk-lKCyCt5y5b3VYm4oFwsEOnrts0NllqzhF1MJ-oDcSdTcwWVOszAurdT4N25mixrKdwBk5oMexHtE7jB9H7OH_o8trz9ohfTgcLcqe8CH7O0_zku1Fu0dNItQMPykL1YlxFoabC1wab8AwvfTtAwhnq-b7FIA5R3uCG9bK8XLaWtFzZt3V-eCWJYjVTyZ4ai8OsnkYIdbIuJ8epJ3y7xqB2w',
        ]);

        Article::create([
            'title' => 'Sukses Reuni Akbar Lintas Generasi: Mengokohkan Komitmen Bersama untuk Balikpapan',
            'slug' => 'sukses-reuni-akbar-lintas-generasi',
            'category' => 'Kegiatan',
            'badge_bg_class' => 'bg-tertiary text-on-primary',
            'author_and_date' => '20 Desember 2024 • Oleh Humas IKA KPS',
            'summary' => 'Dihadiri lebih dari 800 alumni dari angkatan 1978 hingga 2023, Reuni Akbar tahun ini sukses menyepakati pembentukan dana abadi pendidikan dan inkubasi bisnis alumni.',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDTpgEQ5g03okm3jvn8RhQT7zgyH2lFE4LyItoqNXZcFP5qwzR9JFLlMkODhJnU1O8F9CYGY61bpSZzldAt2K41JF-9brdI3QTBq-1bf17qDYaDMoQL7533wFDWxf210vjsLaOgtdOQSfawaOAfwR8R70SSDLnW7nDux9d6PUD91Kazba2MKV27O96UDNWp5tZmnZHkY6iJwxLYOA8TXElzPtw7w9ux1kpMcEX36Ky_0RnXV-0AcNl6HQ',
        ]);

        Article::create([
            'title' => 'Alumni KPS Terpilih Memimpin Proyek Infrastruktur Berkelanjutan di Kalimantan Timur',
            'slug' => 'alumni-kps-terpilih-proyek-infrastruktur-berkelanjutan',
            'category' => 'Prestasi',
            'badge_bg_class' => 'bg-primary text-on-primary',
            'author_and_date' => '15 Januari 2025 • Oleh Dewan Redaksi',
            'summary' => 'Kiprah alumni KPS angkatan 2001 dalam merancang tata kelola energi hijau dan sistem logistik modern menegaskan sumbangsih nyata almamater untuk pembangunan daerah.',
            'image_url' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuA4eqhMHZgj2u6lKyL2mluX88BN8tl0vE7Mfk5DFzhFQ-Z6PJtpMMcyWwzuJOfCliMSHsBb6p-Bgg0Ew3monTe0Xn210Pjegco1wEhBUNlGaMvH4BfOPu0GJ24iYLSFVN3RzcV2zW_4pi5xLYyy8ZRhm5UeJk5J9qaZrDqj8GnRZnn5CkKS9AeDTg1QbOjspAFyXVXmDM6pL2go8j8lLaBZgP_YEw-bfnnVy4fJjCiFFyJhQxDXG99M2w',
        ]);
    }
}
