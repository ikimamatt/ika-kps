<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Requests\UploadGalleryPhotosRequest;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminGalleryController extends Controller
{
    /**
     * Display a listing of galleries for administrators.
     */
    public function index(Request $request): View
    {
        $query = Gallery::query()->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

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

        $galleries = $query->withCount('photos')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => Gallery::count(),
            'published' => Gallery::where('status', 'published')->count(),
            'draft' => Gallery::where('status', 'draft')->count(),
            'total_photos' => GalleryPhoto::count(),
        ];

        return view('admin.galleries.index', compact('galleries', 'stats'));
    }

    /**
     * Show the form for creating a new gallery album.
     */
    public function create(): View
    {
        return view('admin.galleries.create');
    }

    /**
     * Store a newly created gallery album in storage.
     */
    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('galleries/covers', 'public');
            $data['cover_image_url'] = Storage::url($path);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.rand(100, 999);
        }

        $gallery = Gallery::create($data);

        // Process initial batch photos if uploaded
        if ($request->hasFile('photos')) {
            $captions = $request->input('captions', []);
            foreach ($request->file('photos') as $index => $photoFile) {
                $photoPath = $photoFile->store('galleries/photos', 'public');
                $gallery->photos()->create([
                    'image_url' => Storage::url($photoPath),
                    'caption' => $captions[$index] ?? null,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', "Album galeri '{$gallery->title}' berhasil dibuat.");
    }

    /**
     * Show the form for editing the specified gallery album and managing its photos.
     */
    public function edit(Gallery $gallery): View
    {
        $gallery->load(['photos' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.galleries.edit', compact('gallery'));
    }

    /**
     * Update the specified gallery album in storage.
     */
    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            if ($gallery->cover_image_url && Str::contains($gallery->cover_image_url, '/storage/galleries/covers/')) {
                $oldPath = Str::after($gallery->cover_image_url, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('cover')->store('galleries/covers', 'public');
            $data['cover_image_url'] = Storage::url($path);
        }

        $gallery->update($data);

        return redirect()->route('admin.galleries.index')
            ->with('success', "Album galeri '{$gallery->title}' berhasil diperbarui.");
    }

    /**
     * Remove the specified gallery album from storage.
     */
    public function destroy(Gallery $gallery): RedirectResponse
    {
        // Delete all photos files in this gallery
        foreach ($gallery->photos as $photo) {
            if ($photo->image_url && Str::contains($photo->image_url, '/storage/galleries/photos/')) {
                $photoPath = Str::after($photo->image_url, '/storage/');
                Storage::disk('public')->delete($photoPath);
            }
        }

        if ($gallery->cover_image_url && Str::contains($gallery->cover_image_url, '/storage/galleries/covers/')) {
            $coverPath = Str::after($gallery->cover_image_url, '/storage/');
            Storage::disk('public')->delete($coverPath);
        }

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', "Album galeri '{$gallery->title}' berhasil dihapus.");
    }

    /**
     * Toggle the publication status of the gallery.
     */
    public function toggleStatus(Gallery $gallery): RedirectResponse
    {
        $newStatus = $gallery->status === 'published' ? 'draft' : 'published';
        $gallery->update(['status' => $newStatus]);

        $statusLabel = $newStatus === 'published' ? 'dipublikasikan ke publik' : 'ditarik ke draft';

        return back()->with('success', "Status album '{$gallery->title}' berhasil {$statusLabel}.");
    }

    /**
     * Upload additional photos to the specified gallery.
     */
    public function uploadPhotos(UploadGalleryPhotosRequest $request, Gallery $gallery): RedirectResponse
    {
        $currentMaxOrder = $gallery->photos()->max('sort_order') ?? 0;
        $captions = $request->input('captions', []);

        $uploadedCount = 0;
        foreach ($request->file('photos') as $index => $photoFile) {
            $photoPath = $photoFile->store('galleries/photos', 'public');
            $gallery->photos()->create([
                'image_url' => Storage::url($photoPath),
                'caption' => $captions[$index] ?? null,
                'sort_order' => $currentMaxOrder + $index + 1,
            ]);
            $uploadedCount++;
        }

        return back()->with('success', "{$uploadedCount} foto baru berhasil ditambahkan ke album.");
    }

    /**
     * Remove a single photo from the gallery.
     */
    public function deletePhoto(GalleryPhoto $photo): RedirectResponse
    {
        if ($photo->image_url && Str::contains($photo->image_url, '/storage/galleries/photos/')) {
            $photoPath = Str::after($photo->image_url, '/storage/');
            Storage::disk('public')->delete($photoPath);
        }

        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus dari album.');
    }
}
