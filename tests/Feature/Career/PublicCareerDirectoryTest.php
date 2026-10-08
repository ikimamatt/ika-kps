<?php

namespace Tests\Feature\Career;

use App\Models\JobVacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCareerDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_non_expired_jobs_are_rendered_on_career_page(): void
    {
        $activeJob = JobVacancy::factory()->create([
            'title' => 'Electrical Specialist Offshore',
            'status' => 'active',
            'deadline' => now()->addDays(15),
        ]);

        $expiredJob = JobVacancy::factory()->expired()->create([
            'title' => 'Lowongan Sudah Kadaluarsa',
        ]);

        $pendingJob = JobVacancy::factory()->pending()->create([
            'title' => 'Lowongan Belum Disetujui',
        ]);

        $response = $this->get('/karier');

        $response->assertStatus(200);
        $response->assertSee('Electrical Specialist Offshore');
        $response->assertDontSee('Lowongan Sudah Kadaluarsa');
        $response->assertDontSee('Lowongan Belum Disetujui');
    }

    public function test_jobs_can_be_filtered_by_job_type(): void
    {
        $fullTime = JobVacancy::factory()->create([
            'title' => 'Senior Mining Geologist',
            'job_type' => 'Full Time',
            'status' => 'active',
        ]);

        $internship = JobVacancy::factory()->create([
            'title' => 'Internship UI UX Designer',
            'job_type' => 'Magang',
            'status' => 'active',
        ]);

        $response = $this->get('/karier?job_type=Full+Time');

        $response->assertStatus(200);
        $response->assertSee('Senior Mining Geologist');
        $response->assertDontSee('Internship UI UX Designer');
    }

    public function test_jobs_can_be_filtered_by_location(): void
    {
        $balikpapanJob = JobVacancy::factory()->create([
            'title' => 'HSE Inspector Kilang',
            'location' => 'Balikpapan',
            'status' => 'active',
        ]);

        $iknJob = JobVacancy::factory()->create([
            'title' => 'Smart City Infrastructure Analyst',
            'location' => 'IKN Nusantara',
            'status' => 'active',
        ]);

        $response = $this->get('/karier?location=IKN+Nusantara');

        $response->assertStatus(200);
        $response->assertSee('Smart City Infrastructure Analyst');
        $response->assertDontSee('HSE Inspector Kilang');
    }

    public function test_jobs_can_be_searched_by_keyword(): void
    {
        $targetJob = JobVacancy::factory()->create([
            'title' => 'Civil Construction Lead',
            'company' => 'PT Petrosea Tbk',
            'status' => 'active',
        ]);

        $otherJob = JobVacancy::factory()->create([
            'title' => 'Dokter Umum Poliklinik',
            'company' => 'RSUD Kanujoso',
            'status' => 'active',
        ]);

        $response = $this->get('/karier?q=Petrosea');

        $response->assertStatus(200);
        $response->assertSee('Civil Construction Lead');
        $response->assertDontSee('Dokter Umum Poliklinik');
    }

    public function test_can_view_job_detail_page_by_slug(): void
    {
        $job = JobVacancy::factory()->create([
            'title' => 'Lead Piping Engineer',
            'slug' => 'lead-piping-engineer',
            'company' => 'PT Pertamina Hulu Mahakam',
            'description' => 'Mengawasi desain perpipaan fasilitas kompresi gas lepas pantai.',
            'requirements' => '1. S1 Teknik Mesin\n2. Pengalaman 5 tahun',
            'apply_url' => 'https://careers.phm.com/apply',
            'status' => 'active',
        ]);

        $response = $this->get('/karier/lead-piping-engineer');

        $response->assertStatus(200);
        $response->assertSee('Lead Piping Engineer');
        $response->assertSee('PT Pertamina Hulu Mahakam');
        $response->assertSee('Mengawasi desain perpipaan fasilitas kompresi gas lepas pantai.');
        $response->assertSee('https://careers.phm.com/apply');
    }

    public function test_viewing_nonexistent_or_inactive_job_returns_404(): void
    {
        $pendingJob = JobVacancy::factory()->pending()->create([
            'slug' => 'job-masih-pending',
        ]);

        $response = $this->get('/karier/job-masih-pending');

        $response->assertStatus(404);
    }

    public function test_job_with_plain_email_apply_url_automatically_formats_to_mailto_and_renders_email_box(): void
    {
        $job = JobVacancy::factory()->create([
            'title' => 'Geophysical Data Analyst',
            'slug' => 'geophysical-data-analyst',
            'company' => 'PT Geosains Nusantara',
            'apply_url' => 'hrd.recruitment@geosains.co.id',
            'status' => 'active',
        ]);

        $this->assertTrue($job->is_email_application);
        $this->assertEquals('hrd.recruitment@geosains.co.id', $job->application_email);
        $this->assertStringStartsWith('mailto:hrd.recruitment@geosains.co.id?subject=', $job->formatted_apply_url);
        $this->assertEquals('Kirim Email Lamaran', $job->application_action_label);

        $response = $this->get('/karier/geophysical-data-analyst');

        $response->assertStatus(200);
        $response->assertSee('Kirim Email Lamaran');
        $response->assertSee('hrd.recruitment@geosains.co.id');
        $response->assertSee('mailto:hrd.recruitment@geosains.co.id');
    }

    public function test_job_with_web_apply_url_without_protocol_automatically_prepends_https(): void
    {
        $job = JobVacancy::factory()->create([
            'title' => 'Process Safety Engineer',
            'slug' => 'process-safety-engineer',
            'company' => 'PT Kilang Nusantara',
            'apply_url' => 'recruitment.kilangnusantara.com/posisi/hse',
            'status' => 'active',
        ]);

        $this->assertFalse($job->is_email_application);
        $this->assertEquals('https://recruitment.kilangnusantara.com/posisi/hse', $job->formatted_apply_url);

        $response = $this->get('/karier/process-safety-engineer');

        $response->assertStatus(200);
        $response->assertSee('https://recruitment.kilangnusantara.com/posisi/hse');
    }
}
