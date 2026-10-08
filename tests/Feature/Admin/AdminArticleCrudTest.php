<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->regularUser = User::factory()->create(['role' => 'alumni']);
    }

    public function test_non_admin_cannot_access_admin_articles_panel(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/articles');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_articles_index_page(): void
    {
        Article::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/articles');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Berita & Artikel');
    }

    public function test_admin_can_view_create_article_form(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/articles/create');

        $response->assertStatus(200);
        $response->assertSee('Tulis Artikel / Berita Baru');
    }

    public function test_admin_can_store_new_article_with_image(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->image('cover-berita.jpg');

        $data = [
            'title' => 'Liputan Lengkap Munas IKA KPS 2026',
            'category' => 'Kegiatan',
            'summary' => 'Musyawarah Nasional IKA KPS berlangsung khidmat di Hotel Grand Senyiur.',
            'body' => 'Rangkaian Munas diawali dengan laporan pertanggungjawaban pengurus pusat...',
            'status' => 'published',
            'image' => $image,
        ];

        $response = $this->actingAs($this->admin)->post('/admin/articles', $data);

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'title' => 'Liputan Lengkap Munas IKA KPS 2026',
            'category' => 'Kegiatan',
            'status' => 'published',
            'author_id' => $this->admin->id,
        ]);

        $article = Article::where('title', 'Liputan Lengkap Munas IKA KPS 2026')->first();
        $this->assertNotNull($article->image_url);
        $this->assertNotNull($article->published_at);
    }

    public function test_admin_can_view_edit_article_form(): void
    {
        $article = Article::factory()->create([
            'title' => 'Artikel Uji Edit',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/articles/{$article->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Perbarui Artikel / Berita');
        $response->assertSee('Artikel Uji Edit');
    }

    public function test_admin_can_update_existing_article(): void
    {
        $article = Article::factory()->draft()->create([
            'title' => 'Judul Sebelum Direvisi',
        ]);

        $updateData = [
            'title' => 'Judul Telah Direvisi dan Terbit',
            'category' => 'Nostalgia',
            'summary' => 'Ringkasan artikel nostalgia baru.',
            'body' => 'Konten nostalgia yang lebih mendalam.',
            'status' => 'published',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/articles/{$article->id}", $updateData);

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Judul Telah Direvisi dan Terbit',
            'category' => 'Nostalgia',
            'status' => 'published',
        ]);
    }

    public function test_admin_can_delete_article(): void
    {
        $article = Article::factory()->create([
            'title' => 'Artikel Hendak Dihapus',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/articles/{$article->id}");

        $response->assertRedirect('/admin/articles');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('articles', [
            'id' => $article->id,
        ]);
    }

    public function test_admin_can_toggle_article_status_from_draft_to_published(): void
    {
        $article = Article::factory()->draft()->create([
            'title' => 'Artikel Masih Konsep',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/articles/{$article->id}/toggle-status");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'status' => 'published',
        ]);

        $this->assertNotNull($article->fresh()->published_at);
    }

    public function test_admin_can_toggle_article_status_from_published_to_draft(): void
    {
        $article = Article::factory()->create([
            'title' => 'Artikel Mau Ditarik',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/articles/{$article->id}/toggle-status");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'status' => 'draft',
        ]);
    }
}
