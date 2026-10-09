@extends('layouts.app')

@section('content')
<div class="w-full bg-surface">
    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-surface-container-low to-surface border-b border-outline-variant/30 py-12 md:py-16 px-6">
        <div class="max-w-[1280px] mx-auto text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest bg-secondary/10 text-secondary border border-secondary/20">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                <span>Sinergi Ekonomi Alumni KPS</span>
            </span>
            <h1 class="font-headline-sm text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary tracking-tight max-w-3xl mx-auto">
                Katalog Bisnis &amp; UMKM Alumni
            </h1>
            <p class="font-body-md text-sm sm:text-base text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Dukung dan berjejaring dengan ratusan unit usaha milik sesama alumni Sekolah Nasional KPS Balikpapan di berbagai sektor industri.
            </p>
        </div>
    </section>

    <!-- Filter & Content Section -->
    <div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
        <!-- Search & Filter Bar -->
        <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-5 shadow-sm space-y-4">
            <form action="{{ route('business.index') }}" method="GET" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           aria-label="Cari nama usaha, produk, kota, atau pemilik alumni"
                           placeholder="Cari nama usaha, produk, kota, atau pemilik alumni..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all min-h-[44px]">
                </div>

                <div class="relative w-full md:w-60 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">category</span>
                    <select name="category" 
                            aria-label="Filter berdasarkan kategori usaha"
                            onchange="this.form.submit()"
                            class="w-full pl-10.5 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer min-h-[44px]">
                        <option value="">Semua Kategori Usaha</option>
                        @foreach ($categories as $cat)
                            @if ($cat !== 'Semua Kategori')
                                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>

                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm min-h-[44px] flex items-center justify-center">
                    Cari Bisnis
                </button>
            </form>

            <!-- Quick Category Badges -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 pt-1 text-xs">
                <span class="text-[11px] font-bold text-on-surface-variant shrink-0 mr-1">Kategori:</span>
                <a href="{{ route('business.index') }}" 
                   class="px-3.5 py-2 rounded-xl font-semibold shrink-0 transition-colors min-h-[38px] inline-flex items-center justify-center {{ ! request('category') ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
                    Semua
                </a>
                @foreach ($categories as $cat)
                    @if ($cat !== 'Semua Kategori')
                        <a href="{{ route('business.index', ['category' => $cat]) }}" 
                           class="px-3.5 py-2 rounded-xl font-semibold shrink-0 transition-colors min-h-[38px] inline-flex items-center justify-center {{ request('category') === $cat ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
                            {{ $cat }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Grid Bisnis Alumni -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($businesses as $biz)
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all flex flex-col group hover:border-secondary/50">
                    <!-- Thumbnail Usaha -->
                    <div class="relative h-48 w-full bg-surface-container overflow-hidden">
                        @if ($biz->image_url)
                            <img src="{{ $biz->image_url }}" alt="{{ $biz->name }}" 
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=600&auto=format&fit=crop';"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary/5 text-primary">
                                <span class="material-symbols-outlined text-5xl">storefront</span>
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-2xs font-extrabold uppercase tracking-wider bg-black/60 text-white backdrop-blur-xs">
                            {{ $biz->category }}
                        </span>
                        @if ($biz->city)
                            <span class="absolute top-3 right-3 px-2.5 py-0.5 rounded-full text-2xs font-bold bg-white/90 text-on-surface shadow-xs flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-[12px] text-secondary">location_on</span>
                                {{ $biz->city }}
                            </span>
                        @endif
                    </div>

                    <!-- Informasi Usaha -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <h2 class="font-headline-sm text-base font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1">
                                <a href="{{ route('business.show', $biz->slug) }}">
                                    {{ $biz->name }}
                                </a>
                            </h2>
                            <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed">
                                {{ $biz->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-outline-variant/30 space-y-3">
                            @if ($biz->owner_info)
                                <div class="text-[11px] text-on-surface-variant flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[15px] text-primary">person</span>
                                    <span class="truncate font-medium">{{ $biz->owner_info }}</span>
                                </div>
                            @endif

                            <div class="flex items-center justify-between gap-2">
                                <a href="{{ route('business.show', $biz->slug) }}" 
                                   class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-0.5 transition-colors">
                                    <span>Detail Usaha</span>
                                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                                </a>

                                @if ($biz->action_type === 'whatsapp' && ($biz->whatsapp_number || $biz->action_link))
                                    @php
                                        $waTarget = $biz->action_link ?: ('https://wa.me/' . preg_replace('/[^0-9]/', '', $biz->whatsapp_number));
                                    @endphp
                                    <a href="{{ $waTarget }}" target="_blank" rel="noopener noreferrer"
                                       class="px-3.5 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition-all flex items-center gap-1.5 shadow-xs">
                                        <x-icons.whatsapp class="w-3.5 h-3.5 fill-current shrink-0" />
                                        <span>WhatsApp</span>
                                    </a>
                                @elseif ($biz->action_link)
                                    <a href="{{ $biz->action_link }}" target="_blank" rel="noopener noreferrer"
                                       class="px-3.5 py-1.5 rounded-xl bg-surface-container text-primary font-bold text-[11px] hover:bg-surface-container-high transition-all flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                        <span>Kunjungi</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-on-surface-variant bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-8">
                    <span class="material-symbols-outlined text-5xl text-outline mb-2 block">search_off</span>
                    <p class="text-base font-bold text-primary">Belum ada bisnis alumni pada kategori ini.</p>
                    <p class="text-xs text-on-surface-variant mt-1">Coba gunakan kata kunci lain atau daftarkan unit bisnis alumni Anda!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($businesses->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
