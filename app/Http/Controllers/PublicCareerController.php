<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCareerController extends Controller
{
    /**
     * Display the public job vacancy directory.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $jobType = $request->query('job_type');
        $location = $request->query('location');

        $query = JobVacancy::query()->active()->with('alumnus');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('requirements', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($jobType) {
            $query->where('job_type', $jobType);
        }

        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }

        $jobs = $query->latest('id')->paginate(10)->withQueryString();

        $jobTypes = ['Full Time', 'Part Time', 'Magang', 'Kontrak'];
        $locations = ['Balikpapan', 'IKN Nusantara', 'Samarinda', 'Jakarta (Remote)'];

        return view('career.index', compact('jobs', 'search', 'jobType', 'location', 'jobTypes', 'locations'));
    }

    /**
     * Display single job vacancy detail.
     */
    public function show(string $slug): View
    {
        $job = JobVacancy::query()
            ->active()
            ->with(['alumnus.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedJobs = JobVacancy::query()
            ->active()
            ->where('id', '!=', $job->id)
            ->where(function ($q) use ($job) {
                $q->where('job_type', $job->job_type)
                    ->orWhere('location', $job->location);
            })
            ->latest('id')
            ->take(3)
            ->get();

        return view('career.show', compact('job', 'relatedJobs'));
    }
}
