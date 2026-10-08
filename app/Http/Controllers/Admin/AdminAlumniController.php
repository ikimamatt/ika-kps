<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAlumnusRequest;
use App\Http\Requests\Admin\UpdateAlumnusRequest;
use App\Models\Alumnus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminAlumniController extends Controller
{
    /**
     * Display a listing of all alumni.
     */
    public function index(Request $request): View
    {
        $query = Alumnus::query()->withTrashed();

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('profession', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('status')) {
            if ($request->input('status') === 'verified') {
                $query->where('is_verified', true);
            } elseif ($request->input('status') === 'unverified') {
                $query->where('is_verified', false);
            } elseif ($request->input('status') === 'trashed') {
                $query->onlyTrashed();
            }
        }

        $alumni = $query->latest('id')->paginate(15)->withQueryString();

        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Show the form for creating a new alumnus.
     */
    public function create(): View
    {
        return view('admin.alumni.create');
    }

    /**
     * Store a newly created alumnus in storage.
     */
    public function store(StoreAlumnusRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $year = $request->input('graduation_year');
        if ($year) {
            $data['full_year'] = (string) $year;
            $data['class_year'] = substr((string) $year, -2);
        }

        $data['slug'] = Str::slug($data['name'].'-'.($year ?? rand(1000, 9999)));
        $data['is_verified'] = $request->boolean('is_verified');

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = Storage::url($path);
        }
        unset($data['avatar']);

        if ($data['is_verified']) {
            $data['verified_at'] = now();
            $data['verified_by'] = $request->user()->id;
        }

        Alumnus::create($data);

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified alumnus.
     */
    public function edit(Alumnus $alumnus): View
    {
        return view('admin.alumni.edit', compact('alumnus'));
    }

    /**
     * Update the specified alumnus in storage.
     */
    public function update(UpdateAlumnusRequest $request, Alumnus $alumnus): RedirectResponse
    {
        $data = $request->validated();

        $year = $request->input('graduation_year');
        if ($year) {
            $data['full_year'] = (string) $year;
            $data['class_year'] = substr((string) $year, -2);
        }

        if ($request->hasFile('avatar')) {
            if ($alumnus->avatar_url) {
                $parsedPath = parse_url($alumnus->avatar_url, PHP_URL_PATH);
                if ($parsedPath && str_contains($parsedPath, '/storage/')) {
                    $oldPath = substr($parsedPath, strpos($parsedPath, '/storage/') + strlen('/storage/'));
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_url'] = Storage::url($path);
        }
        unset($data['avatar']);

        if ($request->has('is_verified')) {
            $data['is_verified'] = $request->boolean('is_verified');
            if ($data['is_verified'] && ! $alumnus->is_verified) {
                $data['verified_at'] = now();
                $data['verified_by'] = $request->user()->id;
            }
        }

        $alumnus->update($data);

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil diperbarui.');
    }

    /**
     * Soft delete the specified alumnus.
     */
    public function destroy(Alumnus $alumnus): RedirectResponse
    {
        $alumnus->delete();

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dipindahkan ke arsip sampah.');
    }

    /**
     * Restore a soft-deleted alumnus.
     */
    public function restore(int|string $id): RedirectResponse
    {
        $alumnus = Alumnus::onlyTrashed()->findOrFail($id);
        $alumnus->restore();

        return redirect()->route('admin.alumni.index')->with('success', 'Data alumni berhasil dipulihkan.');
    }

    /**
     * Toggle the verification status of an alumnus.
     */
    public function toggleVerification(Alumnus $alumnus): RedirectResponse
    {
        $alumnus->is_verified = ! $alumnus->is_verified;
        $alumnus->verified_at = $alumnus->is_verified ? now() : null;
        $alumnus->verified_by = $alumnus->is_verified ? auth()->id() : null;
        $alumnus->save();

        $statusText = $alumnus->is_verified ? 'diverifikasi' : 'dibatalkan verifikasinya';

        return redirect()->back()->with('success', "Alumni {$alumnus->name} berhasil {$statusText}.");
    }
}
