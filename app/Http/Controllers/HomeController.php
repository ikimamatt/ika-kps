<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use App\Models\Article;
use App\Models\Business;
use App\Models\JobVacancy;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the landing page with dynamic IKA KPS content.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $level = $request->query('level');
        $yearRange = $request->query('year');

        $alumniQuery = Alumnus::query()->verified();

        if ($search) {
            $alumniQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('domicile', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        if ($level && in_array(strtolower($level), ['sma', 'smp', 'sd', 'tk'])) {
            $alumniQuery->where('level', strtolower($level));
        }

        if ($yearRange) {
            if ($yearRange === '2020-2024') {
                $alumniQuery->whereBetween('full_year', ['2020', '2024']);
            } elseif ($yearRange === '2010-2019') {
                $alumniQuery->whereBetween('full_year', ['2010', '2019']);
            } elseif ($yearRange === '2000-2009') {
                $alumniQuery->whereBetween('full_year', ['2000', '2009']);
            } elseif ($yearRange === '1990-1999') {
                $alumniQuery->whereBetween('full_year', ['1990', '1999']);
            } elseif ($yearRange === '1980-1989') {
                $alumniQuery->whereBetween('full_year', ['1980', '1989']);
            }
        }

        $totalAlumniCount = Alumnus::query()->verified()->count();
        $totalBusinessesCount = Business::query()->published()->count();
        $totalArticlesCount = Article::published()->count();

        // Paginate alumni directory preview with 6 items per page, fragmenting to section anchor
        $alumni = $alumniQuery->orderBy('id', 'desc')->paginate(6)->withQueryString()->fragment('direktori-alumni');

        // Showcase businesses limited to top 6
        $businesses = Business::query()->published()->orderBy('id', 'desc')->take(6)->get();

        // Active job vacancies limited to 4
        $jobVacancies = JobVacancy::query()->active()->orderBy('id', 'desc')->take(4)->get();

        // Featured community programs limited to 4
        $programs = Program::take(4)->get();

        // Showcase latest articles limited to 3 published
        $articles = Article::published()->latest('published_at')->take(3)->get();
        if ($articles->isEmpty()) {
            $articles = Article::orderBy('id', 'desc')->take(3)->get();
        }

        return view('home', compact(
            'alumni',
            'businesses',
            'jobVacancies',
            'programs',
            'articles',
            'search',
            'level',
            'yearRange',
            'totalAlumniCount',
            'totalBusinessesCount',
            'totalArticlesCount'
        ));
    }
}
