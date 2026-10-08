<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicBusinessController extends Controller
{
    /**
     * Display a listing of published businesses in public directory.
     */
    public function index(Request $request): View
    {
        $query = Business::query()->published()->with('alumnus');

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('city', 'like', "%{$keyword}%")
                    ->orWhere('owner_info', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $businesses = $query->latest('id')->paginate(12)->withQueryString();

        $categories = [
            'Semua Kategori',
            'Kuliner & F&B',
            'Jasa & Konsultan',
            'Teknologi Informasi',
            'Kesehatan',
            'Konstruksi & Energi',
            'Retail & Fesyen',
            'Otomotif & Logistik',
        ];

        return view('business.index', compact('businesses', 'categories'));
    }

    /**
     * Display the specified business detail page.
     */
    public function show(string $slug): View
    {
        $business = Business::where('slug', $slug)
            ->where('status', 'published')
            ->with('alumnus')
            ->firstOrFail();

        $relatedBusinesses = Business::where('category', $business->category)
            ->where('id', '!=', $business->id)
            ->where('status', 'published')
            ->take(3)
            ->get();

        return view('business.show', compact('business', 'relatedBusinesses'));
    }
}
