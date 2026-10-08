# 📅 Task 08: Agenda Kegiatan, Kalender Event & RSVP Online

> **Status:** Siap Dikerjakan  
> **Prioritas:** 🟡 Medium / Engagement (Fase 3)  
> **Modul PRD:** Modul 8: Event & Agenda (BARU)  
> **Ketergantungan:** Task 01 (Autentikasi & Multi-Role), Task 02 (Manajemen Alumni)  

---

## 1. Deskripsi Fitur

Modul Agenda & Event menghubungkan alumni dalam berbagai kegiatan tatap muka dan daring:
1. **Kalender & Direktori Agenda Publik (`/event`)**: Menampilkan jadwal kegiatan mendatang (*Upcoming Events*) dan riwayat acara terlaksana, dilengkapi filter kategori (Reuni, Baksos, Seminar, Olahraga).
2. **Detail Event (`/event/{slug}`)**: Informasi jadwal, lokasi/venue acara, narahubung panitia, batasan kuota peserta, dan peta lokasi.
3. **Sistem RSVP Alumni (`/event/{slug}/rsvp`)**: Alumni yang telah login dapat melakukan konfirmasi kehadiran secara instan, memantau ketersediaan kuota tersisa, dan membatalkan kehadiran jika berhalangan.
4. **Admin Panel Event (`/admin/events`)**: Pembuatan event, pengaturan kapasitas kuota, serta pemantauan daftar peserta RSVP yang dapat diunduh sebagai lembar absensi.

---

## 2. Perintah Migration Database

Jalankan perintah migrasi:

```bash
php artisan make:migration create_events_and_event_registrations_tables
```

### Kode Migrasi (`database/migrations/xxxx_xx_xx_create_events_and_event_registrations_tables.php`):

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
        // 1. Tabel Events
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('body')->nullable();
            $table->string('location');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->string('category')->default('reuni'); // reuni, baksos, seminar, olahraga, lainnya
            $table->text('image_url')->nullable();
            $table->string('status')->default('draft')->index(); // draft, published, cancelled
            $table->unsignedInteger('max_participants')->nullable();
            $table->decimal('fee', 12, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'start_date', 'category']);
        });

        // 2. Tabel Registrasi RSVP Event
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('confirmed'); // confirmed, attended, cancelled
            $table->timestamp('registered_at')->useCurrent();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('events');
    }
};
```

---

## 3. Model, Factory & Seeder

### A. Model `app/Models/Event.php`

```bash
php artisan make:model Event
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'location',
        'start_date',
        'end_date',
        'category',
        'image_url',
        'status',
        'max_participants',
        'fee',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'fee' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title) . '-' . rand(100, 999);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isFullyBooked(): bool
    {
        if (is_null($this->max_participants)) {
            return false;
        }

        return $this->registrations()->where('status', 'confirmed')->count() >= $this->max_participants;
    }
}
```

### B. Model `app/Models/EventRegistration.php`

```bash
php artisan make:model EventRegistration
```

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'registered_at',
        'notes',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```

### C. Factory `database/factories/EventFactory.php`

```bash
php artisan make:factory EventFactory --model=Event
```

```php
<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = fake('id_ID')->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . rand(100, 999),
            'description' => fake('id_ID')->paragraph(),
            'body' => fake('id_ID')->paragraphs(3, true),
            'location' => 'Grand Jatra Hotel Balikpapan',
            'start_date' => now()->addDays(rand(5, 60)),
            'end_date' => now()->addDays(rand(5, 60))->addHours(4),
            'category' => fake()->randomElement(['reuni', 'baksos', 'seminar', 'olahraga']),
            'image_url' => 'https://picsum.photos/seed/' . rand(1, 999) . '/800/450',
            'status' => 'published',
            'max_participants' => 150,
            'fee' => 0,
            'created_by' => User::factory()->admin(),
        ];
    }
}
```

### D. Seeder `database/seeders/EventSeeder.php`

```bash
php artisan make:seeder EventSeeder
```

```php
<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->admin()->create();

        $curatedEvents = [
            [
                'title' => 'Musyawarah Besar & Silaturahmi Akbar IKA KPS 2026',
                'slug' => 'mubes-silaturahmi-akbar-ika-kps-2026',
                'description' => 'Pemilihan Ketua Umum periode baru serta pembahasan arah strategis kontribusi alumni KPS untuk kemajuan IKN dan Balikpapan.',
                'body' => 'Mengundang seluruh perwakilan angkatan alumni Sekolah Nasional KPS Balikpapan untuk hadir dalam Musyawarah Besar tahun 2026.',
                'location' => 'Ballroom Hotel Novotel Balikpapan',
                'start_date' => now()->addDays(20)->setHour(9)->setMinute(0),
                'end_date' => now()->addDays(20)->setHour(16)->setMinute(0),
                'category' => 'reuni',
                'image_url' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800',
                'status' => 'published',
                'max_participants' => 300,
                'fee' => 0,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Turnamen Fun Golf & Badminton Lintas Angkatan',
                'slug' => 'turnamen-fun-golf-badminton-2026',
                'description' => 'Ajang olahraga bersama untuk merekatkan keakraban alumni lintas generasi sambil menjaga kebugaran.',
                'body' => 'Turnamen diselenggarakan di Balikpapan Golf Club Sepinggan dan GOR Hevindo Balikpapan.',
                'location' => 'Balikpapan Golf Club (Pertamina Sepinggan)',
                'start_date' => now()->addDays(40)->setHour(7)->setMinute(0),
                'end_date' => now()->addDays(40)->setHour(14)->setMinute(0),
                'category' => 'olahraga',
                'image_url' => 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?w=800',
                'status' => 'published',
                'max_participants' => 100,
                'fee' => 150000,
                'created_by' => $admin->id,
            ],
        ];

        foreach ($curatedEvents as $ev) {
            Event::updateOrCreate(['slug' => $ev['slug']], $ev);
        }

        Event::factory()->count(4)->create(['created_by' => $admin->id]);
    }
}
```

---

## 4. Panduan & Skenario TDD

Buat berkas Feature Test dengan PHPUnit:
```bash
php artisan make:test --phpunit Feature/Event/PublicEventAgendaTest
php artisan make:test --phpunit Feature/Event/EventRsvpWorkflowTest
php artisan make:test --phpunit Feature/Admin/AdminEventCrudTest
```

### Skenario Uji TDD:

```php
<?php

namespace Tests\Feature\Event;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRsvpWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_attempting_rsvp(): void
    {
        $event = Event::factory()->create();

        $response = $this->post("/event/{$event->slug}/rsvp");
        $response->assertRedirect('/login');
    }

    public function test_authenticated_alumni_can_rsvp_to_an_open_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['max_participants' => 100]);

        $response = $this->actingAs($user)->post("/event/{$event->slug}/rsvp", [
            'notes' => 'Akan hadir bersama rekan seangkatan 2012',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_user_cannot_rsvp_twice_to_the_same_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $this->actingAs($user)->post("/event/{$event->slug}/rsvp");
        $secondResponse = $this->actingAs($user)->post("/event/{$event->slug}/rsvp");

        $secondResponse->assertSessionHas('error');
        $this->assertEquals(1, $event->registrations()->where('user_id', $user->id)->count());
    }

    public function test_cannot_rsvp_when_event_is_fully_booked(): void
    {
        $event = Event::factory()->create(['max_participants' => 1]);
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();

        $this->actingAs($firstUser)->post("/event/{$event->slug}/rsvp");
        $response = $this->actingAs($secondUser)->post("/event/{$event->slug}/rsvp");

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', [
            'event_id' => $event->id,
            'user_id' => $secondUser->id,
        ]);
    }
}
```

---

## 5. Rincian Endpoint & Route

```php
// Publik
Route::get('/event', [PublicEventController::class, 'index'])->name('event.index');
Route::get('/event/{slug}', [PublicEventController::class, 'show'])->name('event.show');

// RSVP (Auth Required)
Route::middleware('auth')->post('/event/{slug}/rsvp', [EventRsvpController::class, 'store'])->name('event.rsvp');
Route::middleware('auth')->delete('/event/{slug}/rsvp', [EventRsvpController::class, 'destroy'])->name('event.rsvp.cancel');

// Admin Panel
Route::middleware(['auth', 'role:admin,pengurus'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', AdminEventController::class);
    Route::get('events/{event}/participants', [AdminEventController::class, 'participants'])->name('events.participants');
});
```

---

## 6. Checklist Implementasi

- [ ] Jalankan migrasi pembuatan tabel `events` dan `event_registrations`
- [ ] Atur Model `Event` dan `EventRegistration` beserta relasinya
- [ ] Buat Form Request `StoreEventRequest`
- [ ] Buat Controller Event Publik, RSVP Controller, dan Admin Controller
- [ ] Buat tampilan Blade:
  - `event/index.blade.php` (Kalender grid/list view, filter kategori, badge tanggal)
  - `event/show.blade.php` (Detail kegiatan, kuota tersisa, tombol RSVP interaktif)
  - `admin/events/index.blade.php` & `admin/events/participants.blade.php`
- [ ] Tulis skenario pengujian TDD dan pastikan semua lulus
- [ ] Format kode: `vendor/bin/pint --dirty --format agent`
