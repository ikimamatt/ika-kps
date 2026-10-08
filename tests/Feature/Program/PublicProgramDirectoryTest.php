<?php

namespace Tests\Feature\Program;

use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProgramDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_public_programs_page(): void
    {
        $program = Program::factory()->create([
            'title' => 'Beasiswa Pendidikan Siswa Berprestasi KPS',
            'status' => 'active',
        ]);

        $response = $this->get('/program');

        $response->assertStatus(200);
        $response->assertSee('Beasiswa Pendidikan Siswa Berprestasi KPS');
        $response->assertSee('Program Kerja &amp; Kontribusi Alumni', false);
    }

    public function test_can_view_program_detail_page_by_slug(): void
    {
        $program = Program::factory()->create([
            'title' => 'Gerakan Penanaman 2000 Bibit Mangrove',
            'slug' => 'gerakan-penanaman-2000-bibit-mangrove',
            'description' => 'Aksi nyata pelestarian lingkungan pesisir Balikpapan oleh alumni KPS.',
            'body' => 'Rincian aksi lapangan penanaman mangrove di Graha Indah.',
            'status' => 'active',
        ]);

        $response = $this->get('/program/gerakan-penanaman-2000-bibit-mangrove');

        $response->assertStatus(200);
        $response->assertSee('Gerakan Penanaman 2000 Bibit Mangrove');
        $response->assertSee('Aksi nyata pelestarian lingkungan pesisir Balikpapan oleh alumni KPS.');
        $response->assertSee('Rincian aksi lapangan penanaman mangrove di Graha Indah.');
        $response->assertSee('Rekening Resmi IKA KPS');
    }

    public function test_viewing_nonexistent_program_returns_404(): void
    {
        $response = $this->get('/program/slug-yang-tidak-ada-di-database');

        $response->assertStatus(404);
    }

    public function test_can_filter_programs_by_status(): void
    {
        $activeProg = Program::factory()->create([
            'title' => 'Program Sedang Aktif Berjalan',
            'status' => 'active',
        ]);

        $completedProg = Program::factory()->completed()->create([
            'title' => 'Program Yang Sudah Tuntas Selesai',
        ]);

        $response = $this->get('/program?status=active');

        $response->assertStatus(200);
        $response->assertSee('Program Sedang Aktif Berjalan');
        $response->assertDontSee('Program Yang Sudah Tuntas Selesai');
    }

    public function test_can_search_programs_by_keyword(): void
    {
        $targetProg = Program::factory()->create([
            'title' => 'Donasi Fasilitas Laboratorium Komputer',
            'description' => 'Pengadaan komputer baru untuk lab sekolah.',
        ]);

        $otherProg = Program::factory()->create([
            'title' => 'Turnamen Olahraga Basket Reuni',
            'description' => 'Kegiatan silaturahmi olahraga tahunan.',
        ]);

        $response = $this->get('/program?q=Laboratorium');

        $response->assertStatus(200);
        $response->assertSee('Donasi Fasilitas Laboratorium Komputer');
        $response->assertDontSee('Turnamen Olahraga Basket Reuni');
    }
}
