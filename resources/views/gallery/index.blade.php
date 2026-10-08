@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">
        
        <!-- Header & Breadcrumbs -->
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Dokumentasi &amp; Galeri Foto</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/30">
                <div class="max-w-2xl space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20 inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">photo_library</span>
                        <span>Album Kenangan &amp; Dokumentasi</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight">
                        Galeri &amp; Jejak Waktu Alumni KPS
                    </h1>
                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Koleksi album foto kenangan masa sekolah tempo doeloe, temu kangen reuni akbar, aksi sosial, hingga kemeriahan turnamen olahraga keluarga besar Sekolah Nasional KPS Balikpapan.
                    </p>
                </div>

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                        <div class="shrink-0">
                            <a href="{{ route('admin.galleries.create') }}" 
                               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                                <span>Tambah Album Baru</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">collections_bookmark</span>
                </div>
                <div>
                    <span class="text-2xl font-extrabold text-primary block">{{ $stats['total_albums'] }}</span>
                    <span class="text-xs text-on-surface-variant font-medium">Album Kenangan</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">photo_camera</span>
                </div>
                <div>
                    <span class="text-2xl font-extrabold text-primary block">{{ $stats['total_photos'] }}</span>
                    <span class="text-xs text-on-surface-variant font-medium">Total Arsip Foto</span>
                </div>
            </div>

            <div class="col-span-2 sm:col-span-1 bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">history_edu</span>
                </div>
                <div>
                    <span class="text-xs text-emerald-700 font-bold block uppercase tracking-wide">Merawat Memori</span>
                    <span class="text-xs text-on-surface-variant leading-tight block mt-0.5">Arsip foto sejak era 1985 hingga masa kini</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                
                <!-- Category Pills -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none text-xs font-bold">
                    <a href="{{ route('gallery.index', array_filter(['search' => request('search')])) }}" 
                       class="px-4 py-2 rounded-xl whitespace-nowrap transition-all {{ !request('category') ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
                        Semua Album ({{ $stats['total_albums'] }})
                    </a>

                    @foreach(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial'] as $cat)
                        @php
                            $catCount = $categoryCounts[$cat] ?? 0;
                        @endphp
                        <a href="{{ route('gallery.index', array_filter(['category' => $cat, 'search' => request('search')])) }}" 
                           class="px-4 py-2 rounded-xl whitespace-nowrap transition-all {{ request('category') === $cat ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
                            {{ $cat }}
                            @if($catCount > 0)
                                <span class="opacity-75 text-2xs ml-1">({{ $catCount }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('gallery.index') }}" class="relative max-w-sm w-full">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari album kenangan..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    @if(request('search'))
                        <a href="{{ route('gallery.index', array_filter(['category' => request('category')])) }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Album Cards Grid -->
        @if ($galleries->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($galleries as $gallery)
                    <div class="group bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between">
                        
                        <!-- Album Cover Image -->
                        <div class="relative aspect-[16/10] bg-surface-container-high overflow-hidden">
                            @if ($gallery->cover_image_url)
                                <img src="{{ $gallery->cover_image_url }}" 
                                     alt="{{ $gallery->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-outline-variant">
                                    <span class="material-symbols-outlined text-5xl">collections</span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 opacity-80 group-hover:opacity-90 transition-opacity"></div>

                            <!-- Category Badge -->
                            <div class="absolute top-3.5 left-3.5">
                                <span class="px-3 py-1 rounded-full text-2xs font-extrabold uppercase tracking-wide bg-surface-container-lowest/90 backdrop-blur-xs text-primary shadow-2xs">
                                    {{ $gallery->category ?? 'Galeri' }}
                                </span>
                            </div>

                            <!-- Photo Count Indicator Badge -->
                            <div class="absolute bottom-3.5 right-3.5 flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 backdrop-blur-xs text-white text-2xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-[14px]">photo_camera</span>
                                <span>{{ $gallery->photos_count }} Foto</span>
                            </div>

                            <!-- Date Badge -->
                            @if ($gallery->event_date)
                                <div class="absolute bottom-3.5 left-3.5 flex items-center gap-1 text-white/90 text-2xs font-semibold">
                                    <span class="material-symbols-outlined text-[14px]">event</span>
                                    <span>{{ $gallery->event_date->isoFormat('D MMM Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Card Content -->
                        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <h3 class="font-bold text-base sm:text-lg text-primary line-clamp-2 group-hover:text-secondary transition-colors">
                                    <a href="{{ route('gallery.show', $gallery->slug) }}">
                                        {{ $gallery->title }}
                                    </a>
                                </h3>
                                @if ($gallery->description)
                                    <p class="text-xs sm:text-sm text-on-surface-variant line-clamp-2 leading-relaxed">
                                        {{ $gallery->description }}
                                    </p>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                                <span class="text-2xs text-on-surface-variant font-medium">
                                    Sekolah Nasional KPS
                                </span>
                                <a href="{{ route('gallery.show', $gallery->slug) }}" 
                                   class="text-xs font-bold text-primary hover:text-secondary inline-flex items-center gap-1 group-hover:translate-x-1 transition-all">
                                    <span>Buka Album</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($galleries->hasPages())
                <div class="pt-6">
                    {{ $galleries->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-12 text-center space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-surface-container flex items-center justify-center text-outline-variant">
                    <span class="material-symbols-outlined text-3xl">photo_library</span>
                </div>
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-primary">Belum Ada Album yang Ditemukan</h3>
                    <p class="text-xs text-on-surface-variant max-w-md mx-auto">
                        @if(request('search') || request('category'))
                            Tidak ada album foto yang sesuai dengan kata kunci pencarian atau kategori yang dipilih.
                        @else
                            Saat ini belum ada dokumentasi foto yang dipublikasikan.
                        @endif
                    </p>
                </div>
                @if(request('search') || request('category'))
                    <div>
                        <a href="{{ route('gallery.index') }}" 
                           class="px-4 py-2 rounded-xl bg-surface-container text-xs font-bold text-primary hover:bg-surface-container-high transition-colors inline-block">
                            Reset Filter &amp; Pencarian
                        </a>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
