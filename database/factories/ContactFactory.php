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
                'Kerjasama Kegiatan Pengabdian Masyarakat',
                'Permintaan Data Kontak Perwakilan Angkatan 2008',
            ]),
            'message' => fake('id_ID')->paragraph(3),
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
