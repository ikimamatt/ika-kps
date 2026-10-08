<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactInboxTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_regular_user_cannot_access_admin_contacts(): void
    {
        $guestResponse = $this->get(route('admin.contacts.index'));
        $guestResponse->assertRedirect('/login');

        $regularUser = User::factory()->create(['role' => 'alumni']);
        $userResponse = $this->actingAs($regularUser)->get(route('admin.contacts.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_contact_inbox(): void
    {
        $admin = User::factory()->admin()->create();
        $contact = Contact::factory()->create([
            'name' => 'Bambang Pamungkas',
            'subject' => 'Apresiasi Turnamen Futsal Alumni',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.contacts.index'));

        $response->assertOk();
        $response->assertSee('Bambang Pamungkas');
        $response->assertSee('Apresiasi Turnamen Futsal Alumni');
    }

    public function test_admin_can_filter_unread_messages(): void
    {
        $admin = User::factory()->admin()->create();

        $unread = Contact::factory()->create([
            'subject' => 'Pesan Masih Baru',
            'is_read' => false,
        ]);

        $read = Contact::factory()->read()->create([
            'subject' => 'Pesan Telah Dibaca',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.contacts.index', ['status' => 'unread']));

        $response->assertOk();
        $response->assertSee('Pesan Masih Baru');
        $response->assertDontSee('Pesan Telah Dibaca');
    }

    public function test_admin_can_search_messages_by_keyword(): void
    {
        $admin = User::factory()->admin()->create();

        $matched = Contact::factory()->create([
            'name' => 'Dwi Handayani',
            'subject' => 'Pertanyaan Ijazah SMA KPS 2004',
        ]);

        $other = Contact::factory()->create([
            'name' => 'Rahmat Hidayat',
            'subject' => 'Usulan Donor Darah',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.contacts.index', ['search' => 'Dwi Handayani']));

        $response->assertOk();
        $response->assertSee('Dwi Handayani');
        $response->assertDontSee('Rahmat Hidayat');
    }

    public function test_viewing_message_automatically_marks_it_as_read(): void
    {
        $admin = User::factory()->admin()->create();
        $contact = Contact::factory()->create([
            'is_read' => false,
            'read_at' => null,
        ]);

        $this->assertFalse($contact->is_read);

        $response = $this->actingAs($admin)->get(route('admin.contacts.show', $contact->id));

        $response->assertOk();
        $response->assertSee($contact->subject);

        $this->assertTrue($contact->fresh()->is_read);
        $this->assertNotNull($contact->fresh()->read_at);
    }

    public function test_admin_can_toggle_read_status(): void
    {
        $admin = User::factory()->admin()->create();
        $contact = Contact::factory()->read()->create();

        // Toggle back to unread
        $response = $this->actingAs($admin)->patch(route('admin.contacts.read', $contact->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertFalse($contact->fresh()->is_read);
        $this->assertNull($contact->fresh()->read_at);

        // Toggle back to read
        $this->actingAs($admin)->patch(route('admin.contacts.read', $contact->id));
        $this->assertTrue($contact->fresh()->is_read);
        $this->assertNotNull($contact->fresh()->read_at);
    }

    public function test_admin_can_delete_message(): void
    {
        $admin = User::factory()->admin()->create();
        $contact = Contact::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.contacts.destroy', $contact->id));

        $response->assertRedirect(route('admin.contacts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }
}
