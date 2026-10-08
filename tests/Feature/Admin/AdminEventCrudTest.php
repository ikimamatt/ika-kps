<?php

namespace Tests\Feature\Admin;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminEventCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_regular_user_cannot_access_admin_events(): void
    {
        $guestResponse = $this->get(route('admin.events.index'));
        $guestResponse->assertRedirect('/login');

        $regularUser = User::factory()->create(['role' => 'alumni']);
        $userResponse = $this->actingAs($regularUser)->get(route('admin.events.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_event_list(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create([
            'title' => 'Musyawarah Besar KPS 2026',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.events.index'));

        $response->assertOk();
        $response->assertSee('Musyawarah Besar KPS 2026');
    }

    public function test_admin_can_view_create_event_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.events.create'));

        $response->assertOk();
        $response->assertSee('Tambah Agenda Kegiatan Baru');
    }

    public function test_admin_can_store_new_event(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $poster = UploadedFile::fake()->image('pamflet-mubes.jpg');

        $payload = [
            'title' => 'Turnamen Golf IKA KPS Sepinggan',
            'slug' => 'turnamen-golf-ika-kps-sepinggan',
            'description' => 'Ajang silaturahmi olahraga golf alumni KPS.',
            'body' => 'Rincian jadwal tee-off jam 07.00 WITA.',
            'location' => 'Pertamina Golf Club Sepinggan',
            'start_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(20)->addHours(6)->format('Y-m-d H:i:s'),
            'category' => 'olahraga',
            'status' => 'published',
            'max_participants' => 80,
            'fee' => 100000,
            'image' => $poster,
        ];

        $response = $this->actingAs($admin)->post(route('admin.events.store'), $payload);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'title' => 'Turnamen Golf IKA KPS Sepinggan',
            'slug' => 'turnamen-golf-ika-kps-sepinggan',
            'category' => 'olahraga',
            'status' => 'published',
            'max_participants' => 80,
        ]);

        $event = Event::where('slug', 'turnamen-golf-ika-kps-sepinggan')->first();
        $this->assertNotNull($event->image_url);
    }

    public function test_admin_can_view_edit_event_page(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->get(route('admin.events.edit', $event->id));

        $response->assertOk();
        $response->assertSee($event->title);
    }

    public function test_admin_can_update_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create([
            'title' => 'Judul Lama',
            'created_by' => $admin->id,
        ]);

        $payload = [
            'title' => 'Judul Baru Yang Diperbarui',
            'slug' => $event->slug,
            'description' => 'Deskripsi kegiatan terbaru yang sudah diedit.',
            'location' => 'Lokasi Terkini',
            'start_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'category' => 'seminar',
            'status' => 'published',
            'max_participants' => 50,
            'fee' => 0,
        ];

        $response = $this->actingAs($admin)->put(route('admin.events.update', $event->id), $payload);

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Judul Baru Yang Diperbarui',
            'category' => 'seminar',
        ]);
    }

    public function test_admin_can_delete_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->delete(route('admin.events.destroy', $event->id));

        $response->assertRedirect(route('admin.events.index'));
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('events', [
            'id' => $event->id,
        ]);
    }

    public function test_admin_can_toggle_event_status(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        // Toggle from published to draft
        $response = $this->actingAs($admin)->patch(route('admin.events.toggle-status', $event->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('draft', $event->fresh()->status);

        // Toggle from draft back to published
        $this->actingAs($admin)->patch(route('admin.events.toggle-status', $event->id));
        $this->assertEquals('published', $event->fresh()->status);
    }

    public function test_admin_can_view_event_participants(): void
    {
        $admin = User::factory()->admin()->create();
        $alumni = User::factory()->create(['name' => 'Budi Santoso Alumni KPS']);
        $event = Event::factory()->create(['created_by' => $admin->id]);

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $alumni->id,
            'status' => 'confirmed',
            'registered_at' => now(),
            'notes' => 'Hadir dengan senang hati',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.events.participants', $event->id));

        $response->assertOk();
        $response->assertSee('Budi Santoso Alumni KPS');
        $response->assertSee('Hadir dengan senang hati');
    }
}
