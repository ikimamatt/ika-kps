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

        $response = $this->get(route('gallery.index'));

        $response->assertStatus(200);
        $response->assertSee('Album Nostalgia Putih Abu');
        $response->assertDontSee('Album Rahasia');
    }

    public function test_can_filter_gallery_by_category(): void
    {
        $reuniAlbum = Gallery::factory()->create([
            'title' => 'Reuni Perak Angkatan 2000',
            'category' => 'Reuni',
            'status' => 'published',
        ]);

        $olahragaAlbum = Gallery::factory()->create([
            'title' => 'Turnamen Badminton Alumni',
            'category' => 'Olahraga',
            'status' => 'published',
        ]);

        $response = $this->get(route('gallery.index', ['category' => 'Reuni']));

        $response->assertStatus(200);
        $response->assertSee('Reuni Perak Angkatan 2000');
        $response->assertDontSee('Turnamen Badminton Alumni');
    }

    public function test_can_search_galleries_by_keyword(): void
    {
        $matched = Gallery::factory()->create([
            'title' => 'Upacara Hari Pahlawan di Kampus KPS',
            'status' => 'published',
        ]);

        $other = Gallery::factory()->create([
            'title' => 'Bakti Sosial Ramadhan Berkah',
            'status' => 'published',
        ]);

        $response = $this->get(route('gallery.index', ['search' => 'Hari Pahlawan']));

        $response->assertStatus(200);
        $response->assertSee('Upacara Hari Pahlawan di Kampus KPS');
        $response->assertDontSee('Bakti Sosial Ramadhan Berkah');
    }

    public function test_can_view_gallery_album_detail_with_photos(): void
    {
        $gallery = Gallery::factory()->create([
            'title' => 'Album Sejarah KPS 1990',
            'status' => 'published',
        ]);

        GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'caption' => 'Foto Bersama Guru-Guru Senior KPS',
        ]);

        $response = $this->get(route('gallery.show', $gallery->slug));

        $response->assertStatus(200);
        $response->assertSee('Album Sejarah KPS 1990');
        $response->assertSee('Foto Bersama Guru-Guru Senior KPS');
    }

    public function test_cannot_view_unpublished_gallery_detail(): void
    {
        $draft = Gallery::factory()->create([
            'title' => 'Album Masih Draft',
            'slug' => 'album-masih-draft',
            'status' => 'draft',
        ]);

        $response = $this->get(route('gallery.show', $draft->slug));

        $response->assertNotFound();
    }
}
