<?php

namespace Tests\Feature\Alumni;

use App\Models\Alumnus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAlumniDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_alumni_directory(): void
    {
        $alumnus = Alumnus::factory()->create([
            'name' => 'Bambang Trihatmojo',
            'is_verified' => true,
        ]);

        $response = $this->get('/alumni');

        $response->assertStatus(200);
        $response->assertSee('Direktori Alumni');
        $response->assertSee('Bambang Trihatmojo');
    }

    public function test_directory_paginates_alumni_at_twelve_records_per_page(): void
    {
        Alumnus::factory()->count(15)->create(['is_verified' => true]);

        $response = $this->get('/alumni');

        $response->assertStatus(200);
        $response->assertViewHas('alumni', function ($alumni) {
            return $alumni->count() === 12 && $alumni->total() === 15;
        });
    }

    public function test_public_can_search_alumni_by_name_and_profession(): void
    {
        Alumnus::factory()->create([
            'name' => 'Fajar Aditya',
            'profession' => 'Senior Software Architect',
            'summary' => 'Pengembang arsitektur perangkat lunak komputasi awan.',
            'is_verified' => true,
        ]);

        Alumnus::factory()->create([
            'name' => 'Siti Nurhaliza',
            'profession' => 'Dokter Gigi Spesialis',
            'summary' => 'Dokter spesialis kesehatan gigi dan mulut.',
            'is_verified' => true,
        ]);

        $response = $this->get('/alumni?q=Architect');

        $response->assertStatus(200);
        $response->assertSee('Fajar Aditya');
        $response->assertDontSee('Siti Nurhaliza');
    }

    public function test_public_can_filter_alumni_by_level(): void
    {
        Alumnus::factory()->create([
            'name' => 'Alumni SMA Terdaftar',
            'level' => 'sma',
            'is_verified' => true,
        ]);

        Alumnus::factory()->create([
            'name' => 'Alumni SMP Lain',
            'level' => 'smp',
            'is_verified' => true,
        ]);

        $response = $this->get('/alumni?level=sma');

        $response->assertStatus(200);
        $response->assertSee('Alumni SMA Terdaftar');
        $response->assertDontSee('Alumni SMP Lain');
    }

    public function test_unverified_alumni_are_excluded_from_public(): void
    {
        Alumnus::factory()->unverified()->create([
            'name' => 'Alumni Belum Valid',
        ]);

        $response = $this->get('/alumni');

        $response->assertStatus(200);
        $response->assertDontSee('Alumni Belum Valid');
    }

    public function test_alumnus_detail_page_renders_successfully(): void
    {
        $alumnus = Alumnus::factory()->create([
            'name' => 'Nabila Rahmadani',
            'slug' => 'nabila-rahmadani-2012',
            'profession' => 'Dokter Spesialis Anak',
            'is_verified' => true,
        ]);

        $response = $this->get('/alumni/'.$alumnus->slug);

        $response->assertStatus(200);
        $response->assertSee('Nabila Rahmadani');
        $response->assertSee('Dokter Spesialis Anak');
    }

    public function test_missing_alumnus_slug_returns_404_not_found(): void
    {
        $response = $this->get('/alumni/non-existent-alumnus-slug');

        $response->assertStatus(404);
    }

    public function test_unverified_alumnus_detail_page_returns_404(): void
    {
        $alumnus = Alumnus::factory()->unverified()->create([
            'slug' => 'alumni-unverified-detail',
        ]);

        $response = $this->get('/alumni/'.$alumnus->slug);

        $response->assertStatus(404);
    }
}
