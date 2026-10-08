<?php

namespace Tests\Feature\Admin;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBusinessCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_alumni_forbidden_from_admin_businesses(): void
    {
        $this->get('/admin/businesses')->assertRedirect('/login');

        $alumni = User::factory()->alumni()->create();
        $this->actingAs($alumni)->get('/admin/businesses')->assertStatus(403);
    }

    public function test_admin_can_view_businesses_index(): void
    {
        $admin = User::factory()->admin()->create();
        $business = Business::factory()->create(['name' => 'Bisnis Unggulan']);

        $response = $this->actingAs($admin)->get('/admin/businesses');

        $response->assertStatus(200);
        $response->assertSee('Bisnis Unggulan');
    }

    public function test_admin_can_toggle_publish_business(): void
    {
        $admin = User::factory()->admin()->create();
        $business = Business::factory()->pending()->create();

        $response = $this->actingAs($admin)->patch("/admin/businesses/{$business->id}/publish");

        $response->assertRedirect();
        $business->refresh();
        $this->assertEquals('published', $business->status);
    }

    public function test_admin_can_update_business(): void
    {
        $admin = User::factory()->admin()->create();
        $business = Business::factory()->create(['name' => 'Nama Usaha Lama']);

        $response = $this->actingAs($admin)->put("/admin/businesses/{$business->id}", [
            'name' => 'Nama Usaha Baru',
            'category' => 'Kuliner & F&B',
            'description' => 'Deskripsi usaha yang diperbarui.',
            'status' => 'published',
            'city' => 'Balikpapan',
        ]);

        $response->assertRedirect('/admin/businesses');
        $business->refresh();
        $this->assertEquals('Nama Usaha Baru', $business->name);
    }

    public function test_admin_can_delete_business(): void
    {
        $admin = User::factory()->admin()->create();
        $business = Business::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/businesses/{$business->id}");

        $response->assertRedirect('/admin/businesses');
        $this->assertSoftDeleted('businesses', ['id' => $business->id]);
    }
}
