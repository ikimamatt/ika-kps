<?php

namespace Database\Factories;

use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'alumnus_id' => Alumnus::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.rand(100, 999),
            'category' => fake()->randomElement(['Kuliner & F&B', 'Jasa & Konsultan', 'Teknologi Informasi', 'Kesehatan', 'Konstruksi & Energi']),
            'owner_info' => fake('id_ID')->name().' (SMA KPS)',
            'description' => fake('id_ID')->paragraph(),
            'image_url' => 'https://picsum.photos/seed/'.rand(1, 999).'/600/400',
            'action_type' => 'whatsapp',
            'action_label' => 'Hubungi via WhatsApp',
            'action_link' => 'https://wa.me/628125555444',
            'status' => 'published',
            'address' => fake('id_ID')->streetAddress(),
            'city' => 'Balikpapan',
            'phone' => '0542-734123',
            'whatsapp_number' => '08125555444',
            'website_url' => 'https://example.com',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function published(): static
    {
        return $this->state(fn () => ['status' => 'published']);
    }
}
