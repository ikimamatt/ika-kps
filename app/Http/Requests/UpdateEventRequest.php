<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'pengurus');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $eventId = $this->route('event')?->id ?? $this->route('event');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($eventId)],
            'description' => ['required', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'category' => ['required', 'string', 'in:reuni,baksos,seminar,olahraga,lainnya'],
            'status' => ['required', 'string', 'in:draft,published,cancelled'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'image_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'Nama kegiatan / event',
            'slug' => 'Slug URL',
            'description' => 'Ringkasan kegiatan',
            'body' => 'Rincian & deskripsi kegiatan',
            'location' => 'Lokasi / tempat kegiatan',
            'start_date' => 'Waktu mulai',
            'end_date' => 'Waktu selesai',
            'category' => 'Kategori event',
            'status' => 'Status event',
            'max_participants' => 'Batas kuota peserta',
            'fee' => 'Biaya pendaftaran',
            'image' => 'Poster / pamflet event',
        ];
    }
}
