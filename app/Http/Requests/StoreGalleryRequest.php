<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:galleries,slug'],
            'description' => ['nullable', 'string', 'max:1500'],
            'event_date' => ['nullable', 'date'],
            'category' => ['required', 'string', 'in:Nostalgia,Reuni,Kegiatan,Olahraga,Sosial,Lainnya'],
            'status' => ['required', 'string', 'in:draft,published'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'cover_image_url' => ['nullable', 'string', 'max:500'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
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
            'title' => 'Judul album galeri',
            'slug' => 'Slug URL',
            'description' => 'Deskripsi album',
            'event_date' => 'Tanggal kegiatan / dokumentasi',
            'category' => 'Kategori galeri',
            'status' => 'Status album',
            'cover' => 'Foto sampul (cover album)',
            'cover_image_url' => 'URL foto sampul',
            'photos' => 'Foto-foto galeri',
            'photos.*' => 'Berkas foto',
        ];
    }
}
