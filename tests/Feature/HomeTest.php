<?php

namespace Tests\Feature;

use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test home page renders successfully with IKA KPS brand elements.
     */
    public function test_home_page_can_be_rendered(): void
    {
        $alumnus = Alumnus::create([
            'name' => 'Budi Santoso',
            'title' => 'S.T.',
            'level' => 'sma',
            'class_year' => "'05",
            'full_year' => '2005',
            'profession' => 'Software Engineer',
            'summary' => 'Alumni berkarier di bidang teknologi.',
            'location' => 'Balikpapan',
            'is_verified' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('IKA KPS BALIKPAPAN');
        $response->assertSee('Satu Almamater, Seribu Cerita');
        $response->assertSee('Budi Santoso');
    }

    /**
     * Test alumni directory filtering by search query.
     */
    public function test_alumni_directory_can_be_filtered(): void
    {
        Alumnus::create([
            'name' => 'Ahmad Dahlan',
            'level' => 'sma',
            'class_year' => "'99",
            'full_year' => '1999',
            'profession' => 'Akuntan Publik',
            'summary' => 'Spesialis audit keuangan.',
            'is_verified' => true,
        ]);

        Alumnus::create([
            'name' => 'Siti Nurhaliza',
            'level' => 'smp',
            'class_year' => "'08",
            'full_year' => '2008',
            'profession' => 'Dokter Gigi',
            'summary' => 'Praktik di Balikpapan Baru.',
            'is_verified' => true,
        ]);

        $response = $this->get('/?q=Akuntan');
        $response->assertStatus(200);
        $response->assertSee('Ahmad Dahlan');
        $response->assertDontSee('Siti Nurhaliza');
    }

    /**
     * Test alumni online registration form submission.
     */
    public function test_alumni_registration_can_be_submitted(): void
    {
        $payload = [
            'full_name' => 'Fajar Pratama',
            'level' => 'sma',
            'graduation_year' => '2015',
            'phone_whatsapp' => '081234567890',
            'email' => 'fajar@example.com',
            'profession' => 'Data Analyst',
            'institution' => 'PT Kaltim Daya Prima',
            'domicile' => 'Balikpapan',
            'notes' => 'Siap berkontribusi sharing knowledge.',
        ];

        $response = $this->post('/daftar-alumni', $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@example.com',
            'role' => 'alumni',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('alumni', [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@example.com',
            'full_year' => '2015',
            'is_verified' => false,
        ]);
    }

    public function test_unverified_alumni_not_visible_on_home_page(): void
    {
        Alumnus::create([
            'name' => 'Alumni Belum Diverifikasi',
            'level' => 'sma',
            'full_year' => '2019',
            'is_verified' => false,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Alumni Belum Diverifikasi');
    }

    public function test_unpublished_business_not_visible_on_home_page(): void
    {
        Business::factory()->create([
            'name' => 'Bisnis Sudah Terbit',
            'status' => 'published',
        ]);

        Business::factory()->pending()->create([
            'name' => 'Bisnis Masih Pending',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bisnis Sudah Terbit');
        $response->assertDontSee('Bisnis Masih Pending');
    }

    public function test_home_page_alumni_directory_is_paginated_to_six_items(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Alumnus::create([
                'name' => "Alumni Terdaftar {$i}",
                'level' => 'sma',
                'class_year' => "'10",
                'full_year' => '2010',
                'profession' => "Profesi {$i}",
                'is_verified' => true,
            ]);
        }

        $responsePage1 = $this->get('/');
        $responsePage1->assertStatus(200);
        $responsePage1->assertSee('Alumni Terdaftar 8'); // latest id first
        $responsePage1->assertSee('Alumni Terdaftar 3');
        $responsePage1->assertDontSee('Alumni Terdaftar 2');
        $responsePage1->assertDontSee('Alumni Terdaftar 1');

        $responsePage2 = $this->get('/?page=2');
        $responsePage2->assertStatus(200);
        $responsePage2->assertSee('Alumni Terdaftar 2');
        $responsePage2->assertSee('Alumni Terdaftar 1');
    }
}
