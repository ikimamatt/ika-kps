<?php

namespace Tests\Feature\Setting;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SettingManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_setting_helper_can_retrieve_default_value_and_cache_it(): void
    {
        $val = Setting::get('non_existent_key', 'DefaultValue');
        $this->assertEquals('DefaultValue', $val);

        Setting::set('org_name', 'IKA KPS Updated');
        $this->assertEquals('IKA KPS Updated', Setting::get('org_name'));
        $this->assertEquals('IKA KPS Updated', Cache::get('setting.org_name'));
    }

    public function test_guest_and_regular_user_cannot_access_settings_panel(): void
    {
        $guestResponse = $this->get(route('admin.settings.index'));
        $guestResponse->assertRedirect('/login');

        $regularUser = User::factory()->create(['role' => 'alumni']);
        $userResponse = $this->actingAs($regularUser)->get(route('admin.settings.index'));
        $userResponse->assertForbidden();
    }

    public function test_admin_can_view_settings_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));

        $response->assertOk();
        $response->assertSee('Pengaturan Umum & Konfigurasi Website');
        $response->assertSee('Identitas Organisasi');
    }

    public function test_admin_can_update_settings_via_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'settings' => [
                'org_name' => 'IKA KPS Balikpapan Jaya',
                'contact_email' => 'halo@ikakps.org',
                'contact_whatsapp' => '081234567890',
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('IKA KPS Balikpapan Jaya', Setting::get('org_name'));
        $this->assertEquals('halo@ikakps.org', Setting::get('contact_email'));
        $this->assertEquals('081234567890', Setting::get('contact_whatsapp'));
    }

    public function test_public_pages_render_dynamic_settings(): void
    {
        Setting::set('org_name', 'IKA KPS Nusantara');
        Setting::set('org_description', 'Deskripsi khusus organisasi alumni KPS terbaru.');
        Setting::set('contact_address', 'Jl. Jenderal Sudirman No. 99 Balikpapan');

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('IKA KPS Nusantara');
        $response->assertSee('Deskripsi khusus organisasi alumni KPS terbaru.');
        $response->assertSee('Jl. Jenderal Sudirman No. 99 Balikpapan');
    }
}
