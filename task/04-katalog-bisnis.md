# 🏢 Task 04: Katalog Bisnis Alumni & Sinergi Ekonomi

> **Status:** Selesai (Completed ✅)  
> **Prioritas:** 🟠 High / Core Content (Fase 2)  
> **Modul PRD:** Modul 4: Katalog Bisnis Alumni (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi), Task 02 (Manajemen Alumni)  

---

## 1. Deskripsi Fitur

Katalog Bisnis memfasilitasi pemberdayaan ekonomi dan jaringan usaha antar alumni:
1. **Katalog Publik (`/bisnis`)**: Direktori direktori usaha/UMKM alumni dengan filter kategori (Kuliner, Jasa Migas, Teknologi, Properti, Kesehatan), pencarian, dan pagination.
2. **Halaman Detail Bisnis (`/bisnis/{slug}`)**: Informasi profil usaha, galeri produk, informasi pemilik (tautan ke profil alumni), tombol pesan via WhatsApp langsung, dan alamat.
3. **Pengajuan Usaha Mandiri Alumni (`/profil/bisnis`)**: Alumni terdaftar dapat mendaftarkan usaha miliknya secara mandiri yang akan masuk antrean review (*status: pending*).
4. **Admin Panel Bisnis (`/admin/businesses`)**: Verifikasi usaha alumni, pengelolaan kurasi, ubah status publish, dan soft delete.

---

## 2. Pembaruan Skema Basis Data (Langsung pada Migration Dasar)

> [!IMPORTANT]
> **Aturan**: Jangan membuat file migrasi `enhance_businesses_table_with_slug_and_status` atau migrasi add lainnya. Perbarui langsung skema dasar pada file `database/migrations/2026_10_06_071755_create_businesses_table.php`.

### Definisi Skema Tabel `businesses`:

```php
Schema::create('businesses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('alumnus_id')->nullable()->constrained('alumni')->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->string('category')->index();
    $table->string('owner_info')->nullable();
    $table->text('description');
    $table->text('image_url')->nullable();
    $table->string('action_type')->default('whatsapp'); // whatsapp, phone, link
    $table->string('action_url')->nullable();
    $table->string('status')->default('pending')->index(); // draft, pending, published, rejected
    $table->string('address')->nullable();
    $table->string('city')->default('Balikpapan');
    $table->string('phone')->nullable();
    $table->string('whatsapp_number')->nullable();
    $table->string('website_url')->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->index(['category', 'status']);
});
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/Business.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'alumnus_id',
        'name',
        'slug',
        'category',
        'owner_info',
        'description',
        'image_url',
        'action_type',
        'action_url',
        'status',
        'address',
        'city',
        'phone',
        'whatsapp_number',
        'website_url',
    ];

    protected static function booted(): void
    {
        static::creating(function (Business $business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name) . '-' . rand(100, 999);
            }
        });
    }

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
```

### B. Factory `database/factories/BusinessFactory.php`

```bash
php artisan make:factory BusinessFactory --model=Business
```

```php
<?php

namespace Database\Factories;

use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition(): array
    {
        $name = fake()->company();
        return [
            'alumnus_id' => Alumnus::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . rand(100, 999),
            'category' => fake()->randomElement(['Kuliner & F&B', 'Jasa & Konsultan', 'Teknologi Informasi', 'Kesehatan', 'Konstruksi & Energi']),
            'owner_info' => fake('id_ID')->name() . ' (SMA KPS)',
            'description' => fake('id_ID')->paragraph(),
            'image_url' => 'https://picsum.photos/seed/' . rand(1, 999) . '/600/400',
            'action_type' => 'whatsapp',
            'action_url' => 'https://wa.me/628125555444',
            'status' => 'published',
            'address' => fake('id_ID')->streetAddress(),
            'city' => 'Balikpapan',
            'phone' => '0542-734123',
            'whatsapp_number' => '08125555444',
            'website_url' => 'https://example.com',
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }
}
```

### C. Seeder `database/seeders/BusinessSeeder.php`

```bash
php artisan make:seeder BusinessSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $alumnus = Alumnus::first() ?? Alumnus::factory()->create();

        $curatedBusinesses = [
            [
                'alumnus_id' => $alumnus->id,
                'name' => 'Kedai Kopi Selangit Balikpapan',
                'slug' => 'kedai-kopi-selangit',
                'category' => 'Kuliner & F&B',
                'owner_info' => 'Aditya Wicaksono (SMA KPS 2008)',
                'description' => 'Roastery artisan kopi lokal Kalimantan dengan biji pilihan nusantara.',
                'image_url' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=600',
                'action_type' => 'whatsapp',
                'action_url' => 'https://wa.me/62811540111',
                'status' => 'published',
                'address' => 'Jl. MT Haryono No. 45, Balikpapan',
                'city' => 'Balikpapan',
                'whatsapp_number' => '0811540111',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'name' => 'Borneo Engineering Consultant',
                'slug' => 'borneo-engineering-consultant',
                'category' => 'Jasa & Konsultan',
                'owner_info' => 'Ir. Hendra Gunawan (SMA KPS 1995)',
                'description' => 'Konsultan rekayasa struktur sipil dan pengawasan proyek kilang serta infrastruktur.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600',
                'action_type' => 'website',
                'action_url' => 'https://borneoengineering.co.id',
                'status' => 'published',
                'address' => 'Komp. Balikpapan Baru Blok AB-3',
                'city' => 'Balikpapan',
                'website_url' => 'https://borneoengineering.co.id',
            ],
        ];

        foreach ($curatedBusinesses as $biz) {
            Business::updateOrCreate(['slug' => $biz['slug']], $biz);
        }

        Business::factory()->count(10)->create();
        Business::factory()->pending()->count(4)->create();
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat dua berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Business/PublicBusinessDirectoryTest
php artisan make:test --phpunit Feature/Business/AlumniBusinessSubmissionTest
php artisan make:test --phpunit Feature/Admin/AdminBusinessCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Business;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicBusinessDirectoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_published_businesses_are_visible_to_public(): void
    {
        $published = Business::factory()->create([
            'name' => 'Usaha Aktif Alumni',
            'status' => 'published',
        ]);

        $pending = Business::factory()->pending()->create([
            'name' => 'Usaha Masih Pending',
        ]);

        $response = $this->get('/bisnis');

        $response->assertStatus(200);
        $response->assertSee('Usaha Aktif Alumni');
        $response->assertDontSee('Usaha Masih Pending');
    }

    public function test_business_detail_page_loads_with_correct_data(): void
    {
        $business = Business::factory()->create([
            'name' => 'Klinik Sehat KPS',
            'slug' => 'klinik-sehat-kps',
            'status' => 'published',
        ]);

        $response = $this->get('/bisnis/klinik-sehat-kps');

        $response->assertStatus(200);
        $response->assertSee('Klinik Sehat KPS');
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/bisnis', [PublicBusinessController::class, 'index'])->name('business.index');
Route::get('/bisnis/{slug}', [PublicBusinessController::class, 'show'])->name('business.show');

// Alumni Self-Submit
Route::middleware('auth')->prefix('profil')->name('profile.')->group(function () {
    Route::get('/bisnis', [UserBusinessController::class, 'index'])->name('business.index');
    Route::get('/bisnis/create', [UserBusinessController::class, 'create'])->name('business.create');
    Route::post('/bisnis', [UserBusinessController::class, 'store'])->name('business.store');
});

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('businesses', AdminBusinessController::class);
    Route::patch('businesses/{business}/publish', [AdminBusinessController::class, 'publish'])->name('businesses.publish');
});
```

---

## 6. Checklist Implementasi

- [x] Pastikan skema tabel `businesses` lengkap pada file migrasi dasar
- [x] Atur relasi dan scopes di Model `Business` (`scopePublished`, `alumnus`)
- [x] Buat Form Request `StoreBusinessRequest` & `UpdateBusinessRequest`
- [x] Buat Controller Publik (`PublicBusinessController`), Member (`UserBusinessController`), dan Admin (`AdminBusinessController`)
- [x] Implementasikan tampilan Blade:
  - `business/index.blade.php` (Filter kategori tabs, search, grid cards)
  - `business/show.blade.php` (Detail bisnis, Tombol Hubungi WhatsApp)
  - `profile/business/index.blade.php` & `profile/business/create.blade.php` (Form alumni daftarkan usaha)
  - `admin/businesses/index.blade.php`, `create.blade.php`, & `edit.blade.php`
- [x] Tulis unit & feature test TDD dan jalankan
- [x] Jalankan `vendor/bin/pint --dirty --format agent`
