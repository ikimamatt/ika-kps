<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRsvpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_attempting_rsvp(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $response = $this->post(route('event.rsvp', $event->slug));
        $response->assertRedirect('/login');
    }

    public function test_authenticated_alumni_can_rsvp_to_an_open_event(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'max_participants' => 100,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($user)->post(route('event.rsvp', $event->slug), [
            'notes' => 'Akan hadir bersama rekan seangkatan 2012',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'confirmed',
            'notes' => 'Akan hadir bersama rekan seangkatan 2012',
        ]);
    }

    public function test_user_cannot_rsvp_twice_to_the_same_event(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $this->actingAs($user)->post(route('event.rsvp', $event->slug));
        $secondResponse = $this->actingAs($user)->post(route('event.rsvp', $event->slug));

        $secondResponse->assertSessionHas('error');
        $this->assertEquals(1, $event->registrations()->where('user_id', $user->id)->count());
    }

    public function test_cannot_rsvp_when_event_is_fully_booked(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'max_participants' => 1,
            'created_by' => $admin->id,
        ]);

        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->actingAs($firstUser)->post(route('event.rsvp', $event->slug));
        $response = $this->actingAs($secondUser)->post(route('event.rsvp', $event->slug));

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $secondUser->id,
        ]);
    }

    public function test_registered_user_can_cancel_rsvp(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        // First RSVP
        $this->actingAs($user)->post(route('event.rsvp', $event->slug));
        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        // Cancel RSVP
        $response = $this->actingAs($user)->delete(route('event.rsvp.cancel', $event->slug));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_unregistered_user_cannot_cancel_rsvp(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'status' => 'published',
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($user)->delete(route('event.rsvp.cancel', $event->slug));
        $response->assertSessionHas('error');
    }
}
