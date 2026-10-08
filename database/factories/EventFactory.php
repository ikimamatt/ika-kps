<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = fake('id_ID')->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'description' => fake('id_ID')->paragraph(),
            'body' => fake('id_ID')->paragraphs(3, true),
            'location' => 'Grand Jatra Hotel Balikpapan',
            'start_date' => now()->addDays(rand(5, 60)),
            'end_date' => now()->addDays(rand(5, 60))->addHours(4),
            'category' => fake()->randomElement(['reuni', 'baksos', 'seminar', 'olahraga']),
            'image_url' => 'https://picsum.photos/seed/'.rand(1, 999).'/800/450',
            'status' => 'published',
            'max_participants' => 150,
            'fee' => 0,
            'created_by' => User::factory()->admin(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
        ]);
    }
}
