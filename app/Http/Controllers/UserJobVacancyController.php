<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobVacancyRequest;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserJobVacancyController extends Controller
{
    /**
     * Display a listing of jobs submitted by the logged-in alumnus.
     */
    public function index(): View
    {
        $alumnus = auth()->user()->alumnus;
        $jobs = $alumnus ? $alumnus->jobVacancies()->latest('id')->paginate(10) : collect();

        return view('profile.job.index', compact('jobs', 'alumnus'));
    }

    /**
     * Show the form for creating a new job vacancy submission.
     */
    public function create(): View
    {
        $alumnus = auth()->user()->alumnus;

        return view('profile.job.create', compact('alumnus'));
    }

    /**
     * Store a newly created job vacancy submission.
     */
    public function store(StoreJobVacancyRequest $request): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        $data = $request->validated();
        $data['status'] = 'pending';

        if (empty($data['alumni_info']) && $alumnus) {
            $levelName = strtoupper($alumnus->level ?? 'Alumni');
            $yearName = $alumnus->full_year ?? $alumnus->class_year ?? '';
            $data['alumni_info'] = "Direferensikan oleh {$alumnus->name} ({$levelName} {$yearName})";
            $data['company_alumni_info'] = $data['alumni_info'];
        }

        if (empty($data['cta_label'])) {
            $data['cta_label'] = 'Lamar via Rekomendasi IKA';
        }

        $alumnus->jobVacancies()->create($data);

        return redirect()->route('profile.job.index')
            ->with('success', 'Info lowongan kerja berhasil dikirimkan dan menunggu peninjauan oleh pengurus.');
    }

    /**
     * Show the form for editing an existing job vacancy owned by the alumnus.
     */
    public function edit(JobVacancy $jobVacancy): View
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $jobVacancy->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit lowongan kerja ini.');
        }

        return view('profile.job.edit', compact('jobVacancy', 'alumnus'));
    }

    /**
     * Update the specified job vacancy owned by the alumnus.
     */
    public function update(StoreJobVacancyRequest $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $jobVacancy->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit lowongan kerja ini.');
        }

        $jobVacancy->update($request->validated());

        return redirect()->route('profile.job.index')
            ->with('success', 'Informasi lowongan kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified job vacancy owned by the alumnus.
     */
    public function destroy(JobVacancy $jobVacancy): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $jobVacancy->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus lowongan kerja ini.');
        }

        $jobVacancy->delete();

        return redirect()->route('profile.job.index')
            ->with('success', 'Lowongan kerja berhasil dihapus dari daftar Anda.');
    }

    /**
     * Toggle the status of a job vacancy between active and closed.
     */
    public function toggleStatus(JobVacancy $jobVacancy): RedirectResponse
    {
        $alumnus = auth()->user()->alumnus;

        if (! $alumnus || (int) $jobVacancy->alumnus_id !== (int) $alumnus->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah status lowongan kerja ini.');
        }

        $newStatus = $jobVacancy->status === 'active' ? 'closed' : 'active';
        $jobVacancy->update(['status' => $newStatus]);

        $label = $newStatus === 'active' ? 'dibuka kembali' : 'ditutup';

        return back()->with('success', "Lowongan kerja berhasil {$label}.");
    }
}
