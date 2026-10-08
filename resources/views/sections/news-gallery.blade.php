<!-- NOSTALGIA & NEWS SECTION ("Kabar & Kenangan KPS") -->
<section class="w-full py-20 bg-surface" id="galeri-nostalgia">
    <div class="max-w-[1280px] mx-auto px-6 flex flex-col gap-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-2 max-w-2xl">
                <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Kilas Cerita &amp; Informasi</span>
                <h2 class="font-headline-lg text-headline-xl text-primary tracking-tight">Kabar Terkini &amp; Kenangan KPS</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Nostalgia masa putih abu-abu di Prapatan serta liputan kegiatan terbaru alumni di tanah rantau dan kota asal.
                </p>
            </div>
            <a class="font-label-lg text-label-lg text-secondary font-bold hover:text-primary inline-flex items-center gap-1 transition-colors" href="#galeri-nostalgia">
                Lihat Semua Artikel &amp; Galeri
                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
            </a>
        </div>

        <!-- News & Nostalgia Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($articles as $art)
                <article class="flex flex-col rounded-xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md transition-shadow">
                    <div class="relative h-48 bg-surface-container">
                        <img alt="{{ $art->title }}" 
                             class="w-full h-full object-cover" 
                             src="{{ $art->image_url ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&auto=format&fit=crop' }}">
                        <span class="absolute top-3 left-3 px-3 py-1 {{ $art->badge_bg_class }} font-label-sm text-label-sm font-bold rounded">
                            {{ $art->category }}
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="font-label-sm text-label-sm text-on-surface-variant">{{ $art->author_and_date }}</span>
                            <h3 class="font-title-md text-title-md text-primary font-bold hover:text-secondary transition-colors cursor-pointer">
                                {{ $art->title }}
                            </h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-3">
                                {{ $art->summary }}
                            </p>
                        </div>
                        <a class="font-label-md text-label-md text-secondary font-bold inline-flex items-center gap-1 hover:text-primary transition-colors" href="#kontak">
                            Baca Selengkapnya
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>
                    </div>
                </article>
            @empty
                <p class="col-span-3 text-center py-8 text-on-surface-variant">Belum ada artikel berita terkini.</p>
            @endforelse
        </div>
    </div>
</section>
