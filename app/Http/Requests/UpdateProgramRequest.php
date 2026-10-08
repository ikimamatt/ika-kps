<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
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
            'description' => ['required', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'icon_bg_class' => ['nullable', 'string', 'max:100'],
            'progress_label' => ['nullable', 'string', 'max:150'],
            'progress_status' => ['nullable', 'string', 'max:150'],
            'progress_percent' => ['nullable', 'integer', 'min:0'],
            'bar_color_class' => ['nullable', 'string', 'max:100'],
            'achievement_text' => ['nullable', 'string', 'max:255'],
            'target_amount' => ['nullable', 'numeric', 'min:0'],
            'collected_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'string', 'in:upcoming,active,completed'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
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
            'title' => 'Nama program kerja',
            'description' => 'Ringkasan program',
            'body' => 'Narasi / rincian kegiatan',
            'progress_percent' => 'Persentase progres capaian',
            'target_amount' => 'Target nominal dana',
            'collected_amount' => 'Nominal dana terkumpul',
            'status' => 'Status program',
            'start_date' => 'Tanggal mulai',
            'end_date' => 'Tanggal berakhir',
            'image' => 'Foto dokumentasi/poster',
        ];
    }
}
