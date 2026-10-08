<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\UpdateBusinessRequest;
use App\Models\Alumnus;
use App\Models\Business;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminBusinessController extends Controller
{
    /**
     * Display a listing of businesses in admin panel.
     */
    public function index(Request $request): View
    {
        $query = Business::query()->with('alumnus');

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('owner_info', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $businesses = $query->latest('id')->paginate(15)->withQueryString();

        $pendingCount = Business::where('status', 'pending')->count();
        $publishedCount = Business::where('status', 'published')->count();

        $categories = [
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('admin.businesses.index', compact(
            'businesses',
            'pendingCount',
            'publishedCount',
            'categories'
        ));
    }

    /**
     * Show the form for creating a new business.
     */
    public function create(): View
    {
        $alumni = Alumnus::orderBy('name')->get();
        $categories = [
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('admin.businesses.create', compact('alumni', 'categories'));
    }

    /**
     * Store a newly created business in storage.
     */
    public function store(StoreBusinessRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('businesses', 'public');
            $data['image_url'] = Storage::url($path);
        }
        unset($data['image']);

        $data['slug'] = Str::slug($data['name']).'-'.rand(100, 999);
        $data['status'] = $data['status'] ?? 'published';

        Business::create($data);

        return redirect()->route('admin.businesses.index')->with('success', 'Data bisnis alumni berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified business.
     */
    public function edit(Business $business): View
    {
        $alumni = Alumnus::orderBy('name')->get();
        $categories = [
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('admin.businesses.edit', compact('business', 'alumni', 'categories'));
    }

    /**
     * Update the specified business in storage.
     */
    public function update(UpdateBusinessRequest $request, Business $business): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($business->image_url) {
                $parsedPath = parse_url($business->image_url, PHP_URL_PATH);
                if ($parsedPath && str_contains($parsedPath, '/storage/')) {
                    $oldPath = substr($parsedPath, strpos($parsedPath, '/storage/') + strlen('/storage/'));
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('image')->store('businesses', 'public');
            $data['image_url'] = Storage::url($path);
        }
        unset($data['image']);

        $business->update($data);

        return redirect()->route('admin.businesses.index')->with('success', 'Data bisnis alumni berhasil diperbarui.');
    }

    /**
     * Toggle published status of a business.
     */
    public function publish(Business $business): RedirectResponse
    {
        $business->status = ($business->status === 'published') ? 'pending' : 'published';
        $business->save();

        $statusText = $business->status === 'published' ? 'dipublikasikan' : 'disimpan sebagai pending';

        return redirect()->back()->with('success', "Bisnis {$business->name} berhasil {$statusText}.");
    }

    /**
     * Soft delete the specified business.
     */
    public function destroy(Business $business): RedirectResponse
    {
        $business->delete();

        return redirect()->route('admin.businesses.index')->with('success', 'Bisnis berhasil dipindahkan ke arsip sampah.');
    }
}
