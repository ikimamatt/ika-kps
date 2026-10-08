<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectAlumnusRequest;
use App\Models\Alumnus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniVerificationController extends Controller
{
    /**
     * Display a listing of alumni membership verification requests.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');

        $query = Alumnus::query()->with(['user', 'verifier']);

        // Search query
        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('profession', 'like', "%{$keyword}%")
                    ->orWhere('full_year', 'like', "%{$keyword}%");
            });
        }

        // Education level filter
        if ($request->filled('level')) {
            $query->where('level', $request->input('level'));
        }

        // Status tab filtering
        if ($status === 'approved') {
            $query->where('is_verified', true);
        } elseif ($status === 'rejected') {
            $query->whereHas('user', function ($q) {
                $q->where('status', 'rejected');
            });
        } elseif ($status === 'pending') {
            $query->where('is_verified', false)
                ->where(function ($q) {
                    $q->whereDoesntHave('user')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('status', '!=', 'rejected');
                        });
                });
        }

        $alumni = $query->latest('id')->paginate(15)->withQueryString();

        // Counter badges
        $pendingCount = Alumnus::where('is_verified', false)
            ->where(function ($q) {
                $q->whereDoesntHave('user')
                    ->orWhereHas('user', function ($uq) {
                        $uq->where('status', '!=', 'rejected');
                    });
            })->count();

        $approvedCount = Alumnus::where('is_verified', true)->count();

        $rejectedCount = Alumnus::whereHas('user', function ($q) {
            $q->where('status', 'rejected');
        })->count();

        return view('admin.verification.index', compact(
            'alumni',
            'status',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Display the specified verification request details.
     */
    public function show(Alumnus $alumnus): View
    {
        $alumnus->load(['user', 'verifier']);

        return view('admin.verification.show', compact('alumnus'));
    }

    /**
     * Approve the alumnus membership request.
     */
    public function approve(Request $request, Alumnus $alumnus): RedirectResponse
    {
        $alumnus->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
        ]);

        if ($alumnus->user) {
            $alumnus->user->update([
                'status' => 'active',
                'rejection_reason' => null,
            ]);
        }

        return redirect()->back()->with('success', "Permohonan keanggotaan alumni {$alumnus->name} berhasil disetujui. Akun dan profil publik kini aktif.");
    }

    /**
     * Reject the alumnus membership request.
     */
    public function reject(RejectAlumnusRequest $request, Alumnus $alumnus): RedirectResponse
    {
        $reason = $request->validated('rejection_reason');

        $alumnus->update([
            'is_verified' => false,
            'verified_at' => null,
            'verified_by' => null,
        ]);

        if ($alumnus->user) {
            $alumnus->user->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);
        }

        return redirect()->back()->with('success', "Permohonan keanggotaan alumni {$alumnus->name} telah ditolak.");
    }
}
