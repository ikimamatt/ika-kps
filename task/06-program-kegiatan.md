# 🎯 Task 06: Program Kerja Organisasi & Progress Pencapaian

> **Status:** Selesai (Completed ✅)  
> **Prioritas:** 🟠 High / Core Content (Fase 2)  
> **Modul PRD:** Modul 6: Program Kerja & Kegiatan (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Modul Program Kerja menampilkan inisiatif strategis IKA KPS Balikpapan kepada alumni dan publik:
1. **Daftar Program Publik (`/program`)**: Visualisasi program kerja berjalan, indikator persentase ketercapaian, target vs dana terkumpul, serta status program (*Upcoming*, *Active*, *Completed*).
2. **Detail Program (`/program/{slug}`)**: Narasi mendalam tujuan kegiatan, tim penanggung jawab, jadwal pelaksanaan, dokumentasi foto, dan tautan saluran partisipasi/donasi.
3. **Admin Panel Program (`/admin/programs`)**: Pengelolaan data program kerja, pembaruan capaian progres (*progress tracking*), dan pencatatan dana terkumpul.

---

## 2. Pembaruan Skema Basis Data (Langsung pada Migration Dasar)

> [!IMPORTANT]
> **Aturan**: Jangan membuat file migrasi `enhance_programs_table_with_slug_and_targets` atau migrasi add lainnya. Perbarui langsung skema dasar pada file `database/migrations/2026_10_06_071757_create_programs_table.php`.

### Definisi Skema Tabel `programs`:

```php
Schema::create('programs', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->string('slug')->unique();
    $table->string('icon')->nullable();
    $table->string('icon_bg_class')->nullable();
    $table->text('description');
    $table->longText('body')->nullable();
    $table->string('progress_label')->nullable();
    $table->string('progress_status')->nullable();
    $table->unsignedInteger('progress_percent')->default(0);
    $table->string('bar_color_class')->nullable();
    $table->string('achievement_text')->nullable();
    $table->decimal('target_amount', 15, 2)->nullable();
    $table->decimal('collected_amount', 15, 2)->default(0);
    $table->string('status')->default('active')->index(); // upcoming, active, completed
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->text('image_url')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['status', 'start_date']);
});
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/Program.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'icon',
        'icon_bg_class',
        'description',
        'body',
        'progress_label',
        'progress_percent',
        'bar_color_class',
        'achievement_text',
        'target_amount',
        'collected_amount',
        'status',
        'start_date',
        'end_date',
        'image_url',
    ];

    protected $casts = [
        'progress_percent' => 'integer',
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Program $program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->title) . '-' . rand(100, 999);
            }
        });
    }
}
```

### B. Factory `database/factories/ProgramFactory.php`

```bash
php artisan make:factory ProgramFactory --model=Program
```

```php
<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        $title = fake()->randomElement([
            'Beasiswa Pendidikan Putra/Putri KPS',
            'Renovasi Laboratorium Sains SMA KPS',
            'Bakti Sosial Kesehatan Balikpapan Barat',
            'KPS Career Mentoring Bootcamp',
            'Turnamen Futsal Lintas Angkatan IKA KPS',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . rand(100, 999),
            'icon' => 'academic-cap',
            'icon_bg_class' => 'bg-emerald-500/10 text-emerald-600',
            'description' => fake('id_ID')->sentence(15),
            'body' => fake('id_ID')->paragraphs(3, true),
            'progress_label' => 'Target Donasi & Capaian',
            'progress_percent' => fake()->numberBetween(25, 95),
            'bar_color_class' => 'bg-emerald-500',
            'achievement_text' => 'Terkumpul Rp 45.000.000 dari target Rp 50.000.000',
            'target_amount' => 50000000,
            'collected_amount' => 45000000,
            'status' => 'active',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(2),
            'image_url' => 'https://picsum.photos/seed/' . rand(10, 99) . '/800/450',
        ];
    }
}
```

### C. Seeder `database/seeders/ProgramSeeder.php`

```bash
php artisan make:seeder ProgramSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $curatedPrograms = [
            [
                'title' => 'Program Beasiswa Anak Alumni Berprestasi',
                'slug' => 'beasiswa-anak-alumni-berprestasi',
                'icon' => 'academic-cap',
                'icon_bg_class' => 'bg-blue-500/10 text-blue-600',
                'description' => 'Bantuan beasiswa SPP penuh bagi siswa-siswi berprestasi dari keluarga alumni prasejahtera.',
                'body' => 'Program beasiswa ini diinisiasi untuk memastikan tidak ada putra/putri keluarga besar KPS yang terhambat pendidikannya.',
                'progress_label' => 'Dana Terkumpul',
                'progress_percent' => 85,
                'bar_color_class' => 'bg-blue-600',
                'achievement_text' => 'Rp 85.000.000 dari target Rp 100.000.000 (17 Siswa Terbantu)',
                'target_amount' => 100000000,
                'collected_amount' => 85000000,
                'status' => 'active',
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
            ],
            [
                'title' => 'IKA KPS Green School & Mangrove Care Balikpapan',
                'slug' => 'green-school-mangrove-care',
                'icon' => 'globe-alt',
                'icon_bg_class' => 'bg-emerald-500/10 text-emerald-600',
                'description' => 'Aksi penanaman 2.000 bibit mangrove di kawasan Teluk Balikpapan dan edukasi lingkungan hidup di sekolah.',
                'body' => 'Gerakan kepedulian lingkungan alumni KPS untuk menjaga kelestarian habitat pesisir Balikpapan.',
                'progress_label' => 'Bibit Tertanam',
                'progress_percent' => 100,
                'bar_color_class' => 'bg-emerald-600',
                'achievement_text' => '2.000 Bibit Selesai Tertanam di Graha Indah',
                'target_amount' => 25000000,
                'collected_amount' => 25000000,
                'status' => 'completed',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->subMonth(),
            ],
        ];

        foreach ($curatedPrograms as $prog) {
            Program::updateOrCreate(['slug' => $prog['slug']], $prog);
        }

        Program::factory()->count(3)->create();
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat dua berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Program/PublicProgramDirectoryTest
php artisan make:test --phpunit Feature/Admin/AdminProgramCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Program;

use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProgramDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_public_programs_page(): void
    {
        $program = Program::factory()->create([
            'title' => 'Donasi Musholla KPS',
            'status' => 'active',
        ]);

        $response = $this->get('/program');

        $response->assertStatus(200);
        $response->assertSee('Donasi Musholla KPS');
    }

    public function test_can_view_program_detail_page(): void
    {
        $program = Program::factory()->create([
            'title' => 'KPS Peduli Bencana',
            'slug' => 'kps-peduli-bencana',
        ]);

        $response = $this->get('/program/kps-peduli-bencana');

        $response->assertStatus(200);
        $response->assertSee('KPS Peduli Bencana');
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/program', [PublicProgramController::class, 'index'])->name('program.index');
Route::get('/program/{slug}', [PublicProgramController::class, 'show'])->name('program.show');

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('programs', AdminProgramController::class);
});
```

---

## 6. Checklist Implementasi

- [x] Jalankan migrasi kolom tabel `programs`
- [x] Atur Model `Program` dan casts
- [x] Buat Form Request `StoreProgramRequest`
- [x] Buat Controller Publik & Admin
- [x] Buat tampilan Blade:
  - `program/index.blade.php` (Card grid dengan visual progress bar dinamis)
  - `program/show.blade.php` (Detail program, ringkasan capaian, CTA donasi/dukungan)
  - `admin/programs/index.blade.php` & `admin/programs/create.blade.php`
- [x] Jalankan pengujian TDD dan pastikan hijau
- [x] Format kode: `vendor/bin/pint --dirty --format agent`
