<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first() ?? User::factory()->admin()->create();

        $curatedEvents = [
            [
                'title' => 'Musyawarah Besar & Silaturahmi Akbar IKA KPS 2026',
                'slug' => 'mubes-silaturahmi-akbar-ika-kps-2026',
                'description' => 'Pemilihan Ketua Umum periode baru serta pembahasan arah strategis kontribusi alumni KPS untuk kemajuan IKN dan Balikpapan.',
                'body' => "Mengundang seluruh perwakilan angkatan alumni Sekolah Nasional KPS Balikpapan untuk hadir dalam Musyawarah Besar tahun 2026.\n\nAgenda Kegiatan:\n1. Sidang Pleno Laporan Pertanggungjawaban Pengurus Periode 2023-2026.\n2. Pembahasan & Pengesahan AD/ART serta Garis-Garis Besar Haluan Organisasi (GBHO).\n3. Pemilihan & Penetapan Ketua Umum IKA KPS Masa Bakti 2026-2029.\n4. Ramah tamah dan Gala Dinner temu kangen lintas angkatan (1985-2025).\n\nDresscode: Batik Bebas Rapi / Almamater KPS.",
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
                'body' => "Turnamen diselenggarakan di dua lokasi:\n- Golf: Balikpapan Golf Club (Pertamina Sepinggan) - Tee Off 07.00 WITA\n- Badminton: GOR Hevindo Balikpapan - Start 09.00 WITA\n\nFasilitas peserta:\n- Jersey eksklusif turnamen\n- Makan siang & snack box\n- Berpeluang memenangkan doorprize grand prize & trofi juara bergilir lintas angkatan.",
                'location' => 'Balikpapan Golf Club & GOR Hevindo',
                'start_date' => now()->addDays(40)->setHour(7)->setMinute(0),
                'end_date' => now()->addDays(40)->setHour(14)->setMinute(0),
                'category' => 'olahraga',
                'image_url' => 'https://images.unsplash.com/photo-1535131749006-b7f58c99034b?w=800',
                'status' => 'published',
                'max_participants' => 100,
                'fee' => 150000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Bakti Sosial Donor Darah & Pemeriksaan Kesehatan Gratis',
                'slug' => 'baksos-kesehatan-donor-darah-kps-2026',
                'description' => 'Aksi nyata kemanusiaan alumni KPS bekerja sama dengan PMI Kota Balikpapan dan dokter alumni.',
                'body' => "Layanan pemeriksaan tensi darah, kadar gula darah sewaktu, kolesterol, asam urat, serta aksi donor darah sukarela.\n\nTerbuka untuk alumni seluruh angkatan, guru-guru purna tugas, dan masyarakat umum di lingkungan sekitar Sekolah Nasional KPS Prapatan Balikpapan.",
                'location' => 'Aula Serbaguna Kompleks Sekolah Nasional KPS',
                'start_date' => now()->addDays(12)->setHour(8)->setMinute(30),
                'end_date' => now()->addDays(12)->setHour(13)->setMinute(0),
                'category' => 'baksos',
                'image_url' => 'https://images.unsplash.com/photo-1579684385127-1ef15d508118?w=800',
                'status' => 'published',
                'max_participants' => 200,
                'fee' => 0,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Talkshow Nasional: Peluang Karir & Bisnis IKN Nusantara',
                'slug' => 'talkshow-nasional-peluang-karir-bisnis-ikn',
                'description' => 'Diskusi panel interaktif bersama alumni KPS yang berkiprah di Otorita IKN, BUMN, dan praktisi industri hijau.',
                'body' => "Membahas peta jalan kebutuhan talenta digital, energi baru terbarukan, rantai pasok logistik, serta inkubasi bisnis lokal menyongsong kepindahan ibukota ke Nusantara, Kalimantan Timur.\n\nTersedia sertifikat partisipasi fisik & daring.",
                'location' => 'Auditorium Kampus ITK / Hybrid Zoom Webinar',
                'start_date' => now()->addDays(28)->setHour(13)->setMinute(30),
                'end_date' => now()->addDays(28)->setHour(17)->setMinute(0),
                'category' => 'seminar',
                'image_url' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800',
                'status' => 'published',
                'max_participants' => 250,
                'fee' => 0,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Reuni Perak 25 Tahun Angkatan 2001: Merajut Kenangan Prapatan',
                'slug' => 'reuni-perak-25-tahun-angkatan-2001',
                'description' => 'Momen napak tilas seperempat abad kelulusan alumni KPS 2001 dengan mengunjungi gedung sekolah lama dan makan malam bersama.',
                'body' => 'Nostalgia kenangan masa putih abu-abu di Balikpapan dengan agenda ziarah guru, donasi renovasi perpustakaan sekolah, dan pentas musik akustik.',
                'location' => 'Pantai BSB Balikpapan Superblock',
                'start_date' => now()->subDays(60)->setHour(18)->setMinute(0),
                'end_date' => now()->subDays(60)->setHour(22)->setMinute(0),
                'category' => 'reuni',
                'image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
                'status' => 'published',
                'max_participants' => 120,
                'fee' => 200000,
                'created_by' => $admin->id,
            ],
            [
                'title' => 'Workshop Literasi Finansial & Investasi untuk Alumni Muda',
                'slug' => 'workshop-literasi-finansial-alumni-muda',
                'description' => 'Pelatihan perencanaan keuangan pribadi, saham, dan investasi properti bersama praktisi wealth management alumni KPS.',
                'body' => 'Rancangan modul investasi cerdas untuk fresh graduate dan alumni usia produktif.',
                'location' => 'Co-Working Space Balikpapan Creative Center',
                'start_date' => now()->addDays(18)->setHour(10)->setMinute(0),
                'end_date' => now()->addDays(18)->setHour(15)->setMinute(0),
                'category' => 'seminar',
                'image_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=800',
                'status' => 'draft',
                'max_participants' => 50,
                'fee' => 50000,
                'created_by' => $admin->id,
            ],
        ];

        $sampleUsers = User::where('id', '!=', $admin->id)->take(12)->get();
        if ($sampleUsers->isEmpty()) {
            $sampleUsers = User::factory()->count(5)->create();
        }

        $sampleNotes = [
            'Insya Allah hadir bersama rekan seangkatan!',
            'Hadir dan siap bantu tim dokumentasi panitia.',
            'Akan datang tepat waktu dari luar kota.',
            'Mewakili angkatan 2004.',
            'Hadir bersama keluarga.',
            null,
        ];

        foreach ($curatedEvents as $evData) {
            $event = Event::updateOrCreate(['slug' => $evData['slug']], $evData);

            // Populate some RSVPs for published events
            if ($event->status === 'published' && $sampleUsers->isNotEmpty()) {
                $rsvpCount = min($sampleUsers->count(), rand(4, 8));
                $usersToRegister = $sampleUsers->random($rsvpCount);

                foreach ($usersToRegister as $idx => $user) {
                    EventRegistration::updateOrCreate(
                        [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                        ],
                        [
                            'status' => 'confirmed',
                            'registered_at' => now()->subDays(rand(1, 10))->subHours(rand(1, 12)),
                            'notes' => $sampleNotes[array_rand($sampleNotes)],
                        ]
                    );
                }
            }
        }
    }
}
