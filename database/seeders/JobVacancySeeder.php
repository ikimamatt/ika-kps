<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use App\Models\JobVacancy;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $alumnus = Alumnus::first() ?? Alumnus::factory()->create();

        $curatedJobs = [
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Electrical Specialist (Offshore Project)',
                'slug' => 'electrical-specialist-phm',
                'company' => 'PT Pertamina Hulu Mahakam',
                'alumni_info' => 'Bambang Trihatmojo (SMA KPS 1988)',
                'company_alumni_info' => 'Bambang Trihatmojo (SMA KPS 1988)',
                'job_type' => 'Full Time',
                'type_badge_class' => 'bg-emerald-100 text-emerald-800',
                'location' => 'Balikpapan / Offshore',
                'salary_range' => 'Kompetitif (Standar Migas)',
                'posted_time_info' => '2 hari yang lalu',
                'description' => 'Bertanggung jawab atas pemeliharaan, inspeksi, dan troubleshooting sistem kelistrikan anjungan lepas pantai wilayah Kalimantan Timur.',
                'requirements' => "1. Pendidikan min. S1 Teknik Elektro dari universitas terakreditasi\n2. Memiliki sertifikasi K3 Listrik & BOSIET yang masih berlaku\n3. Pengalaman kerja minimal 3 tahun di industri hulu migas\n4. Mampu berbahasa Inggris lisan dan tulisan",
                'deadline' => now()->addDays(20),
                'cta_label' => 'Lamar via Rekomendasi IKA',
                'apply_url' => 'mailto:recruitment@phm.pertamina.com',
                'status' => 'active',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Software Engineer (Laravel & Vue.js)',
                'slug' => 'software-engineer-smart-city-ikn',
                'company' => 'Smart City Project Balikpapan - IKN',
                'alumni_info' => 'Fajar Aditya (SMP KPS 2016)',
                'company_alumni_info' => 'Fajar Aditya (SMP KPS 2016)',
                'job_type' => 'Full Time',
                'type_badge_class' => 'bg-emerald-100 text-emerald-800',
                'location' => 'Balikpapan (Hybrid)',
                'salary_range' => 'Rp 9.000.000 - Rp 15.000.000',
                'posted_time_info' => '1 hari yang lalu',
                'description' => 'Mengembangkan portal dasbor IoT dan sistem pendukung data analytics perkotaan cerdas mitra Otorita IKN Nusantara.',
                'requirements' => "1. Pengalaman kerja 2+ tahun dengan framework PHP Laravel & Vue.js/React\n2. Terbiasa mengelola RESTful API & basis data PostgreSQL/MySQL\n3. Memahami konsep clean architecture dan Git version control\n4. Berdomisili di Balikpapan atau bersedia relokasi",
                'deadline' => now()->addDays(30),
                'cta_label' => 'Kirim Portofolio & CV',
                'apply_url' => 'https://careers.example.com/apply-ikn',
                'status' => 'active',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Dokter Umum Unit Rawat Jalan & IGD',
                'slug' => 'dokter-umum-rs-restu-ibu',
                'company' => 'RS Restu Ibu Balikpapan',
                'alumni_info' => 'Dr. Irfan Mahendra (SMA KPS 2004)',
                'company_alumni_info' => 'Dr. Irfan Mahendra (SMA KPS 2004)',
                'job_type' => 'Full Time',
                'type_badge_class' => 'bg-emerald-100 text-emerald-800',
                'location' => 'Balikpapan Kota',
                'salary_range' => 'Rp 8.000.000 - Rp 12.000.000',
                'posted_time_info' => '3 hari yang lalu',
                'description' => 'Melayani pemeriksaan klinis rawat jalan dan penanganan gawat darurat dasar dengan standar pelayanan medis prima.',
                'requirements' => "1. Memiliki STR Aktif dan sertifikat ACLS / ATLS\n2. Pengalaman klinis minimal 1 tahun pasca-internship\n3. Komunikasi interpersonal empati dan ramah pasien\n4. Bersedia bekerja dalam sistem shift",
                'deadline' => now()->addDays(25),
                'cta_label' => 'Kirim Lamaran ke HRD RS',
                'apply_url' => 'mailto:hrd@restuibu.co.id',
                'status' => 'active',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Magang Graphic Design & Media Sosial',
                'slug' => 'magang-graphic-design-kps-hub',
                'company' => 'KPS Creative Network',
                'alumni_info' => 'Pengurus Bidang Humas & Komunikasi IKA KPS',
                'company_alumni_info' => 'Pengurus Bidang Humas & Komunikasi IKA KPS',
                'job_type' => 'Magang',
                'type_badge_class' => 'bg-purple-100 text-purple-800',
                'location' => 'Balikpapan (Remote Friendly)',
                'salary_range' => 'Uang Saku & Sertifikat Resmi',
                'posted_time_info' => 'Baru saja',
                'description' => 'Membantu pembuatan konten visual instagram, liputan kegiatan alumni, dan materi grafis promosi kegiatan almamater.',
                'requirements' => "1. Mahasiswa aktif / fresh graduate (terbuka khusus keluarga alumni)\n2. Terampil menggunakan Adobe Illustrator / Canva / Figma\n3. Memiliki selera desain modern dan up-to-date\n4. Periode magang 3 bulan",
                'deadline' => now()->addDays(15),
                'cta_label' => 'Daftar Program Magang',
                'apply_url' => 'mailto:humas@ikakpsbalikpapan.id',
                'status' => 'active',
            ],
        ];

        foreach ($curatedJobs as $job) {
            JobVacancy::updateOrCreate(['slug' => $job['slug']], $job);
        }

        // Additional sample jobs
        JobVacancy::factory()->count(4)->create([
            'status' => 'active',
        ]);

        JobVacancy::factory()->pending()->count(2)->create([
            'alumnus_id' => $alumnus->id,
        ]);

        JobVacancy::factory()->expired()->count(2)->create();
    }
}
