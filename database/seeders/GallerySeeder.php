<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'title' => 'Gedung Sekolah KPS Tempo Doeloe (1985–1995)',
                'slug' => 'sekolah-kps-tempo-doeloe-1985-1995',
                'description' => 'Arsip foto jadul kenangan gedung lama, seragam sekolah putih abu-abu, lapangan upacara, dan para guru senior era 80-90an di Prapatan Balikpapan.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=800',
                'event_date' => '1990-08-17',
                'category' => 'Nostalgia',
                'status' => 'published',
                'photos' => [
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=1000',
                        'caption' => 'Gerbang utama Sekolah Nasional KPS Prapatan Balikpapan tempo dulu',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=1000',
                        'caption' => 'Suasana belajar mengajar di laboratorium sains',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1577896851231-70ef18881754?w=1000',
                        'caption' => 'Upacara bendera peringatan Hari Kemerdekaan bersama dewan guru',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1000',
                        'caption' => 'Sesi foto buku tahunan angkatan 1992 di taman sekolah',
                    ],
                ],
            ],
            [
                'title' => 'Dokumentasi Reuni Akbar Lintas Generasi 2026',
                'slug' => 'reuni-akbar-lintas-angkatan-2026',
                'description' => 'Momen kebersamaan temu kangen akbar alumni Sekolah Nasional KPS di Ballroom Novotel Balikpapan.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800',
                'event_date' => '2026-01-15',
                'category' => 'Reuni',
                'status' => 'published',
                'photos' => [
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?w=1000',
                        'caption' => 'Registrasi kehadiran alumni dan pembagian almamater kit',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1000',
                        'caption' => 'Sambutan hangat Ketua Umum IKA KPS membuka acara reuni',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1000',
                        'caption' => 'Malam keakraban, pentas musik alumni, dan makan malam bersama',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1527529482837-4698179dc6ce?w=1000',
                        'caption' => 'Foto bersama perwakilan lintas angkatan di backdrop panggung utama',
                    ],
                ],
            ],
            [
                'title' => 'Kejuaraan Olahraga Fun Games & Badminton Alumni KPS',
                'slug' => 'kejuaraan-olahraga-fun-games-badminton-2026',
                'description' => 'Kemeriahan kompetisi badminton dan fun walk lintas angkatan di GOR Hevindo Balikpapan.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800',
                'event_date' => '2026-02-20',
                'category' => 'Olahraga',
                'status' => 'published',
                'photos' => [
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1000',
                        'caption' => 'Pertandingan ganda putra babak final berlangsung seru',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1000',
                        'caption' => 'Penyerahan trofi dan medali juara umum turnamen olahraga',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?w=1000',
                        'caption' => 'Senam pagi bersama sebelum pembukaan turnamen',
                    ],
                ],
            ],
            [
                'title' => 'Aksi Bakti Sosial Peduli Ramadhan & Santunan Guru Purna Tugas',
                'slug' => 'baksos-peduli-ramadhan-santunan-guru-kps',
                'description' => 'Penyaluran 200 paket sembako dan tali asih untuk para pahlawan tanpa tanda jasa yang telah mendidik alumni KPS.',
                'cover_image_url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=800',
                'event_date' => '2026-03-25',
                'category' => 'Sosial',
                'status' => 'published',
                'photos' => [
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=1000',
                        'caption' => 'Penyerahan bingkisan ramadhan dan santunan kepada perwakilan guru senior',
                    ],
                    [
                        'image_url' => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=1000',
                        'caption' => 'Tim relawan alumni KPS mendistribusikan paket sembako di Balikpapan',
                    ],
                ],
            ],
            [
                'title' => 'Rencana Album Dokumentasi Pelatihan Karir Digital 2026',
                'slug' => 'draft-pelatihan-karir-digital-2026',
                'description' => 'Dokumentasi workshop coding dan UI/UX design alumni (Masih dalam proses kurasi arsip).',
                'cover_image_url' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?w=800',
                'event_date' => '2026-04-10',
                'category' => 'Kegiatan',
                'status' => 'draft',
                'photos' => [],
            ],
        ];

        foreach ($albums as $albumData) {
            $photos = $albumData['photos'];
            unset($albumData['photos']);

            $album = Gallery::updateOrCreate(['slug' => $albumData['slug']], $albumData);

            if (! empty($photos)) {
                foreach ($photos as $order => $p) {
                    GalleryPhoto::updateOrCreate(
                        [
                            'gallery_id' => $album->id,
                            'image_url' => $p['image_url'],
                        ],
                        [
                            'caption' => $p['caption'],
                            'sort_order' => $order + 1,
                        ]
                    );
                }
            }
        }
    }
}
