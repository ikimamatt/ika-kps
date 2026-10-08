@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">

        <!-- Breadcrumbs Navigation -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('gallery.index') }}" class="hover:text-primary transition-colors">Galeri Foto</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary truncate max-w-xs font-bold">{{ $gallery->title }}</span>
        </nav>

        <!-- Album Header Card -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs space-y-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-4 max-w-3xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-secondary/10 text-secondary border border-secondary/20 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">label</span>
                            <span>{{ $gallery->category ?? 'Dokumentasi' }}</span>
                        </span>

                        <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary border border-primary/20 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">photo_camera</span>
                            <span>{{ $gallery->photos->count() }} Foto dalam Album</span>
                        </span>

                        @if ($gallery->event_date)
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant border border-outline-variant/40 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]">event</span>
                                <span>{{ $gallery->event_date->isoFormat('D MMMM Y') }}</span>
                            </span>
                        @endif

                        @auth
                            @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                                <a href="{{ route('admin.galleries.edit', $gallery->id) }}" 
                                   class="px-3 py-1 rounded-full text-xs font-bold bg-surface-container-high text-on-surface hover:text-primary transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    <span>Kelola Foto Album</span>
                                </a>
                            @endif
                        @endauth
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight leading-snug">
                        {{ $gallery->title }}
                    </h1>

                    @if ($gallery->description)
                        <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                            {{ $gallery->description }}
                        </p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row md:flex-col gap-2 shrink-0">
                    <a href="{{ route('gallery.index') }}" 
                       class="px-4 py-2.5 rounded-2xl border border-outline-variant/60 hover:bg-surface-container text-xs font-bold text-on-surface transition-colors inline-flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Semua Album</span>
                    </a>

                    @php
                        $shareText = urlencode("Lihat album dokumentasi alumni IKA KPS: {$gallery->title} di: " . url()->current());
                    @endphp
                    <a href="https://api.whatsapp.com/send?text={{ $shareText }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="px-4 py-2.5 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors inline-flex items-center justify-center gap-1.5">
                        <x-icons.whatsapp class="w-3.5 h-3.5 fill-current shrink-0" />
                        <span>Bagikan Album</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Photo Grid Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-outline-variant/30">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">grid_view</span>
                    <h2 class="text-lg sm:text-xl font-bold text-primary">Koleksi Foto</h2>
                </div>
                <span class="text-xs text-on-surface-variant font-medium">
                    Klik foto mana saja untuk membuka tampilan layar penuh (Lightbox)
                </span>
            </div>

            @if ($gallery->photos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6" id="photoGalleryGrid">
                    @foreach ($gallery->photos as $index => $photo)
                        <div class="group relative rounded-2xl overflow-hidden bg-surface-container-high border border-outline-variant/30 shadow-2xs hover:shadow-md transition-all duration-300 aspect-[4/3] cursor-pointer"
                             onclick="openLightbox({{ $index }})">
                            <img src="{{ $photo->image_url }}" 
                                 alt="{{ $photo->caption ?? $gallery->title }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 p-4 flex flex-col justify-between">
                                <div class="self-end">
                                    <span class="w-8 h-8 rounded-full bg-black/50 backdrop-blur-xs text-white flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[18px]">zoom_in</span>
                                    </span>
                                </div>

                                <div>
                                    @if ($photo->caption)
                                        <p class="text-white text-xs font-medium line-clamp-2 leading-snug drop-shadow-sm">
                                            {{ $photo->caption }}
                                        </p>
                                    @else
                                        <p class="text-white/80 text-2xs font-medium">Foto #{{ $index + 1 }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-12 text-center space-y-3">
                    <span class="material-symbols-outlined text-4xl text-outline-variant">photo</span>
                    <h3 class="text-sm font-bold text-primary">Belum Ada Foto dalam Album Ini</h3>
                    <p class="text-xs text-on-surface-variant">Admin belum menambahkan arsip foto ke dalam album kenangan ini.</p>
                </div>
            @endif
        </div>

        <!-- Related Galleries -->
        @if ($relatedGalleries->isNotEmpty())
            <div class="pt-8 border-t border-outline-variant/30 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-secondary block">Album Lainnya</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-primary">Jelajahi Dokumentasi Lainnya</h2>
                    </div>
                    <a href="{{ route('gallery.index') }}" class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($relatedGalleries as $rel)
                        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group">
                            <div class="relative aspect-[16/10] bg-surface-container-high overflow-hidden">
                                @if ($rel->cover_image_url)
                                    <img src="{{ $rel->cover_image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-outline-variant">
                                        <span class="material-symbols-outlined text-4xl">collections</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold uppercase bg-surface-container-lowest/90 backdrop-blur-xs text-primary shadow-2xs">
                                        {{ $rel->category ?? 'Galeri' }}
                                    </span>
                                </div>
                                <div class="absolute bottom-3 right-3 px-2.5 py-0.5 rounded-full bg-black/60 text-white text-2xs font-bold">
                                    {{ $rel->photos_count }} Foto
                                </div>
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-2">
                                <h3 class="text-sm font-bold text-primary line-clamp-2 group-hover:text-secondary transition-colors">
                                    <a href="{{ route('gallery.show', $rel->slug) }}">
                                        {{ $rel->title }}
                                    </a>
                                </h3>
                                <a href="{{ route('gallery.show', $rel->slug) }}" class="text-2xs font-bold text-primary hover:underline">
                                    Buka Album &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<!-- ========================================== -->
<!-- Interactive Pure JS Lightbox Viewer Modal -->
<!-- ========================================== -->
<div id="lightboxModal" 
     class="fixed inset-0 z-50 bg-black/95 backdrop-blur-md hidden flex-col justify-between p-4 sm:p-6 transition-opacity duration-300"
     role="dialog" 
     aria-modal="true" 
     aria-label="Tampilan Layar Penuh Foto">

    <!-- Lightbox Top Controls Bar -->
    <div class="flex items-center justify-between text-white z-10">
        <div class="flex items-center gap-3">
            <span id="lightboxCounter" class="px-3 py-1 rounded-full bg-white/10 backdrop-blur-xs text-xs font-bold tracking-wide">
                1 / 1
            </span>
            <span class="text-xs text-white/70 font-medium hidden sm:inline">{{ $gallery->title }}</span>
        </div>

        <div class="flex items-center gap-2">
            <a id="lightboxDownloadBtn" href="#" target="_blank" download title="Buka Ukuran Penuh"
               class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">open_in_new</span>
            </a>
            <button type="button" onclick="closeLightbox()" title="Tutup (Esc)"
                    class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors flex items-center justify-center">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
    </div>

    <!-- Lightbox Main Stage -->
    <div class="relative flex-1 flex items-center justify-center my-4 overflow-hidden select-none">
        <!-- Prev Button -->
        <button type="button" onclick="prevPhoto()" title="Foto Sebelumnya (Panah Kiri)"
                class="absolute left-2 sm:left-6 z-20 p-3 rounded-full bg-white/10 hover:bg-white/30 text-white transition-all transform active:scale-95 flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">chevron_left</span>
        </button>

        <!-- Current Active Image -->
        <div class="max-w-5xl max-h-[75vh] flex items-center justify-center p-2">
            <img id="lightboxImage" src="" alt="" 
                 class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl transition-all duration-200">
        </div>

        <!-- Next Button -->
        <button type="button" onclick="nextPhoto()" title="Foto Selanjutnya (Panah Kanan)"
                class="absolute right-2 sm:right-6 z-20 p-3 rounded-full bg-white/10 hover:bg-white/30 text-white transition-all transform active:scale-95 flex items-center justify-center">
            <span class="material-symbols-outlined text-[28px]">chevron_right</span>
        </button>
    </div>

    <!-- Lightbox Caption Bar -->
    <div class="text-center z-10 px-4 py-2 max-w-3xl mx-auto">
        <p id="lightboxCaption" class="text-white/95 text-xs sm:text-sm font-medium leading-relaxed"></p>
    </div>
</div>

<script>
    const galleryPhotos = @json($gallery->photos->map(fn($p) => [
        'url' => $p->image_url,
        'caption' => $p->caption ?? '',
    ]));

    let currentPhotoIndex = 0;

    function openLightbox(index) {
        if (!galleryPhotos || galleryPhotos.length === 0) return;
        currentPhotoIndex = index;
        updateLightboxContent();

        const modal = document.getElementById('lightboxModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function updateLightboxContent() {
        const photo = galleryPhotos[currentPhotoIndex];
        if (!photo) return;

        const img = document.getElementById('lightboxImage');
        const caption = document.getElementById('lightboxCaption');
        const counter = document.getElementById('lightboxCounter');
        const downloadBtn = document.getElementById('lightboxDownloadBtn');

        img.src = photo.url;
        img.alt = photo.caption || 'Foto Dokumentasi';
        caption.textContent = photo.caption;
        counter.textContent = `${currentPhotoIndex + 1} / ${galleryPhotos.length}`;
        downloadBtn.href = photo.url;
    }

    function prevPhoto() {
        if (galleryPhotos.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex - 1 + galleryPhotos.length) % galleryPhotos.length;
        updateLightboxContent();
    }

    function nextPhoto() {
        if (galleryPhotos.length === 0) return;
        currentPhotoIndex = (currentPhotoIndex + 1) % galleryPhotos.length;
        updateLightboxContent();
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('lightboxModal');
        if (modal.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            prevPhoto();
        } else if (e.key === 'ArrowRight') {
            nextPhoto();
        }
    });
</script>
@endsection
