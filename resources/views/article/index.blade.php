@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">
        
        <!-- Header & Breadcrumbs -->
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Kabar &amp; Cerita Alumni</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/30">
                <div class="max-w-2xl space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20 inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">newspaper</span>
                        <span>Ruang Redaksi IKA KPS</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight">
                        Kabar Terkini, Nostalgia &amp; Cerita Alumni
                    </h1>
                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Dokumentasi perjalanan, kenangan masa sekolah di Komplek Prapatan, prestasi membanggakan, serta kabar kegiatan lintas angkatan alumni KPS Balikpapan.
                    </p>
                </div>

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                        <div class="shrink-0">
                            <a href="{{ route('admin.articles.create') }}" 
                               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">post_add</span>
                                <span>Tulis Artikel Baru</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Featured / Headline Article (If Available on Top) -->
        @if ($featuredArticle)
            <div class="relative overflow-hidden rounded-3xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md transition-all">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                    <a href="{{ route('article.show', $featuredArticle->slug) }}" class="lg:col-span-7 relative min-h-[260px] sm:min-h-[340px] lg:min-h-[420px] overflow-hidden group block">
                        <img src="{{ $featuredArticle->image_url ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1000' }}" 
                             alt="{{ $featuredArticle->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent lg:hidden"></div>
                        <span class="absolute top-4 left-4 px-3 py-1.5 rounded-lg {{ $featuredArticle->badge_bg_class ?: 'bg-secondary text-white' }} text-xs font-extrabold tracking-wide shadow-xs">
                            Headline • {{ $featuredArticle->category }}
                        </span>
                    </a>
                    
                    <div class="lg:col-span-5 p-6 sm:p-8 lg:p-10 flex flex-col justify-between gap-6">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 text-xs text-on-surface-variant font-medium">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">schedule</span>
                                    <span>{{ $featuredArticle->published_at ? $featuredArticle->published_at->translatedFormat('d M Y') : 'Terbaru' }}</span>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">visibility</span>
                                    <span>{{ number_format($featuredArticle->views_count) }} dibaca</span>
                                </span>
                            </div>

                            <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-primary leading-tight hover:text-secondary transition-colors">
                                <a href="{{ route('article.show', $featuredArticle->slug) }}">
                                    {{ $featuredArticle->title }}
                                </a>
                            </h2>

                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed line-clamp-4">
                                {{ $featuredArticle->summary }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-secondary/15 text-secondary flex items-center justify-center font-bold text-xs">
                                    <span class="material-symbols-outlined text-[18px]">person</span>
                                </div>
                                <span class="text-xs font-bold text-primary truncate max-w-[150px]">
                                    {{ $featuredArticle->author_and_date ? Str::before($featuredArticle->author_and_date, '·') : ($featuredArticle->author->name ?? 'Tim Redaksi') }}
                                </span>
                            </div>

                            <a href="{{ route('article.show', $featuredArticle->slug) }}" 
                               class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-secondary hover:text-primary transition-colors group">
                                <span>Baca Ulasan</span>
                                <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                
                <!-- Category Tabs / Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none text-xs font-bold">
                    <a href="{{ route('article.index', array_filter(['search' => $search])) }}" 
                       class="px-4 py-2 rounded-xl transition-all whitespace-nowrap {{ empty($selectedCategory) ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/40 hover:bg-surface-container-low' }}">
                        Semua Kategori
                    </a>

                    @php
                        $standardCategories = ['Kegiatan', 'Nostalgia', 'Prestasi', 'Sosial', 'Opini', 'Pengumuman'];
                    @endphp

                    @foreach($standardCategories as $cat)
                        @php
                            $isActive = ($selectedCategory === $cat);
                            $count = $categoryCounts[$cat] ?? 0;
                        @endphp
                        <a href="{{ route('article.index', array_filter(['category' => $cat, 'search' => $search])) }}" 
                           class="px-4 py-2 rounded-xl transition-all whitespace-nowrap inline-flex items-center gap-1.5 {{ $isActive ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/40 hover:bg-surface-container-low' }}">
                            <span>{{ $cat }}</span>
                            @if($count > 0)
                                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface-variant' }}">
                                    {{ $count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('article.index') }}" class="relative max-w-sm w-full">
                    @if($selectedCategory)
                        <input type="hidden" name="category" value="{{ $selectedCategory }}">
                    @endif
                    <input type="text" 
                           name="search" 
                           value="{{ $search }}" 
                           placeholder="Cari kabar, nostalgia, topik..."
                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">search</span>
                    
                    @if(!empty($search))
                        <a href="{{ route('article.index', array_filter(['category' => $selectedCategory])) }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Articles Grid -->
        @if ($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($articles as $art)
                    @php
                        $wordCount = str_word_count(strip_tags($art->body ?? $art->summary));
                        $readTime = max(1, (int) ceil($wordCount / 180));
                    @endphp
                    <article class="flex flex-col rounded-2xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-xs hover:shadow-md transition-all group">
                        
                        <!-- Thumbnail Image -->
                        <a href="{{ route('article.show', $art->slug) }}" class="relative h-48 sm:h-52 overflow-hidden bg-surface-container block">
                            <img alt="{{ $art->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="{{ $art->image_url ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600' }}">
                            
                            <span class="absolute top-3 left-3 px-3 py-1 rounded-lg {{ $art->badge_bg_class ?: 'bg-secondary text-white' }} text-[11px] font-extrabold tracking-wide shadow-2xs">
                                {{ $art->category }}
                            </span>

                            <span class="absolute bottom-3 right-3 px-2 py-0.5 rounded bg-black/60 backdrop-blur-xs text-[10px] text-white font-medium flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">schedule</span>
                                <span>{{ $readTime }} mnt baca</span>
                            </span>
                        </a>

                        <!-- Card Body -->
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between gap-4">
                            <div class="space-y-2.5">
                                <div class="flex items-center justify-between text-[11px] text-on-surface-variant">
                                    <span>{{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : 'Baru' }}</span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">visibility</span>
                                        <span>{{ number_format($art->views_count) }}</span>
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-bold text-primary group-hover:text-secondary transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ route('article.show', $art->slug) }}">
                                        {{ $art->title }}
                                    </a>
                                </h3>

                                <p class="text-xs sm:text-sm text-on-surface-variant line-clamp-3 leading-relaxed">
                                    {{ $art->summary }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-between">
                                <span class="text-[11px] font-semibold text-on-surface-variant truncate max-w-[180px]">
                                    {{ $art->author_and_date ? Str::before($art->author_and_date, '·') : ($art->author->name ?? 'Redaksi') }}
                                </span>

                                <a href="{{ route('article.show', $art->slug) }}" 
                                   class="text-xs font-bold text-secondary inline-flex items-center gap-1 hover:text-primary transition-colors">
                                    <span>Baca</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $articles->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="py-16 text-center space-y-4 max-w-md mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-high text-on-surface-variant flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-3xl">newspaper</span>
                </div>
                <h3 class="text-lg font-bold text-primary">Tidak Ada Artikel Ditemukan</h3>
                <p class="text-xs text-on-surface-variant">
                    @if($search || $selectedCategory)
                        Tidak ada artikel yang cocok dengan filter atau kata kunci pencarian Anda. Silakan coba kata kunci lain.
                    @else
                        Belum ada artikel berita yang dipublikasikan saat ini.
                    @endif
                </p>
                @if($search || $selectedCategory)
                    <a href="{{ route('article.index') }}" 
                       class="inline-block px-4 py-2 rounded-xl bg-surface-container-low text-primary text-xs font-bold hover:bg-surface-container transition-all">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
