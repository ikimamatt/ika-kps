<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    public function definition(): array
    {
        $title = fake('id_ID')->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.rand(100, 999),
            'description' => fake('id_ID')->paragraph(),
            'cover_image_url' => 'https://picsum.photos/seed/gallery-'.rand(1, 999).'/800/500',
            'event_date' => now()->subMonths(rand(1, 36))->format('Y-m-d'),
            'category' => fake()->randomElement(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial']),
            'status' => 'published',
        ];
    }
}
