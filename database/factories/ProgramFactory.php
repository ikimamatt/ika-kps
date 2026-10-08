<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'Beasiswa Putra-Putri Almamater KPS',
            'Renovasi Laboratorium Sains Terpadu SMA KPS',
            'Bakti Sosial Kesehatan Balikpapan Barat',
            'KPS Career Mentoring Bootcamp',
            'Turnamen Futsal Lintas Angkatan IKA KPS',
            'Penanaman 2.000 Mangrove Teluk Balikpapan',
            'Digitalisasi Perpustakaan Sekolah KPS',
        ]);

        $collected = fake()->numberBetween(10, 80) * 1000000;
        $target = fake()->numberBetween(50, 100) * 1000000;
        $percent = min(100, (int) round(($collected / $target) * 100));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'icon' => fake()->randomElement(['school', 'diversity_3', 'volunteer_activism', 'psychology', 'sports_soccer', 'forest']),
            'icon_bg_class' => fake()->randomElement(['bg-primary text-on-primary', 'bg-secondary text-on-secondary', 'bg-tertiary text-on-tertiary', 'bg-emerald-600 text-white']),
            'description' => fake('id_ID')->sentence(15),
            'body' => fake('id_ID')->paragraphs(3, true),
            'progress_label' => 'Dana & Partisipasi Terkumpul',
            'progress_status' => "{$percent}% Terpenuhi",
            'progress_percent' => $percent,
            'bar_color_class' => fake()->randomElement(['bg-primary', 'bg-secondary', 'bg-emerald-600', 'bg-tertiary']),
            'achievement_text' => 'Terkumpul Rp '.number_format($collected, 0, ',', '.').' dari target Rp '.number_format($target, 0, ',', '.'),
            'target_amount' => $target,
            'collected_amount' => $collected,
            'status' => 'active',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(2),
            'image_url' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=800&auto=format&fit=crop',
        ];
    }

    /**
     * State for completed program.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'progress_percent' => 100,
            'progress_status' => '100% Selesai',
        ]);
    }

    /**
     * State for upcoming program.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'upcoming',
            'progress_percent' => 0,
            'progress_status' => 'Segera Dimulai',
            'start_date' => now()->addWeeks(2),
            'end_date' => now()->addMonths(3),
        ]);
    }
}
