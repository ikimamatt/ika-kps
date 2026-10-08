# 👥 Task 02: Direktori & Manajemen Alumni (CRUD)

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🔴 Critical / Foundation (Fase 1)  
> **Modul PRD:** Modul 2: Manajemen Alumni (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Fitur ini merupakan pusat data ekosistem IKA KPS Balikpapan yang mencakup:
1. **Direktori Publik (`/alumni`)**: Halaman publik pencarian alumni dengan filter jenjang (TK, SD, SMP, SMA), rentang tahun angkatan, kata kunci profesi/nama/domisili, serta penomoran halaman (*pagination*).
2. **Profil Publik Alumni (`/alumni/{slug}`)**: Halaman profil individual lengkap dengan bio, riwayat jenjang, tautan media sosial, serta relasi ke usaha/bisnis yang dimiliki.
3. **Panel Admin CRUD Alumni (`/admin/alumni`)**: Manajemen penuh oleh Pengurus/Admin (tambah data, sunting, *soft delete*, *restore*, unduh CSV/Excel, serta verifikasi profil).

---

## 2. Perintah Migration Database

Jalankan perintah pembuatan migrasi untuk melengkapi skema tabel `alumni`:

```bash
php artisan make:migration enhance_alumni_table_with_slug_and_relations --table=alumni
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_enhance_alumni_table_with_slug_and_relations.php`):

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
        Schema::table('alumni', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
            $table->string('email')->nullable()->after('summary');
            $table->string('phone')->nullable()->after('email');
            $table->string('linkedin_url')->nullable()->after('location');
            $table->string('instagram_handle')->nullable()->after('linkedin_url');
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->softDeletes()->after('updated_at');

            $table->index(['level', 'full_year', 'is_verified']);
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIndex(['level', 'full_year', 'is_verified']);
            $table->dropIndex(['name']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'slug',
                'email',
                'phone',
                'linkedin_url',
                'instagram_handle',
                'user_id',
            ]);
        });
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/Alumnus.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Alumnus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'title',
        'level',
        'class_year',
        'full_year',
        'profession',
        'summary',
        'email',
        'phone',
        'tags',
        'badge',
        'location',
        'linkedin_url',
        'instagram_handle',
        'avatar_url',
        'is_verified',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_verified' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Alumnus $alumnus) {
            if (empty($alumnus->slug)) {
                $alumnus->slug = Str::slug($alumnus->name) . '-' . ($alumnus->full_year ?? rand(1000, 9999));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }
}
```

### B. Factory `database/factories/AlumnusFactory.php`

```bash
php artisan make:factory AlumnusFactory --model=Alumnus
```

```php
<?php

namespace Database\Factories;

use App\Models\Alumnus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AlumnusFactory extends Factory
{
    protected $model = Alumnus::class;

    public function definition(): array
    {
        $name = fake('id_ID')->name();
        $year = fake()->numberBetween(1985, 2024);
        $level = fake()->randomElement(['tk', 'sd', 'smp', 'sma']);

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . $year,
            'title' => fake()->randomElement(['S.T.', 'S.E.', 'S.Kom.', 'dr.', 'M.M.', null]),
            'level' => $level,
            'class_year' => "'" . substr((string) $year, -2),
            'full_year' => (string) $year,
            'profession' => fake()->randomElement([
                'Petroleum Engineer - Pertamina',
                'Dokter Spesialis RSUD Kanujoso',
                'Founder Kedai Kopi Borneo',
                'Notaris & PPAT Balikpapan',
                'Senior Software Engineer',
                'Dosen Universitas Mulawarman',
            ]),
            'summary' => fake('id_ID')->sentence(12),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'tags' => fake()->randomElements(['Energi', 'Kesehatan', 'Teknologi', 'F&B', 'Hukum', 'Balikpapan'], 2),
            'badge' => fake()->randomElement(['Pengurus', 'Mentor', 'Donatur', null]),
            'location' => fake()->randomElement(['Balikpapan', 'Samarinda', 'Jakarta', 'Surabaya', 'Penajam']),
            'linkedin_url' => 'https://linkedin.com/in/' . Str::slug($name),
            'instagram_handle' => '@' . Str::slug($name, ''),
            'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($name),
            'is_verified' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['is_verified' => false]);
    }
}
```

### C. Seeder `database/seeders/AlumnusSeeder.php`

```bash
php artisan make:seeder AlumnusSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use Illuminate\Database\Seeder;

class AlumnusSeeder extends Seeder
{
    public function run(): void
    {
        $curatedAlumni = [
            [
                'name' => 'Ir. Bambang Trihatmojo',
                'slug' => 'bambang-trihatmojo-1988',
                'title' => 'M.T.',
                'level' => 'sma',
                'class_year' => "'88",
                'full_year' => '1988',
                'profession' => 'Senior Vice President - Pertamina Hulu Mahakam',
                'summary' => 'Lebih dari 30 tahun mengabdi di industri migas Kalimantan Timur.',
                'email' => 'bambang.tm@phm.co.id',
                'phone' => '0811540988',
                'tags' => ['Migas', 'Kepemimpinan', 'Balikpapan'],
                'badge' => 'Dewan Pembina',
                'location' => 'Balikpapan',
                'is_verified' => true,
            ],
            [
                'name' => 'dr. Nabila Rahmadani',
                'slug' => 'nabila-rahmadani-2012',
                'title' => 'Sp.A.',
                'level' => 'sma',
                'class_year' => "'12",
                'full_year' => '2012',
                'profession' => 'Dokter Spesialis Anak - RS Pertamina Balikpapan',
                'summary' => 'Praktisi kesehatan anak dan koordinator program bakti kesehatan alumni IKA KPS.',
                'email' => 'dr.nabila@gmail.com',
                'phone' => '08125433211',
                'tags' => ['Kesehatan', 'Baksos', 'Pediatri'],
                'badge' => 'Pengurus Bidang Sosial',
                'location' => 'Balikpapan',
                'is_verified' => true,
            ],
            [
                'name' => 'Fajar Aditya Pratama',
                'slug' => 'fajar-aditya-2016',
                'title' => 'S.Kom.',
                'level' => 'smp',
                'class_year' => "'16",
                'full_year' => '2016',
                'profession' => 'Lead Software Architect - GovTech Edu',
                'summary' => 'Membangun platform pendidikan nasional skala puluhan juta pengguna.',
                'email' => 'fajar.aditya@tech.org',
                'phone' => '082155667788',
                'tags' => ['Teknologi', 'Cloud', 'EdTech'],
                'badge' => 'Mentor Karir',
                'location' => 'Jakarta / Remote Balikpapan',
                'is_verified' => true,
            ],
        ];

        foreach ($curatedAlumni as $item) {
            Alumnus::updateOrCreate(['slug' => $item['slug']], $item);
        }

        // Tambahkan 25 data sampel menggunakan factory
        Alumnus::factory()->count(25)->create();
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat dua berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Alumni/PublicAlumniDirectoryTest
php artisan make:test --phpunit Feature/Admin/AdminAlumniCrudTest
```

### Skenario Uji TDD:

#### A. `tests/Feature/Alumni/PublicAlumniDirectoryTest.php`:
1. `test_public_can_view_alumni_directory()`
2. `test_directory_paginates_alumni_at_twelve_records_per_page()`
3. `test_public_can_search_alumni_by_name_and_profession()`
4. `test_public_can_filter_alumni_by_level()`
5. `test_unverified_alumni_are_not_displayed_in_public_directory()`
6. `test_alumnus_detail_page_renders_successfully()`
7. `test_missing_alumnus_slug_returns_404_not_found()`

```php
<?php

namespace Tests\Feature\Alumni;

use App\Models\Alumnus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicAlumniDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_filter_alumni_by_level(): void
    {
        $smaAlumnus = Alumnus::factory()->create([
            'name' => 'Alumni SMA Terdaftar',
            'level' => 'sma',
            'is_verified' => true,
        ]);

        $smpAlumnus = Alumnus::factory()->create([
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
        $unverified = Alumnus::factory()->unverified()->create([
            'name' => 'Alumni Belum Valid',
        ]);

        $response = $this->get('/alumni');
        $response->assertDontSee('Alumni Belum Valid');
    }
}
```

#### B. `tests/Feature/Admin/AdminAlumniCrudTest.php`:
1. `test_guest_cannot_access_admin_alumni()`
2. `test_admin_can_view_all_alumni_including_unverified()`
3. `test_admin_can_create_alumnus_with_valid_payload()`
4. `test_validation_errors_when_required_alumnus_fields_are_missing()`
5. `test_admin_can_update_alumnus()`
6. `test_admin_can_soft_delete_and_restore_alumnus()`
7. `test_admin_can_toggle_verification_status()`

---

## 5. Rincian Endpoint & Route

```php
// Halaman Publik
Route::get('/alumni', [PublicAlumniController::class, 'index'])->name('alumni.index');
Route::get('/alumni/{slug}', [PublicAlumniController::class, 'show'])->name('alumni.show');

// Halaman Admin (Role Protected)
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('alumni', AdminAlumniController::class);
    Route::patch('alumni/{alumnus}/toggle-verification', [AdminAlumniController::class, 'toggleVerification'])->name('alumni.toggle-verification');
    Route::post('alumni/{id}/restore', [AdminAlumniController::class, 'restore'])->name('alumni.restore');
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan `php artisan make:migration enhance_alumni_table_with_slug_and_relations`
- [ ] Terapkan SoftDeletes dan relasi di Model `Alumnus`
- [ ] Buat Form Request `StoreAlumnusRequest` & `UpdateAlumnusRequest`
- [ ] Buat Controller `PublicAlumniController` & `AdminAlumniController`
- [ ] Implementasikan tampilan Blade:
  - `alumni/index.blade.php` (Grid, Search bar, Level filter tabs, Pagination)
  - `alumni/show.blade.php` (Header banner, Profil detail, Bisnis terkait, Kontak)
  - `admin/alumni/index.blade.php`, `admin/alumni/create.blade.php`, `admin/alumni/edit.blade.php`
- [ ] Tulis Factory dan Seeder realistis
- [ ] Jalankan seluruh skenario pengujian TDD dan pastikan lulus
- [ ] Format kode dengan Pint: `vendor/bin/pint --dirty --format agent`
