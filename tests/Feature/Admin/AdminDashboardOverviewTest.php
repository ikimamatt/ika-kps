<?php

namespace Tests\Feature\Admin;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_regular_alumni_cannot_access_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $alumni = User::factory()->alumni()->create();
        $this->actingAs($alumni)->get('/admin')->assertStatus(403);
    }

    public function test_admin_can_view_accurate_metrics_and_distribution_on_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        // Seed alumni with levels
        Alumnus::factory()->count(5)->create(['level' => 'sma', 'is_verified' => true]);
        Alumnus::factory()->count(3)->create(['level' => 'smp', 'is_verified' => true]);
        Alumnus::factory()->count(2)->create(['level' => 'sd', 'is_verified' => true]);
        Alumnus::factory()->count(1)->create(['level' => 'tk', 'is_verified' => true]);

        // Seed pending user
        User::factory()->count(4)->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');

        $response->assertViewHas('metrics', function ($metrics) {
            return $metrics['total_alumni'] === 11
                && $metrics['pending_registrations'] === 4;
        });

        $response->assertViewHas('alumniDistribution', function ($dist) {
            return $dist['sma'] === 5
                && $dist['smp'] === 3
                && $dist['sd'] === 2
                && $dist['tk'] === 1;
        });

        // Verifikasi elemen teks di dashboard
        $response->assertSee('Dashboard Pengurus &amp; Admin', false);
        $response->assertSee('SMA KPS');
        $response->assertSee('SMP KPS');
    }

    public function test_pengurus_can_also_access_admin_dashboard(): void
    {
        $pengurus = User::factory()->pengurus()->create();

        $response = $this->actingAs($pengurus)->get('/admin');

        $response->assertStatus(200);
    }
}
