<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEventAgendaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_public_event_index(): void
    {
        $admin = User::factory()->admin()->create();

        Event::factory()->create([
            'title' => 'Silaturahmi Akbar KPS Balikpapan',
            'status' => 'published',
            'start_date' => now()->addDays(10),
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.index'));

        $response->assertOk();
        $response->assertSee('Silaturahmi Akbar KPS Balikpapan');
    }

    public function test_draft_and_cancelled_events_are_not_shown_in_public_index(): void
    {
        $admin = User::factory()->admin()->create();

        $draft = Event::factory()->create([
            'title' => 'Event Rahasia Masih Draft',
            'status' => 'draft',
            'start_date' => now()->addDays(10),
            'created_by' => $admin->id,
        ]);

        $cancelled = Event::factory()->create([
            'title' => 'Event Yang Dibatalkan Panitia',
            'status' => 'cancelled',
            'start_date' => now()->addDays(10),
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.index'));

        $response->assertOk();
        $response->assertDontSee($draft->title);
        $response->assertDontSee($cancelled->title);
    }

    public function test_can_filter_upcoming_and_past_events(): void
    {
        $admin = User::factory()->admin()->create();

        $upcomingEvent = Event::factory()->create([
            'title' => 'Temu Akbar Masa Depan',
            'status' => 'published',
            'start_date' => now()->addDays(15),
            'created_by' => $admin->id,
        ]);

        $pastEvent = Event::factory()->create([
            'title' => 'Turnamen Nostalgia Masa Lalu',
            'status' => 'published',
            'start_date' => now()->subDays(15),
            'created_by' => $admin->id,
        ]);

        // Default upcoming
        $upcomingResponse = $this->get(route('event.index', ['timeframe' => 'upcoming']));
        $upcomingResponse->assertOk();
        $upcomingResponse->assertSee($upcomingEvent->title);
        $upcomingResponse->assertDontSee($pastEvent->title);

        // Past timeframe
        $pastResponse = $this->get(route('event.index', ['timeframe' => 'past']));
        $pastResponse->assertOk();
        $pastResponse->assertSee($pastEvent->title);
        $upcomingResponse->assertDontSee($pastEvent->title);
    }

    public function test_can_filter_events_by_category(): void
    {
        $admin = User::factory()->admin()->create();

        $reuniEvent = Event::factory()->create([
            'title' => 'Reuni Emas Angkatan 90',
            'category' => 'reuni',
            'status' => 'published',
            'start_date' => now()->addDays(5),
            'created_by' => $admin->id,
        ]);

        $olahragaEvent = Event::factory()->create([
            'title' => 'Fun Run KPS Marathon',
            'category' => 'olahraga',
            'status' => 'published',
            'start_date' => now()->addDays(6),
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.index', ['category' => 'reuni']));
        $response->assertOk();
        $response->assertSee($reuniEvent->title);
        $response->assertDontSee($olahragaEvent->title);
    }

    public function test_can_search_events_by_keyword(): void
    {
        $admin = User::factory()->admin()->create();

        $matched = Event::factory()->create([
            'title' => 'Bakti Sosial Donor Darah Peduli Sesama',
            'location' => 'PMI Cabang Balikpapan',
            'status' => 'published',
            'start_date' => now()->addDays(5),
            'created_by' => $admin->id,
        ]);

        $other = Event::factory()->create([
            'title' => 'Turnamen Futsal Alumni',
            'location' => 'GOR Sepinggan',
            'status' => 'published',
            'start_date' => now()->addDays(5),
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.index', ['search' => 'Donor Darah']));
        $response->assertOk();
        $response->assertSee($matched->title);
        $response->assertDontSee($other->title);
    }

    public function test_can_view_event_detail_page(): void
    {
        $admin = User::factory()->admin()->create();

        $event = Event::factory()->create([
            'title' => 'Seminar Transformasi Karir Alumni di Era IKN',
            'slug' => 'seminar-karir-ikn-2026',
            'status' => 'published',
            'start_date' => now()->addDays(12),
            'location' => 'Hotel Gran Senyiur Balikpapan',
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.show', $event->slug));

        $response->assertOk();
        $response->assertSee($event->title);
        $response->assertSee($event->location);
    }

    public function test_cannot_view_unpublished_event_detail(): void
    {
        $admin = User::factory()->admin()->create();

        $draft = Event::factory()->create([
            'title' => 'Event Yang Belum Dipublikasikan',
            'slug' => 'event-belum-publikasi',
            'status' => 'draft',
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('event.show', $draft->slug));

        $response->assertNotFound();
    }
}
