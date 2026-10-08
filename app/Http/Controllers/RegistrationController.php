<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Handle submission of new alumni registration.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'in:sma,smp,sd,tk'],
            'graduation_year' => ['required', 'string', 'max:10'],
            'phone_whatsapp' => ['required', 'string', 'max:25'],
            'email' => ['required', 'email', 'max:255'],
            'profession' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'domicile' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        Registration::create($validated);

        return redirect()
            ->to(url()->previous().'#daftar-alumni')
            ->with('success', 'Pendaftaran database alumni Anda berhasil dikirim! Tim pengurus IKA KPS akan segera melakukan verifikasi.');
    }
}
