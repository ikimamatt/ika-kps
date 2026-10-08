<?php

namespace App\Http\Controllers;

use App\Models\Alumnus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAlumniController extends Controller
{
    /**
     * Display a listing of verified alumni with search and filtering.
     */
    public function index(Request $request): View
    {
        $query = Alumnus::query()->where('is_verified', true);

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('profession', 'like', "%{$keyword}%")
                    ->orWhere('institution', 'like', "%{$keyword}%")
                    ->orWhere('domicile', 'like', "%{$keyword}%")
                    ->orWhere('summary', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('level') && in_array($request->input('level'), ['tk', 'sd', 'smp', 'sma'], true)) {
            $query->where('level', $request->input('level'));
        }

        if ($request->filled('year')) {
            $query->where('full_year', $request->input('year'));
        }

        $alumni = $query->latest('id')->paginate(12)->withQueryString();

        return view('alumni.index', compact('alumni'));
    }

    /**
     * Display the specified alumni public profile.
     */
    public function show(string $slug): View
    {
        $alumnus = Alumnus::with(['businesses' => function ($q) {
            $q->where('status', 'published');
        }])
            ->where('slug', $slug)
            ->where('is_verified', true)
            ->firstOrFail();

        return view('alumni.show', compact('alumnus'));
    }
}
