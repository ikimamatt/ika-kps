<?php

namespace Tests\Feature\Business;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBusinessDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_businesses_are_visible_to_public(): void
    {
        $published = Business::factory()->create([
            'name' => 'Usaha Aktif Alumni',
            'status' => 'published',
        ]);

        $pending = Business::factory()->pending()->create([
            'name' => 'Usaha Masih Pending',
        ]);

        $response = $this->get('/bisnis');

        $response->assertStatus(200);
        $response->assertSee('Usaha Aktif Alumni');
        $response->assertDontSee('Usaha Masih Pending');
    }

    public function test_business_can_be_filtered_by_category(): void
    {
        $kuliner = Business::factory()->create([
            'name' => 'Kopi Hits Alumni',
            'category' => 'Kuliner & F&B',
            'status' => 'published',
        ]);

        $jasa = Business::factory()->create([
            'name' => 'Konsultan Tambang KPS',
            'category' => 'Jasa & Konsultan',
            'status' => 'published',
        ]);

        $response = $this->get('/bisnis?category=Kuliner+%26+F%26B');

        $response->assertStatus(200);
        $response->assertSee('Kopi Hits Alumni');
        $response->assertDontSee('Konsultan Tambang KPS');
    }

    public function test_business_detail_page_loads_with_correct_data(): void
    {
        $business = Business::factory()->create([
            'name' => 'Klinik Sehat KPS',
            'slug' => 'klinik-sehat-kps',
            'status' => 'published',
            'description' => 'Layanan poliklinik dan fisioterapi keluarga alumni.',
        ]);

        $response = $this->get('/bisnis/klinik-sehat-kps');

        $response->assertStatus(200);
        $response->assertSee('Klinik Sehat KPS');
        $response->assertSee('Layanan poliklinik dan fisioterapi keluarga alumni.');
    }

    public function test_unpublished_business_detail_returns_404_for_guest(): void
    {
        $pending = Business::factory()->pending()->create([
            'slug' => 'bisnis-belum-publish',
        ]);

        $response = $this->get('/bisnis/bisnis-belum-publish');

        $response->assertStatus(404);
    }
}
