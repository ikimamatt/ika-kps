# 📰 Task 07: Berita, Artikel & Publikasi Alumni

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🟠 High / Core Content (Fase 2)  
> **Modul PRD:** Modul 7: Artikel & Berita (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Modul Artikel & Berita menjadi media publikasi resmi kabar ikatan alumni, catatan nostalgia, liputan kegiatan, dan capaian prestasi:
1. **Pojok Berita & Nostalgia Publik (`/artikel`)**: Halaman agregasi artikel dengan artikel utama (*headline banner*), filter kategori (Kegiatan, Nostalgia, Prestasi Alumni, Pengumuman), pencarian, dan penomoran halaman.
2. **Halaman Baca Artikel Lengkap (`/artikel/{slug}`)**: Membaca artikel lengkap, gambar sampul beresolusi tinggi, profil penulis (*author info*), penghitung jumlah pembaca (*views counter*), tombol bagikan media sosial, serta rekomendasi artikel terkait.
3. **Panel Redaksi Admin (`/admin/articles`)**: Pengelolaan tulisan (Draft / Published), penjadwalan tanggal rilis (*publish scheduling*), upload media gambar, dan soft delete.

---

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration enhance_articles_table_with_body_and_author --table=articles
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_enhance_articles_table_with_body_and_author.php`):

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
        Schema::table('articles', function (Blueprint $table) {
            $table->longText('body')->nullable()->after('summary');
            $table->foreignId('author_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('status')->default('draft')->after('category')->index(); // 'draft', 'published'
            $table->timestamp('published_at')->nullable()->after('status');
            $table->unsignedInteger('views_count')->default(0)->after('published_at');
            $table->softDeletes()->after('updated_at');

            $table->index(['status', 'published_at', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropIndex(['status', 'published_at', 'category']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'body',
                'author_id',
                'status',
                'published_at',
                'views_count',
            ]);
        });
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/Article.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'category',
        'badge_bg_class',
        'summary',
        'body',
        'author_and_date',
        'image_url',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title) . '-' . rand(100, 999);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
```

### B. Factory `database/factories/ArticleFactory.php`

```bash
php artisan make:factory ArticleFactory --model=Article
```

```php
<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = fake('id_ID')->sentence(6);

        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . rand(100, 999),
            'category' => fake()->randomElement(['Kegiatan', 'Nostalgia', 'Prestasi', 'Sosial']),
            'badge_bg_class' => 'bg-amber-500/10 text-amber-600',
            'summary' => fake('id_ID')->paragraph(),
            'body' => fake('id_ID')->paragraphs(5, true),
            'author_and_date' => fake('id_ID')->name() . ' · ' . now()->format('d M Y'),
            'image_url' => 'https://picsum.photos/seed/' . rand(1, 999) . '/800/500',
            'status' => 'published',
            'published_at' => now()->subDays(rand(1, 30)),
            'views_count' => fake()->numberBetween(10, 500),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }
}
```

### C. Seeder `database/seeders/ArticleSeeder.php`

```bash
php artisan make:seeder ArticleSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->admin()->create();

        $curatedArticles = [
            [
                'author_id' => $admin->id,
                'title' => 'Menatap Masa Depan: Reuni Akbar Lintas Generasi KPS Balikpapan 2026',
                'slug' => 'reuni-akbar-lintas-generasi-kps-balikpapan-2026',
                'category' => 'Kegiatan',
                'badge_bg_class' => 'bg-blue-500/10 text-blue-600',
                'summary' => 'Lebih dari 1.500 alumni dari 40 angkatan berkumpul kembali di Balikpapan Sport and Convention Center (DOME).',
                'body' => 'Suasana haru dan sukacita menyelimuti pembukaan Reuni Akbar IKA KPS Balikpapan. Acara yang dipersiapkan selama enam bulan ini mempertemukan kembali para lulusan Sekolah Nasional KPS mulai dari angkatan 1970-an hingga lulusan termuda 2025.',
                'author_and_date' => 'Humas IKA KPS · 15 Jan 2026',
                'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800',
                'status' => 'published',
                'published_at' => now()->subWeeks(2),
                'views_count' => 1240,
            ],
            [
                'author_id' => $admin->id,
                'title' => 'Nostalgia Lorong Sekolah dan Lapangan Upacara Lapangan Pasir',
                'slug' => 'nostalgia-lorong-sekolah-lapangan-pasir',
                'category' => 'Nostalgia',
                'badge_bg_class' => 'bg-amber-500/10 text-amber-600',
                'summary' => 'Kenangan masa-masa seragam putih-abu dan aroma angin pesisir Balikpapan yang tak pernah lekang oleh waktu.',
                'body' => 'Bagi siapa pun yang pernah menghabiskan masa remaja di bangku SMA Nasional KPS Balikpapan, deru ombak Selat Makassar dan pepohonan rindang di sekitar komplek sekolah selalu menyisakan kerinduan yang mendalam.',
                'author_and_date' => 'Redaksi Alumni · 02 Feb 2026',
                'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views_count' => 640,
            ],
        ];

        foreach ($curatedArticles as $art) {
            Article::updateOrCreate(['slug' => $art['slug']], $art);
        }

        Article::factory()->count(8)->create();
        Article::factory()->draft()->count(3)->create();
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Article/PublicArticleTest
php artisan make:test --phpunit Feature/Admin/AdminArticleCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_only_view_published_articles(): void
    {
        $published = Article::factory()->create([
            'title' => 'Kabar Gembira Alumni',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $draft = Article::factory()->draft()->create([
            'title' => 'Tulisan Masih Disimpan',
        ]);

        $response = $this->get('/artikel');

        $response->assertStatus(200);
        $response->assertSee('Kabar Gembira Alumni');
        $response->assertDontSee('Tulisan Masih Disimpan');
    }

    public function test_viewing_article_increments_views_count(): void
    {
        $article = Article::factory()->create([
            'slug' => 'artikel-views-test',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'views_count' => 10,
        ]);

        $this->get('/artikel/artikel-views-test');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'views_count' => 11,
        ]);
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/artikel', [PublicArticleController::class, 'index'])->name('article.index');
Route::get('/artikel/{slug}', [PublicArticleController::class, 'show'])->name('article.show');

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('articles', AdminArticleController::class);
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan migrasi kolom `articles`
- [ ] Atur model `Article`, relasi author, dan scope `published`
- [ ] Buat Form Request `StoreArticleRequest` & `UpdateArticleRequest`
- [ ] Buat Controller Publik & Admin
- [ ] Buat tampilan Blade:
  - `article/index.blade.php` (Headline carousel/banner, kategori tabs, grid kartu)
  - `article/show.blade.php` (Halaman baca responsif, format tipografi artikel, artikel terkait)
  - `admin/articles/index.blade.php`, `admin/articles/create.blade.php`, `admin/articles/edit.blade.php`
- [ ] Tulis test TDD dan jalankan
- [ ] Format kode: `vendor/bin/pint --dirty --format agent`
