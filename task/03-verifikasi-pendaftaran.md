# 📋 Task 03: Pendaftaran Online & Workflow Verifikasi Alumni

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🔴 Critical / Foundation (Fase 1)  
> **Modul PRD:** Modul 3: Verifikasi Pendaftaran Alumni (CRUD)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role), Task 02 (Manajemen Alumni)  

---

## 1. Deskripsi Fitur

Fitur ini menjembatani calon anggota atau alumni yang belum terdata di portal untuk mengajukan pendaftaran secara mandiri:
1. **Formulir Pendaftaran Publik**: Pengajuan data alumni baru secara online via landing page dan halaman khusus `/daftar-alumni`.
2. **Dashboard Verifikasi Pengurus (`/admin/registrations`)**: Daftar permohonan masuk dengan filter status (`pending`, `verified`, `rejected`), pencarian, dan penanda jumlah pending baru.
3. **Workflow Persetujuan Otomatis (*Approval Automation*)**:
   - **Setujui (Approve)**: Mengubah status menjadi `verified`, mencatat admin pemverifikasi dan waktu verifikasi, serta **secara otomatis meng-generate record baru pada tabel `alumni`** sehingga langsung tampil di direktori alumni.
   - **Tolak (Reject)**: Mengubah status menjadi `rejected` disertai alasan penolakan (*rejection reason*).

---

## 2. Perintah Migration Database

Jalankan perintah pembuatan migrasi untuk menambahkan kolom workflow verifikasi pada tabel `registrations`:

```bash
php artisan make:migration enhance_registrations_table_with_workflow --table=registrations
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_enhance_registrations_table_with_workflow.php`):

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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('notes')->index(); // 'pending', 'verified', 'rejected'
            $table->text('rejection_reason')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('rejection_reason');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->foreignId('alumnus_id')->nullable()->after('verified_by')->constrained('alumni')->nullOnDelete();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['alumnus_id']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropColumn([
                'status',
                'rejection_reason',
                'verified_at',
                'verified_by',
                'alumnus_id',
            ]);
        });
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/Registration.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'level',
        'graduation_year',
        'phone_whatsapp',
        'email',
        'profession',
        'institution',
        'domicile',
        'notes',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'alumnus_id',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }
}
```

### B. Factory `database/factories/RegistrationFactory.php`

```bash
php artisan make:factory RegistrationFactory --model=Registration
```

```php
<?php

namespace Database\Factories;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'full_name' => fake('id_ID')->name(),
            'level' => fake()->randomElement(['tk', 'sd', 'smp', 'sma']),
            'graduation_year' => (string) fake()->numberBetween(1990, 2024),
            'phone_whatsapp' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'profession' => fake()->jobTitle(),
            'institution' => fake()->company(),
            'domicile' => fake()->randomElement(['Balikpapan', 'Samarinda', 'Jakarta', 'Surabaya']),
            'notes' => fake()->sentence(),
            'status' => 'pending',
        ];
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => 'rejected',
            'rejection_reason' => 'Data tidak cocok dengan arsip buku tahunan sekolah.',
            'verified_at' => now(),
        ]);
    }
}
```

### C. Seeder `database/seeders/RegistrationSeeder.php`

```bash
php artisan make:seeder RegistrationSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 8 Pendaftaran Pending
        Registration::factory()->count(8)->create([
            'status' => 'pending',
        ]);

        // 3 Pendaftaran Terverifikasi
        Registration::factory()->count(3)->create([
            'status' => 'verified',
            'verified_at' => now()->subDays(2),
            'verified_by' => $admin?->id,
        ]);

        // 2 Pendaftaran Ditolak
        Registration::factory()->count(2)->create([
            'status' => 'rejected',
            'rejection_reason' => 'Nomor kontak tidak dapat dihubungi untuk konfirmasi angkatan.',
            'verified_at' => now()->subDay(),
            'verified_by' => $admin?->id,
        ]);
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Registration/RegistrationWorkflowTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Registration;

use App\Models\Alumnus;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_submit_alumni_registration_form(): void
    {
        $payload = [
            'full_name' => 'Dimas Arya Setiawan',
            'level' => 'sma',
            'graduation_year' => '2010',
            'phone_whatsapp' => '081299887766',
            'email' => 'dimas.arya@example.com',
            'profession' => 'Civil Engineer',
            'institution' => 'PT PP Properti Balikpapan',
            'domicile' => 'Balikpapan',
            'notes' => 'Salam kangen angkatan 2010!',
        ];

        $response = $this->post('/daftar-alumni', $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('registrations', [
            'email' => 'dimas.arya@example.com',
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_registration_and_automatically_creates_alumnus(): void
    {
        $admin = User::factory()->admin()->create();
        $registration = Registration::factory()->create([
            'full_name' => 'Sinta Kusuma',
            'level' => 'sma',
            'graduation_year' => '2014',
            'email' => 'sinta@example.com',
            'profession' => 'Finance Specialist',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->post("/admin/registrations/{$registration->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Pastikan status registrasi terupdate
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
        ]);

        // Pastikan data record Alumnus baru berhasil digenerate otomatis
        $this->assertDatabaseHas('alumni', [
            'name' => 'Sinta Kusuma',
            'level' => 'sma',
            'full_year' => '2014',
            'email' => 'sinta@example.com',
            'profession' => 'Finance Specialist',
            'is_verified' => true,
        ]);
    }

    public function test_admin_can_reject_registration_with_reason(): void
    {
        $admin = User::factory()->admin()->create();
        $registration = Registration::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($admin)->post("/admin/registrations/{$registration->id}/reject", [
            'rejection_reason' => 'Identitas tidak ditemukan dalam daftar angkatan.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('registrations', [
            'id' => $registration->id,
            'status' => 'rejected',
            'rejection_reason' => 'Identitas tidak ditemukan dalam daftar angkatan.',
        ]);

        $this->assertDatabaseMissing('alumni', [
            'name' => $registration->full_name,
        ]);
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Form Publik
Route::post('/daftar-alumni', [RegistrationController::class, 'store'])->name('registration.store');

// Admin Panel (Role Protected)
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{registration}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('/registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('/registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
    Route::delete('/registrations/{registration}', [AdminRegistrationController::class, 'destroy'])->name('registrations.destroy');
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan `php artisan make:migration enhance_registrations_table_with_workflow`
- [ ] Terapkan fillable, casts, dan relasi di Model `Registration`
- [ ] Buat Form Request `RejectRegistrationRequest`
- [ ] Implementasikan Service/Action `ApproveRegistrationAction` (transaksional DB: update status + `Alumnus::create`)
- [ ] Buat `AdminRegistrationController`
- [ ] Buat Blade view admin: `admin/registrations/index.blade.php` (Filter tabs pending/verified/rejected, badge counter)
- [ ] Buat modal persetujuan dan penolakan (dengan input alasan) di tampilan detail
- [ ] Tulis Factory dan Seeder
- [ ] Jalankan pengujian TDD dan pastikan semua skenario lolos 100%
- [ ] Jalankan `vendor/bin/pint --dirty --format agent`
