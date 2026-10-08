<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->admin()->create();

        $curatedArticles = [
            [
                'author_id' => $admin->id,
                'title' => 'Menatap Masa Depan: Reuni Akbar Lintas Generasi KPS Balikpapan 2026',
                'slug' => 'reuni-akbar-lintas-generasi-kps-balikpapan-2026',
                'category' => 'Kegiatan',
                'badge_bg_class' => 'bg-blue-500/10 text-blue-600',
                'summary' => 'Lebih dari 1.500 alumni dari 40 angkatan berkumpul kembali di Balikpapan Sport and Convention Center (DOME).',
                'body' => 'Suasana haru dan sukacita menyelimuti pembukaan Reuni Akbar IKA KPS Balikpapan. Acara yang dipersiapkan selama enam bulan ini mempertemukan kembali para lulusan Sekolah Nasional KPS mulai dari angkatan 1970-an hingga lulusan termuda 2025.',
                'author_and_date' => 'Humas IKA KPS · 15 Jan 2026',
                'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=800',
                'status' => 'published',
                'published_at' => now()->subWeeks(2),
                'views_count' => 1240,
            ],
            [
                'author_id' => $admin->id,
                'title' => 'Nostalgia Lorong Sekolah dan Lapangan Upacara Lapangan Pasir',
                'slug' => 'nostalgia-lorong-sekolah-lapangan-pasir',
                'category' => 'Nostalgia',
                'badge_bg_class' => 'bg-amber-500/10 text-amber-600',
                'summary' => 'Kenangan masa-masa seragam putih-abu dan aroma angin pesisir Balikpapan yang tak pernah lekang oleh waktu.',
                'body' => 'Bagi siapa pun yang pernah menghabiskan masa remaja di bangku SMA Nasional KPS Balikpapan, deru ombak Selat Makassar dan pepohonan rindang di sekitar komplek sekolah selalu menyisakan kerinduan yang mendalam.',
                'author_and_date' => 'Redaksi Alumni · 02 Feb 2026',
                'image_url' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'views_count' => 640,
            ],
            [
                'author_id' => $admin->id,
                'title' => 'Alumni KPS Terpilih Memimpin Proyek Infrastruktur Berkelanjutan di Kalimantan Timur',
                'slug' => 'alumni-kps-terpilih-proyek-infrastruktur-berkelanjutan',
                'category' => 'Prestasi',
                'badge_bg_class' => 'bg-primary text-on-primary',
                'summary' => 'Kiprah alumni KPS angkatan 2001 dalam merancang tata kelola energi hijau dan sistem logistik modern menegaskan sumbangsih nyata almamater untuk pembangunan daerah.',
                'body' => 'Prestasi gemilang kembali ditorehkan oleh alumni SMA KPS Balikpapan. Ir. Hendra Saputra, M.Sc (alumni 2001) secara resmi dipercaya mengomandoi konsorsium rekayasa tata kota terpadu berbasis energi terbarukan di wilayah penyangga IKN Nusantara.',
                'author_and_date' => 'Dewan Redaksi · 15 Jan 2026',
                'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'views_count' => 890,
            ],
        ];

        foreach ($curatedArticles as $art) {
            Article::updateOrCreate(['slug' => $art['slug']], $art);
        }

        Article::factory()->count(6)->create();
        Article::factory()->draft()->count(2)->create();
    }
}
