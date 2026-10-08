# 📊 Task 11: Admin Layout & Dashboard Overview

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🔴 Critical / Foundation (Fase 1)  
> **Modul PRD:** Modul 11: Dashboard Admin  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role)  

---

## 1. Deskripsi Fitur

Dashboard Admin merupakan pusat kendali operasional bagi Pengurus dan Administrator IKA KPS:
1. **Master Layout Admin (`layouts/admin.blade.php`)**: Sidebar terstruktur yang mencakup 10 modul, drawer responsif untuk tampilan smartphone/tablet, topbar dengan foto profil admin, tautan langsung ke website publik, serta badge indikator real-time untuk data yang butuh perhatian (*pending approvals* dan *unread messages*).
2. **Dashboard Metrics Overview (`/admin`)**: Kartu statistik ringkas menampilkan:
   - Total Alumni Terverifikasi
   - Pendaftaran Baru Menunggu Persetujuan (*Pending*)
   - Total Bisnis Alumni Aktif
   - Lowongan Kerja Buka
   - Agenda Event Mendatang
3. **Quick Action & Tabel Antrean**: Tabel 5 permohonan alumni pending terbaru untuk tindakan cepat (*One-Click Review*) dan ringkasan distribusi alumni per jenjang pendidikan.

---

## 2. Struktur Navigasi Sidebar

```
📊 Dashboard Overview (/admin)
👥 Direktori Alumni (/admin/alumni)
📋 Verifikasi Pendaftaran (/admin/registrations) [Badge: Pending Count]
🏢 Bisnis Alumni (/admin/businesses)
💼 Lowongan Kerja (/admin/job-vacancies)
📰 Berita & Artikel (/admin/articles)
📅 Event & Agenda (/admin/events)
🖼️ Galeri Foto (/admin/galleries)
🎯 Program Kerja (/admin/programs)
📬 Kotak Masuk (/admin/contacts) [Badge: Unread Count]
⚙️ Pengaturan Website (/admin/settings)
```

---

## 3. Komponen Controller & Pengumpulan Metrik

### Controller `app/Http/Controllers/Admin/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\Business;
use App\Models\Contact;
use App\Models\Event;
use App\Models\JobVacancy;
use App\Models\Registration;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'total_alumni' => Alumnus::where('is_verified', true)->count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'active_businesses' => Business::where('status', 'published')->count(),
            'active_jobs' => JobVacancy::active()->count(),
            'upcoming_events' => Event::where('status', 'published')->where('start_date', '>=', now())->count(),
            'unread_contacts' => Contact::unread()->count(),
        ];

        $latestRegistrations = Registration::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $alumniDistribution = [
            'sma' => Alumnus::where('level', 'sma')->count(),
            'smp' => Alumnus::where('level', 'smp')->count(),
            'sd' => Alumnus::where('level', 'sd')->count(),
            'tk' => Alumnus::where('level', 'tk')->count(),
        ];

        return view('admin.dashboard', compact('metrics', 'latestRegistrations', 'alumniDistribution'));
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Admin/AdminDashboardOverviewTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Alumnus;
use App\Models\Contact;
use App\Models\Registration;
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

    public function test_admin_can_view_accurate_metrics_on_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        Alumnus::factory()->count(15)->create(['is_verified' => true]);
        Registration::factory()->count(4)->create(['status' => 'pending']);
        Contact::factory()->count(3)->create(['is_read' => false]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewHas('metrics', function ($metrics) {
            return $metrics['total_alumni'] === 15
                && $metrics['pending_registrations'] === 4
                && $metrics['unread_contacts'] === 3;
        });

        // Verifikasi badge pada tampilan
        $response->assertSee('4'); // Pending registrations count badge
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
```

---

## 6. Checklist Implementasi

- [ ] Buat layout Blade master admin `resources/views/layouts/admin.blade.php`
- [ ] Buat komponen partial sidebar `resources/views/components/admin/sidebar.blade.php` dengan badge dinamis
- [ ] Buat komponen topbar `resources/views/components/admin/topbar.blade.php`
- [ ] Buat view `resources/views/admin/dashboard.blade.php` dengan grid metrik modern Tailwind CSS
- [ ] Implementasikan `DashboardController`
- [ ] Tulis test TDD dan verifikasi kelulusan pengujian
- [ ] Format kode: `vendor/bin/pint --dirty --format agent`
