<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = fake('id_ID')->sentence(6);

        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'category' => fake()->randomElement(['Kegiatan', 'Nostalgia', 'Prestasi', 'Sosial']),
            'badge_bg_class' => 'bg-amber-500/10 text-amber-600',
            'summary' => fake('id_ID')->paragraph(),
            'body' => fake('id_ID')->paragraphs(5, true),
            'author_and_date' => fake('id_ID')->name().' · '.now()->format('d M Y'),
            'image_url' => 'https://picsum.photos/seed/'.rand(1, 999).'/800/500',
            'status' => 'published',
            'published_at' => now()->subDays(rand(1, 30)),
            'views_count' => fake()->numberBetween(10, 500),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
