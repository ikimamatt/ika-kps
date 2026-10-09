@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1000px] mx-auto px-6 space-y-10">
        
        <!-- Breadcrumbs Navigation -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('article.index') }}" class="hover:text-primary transition-colors">Kabar &amp; Cerita</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('article.index', ['category' => $article->category]) }}" class="hover:text-primary transition-colors">{{ $article->category }}</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-bold truncate max-w-[200px] sm:max-w-[300px]">{{ $article->title }}</span>
        </nav>

        <!-- Article Header -->
        <header class="space-y-4">
            <div class="flex items-center gap-2.5 flex-wrap">
                <span class="px-3.5 py-1 rounded-lg {{ $article->badge_bg_class ?: 'bg-secondary text-white' }} text-xs font-extrabold tracking-wide shadow-2xs">
                    {{ $article->category }}
                </span>
                <span class="text-xs text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px] text-secondary">schedule</span>
                    <span>{{ $article->published_at ? $article->published_at->translatedFormat('l, d F Y') : 'Terbit Baru' }}</span>
                </span>
                <span class="text-xs text-on-surface-variant flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px] text-secondary">visibility</span>
                    <span>{{ number_format($article->views_count) }} kali dibaca</span>
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-primary tracking-tight leading-tight">
                {{ $article->title }}
            </h1>

            <!-- Author & Metadata Row -->
            <div class="flex items-center justify-between py-4 border-y border-outline-variant/30 gap-4 flex-wrap">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary/15 text-secondary flex items-center justify-center font-bold text-sm shrink-0">
                        @if($article->author && $article->author->avatar_url)
                            <img src="{{ $article->author->avatar_url }}" alt="{{ $article->author->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="material-symbols-outlined text-[20px]">person</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs sm:text-sm font-bold text-primary block">
                            {{ $article->author_and_date ? Str::before($article->author_and_date, '·') : ($article->author->name ?? 'Redaksi IKA KPS') }}
                        </span>
                        <span class="text-[11px] text-on-surface-variant">
                            Penulis &amp; Kontributor Berita
                        </span>
                    </div>
                </div>

                <!-- Quick Share Icons -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-on-surface-variant font-medium hidden sm:inline">Bagikan:</span>
                    
                    <!-- WhatsApp Share -->
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" 
                       target="_blank" 
                       rel="noopener"
                       title="Bagikan ke WhatsApp"
                       class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center border border-emerald-200">
                        <x-icons.whatsapp class="w-4 h-4 fill-current" />
                    </a>

                    <!-- Twitter / X Share -->
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(url()->current()) }}" 
                       target="_blank" 
                       rel="noopener"
                       title="Bagikan ke Twitter / X"
                       class="w-8 h-8 rounded-full bg-surface-container text-on-surface hover:bg-primary hover:text-white transition-all flex items-center justify-center border border-outline-variant/40">
                        <span class="font-bold text-xs">𝕏</span>
                    </a>

                    <!-- Copy Link -->
                    <button type="button" 
                            onclick="copyArticleLink(this)"
                            title="Salin Tautan Artikel"
                            class="px-3 py-1.5 rounded-full bg-surface-container-low text-primary text-xs font-bold hover:bg-surface-container transition-all border border-outline-variant/40 inline-flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[14px] copy-icon">content_copy</span>
                        <span class="copy-text text-[11px]">Salin</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- High-Res Cover Image -->
        @if ($article->image_url)
            <figure class="rounded-3xl overflow-hidden border border-outline-variant/40 shadow-sm bg-surface-container">
                <img src="{{ $article->image_url }}" 
                     alt="{{ $article->title }}" 
                     loading="lazy"
                     onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1000'"
                     class="w-full max-h-[520px] object-cover">
                @if ($article->author_and_date)
                    <figcaption class="p-3 text-[11px] text-on-surface-variant bg-surface-container-low border-t border-outline-variant/20 italic text-center">
                        Dokumentasi: {{ $article->title }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <!-- Article Summary Lead Paragraph -->
        <div class="p-5 sm:p-6 rounded-2xl bg-surface-container-low border border-outline-variant/30 ring-1 ring-primary/5 text-primary font-medium text-sm sm:text-base leading-relaxed">
            {{ $article->summary }}
        </div>

        <!-- Full Article Body -->
        <article class="prose prose-slate max-w-none space-y-5 text-sm sm:text-base text-on-surface leading-loose">
            @if ($article->body)
                @foreach (explode("\n\n", $article->body) as $paragraph)
                    @if (trim($paragraph))
                        <p class="leading-relaxed">{{ trim($paragraph) }}</p>
                    @endif
                @endforeach
            @else
                <p class="text-on-surface-variant italic">Belum ada konten tambahan untuk artikel ini.</p>
            @endif
        </article>

        <!-- Social Share Bar Bottom -->
        <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="space-y-0.5 text-center sm:text-left">
                <span class="text-xs font-bold text-primary block">Suka dengan kabar atau cerita ini?</span>
                <span class="text-[11px] text-on-surface-variant">Bagikan tautan ini ke grup alumni angkatan Anda.</span>
            </div>

            <div class="flex items-center gap-2">
                <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . url()->current()) }}" 
                   target="_blank" 
                   rel="noopener"
                   class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-all inline-flex items-center gap-1.5 shadow-xs">
                    <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                    <span>WhatsApp</span>
                </a>

                <button type="button" 
                        onclick="copyArticleLink(this)"
                        class="px-4 py-2 rounded-xl bg-surface-container text-primary font-bold text-xs hover:bg-surface-container-high transition-all inline-flex items-center gap-1.5 border border-outline-variant/50 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px] copy-icon">content_copy</span>
                    <span class="copy-text">Salin Tautan</span>
                </button>
            </div>
        </div>

        <!-- Related Articles Section -->
        @if ($relatedArticles->count() > 0)
            <div class="space-y-6 pt-6 border-t border-outline-variant/30">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-secondary uppercase tracking-wider block">Kabar Lainnya</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-primary">Artikel &amp; Catatan Terkait</h2>
                    </div>
                    <a href="{{ route('article.index') }}" class="text-xs font-bold text-secondary hover:text-primary transition-colors inline-flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ($relatedArticles as $rel)
                        <div class="flex flex-col rounded-xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-xs hover:shadow-md transition-all group">
                            <a href="{{ route('article.show', $rel->slug) }}" class="relative h-36 overflow-hidden bg-surface-container block">
                                <img src="{{ $rel->image_url ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=400' }}" 
                                     alt="{{ $rel->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded text-2xs font-extrabold {{ $rel->badge_bg_class ?: 'bg-secondary text-white' }}">
                                    {{ $rel->category }}
                                </span>
                            </a>
                            <div class="p-4 flex-1 flex flex-col justify-between gap-3">
                                <div class="space-y-1">
                                    <span class="text-2xs text-on-surface-variant block">
                                        {{ $rel->published_at ? $rel->published_at->translatedFormat('d M Y') : 'Baru' }}
                                    </span>
                                    <h3 class="text-xs sm:text-sm font-bold text-primary group-hover:text-secondary transition-colors line-clamp-2">
                                        <a href="{{ route('article.show', $rel->slug) }}">{{ $rel->title }}</a>
                                    </h3>
                                </div>
                                <a href="{{ route('article.show', $rel->slug) }}" class="text-xs font-bold text-secondary inline-flex items-center gap-1 hover:text-primary">
                                    <span>Baca</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back to Index Button -->
        <div class="text-center pt-4">
            <a href="{{ route('article.index') }}" 
               class="px-6 py-2.5 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Semua Kabar &amp; Cerita</span>
            </a>
        </div>

    </div>
</div>

@push('scripts')
<script>
function copyArticleLink(btn) {
    window.copyToClipboard(window.location.href).then(() => {
        const iconSpan = btn.querySelector('.copy-icon');
        const textSpan = btn.querySelector('.copy-text');
        
        if (iconSpan) iconSpan.innerText = 'check';
        if (textSpan) textSpan.innerText = 'Tersalin untuk Grup! ✨';
        btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');

        setTimeout(() => {
            if (iconSpan) iconSpan.innerText = 'content_copy';
            if (textSpan) textSpan.innerText = 'Salin Tautan';
            btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        }, 2500);
    }).catch(err => {
        alert('Gagal menyalin tautan: ' + err);
    });
}
</script>
@endpush
@endsection
