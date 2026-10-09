<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $alumnus = $user->alumnus;
        $businesses = $alumnus ? $alumnus->businesses()->latest('id')->get() : collect();
        $jobVacancies = $alumnus ? $alumnus->jobVacancies()->latest('id')->get() : collect();

        return view('profile.show', [
            'user' => $user,
            'alumnus' => $alumnus,
            'businesses' => $businesses,
            'jobVacancies' => $jobVacancies,
        ]);
    }

    /**
     * Display the profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'alumnus' => $request->user()->alumnus,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $avatarUrl = $user->avatar_url;

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url) {
                $parsedPath = parse_url($user->avatar_url, PHP_URL_PATH);
                if ($parsedPath && str_contains($parsedPath, '/storage/')) {
                    $oldPath = substr($parsedPath, strpos($parsedPath, '/storage/') + strlen('/storage/'));
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $avatarUrl = Storage::url($path);
        } elseif ($request->filled('avatar_url')) {
            $avatarUrl = $request->validated('avatar_url');
        }

        $user->update([
            'name' => $request->validated('name'),
            'phone' => $request->validated('phone'),
            'avatar_url' => $avatarUrl,
        ]);

        $year = $request->validated('graduation_year');
        $alumnusData = [
            'name' => $user->name,
            'title' => $request->validated('title'),
            'level' => $request->validated('level', $user->alumnus?->level ?? 'sma'),
            'class_year' => $year ? substr((string) $year, -2) : $user->alumnus?->class_year,
            'full_year' => $year ? (string) $year : $user->alumnus?->full_year,
            'profession' => $request->validated('profession'),
            'institution' => $request->validated('institution'),
            'domicile' => $request->validated('domicile'),
            'summary' => $request->validated('summary'),
            'avatar_url' => $user->avatar_url,
            'phone' => $user->phone,
            'linkedin_url' => $request->validated('linkedin_url'),
            'instagram_handle' => $request->validated('instagram_handle'),
        ];

        if ($user->alumnus) {
            $user->alumnus->update($alumnusData);
        } elseif ($user->role === 'alumni') {
            $alumnusData['email'] = $user->email;
            $alumnusData['slug'] = Str::slug($user->name.'-'.($year ?? rand(100, 999)));
            $alumnusData['is_verified'] = $user->status === 'active';
            $user->alumnus()->create($alumnusData);
        }

        return redirect()->route('profile.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()->route('profile.show')->with('success', 'Kata sandi berhasil diperbarui.');
    }
}
