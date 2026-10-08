<?php

namespace Database\Seeders;

use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    public function run(): void
    {
        $alumnus = Alumnus::first() ?? Alumnus::factory()->create();

        $curatedBusinesses = [
            [
                'alumnus_id' => $alumnus->id,
                'name' => 'Kedai Kopi Selangit Balikpapan',
                'slug' => 'kedai-kopi-selangit',
                'category' => 'Kuliner & F&B',
                'owner_info' => 'Aditya Wicaksono (SMA KPS 2008)',
                'description' => 'Roastery artisan kopi lokal Kalimantan dengan biji pilihan nusantara dan pastry segar setiap hari.',
                'image_url' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=600',
                'action_type' => 'whatsapp',
                'action_label' => 'Order via WhatsApp',
                'action_link' => 'https://wa.me/62811540111',
                'status' => 'published',
                'address' => 'Jl. MT Haryono No. 45, Balikpapan',
                'city' => 'Balikpapan',
                'phone' => '0542-765432',
                'whatsapp_number' => '0811540111',
                'website_url' => 'https://kedaikopiselangit.com',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'name' => 'Borneo Engineering Consultant',
                'slug' => 'borneo-engineering-consultant',
                'category' => 'Jasa & Konsultan',
                'owner_info' => 'Ir. Hendra Gunawan (SMA KPS 1995)',
                'description' => 'Konsultan rekayasa struktur sipil, geoteknik, dan pengawasan proyek kilang serta infrastruktur IKN.',
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=600',
                'action_type' => 'link',
                'action_label' => 'Kunjungi Website',
                'action_link' => 'https://borneoengineering.co.id',
                'status' => 'published',
                'address' => 'Komp. Balikpapan Baru Blok AB-3',
                'city' => 'Balikpapan',
                'phone' => '0542-876543',
                'whatsapp_number' => '0811540222',
                'website_url' => 'https://borneoengineering.co.id',
            ],
            [
                'alumnus_id' => $alumnus->id,
                'name' => 'Mahakam Digital Solusindo',
                'slug' => 'mahakam-digital-solusindo',
                'category' => 'Teknologi Informasi',
                'owner_info' => 'Rian Pratama (SMA KPS 2012)',
                'description' => 'Pengembangan software house, aplikasi ERP, dan infrastruktur cloud untuk industri logistik & migas.',
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600',
                'action_type' => 'whatsapp',
                'action_label' => 'Konsultasi IT via WA',
                'action_link' => 'https://wa.me/6281234567890',
                'status' => 'published',
                'address' => 'Ruko Sentra Eropa Blok AA-2, Balikpapan Baru',
                'city' => 'Balikpapan',
                'phone' => '0542-890123',
                'whatsapp_number' => '081234567890',
                'website_url' => 'https://mahakamdigital.com',
            ],
        ];

        foreach ($curatedBusinesses as $biz) {
            Business::updateOrCreate(['slug' => $biz['slug']], $biz);
        }

        Business::factory()->count(6)->published()->create();
        Business::factory()->count(3)->pending()->create();
    }
}
