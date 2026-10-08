<?php

namespace Database\Factories;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlumnusFactory extends Factory
{
    protected $model = Alumnus::class;

    public function definition(): array
    {
        $name = fake('id_ID')->name();
        $year = fake()->numberBetween(1985, 2025);
        $level = fake()->randomElement(['tk', 'sd', 'smp', 'sma']);

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.$year.'-'.rand(100, 999),
            'title' => fake()->randomElement(['S.T.', 'S.E.', 'S.Kom.', 'dr.', 'M.M.', 'S.H.']),
            'level' => $level,
            'class_year' => "'".substr((string) $year, -2),
            'full_year' => (string) $year,
            'profession' => fake()->randomElement([
                'Petroleum Engineer - Pertamina',
                'Dokter Spesialis RSUD Kanujoso',
                'Founder Kedai Kopi Borneo',
                'Notaris & PPAT Balikpapan',
                'Senior Software Engineer',
                'Dosen Universitas Mulawarman',
            ]),
            'institution' => fake()->company(),
            'domicile' => fake()->randomElement(['Balikpapan', 'Samarinda', 'Jakarta', 'Surabaya', 'Penajam']),
            'summary' => fake('id_ID')->sentence(12),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'location' => fake()->randomElement(['Balikpapan Kota', 'Balikpapan Selatan', 'Balikpapan Baru']),
            'linkedin_url' => 'https://linkedin.com/in/'.Str::slug($name),
            'instagram_handle' => '@'.Str::slug($name, ''),
            'avatar_url' => 'https://ui-avatars.com/api/?name='.urlencode($name),
            'is_verified' => true,
            'verified_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => [
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ]);
    }
}
