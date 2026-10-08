<?php

namespace Tests\Feature\Admin;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminJobVacancyCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_users_cannot_access_admin_job_vacancies(): void
    {
        $alumniUser = User::factory()->create(['role' => 'alumni']);

        $response = $this->actingAs($alumniUser)->get('/admin/job-vacancies');
        $response->assertForbidden();
    }

    public function test_admin_can_view_job_vacancies_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        JobVacancy::factory()->create([
            'title' => 'Geotechnical Engineer Balikpapan',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/job-vacancies');

        $response->assertStatus(200);
        $response->assertSee('Geotechnical Engineer Balikpapan');
        $response->assertSee('Kelola Bursa Kerja &amp; Lowongan', false);
    }

    public function test_admin_can_create_job_vacancy(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $payload = [
            'title' => 'Chief Technology Officer Smart IKN',
            'company' => 'Borneo Digital Venture',
            'alumni_info' => 'Kurasi Pengurus Pusat IKA KPS',
            'job_type' => 'Full Time',
            'location' => 'IKN Nusantara',
            'salary_range' => 'Rp 25.000.000 - Rp 40.000.000',
            'description' => 'Memimpin roadmap teknologi cerdas di kawasan Nusantara.',
            'requirements' => '1. Pengalaman 7+ tahun memimpin tim engineering.',
            'deadline' => now()->addDays(30)->toDateString(),
            'apply_url' => 'https://borneoventure.com/careers',
            'status' => 'active',
        ];

        $response = $this->actingAs($admin)->post('/admin/job-vacancies', $payload);

        $response->assertRedirect('/admin/job-vacancies');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_vacancies', [
            'title' => 'Chief Technology Officer Smart IKN',
            'company' => 'Borneo Digital Venture',
            'status' => 'active',
        ]);
    }

    public function test_admin_can_update_job_vacancy(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $job = JobVacancy::factory()->create([
            'title' => 'Judul Lama Loker',
        ]);

        $response = $this->actingAs($admin)->put("/admin/job-vacancies/{$job->id}", [
            'title' => 'Judul Baru Loker Diupdate',
            'company' => $job->company,
            'job_type' => 'Full Time',
            'location' => 'Balikpapan',
            'description' => 'Deskripsi update pekerjaan.',
            'apply_url' => 'https://example.com/apply',
            'status' => 'active',
        ]);

        $response->assertRedirect('/admin/job-vacancies');
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('Judul Baru Loker Diupdate', $job->title);
    }

    public function test_admin_can_toggle_or_approve_job_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $pendingJob = JobVacancy::factory()->pending()->create();

        $response = $this->actingAs($admin)->patch("/admin/job-vacancies/{$pendingJob->id}/toggle-status", [
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pendingJob->refresh();
        $this->assertEquals('active', $pendingJob->status);
    }

    public function test_admin_can_delete_job_vacancy(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $job = JobVacancy::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/job-vacancies/{$job->id}");

        $response->assertRedirect('/admin/job-vacancies');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('job_vacancies', [
            'id' => $job->id,
        ]);
    }
}
