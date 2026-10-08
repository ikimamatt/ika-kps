<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryPhotoFactory extends Factory
{
    protected $model = GalleryPhoto::class;

    public function definition(): array
    {
        return [
            'gallery_id' => Gallery::factory(),
            'image_url' => 'https://picsum.photos/seed/photo-'.rand(1, 999).'/900/600',
            'caption' => fake('id_ID')->sentence(6),
            'sort_order' => rand(1, 20),
        ];
    }
}
