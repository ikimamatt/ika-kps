# 📋 Task 03: Verifikasi & Persetujuan Keanggotaan Alumni Baru

> **Status:** Selesai (Completed ✅)  
> **Prioritas:** 🔴 Critical / Foundation (Fase 1)  
> **Modul PRD:** Modul 3: Verifikasi & Persetujuan Keanggotaan Alumni (Approval Workflow)  
> **Ketergantungan:** Task 01 (Autentikasi & Registrasi Terpadu), Task 02 (Manajemen Alumni)  

---

## 1. Deskripsi Fitur

Platform IKA KPS menerapkan prinsip pendaftaran mandiri terpadu (*Unified Self-Registration*). Karena tidak adanya basis data arsip lama, seluruh profil alumni tercipta dari pendaftaran mandiri oleh alumni yang bersangkutan:
1. **Status Akun Baru Menunggu Verifikasi (*Pending Review*)**:
   - Saat alumni mendaftar akun di website, sistem langsung membuat data akun login di `users` (`status: pending`) dan profil di `alumni` (`is_verified: false`).
   - Alumni dapat langsung login, namun melihat banner notifikasi bahwa akunnya sedang ditinjau pengurus.
2. **Dashboard Verifikasi Pengurus (`/admin/verifikasi`)**:
   - Menampilkan daftar permohonan keanggotaan baru dengan badge jumlah antrean pending di sidebar admin.
   - Filter tab: *Pending (Menunggu)*, *Approved (Aktif)*, dan *Rejected (Ditolak)*.
3. **Aksi Persetujuan (Approve) & Penolakan (Reject)**:
   - **Setujui (Approve)**:
     - Mengubah status akun pengguna menjadi `active`.
     - Mengubah profil alumni menjadi `is_verified = true` dan mencatat admin pemverifikasi.
     - **Profil alumni otomatis langsung tayang di direktori publik `/alumni`**.
     - **Hak akses penuh terbuka otomatis**: Pasang lowongan kerja, submit bisnis alumni, dan RSVP agenda kegiatan.
   - **Tolak (Reject)**:
     - Mengubah status akun menjadi `rejected` dan menyimpan alasan penolakan (*rejection reason*).
     - Pengguna dapat membaca alasan penolakan saat masuk ke akun.

---

## 2. Arsitektur Basis Data (Tanpa Tabel Tambahan)

> [!IMPORTANT]
> **Tidak Menggunakan Tabel `registrations`**:
> Redundansi tabel `registrations` dihilangkan. Seluruh data identitas akun tersimpan di tabel `users` dan data keanggotaan/almamater tersimpan di tabel `alumni` yang terhubung secara 1-to-1 (`user_id`).

### Kolom Workflow pada Tabel Terkait:
- **Tabel `users`**:
  - `status`: enum/string (`pending`, `active`, `rejected`, `suspended`) — default `pending`
  - `rejection_reason`: text nullable
- **Tabel `alumni`**:
  - `is_verified`: boolean — default `false`
  - `verified_at`: timestamp nullable
  - `verified_by`: foreignId nullable (constrained ke `users`)

---

## 3. Middleware Proteksi Hak Akses Alumni

Buat middleware `EnsureAlumniIsApproved` (`app/Http/Middleware/EnsureAlumniIsApproved.php`):

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAlumniIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Admin dan pengurus selalu diizinkan
        if ($user->isAdmin() || $user->isPengurus()) {
            return $next($request);
        }

        // Alumni harus memiliki status active dan alumnus terverifikasi
        if ($user->status !== 'active' || ! ($user->alumnus?->is_verified)) {
            return redirect()->route('profile.show')->with('warning', 'Fitur ini hanya dapat diakses setelah keanggotaan alumni Anda disetujui oleh pengurus.');
        }

        return $next($request);
    }
}
```

Daftarkan alias `'alumni.approved'` di `bootstrap/app.php`:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    'alumni.approved' => \App\Http\Middleware\EnsureAlumniIsApproved::class,
]);
```

---

## 4. Panduan & Skenario TDD (Test-Driven Development)

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Admin/AlumniApprovalWorkflowTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Admin;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_pending_alumni_verification_queue(): void
    {
        $admin = User::factory()->admin()->create();

        $pendingUser = User::factory()->create(['status' => 'pending']);
        Alumnus::factory()->create([
            'user_id' => $pendingUser->id,
            'name' => 'Calon Alumni Baru',
            'is_verified' => false,
        ]);

        $response = $this->actingAs($admin)->get('/admin/verifikasi');

        $response->assertStatus(200);
        $response->assertSee('Calon Alumni Baru');
    }

    public function test_admin_can_approve_pending_alumnus(): void
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->create(['status' => 'pending']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
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

    public function test_unapproved_alumni_cannot_access_gated_submission_features(): void
    {
        $unapprovedUser = User::factory()->create(['status' => 'pending']);
        Alumnus::factory()->create([
            'user_id' => $unapprovedUser->id,
            'is_verified' => false,
        ]);

        // Mencoba mengakses pendaftaran bisnis mandiri sebelum di-approve
        $response = $this->actingAs($unapprovedUser)->get('/profil/bisnis/create');

        $response->assertRedirect('/profil');
        $response->assertSessionHas('warning');
    }

    public function test_admin_can_reject_alumnus_with_reason(): void
    {
        $admin = User::factory()->admin()->create();

        $user = User::factory()->create(['status' => 'pending']);
        $alumnus = Alumnus::factory()->create([
            'user_id' => $user->id,
            'is_verified' => false,
        ]);

        $response = $this->actingAs($admin)->post("/admin/verifikasi/{$alumnus->id}/reject", [
            'rejection_reason' => 'Identitas angkatan tidak dapat dikonfirmasi oleh pengurus.',
        ]);

        $response->assertRedirect();

        $user->refresh();
        $alumnus->refresh();

        $this->assertEquals('rejected', $user->status);
        $this->assertEquals('Identitas angkatan tidak dapat dikonfirmasi oleh pengurus.', $user->rejection_reason);
        $this->assertFalse($alumnus->is_verified);
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Admin Panel Verification (Role Protected)
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/verifikasi', [AlumniVerificationController::class, 'index'])->name('verification.index');
    Route::get('/verifikasi/{alumnus}', [AlumniVerificationController::class, 'show'])->name('verification.show');
    Route::post('/verifikasi/{alumnus}/approve', [AlumniVerificationController::class, 'approve'])->name('verification.approve');
    Route::post('/verifikasi/{alumnus}/reject', [AlumniVerificationController::class, 'reject'])->name('verification.reject');
});
```

---

## 6. Checklist Implementasi

- [x] Pastikan kolom `status` & `rejection_reason` ada di tabel `users` (di file migrasi create dasar)
- [x] Pastikan kolom `is_verified`, `verified_at`, `verified_by` ada di tabel `alumni`
- [x] Buat middleware `EnsureAlumniIsApproved` dan daftarkan alias `alumni.approved`
- [x] Buat Form Request `RejectAlumnusRequest`
- [x] Buat Controller `AlumniVerificationController`
- [x] Buat Blade view admin: `admin/verification/index.blade.php` (Tab antrean: Pending, Disetujui, Ditolak)
- [x] Buat modal konfirmasi Approve dan modal Reject dengan input teks alasan
- [x] Terapkan TDD: jalankan pengujian sampai lulus 100%
- [x] Format kode: `vendor/bin/pint --dirty --format agent`
