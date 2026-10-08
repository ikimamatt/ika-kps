<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
        $articleId = $this->route('article')?->id ?? $this->route('article');

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('articles', 'slug')->ignore($articleId)],
            'category' => ['required', 'string', 'max:100'],
            'badge_bg_class' => ['nullable', 'string', 'max:100'],
            'summary' => ['required', 'string', 'max:1000'],
            'body' => ['nullable', 'string'],
            'author_and_date' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
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
            'title' => 'Judul artikel',
            'slug' => 'Slug URL artikel',
            'category' => 'Kategori artikel',
            'summary' => 'Ringkasan artikel',
            'body' => 'Isi / narasi lengkap artikel',
            'status' => 'Status publikasi',
            'image' => 'Foto sampul artikel',
        ];
    }
}
