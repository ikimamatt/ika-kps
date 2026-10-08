<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\Article;
use App\Models\Business;
use App\Models\JobVacancy;
use App\Models\Program;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard overview.
     */
    public function index(): View
    {
        $metrics = [
            'total_alumni' => Alumnus::where('is_verified', true)->count(),
            'pending_registrations' => User::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'active_businesses' => Schema::hasTable('businesses') ? Business::where('status', 'published')->count() : 0,
            'active_jobs' => Schema::hasTable('job_vacancies') ? JobVacancy::where('status', 'published')->count() : 0,
            'total_programs' => Schema::hasTable('programs') ? Program::count() : 0,
            'total_articles' => Schema::hasTable('articles') ? Article::count() : 0,
        ];

        $latestPendingAlumni = User::with('alumnus')
            ->where('status', 'pending')
            ->latest('id')
            ->take(5)
            ->get();

        $alumniDistribution = [
            'sma' => Alumnus::where('level', 'sma')->count(),
            'smp' => Alumnus::where('level', 'smp')->count(),
            'sd' => Alumnus::where('level', 'sd')->count(),
            'tk' => Alumnus::where('level', 'tk')->count(),
        ];

        return view('admin.dashboard', compact('metrics', 'latestPendingAlumni', 'alumniDistribution'));
    }
}
