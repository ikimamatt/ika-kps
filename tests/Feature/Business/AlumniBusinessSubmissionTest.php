<?php

namespace Tests\Feature\Business;

use App\Models\Alumnus;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AlumniBusinessSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_access_business_submission(): void
    {
        $this->get('/profil/bisnis/create')->assertRedirect('/login');
    }

    public function test_unapproved_alumni_blocked_from_submitting_business(): void
    {
        $user = User::factory()->pending()->create();
        Alumnus::factory()->unverified()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/profil/bisnis/create');

        $response->assertRedirect('/profil');
        $response->assertSessionHas('warning');
    }

    public function test_approved_alumni_can_view_create_business_form(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)->get('/profil/bisnis/create');

        $response->assertStatus(200);
        $response->assertSee('Daftarkan Usaha / Bisnis Alumni');
    }

    public function test_approved_alumni_can_submit_business(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'name' => 'Budi Santoso',
            'is_verified' => true,
        ]);

        $image = UploadedFile::fake()->image('kedai.jpg');

        $payload = [
            'name' => 'Kedai Kopi Alumni 99',
            'category' => 'Kuliner & F&B',
            'description' => 'Warung kopi khas Balikpapan dengan suasana hangat.',
            'image' => $image,
            'action_type' => 'whatsapp',
            'action_label' => 'Order via WA',
            'whatsapp_number' => '081234567890',
            'address' => 'Jl. MT Haryono No. 12',
            'city' => 'Balikpapan',
        ];

        $response = $this->actingAs($user)->post('/profil/bisnis', $payload);

        $response->assertRedirect('/profil/bisnis');
        $response->assertSessionHas('success');

        $business = Business::where('name', 'Kedai Kopi Alumni 99')->first();
        $this->assertNotNull($business);
        $this->assertEquals($alumnus->id, $business->alumnus_id);
        $this->assertEquals('pending', $business->status); // Submitted as pending approval
        $this->assertNotNull($business->image_url);
    }

    public function test_approved_alumni_can_view_edit_form_for_their_business(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $business = Business::factory()->create([
            'alumnus_id' => $alumnus->id,
            'name' => 'Bisnis Saya',
        ]);

        $response = $this->actingAs($user)->get("/profil/bisnis/{$business->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Edit Informasi Usaha Alumni');
        $response->assertSee('Bisnis Saya');
    }

    public function test_alumni_cannot_edit_another_alumnis_business(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $otherAlumnus = Alumnus::factory()->create();
        $otherBusiness = Business::factory()->create([
            'alumnus_id' => $otherAlumnus->id,
            'name' => 'Bisnis Alumni Lain',
        ]);

        $response = $this->actingAs($user)->get("/profil/bisnis/{$otherBusiness->id}/edit");
        $response->assertForbidden();

        $responseUpdate = $this->actingAs($user)->put("/profil/bisnis/{$otherBusiness->id}", [
            'name' => 'Coba Ubah Paksa',
            'category' => 'Kuliner & F&B',
            'description' => 'Mencoba meretas',
        ]);
        $responseUpdate->assertForbidden();
    }

    public function test_alumni_can_update_their_business(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $business = Business::factory()->create([
            'alumnus_id' => $alumnus->id,
            'name' => 'Nama Usaha Lama',
        ]);

        $response = $this->actingAs($user)->put("/profil/bisnis/{$business->id}", [
            'name' => 'Nama Usaha Baru Diupdate',
            'category' => 'Kuliner & F&B',
            'description' => 'Deskripsi baru yang diperbarui oleh pemilik usaha.',
            'whatsapp_number' => '081299998888',
            'city' => 'Balikpapan',
        ]);

        $response->assertRedirect('/profil/bisnis');
        $response->assertSessionHas('success');

        $business->refresh();
        $this->assertEquals('Nama Usaha Baru Diupdate', $business->name);
        $this->assertEquals('081299998888', $business->whatsapp_number);
    }

    public function test_alumni_can_delete_their_business(): void
    {
        $user = User::factory()->create(['status' => 'active']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $business = Business::factory()->create([
            'alumnus_id' => $alumnus->id,
        ]);

        $response = $this->actingAs($user)->delete("/profil/bisnis/{$business->id}");

        $response->assertRedirect('/profil/bisnis');
        $response->assertSessionHas('success');

        $this->assertSoftDeleted('businesses', [
            'id' => $business->id,
        ]);
    }
}
