<!-- NOSTALGIA & NEWS SECTION ("Kabar & Kenangan KPS") -->
<section class="w-full py-12 sm:py-20 bg-surface" id="galeri-nostalgia">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 flex flex-col gap-8 sm:gap-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-2 max-w-2xl">
                <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Kilas Cerita &amp; Informasi</span>
                <h2 class="font-headline-lg text-2xl sm:text-3xl lg:text-headline-xl text-primary tracking-tight">Kabar Terkini &amp; Kenangan KPS</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Nostalgia masa putih abu-abu di Prapatan serta liputan kegiatan terbaru alumni di tanah rantau dan kota asal.
                </p>
            </div>
            <a class="font-label-lg text-label-lg text-secondary font-bold hover:text-primary inline-flex items-center gap-1.5 transition-colors shrink-0" href="{{ route('article.index') }}">
                <span>Buka Seluruh Arsip Berita ({{ $totalArticlesCount ?? count($articles) }}+)</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <!-- News & Nostalgia Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse ($articles as $art)
                @php
                    $artSvgFallback = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 240'%3E%3Crect width='400' height='240' fill='%23092b5a'/%3E%3Ccircle cx='200' cy='120' r='48' fill='%230084c7' opacity='0.25'/%3E%3Ctext x='50%25' y='52%25' dominant-baseline='middle' text-anchor='middle' fill='%23ffffff' font-family='sans-serif' font-size='16' font-weight='700'%3EKABAR IKA KPS%3C/text%3E%3C/svg%3E";
                    $artImgSrc = $art->image_url ?: $artSvgFallback;
                @endphp
                <article class="flex flex-col rounded-xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md transition-shadow">
                    <a href="{{ route('article.show', $art->slug) }}" class="relative h-44 sm:h-48 bg-surface-container block overflow-hidden group">
                        <img src="{{ $artImgSrc }}" 
                             alt="{{ $art->title }}" 
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                             onerror="this.onerror=null; this.src='{{ $artSvgFallback }}';">
                        <span class="absolute top-3 left-3 px-3 py-1 {{ $art->badge_bg_class }} font-label-sm text-label-sm font-bold rounded">
                            {{ $art->category }}
                        </span>
                    </a>
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $art->author_and_date }}</span>
                            <h3 class="font-title-md text-title-md text-primary font-bold hover:text-secondary transition-colors">
                                <a href="{{ route('article.show', $art->slug) }}">{{ $art->title }}</a>
                            </h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-3">
                                {{ $art->summary }}
                            </p>
                        </div>
                        <a class="font-label-md text-label-md text-secondary font-bold inline-flex items-center gap-1 hover:text-primary transition-colors min-h-[44px]" href="{{ route('article.show', $art->slug) }}">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </article>
            @empty
                <p class="col-span-3 text-center py-8 text-on-surface-variant">Belum ada artikel berita terkini.</p>
            @endforelse
        </div>

        <!-- View All Articles Footer Button -->
        <div class="flex justify-center pt-2">
            <a href="{{ route('article.index') }}" 
               class="w-full sm:w-auto px-6 py-3 rounded-xl bg-surface-container-high hover:bg-primary hover:text-white text-primary font-bold text-xs sm:text-sm transition-all shadow-xs inline-flex items-center justify-center gap-2 min-h-[44px] text-center">
                <span>Lihat Seluruh Arsip Berita &amp; Artikel ({{ $totalArticlesCount ?? count($articles) }} Tulisan)</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
