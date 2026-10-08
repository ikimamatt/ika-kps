<?php

namespace Tests\Feature\Admin;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAlumniCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_alumni(): void
    {
        $response = $this->get('/admin/alumni');

        $response->assertRedirect('/login');
    }

    public function test_alumni_user_is_forbidden_from_admin_alumni(): void
    {
        $alumni = User::factory()->alumni()->create();

        $response = $this->actingAs($alumni)->get('/admin/alumni');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_all_alumni_including_unverified(): void
    {
        $admin = User::factory()->admin()->create();

        $verified = Alumnus::factory()->create([
            'name' => 'Alumni Terverifikasi',
            'is_verified' => true,
        ]);

        $unverified = Alumnus::factory()->unverified()->create([
            'name' => 'Alumni Belum Terverifikasi',
        ]);

        $response = $this->actingAs($admin)->get('/admin/alumni');

        $response->assertStatus(200);
        $response->assertSee('Alumni Terverifikasi');
        $response->assertSee('Alumni Belum Terverifikasi');
    }

    public function test_admin_can_view_create_alumni_page(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/alumni/create');

        $response->assertStatus(200);
        $response->assertSee('Tambah Data Alumni');
    }

    public function test_admin_can_create_alumnus_with_valid_payload(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'name' => 'Bayu Wicaksono',
            'title' => 'S.T., M.Sc.',
            'level' => 'sma',
            'graduation_year' => '2010',
            'profession' => 'Geophysicist',
            'institution' => 'Schlumberger',
            'domicile' => 'Balikpapan',
            'email' => 'bayu@example.com',
            'phone' => '0811559988',
            'summary' => 'Spesialis eksplorasi seismik lepas pantai.',
            'is_verified' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/alumni', $payload);

        $response->assertRedirect('/admin/alumni');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('alumni', [
            'name' => 'Bayu Wicaksono',
            'email' => 'bayu@example.com',
            'level' => 'sma',
            'full_year' => '2010',
            'is_verified' => true,
        ]);
    }

    public function test_validation_errors_when_required_alumnus_fields_are_missing(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post('/admin/alumni', [
            'name' => '',
            'level' => 'invalid_level',
        ]);

        $response->assertSessionHasErrors(['name', 'level']);
    }

    public function test_admin_can_view_edit_alumni_page(): void
    {
        $admin = User::factory()->admin()->create();
        $alumnus = Alumnus::factory()->create();

        $response = $this->actingAs($admin)->get('/admin/alumni/'.$alumnus->id.'/edit');

        $response->assertStatus(200);
        $response->assertSee($alumnus->name);
    }

    public function test_admin_can_update_alumnus(): void
    {
        $admin = User::factory()->admin()->create();
        $alumnus = Alumnus::factory()->create([
            'name' => 'Nama Lama',
            'level' => 'sma',
        ]);

        $response = $this->actingAs($admin)->put('/admin/alumni/'.$alumnus->id, [
            'name' => 'Nama Diperbarui',
            'level' => 'smp',
            'graduation_year' => '2008',
            'profession' => 'Konsultan Bisnis',
        ]);

        $response->assertRedirect('/admin/alumni');
        $response->assertSessionHas('success');

        $alumnus->refresh();
        $this->assertEquals('Nama Diperbarui', $alumnus->name);
        $this->assertEquals('smp', $alumnus->level);
        $this->assertEquals('2008', $alumnus->full_year);
    }

    public function test_admin_can_soft_delete_and_restore_alumnus(): void
    {
        $admin = User::factory()->admin()->create();
        $alumnus = Alumnus::factory()->create();

        // Soft delete
        $deleteResponse = $this->actingAs($admin)->delete('/admin/alumni/'.$alumnus->id);
        $deleteResponse->assertRedirect('/admin/alumni');
        $this->assertSoftDeleted('alumni', ['id' => $alumnus->id]);

        // Restore
        $restoreResponse = $this->actingAs($admin)->post('/admin/alumni/'.$alumnus->id.'/restore');
        $restoreResponse->assertRedirect('/admin/alumni');
        $this->assertNotSoftDeleted('alumni', ['id' => $alumnus->id]);
    }

    public function test_admin_can_toggle_verification_status(): void
    {
        $admin = User::factory()->admin()->create();
        $alumnus = Alumnus::factory()->unverified()->create();

        $response = $this->actingAs($admin)->patch('/admin/alumni/'.$alumnus->id.'/toggle-verification');

        $response->assertRedirect();
        $alumnus->refresh();
        $this->assertTrue($alumnus->is_verified);
        $this->assertNotNull($alumnus->verified_at);
        $this->assertEquals($admin->id, $alumnus->verified_by);
    }

    public function test_admin_can_upload_avatar_when_creating_alumnus(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $file = UploadedFile::fake()->image('alumni-photo.jpg');

        $response = $this->actingAs($admin)->post('/admin/alumni', [
            'name' => 'Alumni Baru Berfoto',
            'level' => 'sma',
            'avatar' => $file,
        ]);

        $response->assertRedirect('/admin/alumni');

        $alumnus = Alumnus::where('name', 'Alumni Baru Berfoto')->first();
        $this->assertNotNull($alumnus);
        $this->assertNotNull($alumnus->avatar_url);
        $this->assertStringContainsString('/storage/avatars/', $alumnus->avatar_url);

        $path = str_replace('/storage/', '', $alumnus->avatar_url);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_update_alumnus_avatar(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $alumnus = Alumnus::factory()->create([
            'name' => 'Alumni Update Foto',
        ]);

        $file = UploadedFile::fake()->image('updated-photo.jpg');

        $response = $this->actingAs($admin)->put('/admin/alumni/'.$alumnus->id, [
            'name' => 'Alumni Update Foto',
            'level' => 'sma',
            'avatar' => $file,
        ]);

        $response->assertRedirect('/admin/alumni');

        $alumnus->refresh();
        $this->assertNotNull($alumnus->avatar_url);
        $this->assertStringContainsString('/storage/avatars/', $alumnus->avatar_url);

        $path = str_replace('/storage/', '', $alumnus->avatar_url);
        Storage::disk('public')->assertExists($path);
    }
}
