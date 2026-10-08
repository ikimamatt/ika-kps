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

        $alumniQuery = Alumnus::query();

        if ($search) {
            $alumniQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('profession', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('badge', 'like', "%{$search}%");
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

        $alumni = $alumniQuery->orderBy('id', 'desc')->get();
        $businesses = Business::all();
        $jobVacancies = JobVacancy::all();
        $programs = Program::all();
        $articles = Article::orderBy('id', 'desc')->get();

        return view('home', compact('alumni', 'businesses', 'jobVacancies', 'programs', 'articles', 'search', 'level', 'yearRange'));
    }
}
