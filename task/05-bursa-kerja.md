# 💼 Task 05: Bursa Kerja (Karir) & Peluang Alumni

> **Status:** Selesai ✅  
> **Prioritas:** 🟠 High / Core Content (Fase 2)  
> **Modul PRD:** Modul 5: Bursa Kerja & Lowongan (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi), Task 02 (Manajemen Alumni)  

---

## 1. Deskripsi Fitur

Modul Bursa Kerja memfasilitasi pertukaran informasi karir dan kesempatan kerja di kalangan alumni dan mitra:
1. **Bursa Kerja Publik (`/karier`)**: Menampilkan lowongan kerja aktif dengan filter tipe pekerjaan (Full Time, Part Time, Magang, Kontrak), lokasi (Balikpapan, IKN Nusantara, Kaltim, Luar Kota), pencarian posisi/perusahaan, dan pagination.
2. **Detail Lowongan (`/karier/{slug}`)**: Rincian deskripsi pekerjaan, persyaratan kualifikasi, estimasi gaji (opsional), tenggat waktu lamaran (*deadline*), dan tombol lamaran langsung (*Apply Button*).
3. **Pengajuan Lowongan oleh Alumni (`/profil/lowongan`)**: Alumni dapat membagikan lowongan dari tempat mereka bekerja untuk membantu sesama alumni (*status: pending approval*).
4. **Admin Panel Lowongan (`/admin/job-vacancies`)**: Approval postingan alumni, pembuatan lowongan kurasi pengurus, perpanjangan masa tayang, dan penutupan loker.

---

## 2. Pembaruan Skema Basis Data (Langsung pada Migration Dasar)

> [!IMPORTANT]
> **Aturan**: Jangan membuat file migrasi `enhance_job_vacancies_table_with_slug_and_status` atau migrasi add lainnya. Perbarui langsung skema dasar pada file `database/migrations/2026_10_06_071756_create_job_vacancies_table.php`.

### Definisi Skema Tabel `job_vacancies`:

```php
Schema::create('job_vacancies', function (Blueprint $table) {
    $table->id();
    $table->foreignId('alumnus_id')->nullable()->constrained('alumni')->cascadeOnDelete();
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('company');
    $table->string('alumni_info')->nullable();
    $table->string('job_type')->default('Full Time'); // Full Time, Part Time, Magang, Kontrak
    $table->string('type_badge_class')->nullable();
    $table->string('location')->default('Balikpapan');
    $table->string('salary_range')->nullable();
    $table->text('description');
    $table->text('requirements')->nullable();
    $table->date('deadline')->nullable();
    $table->string('posted_time_info')->nullable();
    $table->string('apply_url')->nullable();
    $table->string('status')->default('pending')->index(); // draft, pending, active, expired, closed
    $table->timestamps();
    $table->softDeletes();

    $table->index(['job_type', 'status', 'deadline']);
});
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/JobVacancy.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobVacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'alumnus_id',
        'title',
        'slug',
        'company',
        'alumni_info',
        'job_type',
        'type_badge_class',
        'location',
        'salary_range',
        'description',
        'requirements',
        'deadline',
        'posted_time_info',
        'apply_url',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobVacancy $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title . '-' . $job->company) . '-' . rand(100, 999);
            }
        });
    }

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
            });
    }
}
```

### B. Factory `database/factories/JobVacancyFactory.php`

```bash
php artisan make:factory JobVacancyFactory --model=JobVacancy
```

```php
<?php

namespace Database\Factories;

use App\Models\Alumnus;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class JobVacancyFactory extends Factory
{
    protected $model = JobVacancy::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
            'HSE Officer Refinery',
            'Mechanical Maintenance Engineer',
            'Staff Akuntansi & Pajak',
            'Dokter Umum Unit Rawat Jalan',
            'Frontend Web Developer',
            'Legal Corporate Staff',
        ]);
        $company = fake()->randomElement([
            'PT Pertamina Hulu Mahakam',
            'PT Petrosea Tbk Balikpapan',
            'RS Hermina Balikpapan',
            'PT Kilang Pertamina Internasional',
            'Otorita Ibu Kota Nusantara (IKN)',
        ]);

        return [
            'alumnus_id' => Alumnus::factory(),
            'title' => $title,
            'slug' => Str::slug($title . '-' . $company) . '-' . rand(100, 999),
            'company' => $company,
            'alumni_info' => 'Direferensikan oleh Alumni SMA KPS Angkatan 2005',
            'job_type' => fake()->randomElement(['Full Time', 'Kontrak', 'Magang', 'Part Time']),
            'type_badge_class' => 'bg-emerald-500/10 text-emerald-600',
            'location' => fake()->randomElement(['Balikpapan', 'IKN Nusantara', 'Samarinda']),
            'salary_range' => 'Rp 7.000.000 - Rp 12.000.000',
            'description' => fake('id_ID')->paragraphs(2, true),
            'requirements' => "1. Pendidikan min S1 relevan\n2. Pengalaman kerja min 2 tahun\n3. Bersedia ditempatkan di Kaltim",
            'deadline' => now()->addDays(rand(10, 45)),
            'posted_time_info' => 'Baru saja',
            'apply_url' => 'https://careers.example.com',
            'status' => 'active',
        ];
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
            'deadline' => now()->subDays(5),
        ]);
    }
}
```

### C. Seeder `database/seeders/JobVacancySeeder.php`

```bash
php artisan make:seeder JobVacancySeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use App\Models\JobVacancy;
use Illuminate\Database\Seeder;

class JobVacancySeeder extends Seeder
{
    public function run(): void
    {
        $alumnus = Alumnus::first() ?? Alumnus::factory()->create();

        $curatedJobs = [
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Electrical Specialist (Offshore Project)',
                'slug' => 'electrical-specialist-phm',
                'company' => 'PT Pertamina Hulu Mahakam',
                'alumni_info' => 'Bambang Trihatmojo (SMA KPS 1988)',
                'job_type' => 'Full Time',
                'location' => 'Balikpapan / Offshore',
                'salary_range' => 'Kompetitif (Migas Standar)',
                'description' => 'Bertanggung jawab atas pemeliharaan dan inspeksi kelistrikan anjungan lepas pantai.',
                'requirements' => "1. S1 Teknik Elektro\n2. Sertifikasi K3 Listrik & BOSIET\n3. Pengalaman min 3 tahun di industri hulu migas",
                'deadline' => now()->addDays(20),
                'apply_url' => 'mailto:recruitment@phm.pertamina.com',
                'status' => 'active',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'title' => 'Software Engineer (Laravel & Vue.js)',
                'slug' => 'software-engineer-smart-city-ikn',
                'company' => 'Smart City Project Balikpapan - IKN',
                'alumni_info' => 'Fajar Aditya (SMP KPS 2016)',
                'job_type' => 'Full Time',
                'location' => 'Balikpapan (Hybrid)',
                'salary_range' => 'Rp 9.000.000 - Rp 15.000.000',
                'description' => 'Pengembangan dashboard IoT monitoring kota cerdas penyangga IKN.',
                'requirements' => "1. Menguasai PHP 8+, Laravel, MySQL\n2. Terbiasa dengan RESTful API & Tailwind CSS\n3. Portofolio proyek aktif",
                'deadline' => now()->addDays(30),
                'apply_url' => 'https://smartcity.example.com/apply',
                'status' => 'active',
            ],
        ];

        foreach ($curatedJobs as $job) {
            JobVacancy::updateOrCreate(['slug' => $job['slug']], $job);
        }

        JobVacancy::factory()->count(6)->create();
        JobVacancy::factory()->expired()->count(2)->create();
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Career/PublicCareerDirectoryTest
php artisan make:test --phpunit Feature/Career/AlumniJobSubmissionTest
php artisan make:test --phpunit Feature/Admin/AdminJobVacancyCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Career;

use App\Models\JobVacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCareerDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_active_jobs_are_rendered_on_career_page(): void
    {
        $activeJob = JobVacancy::factory()->create([
            'title' => 'Posisi Aktif Dibuka',
            'status' => 'active',
            'deadline' => now()->addDays(10),
        ]);

        $expiredJob = JobVacancy::factory()->create([
            'title' => 'Posisi Sudah Ditutup',
            'status' => 'expired',
            'deadline' => now()->subDay(),
        ]);

        $response = $this->get('/karier');

        $response->assertStatus(200);
        $response->assertSee('Posisi Aktif Dibuka');
        $response->assertDontSee('Posisi Sudah Ditutup');
    }

    public function test_can_view_job_detail_by_slug(): void
    {
        $job = JobVacancy::factory()->create([
            'title' => 'Senior Geologist',
            'slug' => 'senior-geologist',
            'status' => 'active',
        ]);

        $response = $this->get('/karier/senior-geologist');

        $response->assertStatus(200);
        $response->assertSee('Senior Geologist');
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/karier', [PublicCareerController::class, 'index'])->name('career.index');
Route::get('/karier/{slug}', [PublicCareerController::class, 'show'])->name('career.show');

// Alumni Self-Submit
Route::middleware('auth')->prefix('profil')->name('profile.')->group(function () {
    Route::get('/lowongan', [UserJobVacancyController::class, 'index'])->name('job.index');
    Route::get('/lowongan/create', [UserJobVacancyController::class, 'create'])->name('job.create');
    Route::post('/lowongan', [UserJobVacancyController::class, 'store'])->name('job.store');
});

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('job-vacancies', AdminJobVacancyController::class);
    Route::patch('job-vacancies/{jobVacancy}/toggle-status', [AdminJobVacancyController::class, 'toggleStatus'])->name('job-vacancies.toggle-status');
});
```

---

## 6. Checklist Implementasi

- [x] Jalankan migrasi kolom lowongan kerja
- [x] Atur scope `active()` dan relasi di Model `JobVacancy`
- [x] Buat Form Request `StoreJobVacancyRequest` & `UpdateJobVacancyRequest`
- [x] Buat Controller Publik, Member, dan Admin
- [x] Buat view Blade:
  - `career/index.blade.php` (Filter tipe kerja, lokasi, search bar, list cards)
  - `career/show.blade.php` (Detail persyaratan, CTA link lamaran, info poster alumni)
  - `profile/job/index.blade.php` & `profile/job/create.blade.php`
  - `admin/job-vacancies/index.blade.php`, `create.blade.php`, & `edit.blade.php`
- [x] Tulis test TDD dan jalankan test suite (18 assertions baru lolos)
- [x] Format kode dengan Pint: `vendor/bin/pint --dirty --format agent`
