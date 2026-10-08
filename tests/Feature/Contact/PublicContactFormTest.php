<?php

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_public_contact_page(): void
    {
        $response = $this->get(route('contact.show'));

        $response->assertOk();
        $response->assertSee('Hubungi Pengurus IKA KPS Balikpapan');
        $response->assertSee('sekretariat@ika-kps.id');
    }

    public function test_public_can_submit_contact_message(): void
    {
        $payload = [
            'name' => 'Wahyudi Santoso',
            'email' => 'wahyudi@example.com',
            'phone' => '081234567890',
            'subject' => 'Kerjasama Kegiatan Donor Darah',
            'message' => 'Halo Pengurus IKA KPS, kami dari PMI Balikpapan ingin mengajak kolaborasi kegiatan baksos donor darah...',
            'hp_check' => '', // Honeypot field (harus kosong)
        ];

        $response = $this->post(route('contact.store'), $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'email' => 'wahyudi@example.com',
            'subject' => 'Kerjasama Kegiatan Donor Darah',
            'is_read' => false,
        ]);
    }

    public function test_bot_spam_is_rejected_when_honeypot_is_filled(): void
    {
        $payload = [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'subject' => 'Buy Crypto Now',
            'message' => 'Spam link here to invest in crypto...',
            'hp_check' => 'http://spam-link.com', // Terisi oleh spam bot
        ];

        $response = $this->post(route('contact.store'), $payload);

        // Jangan simpan ke database jika bot terdeteksi
        $this->assertDatabaseMissing('contacts', [
            'email' => 'bot@spammer.com',
        ]);
    }

    public function test_validation_fails_for_invalid_input(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'subject' => '',
            'message' => 'pendek',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}
