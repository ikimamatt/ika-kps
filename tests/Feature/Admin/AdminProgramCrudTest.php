<?php

namespace Tests\Feature\Admin;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProgramCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->regularUser = User::factory()->create([
            'role' => 'alumni',
        ]);
    }

    public function test_guest_and_regular_user_cannot_access_admin_programs(): void
    {
        $response = $this->get('/admin/programs');
        $response->assertRedirect('/login');

        $userResponse = $this->actingAs($this->regularUser)->get('/admin/programs');
        $userResponse->assertStatus(403);
    }

    public function test_admin_can_view_programs_index(): void
    {
        Program::factory()->create([
            'title' => 'Renovasi Aula Pertemuan KPS',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/programs');

        $response->assertStatus(200);
        $response->assertSee('Renovasi Aula Pertemuan KPS');
        $response->assertSee('Manajemen Program Kerja');
    }

    public function test_admin_can_view_create_program_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/programs/create');

        $response->assertStatus(200);
        $response->assertSee('Formulir Program Kerja Baru');
    }

    public function test_admin_can_store_new_program(): void
    {
        $data = [
            'title' => 'Pengadaan Mobil Operasional Kas IKA',
            'description' => 'Mobil ambulans dan logistik bantuan bencana.',
            'body' => 'Rincian spesifikasi kendaraan dan pengelolaan driver relawan.',
            'icon' => 'emergency',
            'status' => 'active',
            'progress_percent' => 50,
            'progress_label' => 'Donasi Terkumpul',
            'progress_status' => '50% dari Target',
            'target_amount' => 300000000,
            'collected_amount' => 150000000,
            'achievement_text' => 'Terkumpul Rp 150.000.000',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonths(6)->toDateString(),
        ];

        $response = $this->actingAs($this->admin)->post('/admin/programs', $data);

        $response->assertRedirect('/admin/programs');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('programs', [
            'title' => 'Pengadaan Mobil Operasional Kas IKA',
            'status' => 'active',
            'progress_percent' => 50,
            'target_amount' => 300000000,
        ]);
    }

    public function test_admin_can_view_edit_program_form(): void
    {
        $program = Program::factory()->create([
            'title' => 'Program Binaan UMKM KPS',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/programs/{$program->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Perbarui Program Kerja');
        $response->assertSee('Program Binaan UMKM KPS');
    }

    public function test_admin_can_update_existing_program(): void
    {
        $program = Program::factory()->create([
            'title' => 'Program Beasiswa Awal',
            'progress_percent' => 40,
        ]);

        $updateData = [
            'title' => 'Program Beasiswa Telah Ditingkatkan',
            'description' => 'Update cakupan kuota siswa penerima beasiswa.',
            'body' => 'Narasi baru.',
            'status' => 'completed',
            'progress_percent' => 100,
            'progress_label' => 'Target Tuntas',
            'progress_status' => '100% Selesai',
            'target_amount' => 50000000,
            'collected_amount' => 50000000,
            'achievement_text' => 'Seluruh dana tersalurkan penuh.',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/programs/{$program->id}", $updateData);

        $response->assertRedirect('/admin/programs');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('programs', [
            'id' => $program->id,
            'title' => 'Program Beasiswa Telah Ditingkatkan',
            'status' => 'completed',
            'progress_percent' => 100,
        ]);
    }

    public function test_admin_can_delete_program(): void
    {
        $program = Program::factory()->create([
            'title' => 'Program Yang Akan Dihapus',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/programs/{$program->id}");

        $response->assertRedirect('/admin/programs');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('programs', [
            'id' => $program->id,
        ]);
    }

    public function test_admin_can_store_program_with_auto_calculated_progress_percent(): void
    {
        $data = [
            'title' => 'Pengadaan Laboratorium Robotik KPS',
            'description' => 'Fasilitas edukasi robotik siswa almamater.',
            'status' => 'active',
            'target_amount' => 200000000,
            'collected_amount' => 150000000,
            // progress_percent intentionally omitted
        ];

        $response = $this->actingAs($this->admin)->post('/admin/programs', $data);

        $response->assertRedirect('/admin/programs');

        $this->assertDatabaseHas('programs', [
            'title' => 'Pengadaan Laboratorium Robotik KPS',
            'target_amount' => 200000000,
            'collected_amount' => 150000000,
            'progress_percent' => 75,
        ]);
    }

    public function test_admin_can_update_program_with_auto_recalculated_percent(): void
    {
        $program = Program::factory()->create([
            'title' => 'Program Bantuan Operasional',
            'target_amount' => 100000000,
            'collected_amount' => 50000000,
            'progress_percent' => 50,
        ]);

        $updateData = [
            'title' => 'Program Bantuan Operasional Terkini',
            'description' => 'Pembaruan dana masuk.',
            'status' => 'active',
            'target_amount' => 100000000,
            'collected_amount' => 90000000,
            'auto_calculate_percent' => true,
        ];

        $response = $this->actingAs($this->admin)->put("/admin/programs/{$program->id}", $updateData);

        $response->assertRedirect('/admin/programs');

        $this->assertDatabaseHas('programs', [
            'id' => $program->id,
            'collected_amount' => 90000000,
            'progress_percent' => 90,
        ]);
    }

    public function test_admin_can_store_program_with_visual_icon_preset(): void
    {
        $data = [
            'title' => 'Program Beasiswa Prestasi Unggulan',
            'description' => 'Beasiswa untuk siswa berprestasi.',
            'status' => 'active',
            'icon' => 'school',
            'icon_bg_class' => 'bg-primary text-white',
            'target_amount' => 50000000,
            'collected_amount' => 25000000,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/programs', $data);

        $response->assertRedirect('/admin/programs');

        $this->assertDatabaseHas('programs', [
            'title' => 'Program Beasiswa Prestasi Unggulan',
            'icon' => 'school',
            'icon_bg_class' => 'bg-primary text-white',
            'progress_percent' => 50,
        ]);
    }
}
