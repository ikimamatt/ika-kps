<?php

namespace Tests\Feature\Article;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_published_articles_index(): void
    {
        $published = Article::factory()->create([
            'title' => 'Kabar Gembira Reuni Akbar Alumni',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $draft = Article::factory()->draft()->create([
            'title' => 'Tulisan Masih Disimpan di Draft',
        ]);

        $response = $this->get('/artikel');

        $response->assertStatus(200);
        $response->assertSee('Kabar Gembira Reuni Akbar Alumni');
        $response->assertDontSee('Tulisan Masih Disimpan di Draft');
    }

    public function test_public_can_filter_articles_by_category(): void
    {
        Article::factory()->create([
            'title' => 'Liputan Reuni 2026',
            'category' => 'Kegiatan',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        Article::factory()->create([
            'title' => 'Masa Lalu di Prapatan',
            'category' => 'Nostalgia',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/artikel?category=Kegiatan');

        $response->assertStatus(200);
        $response->assertSee('Liputan Reuni 2026');
        $response->assertDontSee('Masa Lalu di Prapatan');
    }

    public function test_public_can_search_articles_by_keyword(): void
    {
        Article::factory()->create([
            'title' => 'Turnamen Basket Antar Angkatan KPS',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        Article::factory()->create([
            'title' => 'Bakti Sosial Lingkungan Mangrove',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get('/artikel?search=Basket');

        $response->assertStatus(200);
        $response->assertSee('Turnamen Basket Antar Angkatan KPS');
        $response->assertDontSee('Bakti Sosial Lingkungan Mangrove');
    }

    public function test_public_can_view_published_article_detail_and_increments_views(): void
    {
        $article = Article::factory()->create([
            'title' => 'Nostalgia Gedung Tua KPS',
            'slug' => 'nostalgia-gedung-tua-kps',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'views_count' => 15,
        ]);

        $response = $this->get('/artikel/nostalgia-gedung-tua-kps');

        $response->assertStatus(200);
        $response->assertSee('Nostalgia Gedung Tua KPS');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'views_count' => 16,
        ]);

        // Refreshing the page in the same session should NOT increment views_count again
        $this->get('/artikel/nostalgia-gedung-tua-kps');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'views_count' => 16,
        ]);
    }

    public function test_public_cannot_view_draft_article_detail(): void
    {
        $draft = Article::factory()->draft()->create([
            'slug' => 'artikel-rahasia-belum-rilis',
        ]);

        $response = $this->get('/artikel/artikel-rahasia-belum-rilis');

        $response->assertStatus(404);
    }
}
