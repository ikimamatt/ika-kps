<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGalleryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_regular_user_cannot_access_admin_galleries(): void
    {
        $guestResponse = $this->get(route('admin.galleries.index'));
        $guestResponse->assertRedirect('/login');

        $regularUser = User::factory()->create(['role' => 'alumni']);
        $userResponse = $this->actingAs($regularUser)->get(route('admin.galleries.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_gallery_list(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create([
            'title' => 'Album Kenangan Angkatan 2005',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.galleries.index'));

        $response->assertOk();
        $response->assertSee('Album Kenangan Angkatan 2005');
    }

    public function test_admin_can_view_create_gallery_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.galleries.create'));

        $response->assertOk();
        $response->assertSee('Tambah Album Kenangan Baru');
    }

    public function test_admin_can_store_new_gallery_with_cover_and_photos(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $cover = UploadedFile::fake()->image('cover-reuni.jpg');
        $photo1 = UploadedFile::fake()->image('foto1.jpg');
        $photo2 = UploadedFile::fake()->image('foto2.jpg');

        $payload = [
            'title' => 'Reuni Emas 50 Tahun Sekolah KPS',
            'slug' => 'reuni-emas-50-tahun-sekolah-kps',
            'category' => 'Reuni',
            'status' => 'published',
            'event_date' => '2026-05-10',
            'description' => 'Momen emas setengah abad almamater.',
            'cover' => $cover,
            'photos' => [$photo1, $photo2],
            'captions' => ['Sesi pembukaan acara', 'Sesi foto bersama dewan guru'],
        ];

        $response = $this->actingAs($admin)->post(route('admin.galleries.store'), $payload);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galleries', [
            'title' => 'Reuni Emas 50 Tahun Sekolah KPS',
            'slug' => 'reuni-emas-50-tahun-sekolah-kps',
            'category' => 'Reuni',
            'status' => 'published',
        ]);

        $gallery = Gallery::where('slug', 'reuni-emas-50-tahun-sekolah-kps')->first();
        $this->assertNotNull($gallery->cover_image_url);
        $this->assertEquals(2, $gallery->photos()->count());
    }

    public function test_admin_can_view_edit_gallery_page(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.galleries.edit', $gallery->id));

        $response->assertOk();
        $response->assertSee($gallery->title);
    }

    public function test_admin_can_update_gallery(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create([
            'title' => 'Judul Album Lama',
            'category' => 'Nostalgia',
        ]);

        $payload = [
            'title' => 'Judul Album Baru Yang Diperbarui',
            'slug' => $gallery->slug,
            'category' => 'Kegiatan',
            'status' => 'published',
            'description' => 'Deskripsi baru.',
        ];

        $response = $this->actingAs($admin)->put(route('admin.galleries.update', $gallery->id), $payload);

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('galleries', [
            'id' => $gallery->id,
            'title' => 'Judul Album Baru Yang Diperbarui',
            'category' => 'Kegiatan',
        ]);
    }

    public function test_admin_can_delete_gallery_and_its_photos(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();
        $photo = GalleryPhoto::factory()->create(['gallery_id' => $gallery->id]);

        $response = $this->actingAs($admin)->delete(route('admin.galleries.destroy', $gallery->id));

        $response->assertRedirect(route('admin.galleries.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('galleries', ['id' => $gallery->id]);
    }

    public function test_admin_can_toggle_gallery_status(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create(['status' => 'published']);

        // Toggle to draft
        $response = $this->actingAs($admin)->patch(route('admin.galleries.toggle-status', $gallery->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('draft', $gallery->fresh()->status);

        // Toggle back to published
        $this->actingAs($admin)->patch(route('admin.galleries.toggle-status', $gallery->id));
        $this->assertEquals('published', $gallery->fresh()->status);
    }

    public function test_admin_can_upload_additional_photos_to_existing_gallery(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();

        $photo1 = UploadedFile::fake()->image('tambahan1.jpg');
        $photo2 = UploadedFile::fake()->image('tambahan2.jpg');

        $response = $this->actingAs($admin)->post(route('admin.galleries.photos.upload', $gallery->id), [
            'photos' => [$photo1, $photo2],
            'captions' => ['Foto Tambahan Satu', 'Foto Tambahan Dua'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(2, $gallery->photos()->count());
        $this->assertDatabaseHas('gallery_photos', [
            'gallery_id' => $gallery->id,
            'caption' => 'Foto Tambahan Satu',
        ]);
    }

    public function test_admin_can_delete_single_photo(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();
        $photo = GalleryPhoto::factory()->create(['gallery_id' => $gallery->id]);

        $response = $this->actingAs($admin)->delete(route('admin.galleries.photos.delete', $photo->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
    }

    public function test_admin_can_batch_update_photo_captions_and_order(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();
        $photo1 = GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'caption' => 'Caption Lama 1',
            'sort_order' => 1,
        ]);
        $photo2 = GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'caption' => null,
            'sort_order' => 2,
        ]);

        $payload = [
            'photos' => [
                [
                    'id' => $photo1->id,
                    'caption' => 'Caption Baru Foto 1',
                    'sort_order' => 2,
                ],
                [
                    'id' => $photo2->id,
                    'caption' => 'Caption Baru Foto 2',
                    'sort_order' => 1,
                ],
            ],
        ];

        $response = $this->actingAs($admin)->put(route('admin.galleries.photos.batch-update', $gallery->id), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo1->id,
            'caption' => 'Caption Baru Foto 1',
            'sort_order' => 2,
        ]);

        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo2->id,
            'caption' => 'Caption Baru Foto 2',
            'sort_order' => 1,
        ]);
    }

    public function test_admin_can_update_single_photo_caption(): void
    {
        $admin = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();
        $photo = GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'caption' => 'Caption Awal',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.galleries.photos.update', $photo->id), [
            'caption' => 'Keterangan Tunggal Baru',
            'sort_order' => 5,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo->id,
            'caption' => 'Keterangan Tunggal Baru',
            'sort_order' => 5,
        ]);
    }
}
