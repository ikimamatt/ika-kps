<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'profession' => ['nullable', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'domicile' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $password = $validated['password'] ?? Str::random(16);

            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone_whatsapp'],
                'password' => Hash::make($password),
                'role' => 'alumni',
                'status' => 'pending',
            ]);

            Alumnus::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'slug' => Str::slug($user->name.'-'.Str::random(5)),
                'level' => $validated['level'],
                'class_year' => substr((string) $validated['graduation_year'], -2),
                'full_year' => (string) $validated['graduation_year'],
                'profession' => $validated['profession'] ?? null,
                'institution' => $validated['institution'] ?? null,
                'domicile' => $validated['domicile'] ?? null,
                'summary' => $validated['notes'] ?? null,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_verified' => false,
            ]);
        });

        return redirect()
            ->to(url()->previous().'#daftar-alumni')
            ->with('success', 'Pendaftaran database alumni Anda berhasil dikirim! Tim pengurus IKA KPS akan segera melakukan verifikasi.');
    }
}
