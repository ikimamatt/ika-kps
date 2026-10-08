<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PublicArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(Request $request): View
    {
        $query = Article::published()->latest('published_at');

        $search = $request->input('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $selectedCategory = $request->input('category');
        if (! empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        // Available categories with published article counts
        $categoryCounts = Article::published()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Featured headline article (featured on top when no search/filter and on first page)
        $featuredArticle = null;
        if (empty($search) && empty($selectedCategory) && (! $request->has('page') || (int) $request->input('page') === 1)) {
            $featuredArticle = (clone $query)->first();
            if ($featuredArticle) {
                $query->where('id', '!=', $featuredArticle->id);
            }
        }

        $articles = $query->paginate(9)->withQueryString();

        return view('article.index', compact('articles', 'featuredArticle', 'categoryCounts', 'selectedCategory', 'search'));
    }

    /**
     * Display the specified article.
     */
    public function show(string $slug): View
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views count safely (only once per user session to prevent refresh spam)
        $sessionKey = 'viewed_article_'.$article->id;
        if (! session()->has($sessionKey)) {
            $article->increment('views_count');
            session()->put($sessionKey, true);
        }

        // Related articles
        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedArticles->count() < 3) {
            $additionalCount = 3 - $relatedArticles->count();
            $excludeIds = $relatedArticles->pluck('id')->push($article->id)->toArray();

            $fallbackArticles = Article::published()
                ->whereNotIn('id', $excludeIds)
                ->latest('published_at')
                ->take($additionalCount)
                ->get();

            $relatedArticles = $relatedArticles->concat($fallbackArticles);
        }

        return view('article.show', compact('article', 'relatedArticles'));
    }
}
