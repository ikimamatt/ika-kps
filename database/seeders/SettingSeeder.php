<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaultSettings = [
            // Identitas Organisasi
            ['key' => 'org_name', 'value' => 'IKA KPS Balikpapan', 'group' => 'general'],
            ['key' => 'org_tagline', 'value' => 'Satu Almamater, Seribu Cerita', 'group' => 'general'],
            ['key' => 'org_description', 'value' => 'Ikatan Keluarga Alumni Sekolah Nasional KPS Balikpapan lintas generasi.', 'group' => 'general'],

            // Kontak & Lokasi
            ['key' => 'contact_email', 'value' => 'sekretariat@ika-kps.id', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '0542-731234', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '08115401985', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => 'Kompleks Sekolah Nasional KPS Balikpapan, Jl. Prapatan No. 01, Telaga Sari, Kec. Balikpapan Kota, Kota Balikpapan, Kalimantan Timur 76112', 'group' => 'contact'],

            // Media Sosial
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/ikakps_official', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/ika-kps', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@ikakpsbalikpapan', 'group' => 'social'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::set($setting['key'], $setting['value'], $setting['group']);
        }
    }
}
