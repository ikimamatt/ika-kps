# 🔐 Task 01: Autentikasi, Multi-Role & Manajemen Profil Pengguna

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🔴 Critical / Foundation (Fase 1)  
> **Modul PRD:** Modul 1: Autentikasi & Profil Pengguna  
> **Ketergantungan:** Tidak ada (Fondasi Awal)  

---

## 1. Deskripsi Fitur

Modul ini bertanggung jawab atas sistem autentikasi, otorisasi berbasis peran (Multi-Role), dan pengelolaan profil pengguna mandiri (*self-service profile*). Sistem ini membedakan hak akses antara Administrator Organisasi, Pengurus IKA KPS, dan Anggota Alumni Biasa.

### Ruang Lingkup:
1. **Autentikasi Standar**: Register akun baru, Login (dengan Remember Me), Logout, Lupa Password & Reset Password.
2. **Multi-Role Authorization**: Peran `admin`, `pengurus`, dan `alumni`. Middleware proteksi rute admin.
3. **Self-Service Profil**: Alumni yang login dapat melihat dan memperbarui informasi profilnya, kontak telepon, link sosial media, dan foto avatar.
4. **Relasi User ke Alumnus**: Hubungan 1-to-1 opsional antara tabel `users` dan `alumni`.

---

## 2. Perintah Migration Database

Jalankan perintah pembuatan migrasi untuk menambahkan kolom pendukung di tabel `users`:

```bash
php artisan make:migration add_roles_and_profile_fields_to_users_table --table=users
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_add_roles_and_profile_fields_to_users_table.php`):

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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('alumni')->after('password')->index(); // 'admin', 'pengurus', 'alumni'
            $table->foreignId('alumnus_id')->nullable()->after('role')->constrained('alumni')->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar_url')->nullable()->after('phone');
            $table->string('status')->default('active')->after('avatar_url'); // 'active', 'suspended'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['alumnus_id']);
            $table->dropColumn(['role', 'alumnus_id', 'phone', 'avatar_url', 'status']);
        });
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Update Model `app/Models/User.php`
- Tambahkan properti `$fillable`: `'name'`, `'email'`, `'password'`, `'role'`, `'alumnus_id'`, `'phone'`, `'avatar_url'`, `'status'`.
- Tambahkan helper method:
  ```php
  public function isAdmin(): bool
  {
      return $this->role === 'admin';
  }

  public function isPengurus(): bool
  {
      return in_array($this->role, ['admin', 'pengurus']);
  }

  public function alumnus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
  {
      return $this->belongsTo(Alumnus::class);
  }
  ```

### B. Factory `database/factories/UserFactory.php`
Tambahkan states untuk role:
```php
public function admin(): static
{
    return $this->state(fn (array $attributes) => [
        'role' => 'admin',
    ]);
}

public function pengurus(): static
{
    return $this->state(fn (array $attributes) => [
        'role' => 'pengurus',
    ]);
}

public function alumni(): static
{
    return $this->state(fn (array $attributes) => [
        'role' => 'alumni',
    ]);
}
```

### C. Seeder `database/seeders/UserSeeder.php`
Buat seeder dengan perintah:
```bash
php artisan make:seeder UserSeeder
```

Implementasi isi seeder:
```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Administrator
        User::updateOrCreate(
            ['email' => 'admin@ikakps.org'],
            [
                'name' => 'Super Administrator IKA KPS',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '0811540001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 2. Akun Pengurus Organisasi
        User::updateOrCreate(
            ['email' => 'pengurus@ikakps.org'],
            [
                'name' => 'Sekretariat IKA KPS',
                'password' => Hash::make('password'),
                'role' => 'pengurus',
                'phone' => '0811540002',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 3. Akun Sampel Alumni Biasa
        User::updateOrCreate(
            ['email' => 'alumni@ikakps.org'],
            [
                'name' => 'Rangga Perkasa',
                'password' => Hash::make('password'),
                'role' => 'alumni',
                'phone' => '08125000003',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
```

---

## 4. Panduan & Skenario TDD (Test-Driven Development)

Buat dua berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Auth/AuthenticationTest
php artisan make:test --phpunit Feature/Auth/ProfileTest
php artisan make:test --phpunit Feature/Auth/RoleAccessTest
```

### Skenario Uji TDD (Wajib Lulus):

#### 🔴 RED Phase (Tulis Kasus Uji Dahulu):
1. `test_login_screen_can_be_rendered()`
2. `test_users_can_authenticate_using_the_login_screen()`
3. `test_users_cannot_authenticate_with_invalid_password()`
4. `test_users_can_logout()`
5. `test_new_users_can_register_and_get_alumni_role_by_default()`
6. `test_guest_cannot_access_profile_page()`
7. `test_authenticated_user_can_view_and_update_profile()`
8. `test_alumni_role_cannot_access_admin_dashboard_returns_forbidden()`
9. `test_admin_and_pengurus_can_access_admin_dashboard()`

#### 🟢 Contoh Kode Test PHPUnit (`tests/Feature/Auth/RoleAccessTest.php`):
```php
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
```

---

## 5. Rincian Endpoint & Route

```php
// Rute Publik / Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Rute Alumni Terautentikasi
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Middleware Guard Admin / Pengurus
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan `php artisan make:migration add_roles_and_profile_fields_to_users_table`
- [ ] Tulis skema migrasi dan jalankan `php artisan migrate`
- [ ] Buat middleware `EnsureUserHasRole` (`app/Http/Middleware/EnsureUserHasRole.php`)
- [ ] Daftarkan alias middleware `role` di `bootstrap/app.php`
- [ ] Buat Form Request `UpdateProfileRequest` & `RegisterRequest`
- [ ] Implementasikan `AuthController` dan `ProfileController`
- [ ] Buat tampilan Blade UI: `auth/login.blade.php`, `auth/register.blade.php`, `profile/show.blade.php`, `profile/edit.blade.php`
- [ ] Buat file seeder `UserSeeder.php` dan daftarkan di `DatabaseSeeder.php`
- [ ] Terapkan siklus TDD: tulis test ➔ pastikan merah ➔ selesaikan kode ➔ pastikan hijau
- [ ] Jalankan `vendor/bin/pint --dirty --format agent` untuk standarisasi styling kode
