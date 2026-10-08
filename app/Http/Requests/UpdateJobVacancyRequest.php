<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateJobVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPengurus());
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
            'company' => ['required', 'string', 'max:255'],
            'alumni_info' => ['nullable', 'string', 'max:255'],
            'job_type' => ['required', 'string', 'in:Full Time,Part Time,Magang,Kontrak'],
            'location' => ['required', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'apply_url' => ['required', 'string', 'max:500'],
            'cta_label' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:pending,active,closed,expired'],
        ];
    }
}
