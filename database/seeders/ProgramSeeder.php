<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Beasiswa Pendidikan Putra/Putri KPS',
                'slug' => 'beasiswa-pendidikan-putra-putri-kps',
                'icon' => 'school',
                'icon_bg_class' => 'bg-secondary/15 text-secondary',
                'description' => 'Bantuan SPP & perlengkapan sekolah penuh bagi 45+ siswa-siswi berprestasi dari keluarga alumni dan warga sekitar sekolah yang membutuhkan.',
                'body' => "Program Beasiswa Pendidikan Putra/Putri KPS diinisiasi oleh Ikatan Keluarga Alumni Sekolah Nasional KPS untuk memastikan keberlanjutan masa depan generasi penerus almamater.\n\nFasilitas beasiswa mencakup pembiayaan SPP bulanan, seragam sekolah, paket buku pelajaran, serta subsidi bimbingan belajar masuk perguruan tinggi negeri terkemuka di Indonesia.\n\nSetiap donasi disalurkan secara transparan dan diaudit oleh tim bendahara IKA KPS secara berkala.",
                'progress_label' => 'Target Realisasi Donasi',
                'progress_status' => '85% Terpenuhi',
                'progress_percent' => 85,
                'bar_color_class' => 'bg-secondary',
                'achievement_text' => 'Rp 128.500.000 tersalurkan untuk 38 siswa aktif',
                'target_amount' => 150000000,
                'collected_amount' => 128500000,
                'status' => 'active',
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=800&auto=format&fit=crop',
            ],
            [
                'title' => 'KPS Career Mentoring & Bootcamp Kampus',
                'slug' => 'kps-career-mentoring-bootcamp',
                'icon' => 'psychology',
                'icon_bg_class' => 'bg-primary text-secondary-fixed-dim',
                'description' => 'Bimbingan intensif persiapan masuk PTN favorit (ITB, UI, UGM, Unhas) dan coaching persiapan wawancara kerja yang dimentori langsung oleh alumni profesional.',
                'body' => "Program mentoring lintas angkatan ini menjembatani adik-adik siswa SMA Nasional KPS dengan para alumni yang telah berkarier di sektor migas, teknologi, kedokteran, hingga pemerintahan.\n\nKegiatan mencakup seminar daring, tryout UTBK terpadu, review resume/CV, serta simulasi interview kerja langsung.",
                'progress_label' => 'Adik Asuh Terbina',
                'progress_status' => '92% Kuota Terisi',
                'progress_percent' => 92,
                'bar_color_class' => 'bg-primary',
                'achievement_text' => '120 Siswa SMA dimentori oleh 34 Alumni Senior',
                'target_amount' => 35000000,
                'collected_amount' => 32000000,
                'status' => 'active',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(4),
                'image_url' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?w=800&auto=format&fit=crop',
            ],
            [
                'title' => 'Reuni Akbar & Turnamen Olahraga Antar Angkatan',
                'slug' => 'reuni-akbar-turnamen-olahraga-kps',
                'icon' => 'sports_basketball',
                'icon_bg_class' => 'bg-red-50 text-tertiary',
                'description' => 'Ajang silaturahmi akbar tahunan dengan kompetisi basket, futsal, senam bersama, dan panggung apresiasi bakat seluruh generasi alumni KPS.',
                'body' => "Mempererat tali kekeluargaan dan mengenang masa-masa indah bersekolah di KPS Balikpapan melalui kompetisi persahabatan antar angkatan.\n\nAcara dipusatkan di Lapangan Basket Legendaris KPS dan Lapangan Merdeka Balikpapan dengan bazar kuliner UMKM binaan alumni.",
                'progress_label' => 'Partisipasi Angkatan',
                'progress_status' => '75% Pendaftaran Terverifikasi',
                'progress_percent' => 75,
                'bar_color_class' => 'bg-tertiary',
                'achievement_text' => '38 Tim Angkatan Terdaftar dari 1980 hingga 2024',
                'target_amount' => 75000000,
                'collected_amount' => 56000000,
                'status' => 'active',
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(2),
                'image_url' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=800&auto=format&fit=crop',
            ],
            [
                'title' => 'Bakti Sosial & Tanggap Kebencanaan Balikpapan',
                'slug' => 'bakti-sosial-tanggap-kebencanaan',
                'icon' => 'emergency',
                'icon_bg_class' => 'bg-primary text-secondary-fixed-dim',
                'description' => 'Penyaluran sembako, pemeriksaan kesehatan dan pengobatan cuma-cuma, serta donasi cepat tanggap bencana untuk masyarakat Kota Beriman.',
                'body' => 'Bentuk kepedulian sosial alumni KPS terhadap masyarakat kota Balikpapan, bekerja sama dengan ikatan dokter alumni KPS dan relawan kebencanaan.',
                'progress_label' => 'Aksi Selesai',
                'progress_status' => '100% Tuntas',
                'progress_percent' => 100,
                'bar_color_class' => 'bg-primary',
                'achievement_text' => '1.250 Warga Penerima Manfaat di 6 Titik Balikpapan',
                'target_amount' => 50000000,
                'collected_amount' => 50000000,
                'status' => 'completed',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->subMonths(2),
                'image_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=800&auto=format&fit=crop',
            ],
            [
                'title' => 'Revitalisasi Laboratorium Sains & Komputer KPS',
                'slug' => 'revitalisasi-lab-sains-komputer-kps',
                'icon' => 'devices',
                'icon_bg_class' => 'bg-purple-50 text-purple-600',
                'description' => 'Pengadaan 30 unit komputer modern dan peralatan praktikum sains digital untuk menunjang pembelajaran murid SD-SMP-SMA KPS.',
                'body' => 'Dukungan fasilitas infrastruktur teknologi informasi terkini guna meningkatkan daya saing akademik adik-adik almamater.',
                'progress_label' => 'Persiapan Pengadaan',
                'progress_status' => 'Segera Dimulai',
                'progress_percent' => 20,
                'bar_color_class' => 'bg-purple-600',
                'achievement_text' => 'Proposal selesai & pembukaan donasi donatur utama',
                'target_amount' => 200000000,
                'collected_amount' => 40000000,
                'status' => 'upcoming',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(6),
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&auto=format&fit=crop',
            ],
        ];

        foreach ($programs as $prog) {
            Program::updateOrCreate(['slug' => $prog['slug']], $prog);
        }
    }
}
