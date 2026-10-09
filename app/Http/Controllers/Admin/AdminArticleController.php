<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminArticleController extends Controller
{
    /**
     * Map default badge classes for standard article categories.
     */
    protected array $categoryBadges = [
        'Kegiatan' => 'bg-blue-500/10 text-blue-600',
        'Nostalgia' => 'bg-amber-500/10 text-amber-600',
        'Prestasi' => 'bg-emerald-500/10 text-emerald-600',
        'Sosial' => 'bg-rose-500/10 text-rose-600',
        'Opini' => 'bg-purple-500/10 text-purple-600',
        'Pengumuman' => 'bg-primary/10 text-primary',
    ];

    /**
     * Display a listing of articles for administrators.
     */
    public function index(Request $request): View
    {
        $query = Article::query()->latest('id');

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
                    ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'draft' => Article::where('status', 'draft')->count(),
            'total_views' => Article::sum('views_count'),
        ];

        return view('admin.articles.index', compact('articles', 'stats'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create(): View
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $data['image_url'] = Storage::url($path);
        }

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']).'-'.rand(100, 999);
        }

        $data['author_id'] = auth()->id();

        if (empty($data['badge_bg_class'])) {
            $data['badge_bg_class'] = $this->categoryBadges[$data['category']] ?? 'bg-secondary/10 text-secondary';
        }

        if (empty($data['author_and_date'])) {
            $authorName = auth()->user()->name ?? 'Redaksi IKA KPS';
            $data['author_and_date'] = $authorName.' · '.now()->translatedFormat('d M Y');
        }

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel / berita berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(Article $article): View
    {
        return view('admin.articles.edit', compact('article'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($article->image_url && Str::contains($article->image_url, '/storage/articles/')) {
                $oldPath = Str::after($article->image_url, '/storage/');
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('articles', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif (empty($data['image_url'])) {
            $data['image_url'] = $article->image_url;
        } elseif ($article->image_url && $data['image_url'] !== $article->image_url && Str::contains($article->image_url, '/storage/articles/')) {
            $oldPath = Str::after($article->image_url, '/storage/');
            Storage::disk('public')->delete($oldPath);
        }

        if (empty($data['badge_bg_class'])) {
            $data['badge_bg_class'] = $this->categoryBadges[$data['category']] ?? 'bg-secondary/10 text-secondary';
        }

        if ($data['status'] === 'published' && empty($article->published_at) && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel / berita berhasil diperbarui.');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.articles.index')
            ->with('success', 'Artikel / berita berhasil dihapus.');
    }

    /**
     * Toggle the status of the specified article between draft and published.
     */
    public function toggleStatus(Article $article): RedirectResponse
    {
        $newStatus = $article->status === 'published' ? 'draft' : 'published';

        $updates = ['status' => $newStatus];

        if ($newStatus === 'published' && empty($article->published_at)) {
            $updates['published_at'] = now();
        }

        $article->update($updates);

        $statusLabel = $newStatus === 'published' ? 'dipublikasikan ke publik' : 'ditarik kembali ke draft';

        return back()->with('success', "Status artikel '{$article->title}' berhasil {$statusLabel}.");
    }
}
