<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgramRequest;
use App\Http\Requests\UpdateProgramRequest;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProgramController extends Controller
{
    /**
     * Display a listing of programs in Admin Panel.
     */
    public function index(Request $request): View
    {
        $query = Program::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('title', 'like', "%{$search}%");
        }

        $programs = $query->latest('id')->paginate(15)->withQueryString();

        $stats = [
            'total' => Program::count(),
            'active' => Program::where('status', 'active')->count(),
            'upcoming' => Program::where('status', 'upcoming')->count(),
            'completed' => Program::where('status', 'completed')->count(),
            'total_collected' => Program::sum('collected_amount'),
        ];

        return view('admin.programs.index', compact('programs', 'stats'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create(): View
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(StoreProgramRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('programs', 'public');
            $data['image_url'] = Storage::url($path);
        }

        if (empty($data['icon'])) {
            $data['icon'] = 'volunteer_activism';
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.rand(100, 999);
        }

        if (isset($data['target_amount']) && (float) $data['target_amount'] > 0) {
            $collected = (float) ($data['collected_amount'] ?? 0);
            $calculatedPercent = (int) round(($collected / (float) $data['target_amount']) * 100);

            if (! isset($data['progress_percent']) || $data['progress_percent'] === null || $data['progress_percent'] === '' || $request->boolean('auto_calculate_percent')) {
                $data['progress_percent'] = $calculatedPercent;
            }

            if (empty($data['achievement_text'])) {
                $data['achievement_text'] = 'Terkumpul Rp '.number_format($collected, 0, ',', '.').' dari target Rp '.number_format((float) $data['target_amount'], 0, ',', '.');
            }
        } elseif (! isset($data['progress_percent'])) {
            $data['progress_percent'] = 0;
        }

        Program::create($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program kerja berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(Program $program): View
    {
        return view('admin.programs.edit', compact('program'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(UpdateProgramRequest $request, Program $program): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old file if local
            if ($program->image_url && Str::contains($program->image_url, '/storage/programs/')) {
                $oldPath = Str::after($program->image_url, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('programs', 'public');
            $data['image_url'] = Storage::url($path);
        }

        if (isset($data['target_amount']) && (float) $data['target_amount'] > 0) {
            $collected = (float) ($data['collected_amount'] ?? 0);
            $calculatedPercent = (int) round(($collected / (float) $data['target_amount']) * 100);

            if (! isset($data['progress_percent']) || $data['progress_percent'] === null || $data['progress_percent'] === '' || $request->boolean('auto_calculate_percent')) {
                $data['progress_percent'] = $calculatedPercent;
            }

            if (empty($data['achievement_text'])) {
                $data['achievement_text'] = 'Terkumpul Rp '.number_format($collected, 0, ',', '.').' dari target Rp '.number_format((float) $data['target_amount'], 0, ',', '.');
            }
        }

        $program->update($data);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Data program kerja berhasil diperbarui.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program kerja berhasil dihapus.');
    }
}
