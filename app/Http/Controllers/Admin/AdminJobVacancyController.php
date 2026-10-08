<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobVacancyRequest;
use App\Http\Requests\UpdateJobVacancyRequest;
use App\Models\Alumnus;
use App\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminJobVacancyController extends Controller
{
    /**
     * Display a listing of job vacancies in the admin panel.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $query = JobVacancy::query()->with('alumnus');

        if ($status && in_array($status, ['pending', 'active', 'closed', 'expired'], true)) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $vacancies = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => JobVacancy::count(),
            'pending' => JobVacancy::where('status', 'pending')->count(),
            'active' => JobVacancy::where('status', 'active')->count(),
            'closed' => JobVacancy::whereIn('status', ['closed', 'expired'])->count(),
        ];

        return view('admin.job-vacancies.index', compact('vacancies', 'stats', 'status', 'search'));
    }

    /**
     * Show the form for creating a new job vacancy.
     */
    public function create(): View
    {
        $alumni = Alumnus::query()->verified()->orderBy('name')->get();

        return view('admin.job-vacancies.create', compact('alumni'));
    }

    /**
     * Store a newly created job vacancy.
     */
    public function store(StoreJobVacancyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $request->input('status', 'active');

        if (empty($data['alumni_info']) && ! empty($data['alumnus_id'])) {
            $alumnus = Alumnus::find($data['alumnus_id']);
            if ($alumnus) {
                $levelName = strtoupper($alumnus->level ?? 'Alumni');
                $yearName = $alumnus->full_year ?? $alumnus->class_year ?? '';
                $data['alumni_info'] = "Direferensikan oleh {$alumnus->name} ({$levelName} {$yearName})";
            }
        }

        if (empty($data['company_alumni_info'])) {
            $data['company_alumni_info'] = $data['alumni_info'] ?? 'Kurasi Pengurus Pusat IKA KPS';
        }

        JobVacancy::create($data);

        return redirect()->route('admin.job-vacancies.index')
            ->with('success', 'Lowongan kerja berhasil ditambahkan ke direktori aktif.');
    }

    /**
     * Show the form for editing the specified job vacancy.
     */
    public function edit(JobVacancy $jobVacancy): View
    {
        $alumni = Alumnus::query()->verified()->orderBy('name')->get();

        return view('admin.job-vacancies.edit', compact('jobVacancy', 'alumni'));
    }

    /**
     * Update the specified job vacancy.
     */
    public function update(UpdateJobVacancyRequest $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $jobVacancy->update($request->validated());

        return redirect()->route('admin.job-vacancies.index')
            ->with('success', 'Data lowongan kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified job vacancy from storage.
     */
    public function destroy(JobVacancy $jobVacancy): RedirectResponse
    {
        $jobVacancy->delete();

        return redirect()->route('admin.job-vacancies.index')
            ->with('success', 'Lowongan kerja berhasil dihapus.');
    }

    /**
     * Toggle or update the status of the specified job vacancy.
     */
    public function toggleStatus(Request $request, JobVacancy $jobVacancy): RedirectResponse
    {
        $targetStatus = $request->input('status');

        if (! in_array($targetStatus, ['active', 'closed', 'pending', 'expired'], true)) {
            $targetStatus = $jobVacancy->status === 'active' ? 'closed' : 'active';
        }

        $jobVacancy->update(['status' => $targetStatus]);

        $statusLabel = match ($targetStatus) {
            'active' => 'diaktifkan & tayang publik',
            'closed' => 'ditutup',
            'pending' => 'dikembalikan ke status review',
            'expired' => 'ditandai kadaluarsa',
        };

        return back()->with('success', "Status lowongan berhasil {$statusLabel}.");
    }
}
