# ⚙️ Task 12: Pengaturan Umum Website & Konfigurasi Dinamis

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🟢 Low / Management & Polish (Fase 4)  
> **Modul PRD:** Modul 12: Pengaturan Umum (BARU)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Modul Pengaturan Umum memberikan fleksibilitas bagi administrator untuk memperbarui informasi organisasi tanpa menyentuh kode program:
1. **Penyimpanan Konfigurasi Dinamis (Key-Value Store)**: Pengaturan informasi kontak sekretariat, tautan media sosial (Instagram, LinkedIn, YouTube, WhatsApp Community), nomor hotline, alamat fisik, dan tagline landing page.
2. **Sistem Caching Otomatis**: Pengambilan konfigurasi dioptimalkan menggunakan `Cache::rememberForever()` dengan invalidasi instan saat data diubah (*cache busting*).
3. **Panel Pengaturan Admin (`/admin/settings`)**: Antarmuka formulir terbagi dalam tab teratur (Tab Umum, Tab Kontak & Medsos, Tab Brand & Tampilan).

---

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration create_settings_table
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_create_settings_table.php`):

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general')->index(); // 'general', 'contact', 'social'
            $table->string('type')->default('text'); // 'text', 'textarea', 'image'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
```

---

## 3. Model, Helper & Seeder

### A. Model `app/Models/Setting.php`

```bash
php artisan make:model Setting
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Ambil nilai konfigurasi dengan caching.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Simpan atau perbarui nilai konfigurasi dan hapus cache.
     */
    public static function set(string $key, mixed $value, string $group = 'general', string $type = 'text'): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );

        Cache::forget("setting.{$key}");

        return $setting;
    }
}
```

### B. Seeder `database/seeders/SettingSeeder.php`

```bash
php artisan make:seeder SettingSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSettings = [
            // Identitas Organisasi
            ['key' => 'org_name', 'value' => 'IKA KPS Balikpapan', 'group' => 'general'],
            ['key' => 'org_tagline', 'value' => 'Satu Almamater, Seribu Cerita', 'group' => 'general'],
            ['key' => 'org_description', 'value' => 'Ikatan Keluarga Alumni Sekolah Nasional KPS Balikpapan lintas generasi.', 'group' => 'general'],

            // Kontak & Lokasi
            ['key' => 'contact_email', 'value' => 'sekretariat@ikakps.org', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '0542-731234', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '08115401234', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Sekretariat IKA KPS, Komp. Sekolah Nasional KPS, Balikpapan, Kalimantan Timur', 'group' => 'contact'],

            // Media Sosial
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/ikakps_official', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/ika-kps', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@ikakpsbalikpapan', 'group' => 'social'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::set($setting['key'], $setting['value'], $setting['group']);
        }
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Setting/SettingManagementTest
```

### Skenario Uji TDD:

```php
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

    public function test_admin_can_update_settings_via_panel(): void
    {
        $admin = User::factory()->admin()->create();

        $payload = [
            'settings' => [
                'org_name' => 'IKA KPS Balikpapan Jaya',
                'contact_email' => 'halo@ikakps.org',
            ],
        ];

        $response = $this->actingAs($admin)->post('/admin/settings', $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('IKA KPS Balikpapan Jaya', Setting::get('org_name'));
        $this->assertEquals('halo@ikakps.org', Setting::get('contact_email'));
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan migrasi tabel `settings`
- [ ] Atur Model `Setting` dengan static method cache `get()` dan `set()`
- [ ] Buat Form Request `UpdateSettingsRequest`
- [ ] Buat Controller `AdminSettingController`
- [ ] Buat tampilan Blade `admin/settings/index.blade.php` (Tampilan tab modern)
- [ ] Buat seeder konfigurasi awal dan jalankan
- [ ] Tulis test TDD dan jalankan
- [ ] Format kode: `vendor/bin/pint --dirty --format agent`
