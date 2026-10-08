<?php

namespace Tests\Feature\Career;

use App\Models\Alumnus;
use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniJobSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_alumni_job_submission(): void
    {
        $response = $this->get('/profil/lowongan');
        $response->assertRedirect('/login');

        $responseCreate = $this->get('/profil/lowongan/create');
        $responseCreate->assertRedirect('/login');
    }

    public function test_unapproved_alumni_cannot_access_job_submission(): void
    {
        $user = User::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/profil/lowongan');
        $response->assertRedirect('/profil');
        $response->assertSessionHas('warning');
    }

    public function test_approved_alumni_can_view_their_job_vacancies_list(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $job = JobVacancy::factory()->create([
            'alumnus_id' => $alumnus->id,
            'title' => 'Senior Drilling Specialist',
            'company' => 'PT Apexindo Pratama Duta',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)->get('/profil/lowongan');

        $response->assertStatus(200);
        $response->assertSee('Senior Drilling Specialist');
        $response->assertSee('PT Apexindo Pratama Duta');
        $response->assertSee('Menunggu Review');
    }

    public function test_approved_alumni_can_view_job_creation_form(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)->get('/profil/lowongan/create');

        $response->assertStatus(200);
        $response->assertSee('Pasang Info Lowongan Kerja');
        $response->assertSee('Judul Posisi / Pekerjaan');
    }

    public function test_approved_alumni_can_submit_new_job_vacancy_with_pending_status(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $payload = [
            'title' => 'HSE Officer Konstruksi Jembatan',
            'company' => 'PT Waskita Karya (Persero) IKN',
            'job_type' => 'Full Time',
            'location' => 'IKN Nusantara',
            'salary_range' => 'Rp 8.000.000 - Rp 11.000.000',
            'description' => 'Bertanggung jawab atas pengawasan SOP K3 proyek jalan tol penyangga IKN.',
            'requirements' => "1. S1 Teknik / K3\n2. Sertifikasi AK3 Umum\n3. Pengalaman 2 tahun",
            'deadline' => now()->addDays(20)->toDateString(),
            'apply_url' => 'https://waskita.example.com/karir',
        ];

        $response = $this->actingAs($user)->post('/profil/lowongan', $payload);

        $response->assertRedirect('/profil/lowongan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('job_vacancies', [
            'alumnus_id' => $alumnus->id,
            'title' => 'HSE Officer Konstruksi Jembatan',
            'company' => 'PT Waskita Karya (Persero) IKN',
            'status' => 'pending',
            'location' => 'IKN Nusantara',
        ]);
    }

    public function test_job_submission_validates_required_fields(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)->post('/profil/lowongan', []);

        $response->assertSessionHasErrors(['title', 'company', 'job_type', 'location', 'description', 'apply_url']);
    }

    public function test_approved_alumni_can_view_edit_form_for_their_job_vacancy(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $job = JobVacancy::factory()->create([
            'alumnus_id' => $alumnus->id,
            'title' => 'Software Engineer Balikpapan',
        ]);

        $response = $this->actingAs($user)->get("/profil/lowongan/{$job->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Info Lowongan Kerja');
        $response->assertSee('Software Engineer Balikpapan');
    }

    public function test_alumni_cannot_edit_another_alumnis_job_vacancy(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $otherAlumnus = Alumnus::factory()->create();
        $otherJob = JobVacancy::factory()->create([
            'alumnus_id' => $otherAlumnus->id,
            'title' => 'Lowongan Alumni Lain',
        ]);

        $response = $this->actingAs($user)->get("/profil/lowongan/{$otherJob->id}/edit");
        $response->assertForbidden();

        $responseUpdate = $this->actingAs($user)->put("/profil/lowongan/{$otherJob->id}", [
            'title' => 'Mencoba Update Paksa',
            'company' => 'PT Hack',
            'job_type' => 'Full Time',
            'location' => 'Balikpapan',
            'description' => 'Mencoba meretas',
            'apply_url' => 'https://hack.com',
        ]);
        $responseUpdate->assertForbidden();
    }

    public function test_alumni_can_update_their_job_vacancy(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $job = JobVacancy::factory()->create([
            'alumnus_id' => $alumnus->id,
            'title' => 'Judul Lama Loker',
        ]);

        $response = $this->actingAs($user)->put("/profil/lowongan/{$job->id}", [
            'title' => 'Judul Baru Loker Diupdate Alumni',
            'company' => 'PT Petrosea Tbk',
            'job_type' => 'Full Time',
            'location' => 'Balikpapan',
            'description' => 'Deskripsi pekerjaan yang diperbarui oleh alumni.',
            'apply_url' => 'https://careers.petrosea.com',
        ]);

        $response->assertRedirect('/profil/lowongan');
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('Judul Baru Loker Diupdate Alumni', $job->title);
    }

    public function test_alumni_can_delete_their_job_vacancy(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $job = JobVacancy::factory()->create([
            'alumnus_id' => $alumnus->id,
        ]);

        $response = $this->actingAs($user)->delete("/profil/lowongan/{$job->id}");

        $response->assertRedirect('/profil/lowongan');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('job_vacancies', [
            'id' => $job->id,
        ]);
    }

    public function test_alumni_can_toggle_status_of_their_job_vacancy(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $job = JobVacancy::factory()->create([
            'alumnus_id' => $alumnus->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->patch("/profil/lowongan/{$job->id}/toggle-status");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $job->refresh();
        $this->assertEquals('closed', $job->status);

        // Toggle back to active
        $response2 = $this->actingAs($user)->patch("/profil/lowongan/{$job->id}/toggle-status");
        $response2->assertRedirect();

        $job->refresh();
        $this->assertEquals('active', $job->status);
    }
}
