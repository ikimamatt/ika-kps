<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicGalleryController extends Controller
{
    /**
     * Display a listing of public gallery albums.
     */
    public function index(Request $request): View
    {
        $query = Gallery::published()->latest('event_date');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categoryCounts = Gallery::published()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        $stats = [
            'total_albums' => Gallery::published()->count(),
            'total_photos' => GalleryPhoto::whereHas('gallery', fn ($q) => $q->published())->count(),
        ];

        $galleries = $query->withCount('photos')
            ->paginate(9)
            ->withQueryString();

        return view('gallery.index', compact('galleries', 'categoryCounts', 'stats'));
    }

    /**
     * Display the specified gallery album and photos.
     */
    public function show(string $slug): View
    {
        $gallery = Gallery::published()
            ->where('slug', $slug)
            ->with(['photos' => fn ($q) => $q->orderBy('sort_order')])
            ->firstOrFail();

        $relatedGalleries = Gallery::published()
            ->where('id', '!=', $gallery->id)
            ->latest('event_date')
            ->take(3)
            ->withCount('photos')
            ->get();

        return view('gallery.show', compact('gallery', 'relatedGalleries'));
    }
}
