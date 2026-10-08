# 🖼️ Task 09: Dokumentasi Galeri Foto & Album Kenangan

> **Status:** Selesai (Completed)  
> **Prioritas:** 🟡 Medium / Engagement (Fase 3)  
> **Modul PRD:** Modul 9: Galeri Foto (BARU)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Galeri Foto merawat memori nostalgia sejarah almamater Sekolah Nasional KPS serta mendokumentasikan setiap kegiatan alumni:
1. **Daftar Album Publik (`/galeri`)**: Grid kartu album foto dengan gambar sampul (*cover*), tahun kegiatan, kategori (Nostalgia Jadul, Bakti Sosial, Reuni, Olahraga), dan indikator total foto di dalam album.
2. **Halaman Album & Lightbox Viewer (`/galeri/{slug}`)**: Grid foto masonry/responsif, keterangan teks (*caption*) di setiap foto, serta pemutar tampilan layar penuh (*lightbox viewer*).
3. **Admin Manajemen Album & Multi-Upload (`/admin/galleries`)**: Pembuatan album, fitur unggah banyak foto sekaligus (*multiple file upload*), penataan urutan foto, dan penghapusan foto satuan.

---

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration create_galleries_and_gallery_photos_tables
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_create_galleries_and_gallery_photos_tables.php`):

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
        // 1. Tabel Album Galeri
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('cover_image_url')->nullable();
            $table->date('event_date')->nullable();
            $table->string('category')->nullable()->index();
            $table->string('status')->default('draft')->index(); // 'draft', 'published'
            $table->timestamps();
        });

        // 2. Tabel Foto dalam Album
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('galleries')->cascadeOnDelete();
            $table->text('image_url');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['gallery_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('galleries');
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Model `app/Models/Gallery.php` & `app/Models/GalleryPhoto.php`

```bash
php artisan make:model Gallery
php artisan make:model GalleryPhoto
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image_url',
        'event_date',
        'category',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Gallery $gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title) . '-' . rand(100, 999);
            }
        });
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id',
        'image_url',
        'caption',
        'sort_order',
    ];

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}
```

### B. Seeder `database/seeders/GallerySeeder.php`

```bash
php artisan make:seeder GallerySeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $nostalgiaAlbum = Gallery::updateOrCreate(
            ['slug' => 'sekolah-kps-tempo-doeloe-1985-1995'],
            [
                'title' => 'Gedung Sekolah KPS Tempo Doeloe (1985–1995)',
                'description' => 'Arsip foto jadul kenangan gedung lama, seragam sekolah, dan upacara bendera era 80-90an.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800',
                'event_date' => '1990-08-17',
                'category' => 'Nostalgia',
                'status' => 'published',
            ]
        );

        for ($i = 1; $i <= 6; $i++) {
            GalleryPhoto::create([
                'gallery_id' => $nostalgiaAlbum->id,
                'image_url' => "https://picsum.photos/seed/kps-nostalgia-{$i}/900/600",
                'caption' => "Arsip kenangan angkatan ke-{$i} di depan gerbang utama sekolah",
                'sort_order' => $i,
            ]);
        }

        $reuniAlbum = Gallery::updateOrCreate(
            ['slug' => 'reuni-akbar-lintas-angkatan-2026'],
            [
                'title' => 'Dokumentasi Reuni Akbar Lintas Generasi 2026',
                'description' => 'Momen kebersamaan temu kangen akbar di Dome Balikpapan.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
                'event_date' => '2026-01-15',
                'category' => 'Reuni',
                'status' => 'published',
            ]
        );

        for ($j = 1; $j <= 8; $j++) {
            GalleryPhoto::create([
                'gallery_id' => $reuniAlbum->id,
                'image_url' => "https://picsum.photos/seed/kps-reuni-{$j}/900/600",
                'caption' => "Sesi foto bersama alumni perwakilan angkatan #{$j}",
                'sort_order' => $j,
            ]);
        }
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Gallery/PublicGalleryTest
php artisan make:test --phpunit Feature/Admin/AdminGalleryCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Gallery;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicGalleryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_gallery_album_list(): void
    {
        $published = Gallery::factory()->create([
            'title' => 'Album Nostalgia Putih Abu',
            'status' => 'published',
        ]);

        $draft = Gallery::factory()->create([
            'title' => 'Album Rahasia',
            'status' => 'draft',
        ]);

        $response = $this->get('/galeri');

        $response->assertStatus(200);
        $response->assertSee('Album Nostalgia Putih Abu');
        $response->assertDontSee('Album Rahasia');
    }

    public function test_can_view_gallery_album_detail_with_photos(): void
    {
        $gallery = Gallery::factory()->create(['status' => 'published']);
        $photo = GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'caption' => 'Foto Bersama Guru-Guru Senior KPS',
        ]);

        $response = $this->get("/galeri/{$gallery->slug}");

        $response->assertStatus(200);
        $response->assertSee('Foto Bersama Guru-Guru Senior KPS');
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/galeri', [PublicGalleryController::class, 'index'])->name('gallery.index');
Route::get('/galeri/{slug}', [PublicGalleryController::class, 'show'])->name('gallery.show');

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('galleries', AdminGalleryController::class);
    Route::post('galleries/{gallery}/photos', [AdminGalleryController::class, 'uploadPhotos'])->name('galleries.photos.upload');
    Route::delete('gallery-photos/{photo}', [AdminGalleryController::class, 'deletePhoto'])->name('galleries.photos.delete');
});
```

---

## 6. Checklist Implementasi

- [x] Jalankan migrasi tabel `galleries` dan `gallery_photos`
- [x] Atur Model `Gallery` & `GalleryPhoto`
- [x] Buat Form Request `StoreGalleryRequest`, `UpdateGalleryRequest`, & `UploadGalleryPhotosRequest`
- [x] Buat Controller Publik & Admin dengan penanganan upload multi-file gambar
- [x] Buat tampilan Blade:
  - `gallery/index.blade.php` (Grid album kartu, filter kategori, search, counter)
  - `gallery/show.blade.php` (Grid foto responsif dengan modal lightbox viewer interaktif)
  - `admin/galleries/index.blade.php`, `admin/galleries/create.blade.php`, `admin/galleries/edit.blade.php` (Manajemen foto, delete per foto, batch upload)
- [x] Tulis pengujian TDD dan jalankan (15 tests passing)
- [x] Format kode: `vendor/bin/pint --dirty --format agent`
