<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicProgramController extends Controller
{
    /**
     * Display a listing of public programs with filter & search.
     */
    public function index(Request $request): View
    {
        $query = Program::query();

        // Filter status: active, completed, upcoming, or all
        if ($request->filled('status') && in_array($request->status, ['active', 'completed', 'upcoming'])) {
            $query->where('status', $request->status);
        }

        // Search by keyword in title or description
        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $programs = $query->latest('id')->paginate(9)->withQueryString();

        // Metrics for hero stats
        $stats = [
            'total_programs' => Program::count(),
            'active_programs' => Program::where('status', 'active')->count(),
            'completed_programs' => Program::where('status', 'completed')->count(),
            'total_funds' => Program::sum('collected_amount'),
        ];

        return view('program.index', compact('programs', 'stats'));
    }

    /**
     * Display the specified program details.
     */
    public function show(string $slug): View
    {
        $program = Program::where('slug', $slug)->firstOrFail();

        $relatedPrograms = Program::where('id', '!=', $program->id)
            ->where('status', 'active')
            ->latest('id')
            ->take(3)
            ->get();

        return view('program.show', compact('program', 'relatedPrograms'));
    }
}
