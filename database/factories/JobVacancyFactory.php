<?php

namespace Database\Factories;

use App\Models\Alumnus;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    protected $model = JobVacancy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'HSE Officer Refinery',
            'Mechanical Maintenance Engineer',
            'Staff Akuntansi & Pajak',
            'Dokter Umum Unit Rawat Jalan',
            'Frontend Web Developer',
            'Legal Corporate Staff',
            'Civil Engineer IKN Infrastructure',
            'Supply Chain & Procurement Specialist',
        ]);

        $company = fake()->randomElement([
            'PT Pertamina Hulu Mahakam',
            'PT Petrosea Tbk Balikpapan',
            'RS Hermina Balikpapan',
            'PT Kilang Pertamina Internasional',
            'Otorita Ibu Kota Nusantara (IKN)',
            'PT Thiess Contractors Indonesia',
            'Bank Mandiri Region IX Kalimantan',
        ]);

        $alumniInfo = 'Direferensikan oleh Alumni KPS Angkatan '.fake()->numberBetween(1995, 2022);

        return [
            'alumnus_id' => Alumnus::factory(),
            'title' => $title,
            'slug' => Str::slug($title.'-'.$company).'-'.fake()->unique()->numberBetween(100, 99999),
            'company' => $company,
            'alumni_info' => $alumniInfo,
            'company_alumni_info' => $alumniInfo,
            'job_type' => fake()->randomElement(['Full Time', 'Kontrak', 'Magang', 'Part Time']),
            'type_badge_class' => 'bg-emerald-100 text-emerald-800',
            'location' => fake()->randomElement(['Balikpapan', 'IKN Nusantara', 'Samarinda', 'Jakarta (Remote)']),
            'salary_range' => fake()->randomElement(['Rp 6.000.000 - Rp 10.000.000', 'Rp 10.000.000 - Rp 18.000.000', 'Kompetitif (Standar Migas)', 'UMR Kaltim']),
            'posted_time_info' => 'Baru saja',
            'description' => fake('id_ID')->paragraphs(2, true),
            'requirements' => "1. Pendidikan min. D3/S1 bidang terkait\n2. Pengalaman kerja 1-3 tahun\n3. Memiliki integritas dan kemampuan komunikasi yang baik\n4. Bersedia ditempatkan di unit operasional Balikpapan/Kaltim",
            'deadline' => now()->addDays(fake()->numberBetween(10, 45)),
            'cta_label' => 'Lamar via Rekomendasi IKA',
            'cta_link' => 'https://careers.example.com',
            'apply_url' => 'https://careers.example.com/apply',
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the job vacancy is pending review.
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the job vacancy has expired.
     */
    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'deadline' => now()->subDays(5),
        ]);
    }

    /**
     * Indicate that the job vacancy has closed.
     */
    public function closed(): static
    {
        return $this->state(fn () => [
            'status' => 'closed',
        ]);
    }
}
