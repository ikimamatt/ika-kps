<?php

namespace Tests\Feature\Auth;

use App\Models\Alumnus;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_view_profile_page(): void
    {
        $response = $this->get('/profil');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Bambang Alumni',
            'email' => 'bambang@example.com',
        ]);

        $response = $this->actingAs($user)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Bambang Alumni');
        $response->assertSee('bambang@example.com');
    }

    public function test_user_can_update_their_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'phone' => '0811111111',
        ]);

        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'Nama Baru Alumni',
            'phone' => '0899999999',
        ]);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('Nama Baru Alumni', $user->name);
        $this->assertEquals('0899999999', $user->phone);
    }

    public function test_user_can_update_their_alumni_profile_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'phone' => '0811111111',
        ]);

        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'name' => 'Nama Lama',
            'level' => 'sma',
            'full_year' => '2005',
        ]);

        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'Nama Baru Alumni',
            'title' => 'S.T.',
            'phone' => '081234567890',
            'level' => 'smp',
            'graduation_year' => '2008',
            'profession' => 'Senior Geologist',
            'institution' => 'Pertamina Hulu',
            'domicile' => 'Balikpapan',
            'summary' => 'Bio ringkasan karir.',
            'avatar_url' => 'https://example.com/avatar.jpg',
            'linkedin_url' => 'https://linkedin.com/in/namabaru',
            'instagram_handle' => '@namabaru',
        ]);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('success');

        $user->refresh();
        $alumnus->refresh();

        $this->assertEquals('Nama Baru Alumni', $user->name);
        $this->assertEquals('081234567890', $user->phone);
        $this->assertEquals('Nama Baru Alumni', $alumnus->name);
        $this->assertEquals('S.T.', $alumnus->title);
        $this->assertEquals('smp', $alumnus->level);
        $this->assertEquals('2008', $alumnus->full_year);
        $this->assertEquals('Senior Geologist', $alumnus->profession);
        $this->assertEquals('Pertamina Hulu', $alumnus->institution);
        $this->assertEquals('Balikpapan', $alumnus->domicile);
        $this->assertEquals('Bio ringkasan karir.', $alumnus->summary);
        $this->assertEquals('https://example.com/avatar.jpg', $alumnus->avatar_url);
    }

    public function test_user_can_update_their_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('old-password-123'),
        ]);

        $response = $this->actingAs($user)->put('/profil/password', [
            'current_password' => 'old-password-123',
            'password' => 'new-secure-password-456',
            'password_confirmation' => 'new-secure-password-456',
        ]);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(password_verify('new-secure-password-456', $user->password));
    }

    public function test_updating_password_fails_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('correct-old-password'),
        ]);

        $response = $this->actingAs($user)->from('/profil/edit')->put('/profil/password', [
            'current_password' => 'wrong-current-password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect('/profil/edit');
        $response->assertSessionHasErrors('current_password');
    }

    public function test_user_can_upload_avatar_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Bambang Sukses',
        ]);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'name' => 'Bambang Sukses',
        ]);

        $file = UploadedFile::fake()->image('bambang-avatar.jpg', 300, 300);

        $response = $this->actingAs($user)->put('/profil', [
            'name' => 'Bambang Sukses Update',
            'avatar' => $file,
        ]);

        $response->assertRedirect('/profil');
        $response->assertSessionHas('success');

        $user->refresh();
        $alumnus->refresh();

        $this->assertNotNull($user->avatar_url);
        $this->assertStringContainsString('/storage/avatars/', $user->avatar_url);
        $this->assertEquals($user->avatar_url, $alumnus->avatar_url);

        $storedPath = str_replace('/storage/', '', $user->avatar_url);
        Storage::disk('public')->assertExists($storedPath);
    }

    public function test_updating_avatar_replaces_and_deletes_old_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['name' => 'Alumni Foto']);

        // Upload first avatar
        $firstFile = UploadedFile::fake()->image('avatar1.jpg');
        $this->actingAs($user)->put('/profil', [
            'name' => 'Alumni Foto',
            'avatar' => $firstFile,
        ]);

        $user->refresh();
        $firstPath = str_replace('/storage/', '', $user->avatar_url);
        Storage::disk('public')->assertExists($firstPath);

        // Upload second avatar
        $secondFile = UploadedFile::fake()->image('avatar2.png');
        $this->actingAs($user)->put('/profil', [
            'name' => 'Alumni Foto',
            'avatar' => $secondFile,
        ]);

        $user->refresh();
        $secondPath = str_replace('/storage/', '', $user->avatar_url);

        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
    }

    public function test_avatar_upload_fails_if_file_is_not_an_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create(['name' => 'User Non Image']);
        $pdfFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->from('/profil/edit')->put('/profil', [
            'name' => 'User Non Image',
            'avatar' => $pdfFile,
        ]);

        $response->assertRedirect('/profil/edit');
        $response->assertSessionHasErrors('avatar');
    }

    public function test_navbar_displays_profile_dropdown_menu_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Faisal Reza',
            'email' => 'faisal@example.com',
            'role' => 'alumni',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('profile-dropdown-btn');
        $response->assertSee('profile-dropdown-menu');
        $response->assertSee('Profil Saya');
        $response->assertSee('Katalog Bisnis Saya');
        $response->assertSee('Keluar Akun');
    }

    public function test_profile_page_displays_registered_businesses(): void
    {
        $user = User::factory()->create([
            'name' => 'Siti Khadijah',
            'status' => 'active',
        ]);

        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => true,
        ]);

        $business = Business::factory()->create([
            'alumnus_id' => $alumnus->id,
            'name' => 'Kue Tradisional Balikpapan',
            'status' => 'published',
        ]);

        $response = $this->actingAs($user)->get('/profil');

        $response->assertStatus(200);
        $response->assertSee('Katalog Bisnis &amp; UMKM Saya', false);
        $response->assertSee('Kue Tradisional Balikpapan');
    }
}
