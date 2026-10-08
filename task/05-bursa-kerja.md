# 💼 Task 05: Bursa Kerja (Karir) & Peluang Alumni

> **Status:** Siap Dikerjakan  
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

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration enhance_job_vacancies_table_with_slug_and_status --table=job_vacancies
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_enhance_job_vacancies_table_with_slug_and_status.php`):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
            $table->foreignId('alumnus_id')->nullable()->after('id')->constrained('alumni')->nullOnDelete();
            $table->string('location')->default('Balikpapan')->after('job_type');
            $table->string('salary_range')->nullable()->after('location');
            $table->text('requirements')->nullable()->after('description');
            $table->date('deadline')->nullable()->after('requirements');
            $table->string('status')->default('pending')->after('apply_url')->index(); // 'draft', 'pending', 'active', 'expired', 'closed'
            $table->softDeletes()->after('updated_at');

            $table->index(['job_type', 'status', 'deadline']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_vacancies', function (Blueprint $table) {
            $table->dropForeign(['alumnus_id']);
            $table->dropIndex(['job_type', 'status', 'deadline']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'slug',
                'alumnus_id',
                'location',
                'salary_range',
                'requirements',
                'deadline',
                'status',
            ]);
        });
    }
};
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

- [ ] Jalankan migrasi kolom lowongan kerja
- [ ] Atur scope `active()` dan relasi di Model `JobVacancy`
- [ ] Buat Form Request `StoreJobVacancyRequest`
- [ ] Buat Controller Publik, Member, dan Admin
- [ ] Buat view Blade:
  - `career/index.blade.php` (Filter tipe kerja, lokasi, search bar, list cards)
  - `career/show.blade.php` (Detail persyaratan, CTA link lamaran, info poster alumni)
  - `admin/job-vacancies/index.blade.php` & `admin/job-vacancies/create.blade.php`
- [ ] Tulis test TDD dan jalankan test suite
- [ ] Format kode dengan Pint: `vendor/bin/pint --dirty --format agent`
