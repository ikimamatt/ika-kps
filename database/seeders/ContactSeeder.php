<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // 5 Pesan belum dibaca
        Contact::factory()->count(5)->create();

        // 3 Pesan sudah dibaca
        Contact::factory()->read()->count(3)->create();
    }
}
