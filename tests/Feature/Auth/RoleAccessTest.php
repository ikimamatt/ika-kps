<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_alumni_user_is_forbidden_from_admin_area(): void
    {
        $alumni = User::factory()->alumni()->create();

        $response = $this->actingAs($alumni)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_area(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_pengurus_user_can_access_admin_area(): void
    {
        $pengurus = User::factory()->pengurus()->create();

        $response = $this->actingAs($pengurus)->get('/admin');

        $response->assertStatus(200);
    }
}
