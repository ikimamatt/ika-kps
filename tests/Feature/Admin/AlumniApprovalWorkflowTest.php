<?php

namespace Tests\Feature\Admin;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AlumniApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Register dummy test routes protected by alumni.approved
        Route::middleware(['web', 'auth', 'alumni.approved'])->group(function () {
            Route::get('/testing/gated-feature', function () {
                return 'gated-access-granted';
            });
        });
    }

    public function test_guest_cannot_access_verification_queue(): void
    {
        $response = $this->get('/admin/verifikasi');

        $response->assertRedirect('/login');
    }

    public function test_regular_alumni_is_forbidden_from_verification_queue(): void
    {
        $alumni = User::factory()->alumni()->create();

        $response = $this->actingAs($alumni)->get('/admin/verifikasi');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_pending_alumni_verification_queue(): void
    {
        $admin = User::factory()->admin()->create();

        $pendingUser = User::factory()->pending()->create();
        Alumnus::factory()->unverified()->create([
            'user_id' => $pendingUser->id,
            'name' => 'Calon Alumni Baru',
        ]);

        $response = $this->actingAs($admin)->get('/admin/verifikasi');

        $response->assertStatus(200);
        $response->assertSee('Calon Alumni Baru');
    }

    public function test_admin_can_filter_queue_by_status(): void
    {
        $admin = User::factory()->admin()->create();

        $pendingUser = User::factory()->pending()->create();
        Alumnus::factory()->unverified()->create([
            'user_id' => $pendingUser->id,
            'name' => 'Alumni Status Pending',
        ]);

        $activeUser = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $activeUser->id,
            'name' => 'Alumni Status Aktif',
            'is_verified' => true,
        ]);

        // Default tab shows pending
        $responsePending = $this->actingAs($admin)->get('/admin/verifikasi?status=pending');
        $responsePending->assertStatus(200);
        $responsePending->assertSee('Alumni Status Pending');
        $responsePending->assertDontSee('Alumni Status Aktif');

        // Approved tab shows active
        $responseApproved = $this->actingAs($admin)->get('/admin/verifikasi?status=approved');
        $responseApproved->assertStatus(200);
        $responseApproved->assertSee('Alumni Status Aktif');
        $responseApproved->assertDontSee('Alumni Status Pending');
    }

    public function test_admin_can_approve_pending_alumnus(): void
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->pending()->create();
        $alumnus = Alumnus::factory()->unverified()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$alumnus->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $alumnus->refresh();

        $this->assertEquals('active', $user->status);
        $this->assertTrue($alumnus->is_verified);
        $this->assertEquals($admin->id, $alumnus->verified_by);
        $this->assertNotNull($alumnus->verified_at);
    }

    public function test_approved_alumnus_is_immediately_visible_in_public_directory(): void
    {
        $alumnus = Alumnus::factory()->create([
            'name' => 'Hendra Setiawan',
            'is_verified' => true,
        ]);

        $response = $this->get('/alumni');

        $response->assertStatus(200);
        $response->assertSee('Hendra Setiawan');
    }

    public function test_admin_can_reject_alumnus_with_reason(): void
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->pending()->create();
        $alumnus = Alumnus::factory()->unverified()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$alumnus->id}/reject", [
            'rejection_reason' => 'Identitas angkatan tidak dapat dikonfirmasi oleh pengurus.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $alumnus->refresh();

        $this->assertEquals('rejected', $user->status);
        $this->assertEquals('Identitas angkatan tidak dapat dikonfirmasi oleh pengurus.', $user->rejection_reason);
        $this->assertFalse($alumnus->is_verified);
    }

    public function test_rejection_requires_a_reason(): void
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->pending()->create();
        $alumnus = Alumnus::factory()->unverified()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$alumnus->id}/reject", [
            'rejection_reason' => '',
        ]);

        $response->assertSessionHasErrors('rejection_reason');
    }

    public function test_unapproved_alumni_cannot_access_gated_features(): void
    {
        $unapprovedUser = User::factory()->pending()->create();
        Alumnus::factory()->unverified()->create([
            'user_id' => $unapprovedUser->id,
        ]);

        $response = $this->actingAs($unapprovedUser)->get('/testing/gated-feature');

        $response->assertRedirect('/profil');
        $response->assertSessionHas('warning');
    }

    public function test_approved_alumni_can_access_gated_features(): void
    {
        $approvedUser = User::factory()->create(['status' => 'active']);
        Alumnus::factory()->create([
            'user_id' => $approvedUser->id,
            'is_verified' => true,
        ]);

        $response = $this->actingAs($approvedUser)->get('/testing/gated-feature');

        $response->assertStatus(200);
        $response->assertSee('gated-access-granted');
    }

    public function test_admin_can_bypass_gated_features(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/testing/gated-feature');

        $response->assertStatus(200);
        $response->assertSee('gated-access-granted');
    }
}
