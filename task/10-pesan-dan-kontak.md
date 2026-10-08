# 📬 Task 10: Kontak Hub & Kotak Masuk Pesan (Inbox)

> **Status:** Selesai (Completed)  
> **Prioritas:** 🟡 Medium / Engagement (Fase 3)  
> **Modul PRD:** Modul 10: Pesan & Kontak Masuk (BARU)  
> **Ketergantungan:** Tidak ada (Dapat dikerjakan paralel)  

---

## 1. Deskripsi Fitur

Modul Kontak Hub menyediakan jalur komunikasi resmi antara alumni/publik dengan pengurus IKA KPS:
1. **Formulir Kontak Publik (`/kontak`)**: Kirim pesan, pertanyaan, atau usulan program kerja dari publik dan alumni. Dilengkapi perlindungan spam (*Honeypot field* & *Rate limiting*).
2. **Admin Inbox Pesan (`/admin/contacts`)**: Manajemen pesan masuk terpusat dengan filter pesan belum dibaca (*unread messages*), badge jumlah pesan baru di sidebar, detail isi pesan, serta penanda status tindak lanjut.

---

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration create_contacts_table
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_create_contacts_table.php`):

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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['is_read', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Model `app/Models/Contact.php`

```bash
php artisan make:model Contact
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
```

### B. Factory `database/factories/ContactFactory.php`

```bash
php artisan make:factory ContactFactory --model=Contact
```

```php
<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->randomElement([
                'Pertanyaan Reuni Akbar 2026',
                'Penawaran Sinergi Beasiswa Perusahaan',
                'Ralat Informasi Ijazah Alumni',
                'Usulan Pembentukan Komisariat Alumni Luar Kaltim',
            ]),
            'message' => fake('id_ID')->paragraph(),
            'is_read' => false,
            'read_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn () => [
            'is_read' => true,
            'read_at' => now()->subDay(),
        ]);
    }
}
```

### C. Seeder `database/seeders/ContactSeeder.php`

```bash
php artisan make:seeder ContactSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        Contact::factory()->count(5)->create(); // 5 Pesan belum dibaca
        Contact::factory()->read()->count(3)->create(); // 3 Pesan sudah dibaca
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Contact/PublicContactFormTest
php artisan make:test --phpunit Feature/Admin/AdminContactInboxTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Contact;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_submit_contact_message(): void
    {
        $payload = [
            'name' => 'Wahyudi Santoso',
            'email' => 'wahyudi@example.com',
            'phone' => '081234567890',
            'subject' => 'Kerjasama Kegiatan Donor Darah',
            'message' => 'Halo Pengurus IKA KPS, kami dari PMI Balikpapan ingin mengajak kolaborasi...',
            'hp_check' => '', // Honeypot field (harus kosong)
        ];

        $response = $this->post('/kontak', $payload);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contacts', [
            'email' => 'wahyudi@example.com',
            'subject' => 'Kerjasama Kegiatan Donor Darah',
            'is_read' => false,
        ]);
    }

    public function test_bot_spam_is_rejected_when_honeypot_is_filled(): void
    {
        $payload = [
            'name' => 'Spam Bot',
            'email' => 'bot@spammer.com',
            'subject' => 'Buy Crypto Now',
            'message' => 'Spam link here',
            'hp_check' => 'http://spam-link.com', // Terisi bot
        ];

        $response = $this->post('/kontak', $payload);

        // Jangan simpan ke database jika bot terdeteksi
        $this->assertDatabaseMissing('contacts', [
            'email' => 'bot@spammer.com',
        ]);
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/kontak', [PublicContactController::class, 'show'])->name('contact.show');
Route::post('/kontak', [PublicContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Admin Inbox
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}/read', [AdminContactController::class, 'markRead'])->name('contacts.read');
    Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
});
```

---

## 6. Checklist Implementasi

- [x] Jalankan migrasi tabel `contacts`
- [x] Atur Model `Contact` dengan helper `markAsRead()`
- [x] Buat Form Request `StoreContactRequest` (dengan validasi honeypot)
- [x] Buat Controller Publik & Admin
- [x] Buat tampilan Blade:
  - `contact/show.blade.php` (Form kontak, info sekretariat, direct WhatsApp support, FAQ)
  - `admin/contacts/index.blade.php` (Tabel inbox, counter unread, mark read toggle)
  - `admin/contacts/show.blade.php` (Tampilan isi surat lengkap, direct reply email & WhatsApp)
- [x] Tulis test TDD dan jalankan (11 tests passing)
- [x] Format kode: `vendor/bin/pint --dirty --format agent`
