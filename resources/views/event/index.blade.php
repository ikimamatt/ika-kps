@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">
        
        <!-- Header & Breadcrumbs -->
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Agenda &amp; Event</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/30">
                <div class="max-w-2xl space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20 inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">calendar_month</span>
                        <span>Kalender &amp; Silaturahmi Alumni</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight">
                        Agenda Kegiatan &amp; Event Alumni
                    </h1>
                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Temukan berbagai agenda temu kangen, kompetisi olahraga, seminar pengembangan diri, dan bakti sosial Sekolah Nasional KPS Balikpapan.
                    </p>
                </div>

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                        <div class="shrink-0">
                            <a href="{{ route('admin.events.create') }}" 
                               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                <span>Buat Event Baru</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">event_upcoming</span>
                </div>
                <div>
                    <span class="text-2xl font-extrabold text-primary block">{{ $stats['upcoming'] }}</span>
                    <span class="text-xs text-on-surface-variant font-medium">Event Mendatang</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">event_available</span>
                </div>
                <div>
                    <span class="text-2xl font-extrabold text-primary block">{{ $stats['total'] }}</span>
                    <span class="text-xs text-on-surface-variant font-medium">Total Seluruh Agenda</span>
                </div>
            </div>

            <div class="col-span-2 md:col-span-1 bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">how_to_reg</span>
                </div>
                <div>
                    <span class="text-xs text-emerald-700 font-bold block uppercase tracking-wide">RSVP Online</span>
                    <span class="text-xs text-on-surface-variant leading-tight block mt-0.5">Konfirmasi kehadiran instan langsung via akun alumni</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="space-y-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                
                <!-- Timeframe Tabs (Upcoming vs Past) -->
                <div class="flex items-center gap-2 p-1 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs font-bold shrink-0 self-start">
                    <a href="{{ route('event.index', array_filter(['timeframe' => 'upcoming', 'category' => request('category'), 'search' => request('search')])) }}" 
                       class="px-4 py-2 rounded-xl transition-all {{ $timeframe === 'upcoming' ? 'bg-surface-container-lowest text-primary shadow-xs' : 'text-on-surface-variant hover:text-primary' }}">
                        Event Mendatang ({{ $stats['upcoming'] }})
                    </a>
                    <a href="{{ route('event.index', array_filter(['timeframe' => 'past', 'category' => request('category'), 'search' => request('search')])) }}" 
                       class="px-4 py-2 rounded-xl transition-all {{ $timeframe === 'past' ? 'bg-surface-container-lowest text-primary shadow-xs' : 'text-on-surface-variant hover:text-primary' }}">
                        Riwayat Kegiatan
                    </a>
                    <a href="{{ route('event.index', array_filter(['timeframe' => 'all', 'category' => request('category'), 'search' => request('search')])) }}" 
                       class="px-4 py-2 rounded-xl transition-all {{ $timeframe === 'all' ? 'bg-surface-container-lowest text-primary shadow-xs' : 'text-on-surface-variant hover:text-primary' }}">
                        Semua
                    </a>
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('event.index') }}" class="relative max-w-sm w-full">
                    <input type="hidden" name="timeframe" value="{{ $timeframe }}">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari event, lokasi, topik..."
                           class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-on-surface-variant">search</span>
                    
                    @if(request('search'))
                        <a href="{{ route('event.index', array_filter(['timeframe' => $timeframe, 'category' => request('category')])) }}" 
                           class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary">
                            <span class="material-symbols-outlined text-[16px]">close</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Category Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-bold">
                <a href="{{ route('event.index', array_filter(['timeframe' => $timeframe, 'search' => request('search')])) }}" 
                   class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap {{ !request('category') ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/40 hover:bg-surface-container-low' }}">
                    Semua Kategori
                </a>

                @php
                    $eventCategories = [
                        ['id' => 'reuni', 'label' => 'Reuni & Silaturahmi'],
                        ['id' => 'olahraga', 'label' => 'Olahraga & Turnamen'],
                        ['id' => 'baksos', 'label' => 'Bakti Sosial & Medis'],
                        ['id' => 'seminar', 'label' => 'Seminar & Edukasi'],
                        ['id' => 'lainnya', 'label' => 'Kegiatan Lainnya'],
                    ];
                @endphp

                @foreach($eventCategories as $cat)
                    @php
                        $isActive = (request('category') === $cat['id']);
                        $count = $categoryCounts[$cat['id']] ?? 0;
                    @endphp
                    <a href="{{ route('event.index', array_filter(['category' => $cat['id'], 'timeframe' => $timeframe, 'search' => request('search')])) }}" 
                       class="px-3.5 py-1.5 rounded-xl transition-all whitespace-nowrap inline-flex items-center gap-1.5 {{ $isActive ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-lowest text-on-surface-variant border border-outline-variant/40 hover:bg-surface-container-low' }}">
                        <span>{{ $cat['label'] }}</span>
                        @if($count > 0)
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $isActive ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface-variant' }}">
                                {{ $count }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Events Grid -->
        @if ($events->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach ($events as $ev)
                    @php
                        $confirmedCount = $ev->registrations_count;
                        $isPast = $ev->start_date->isPast();
                        $isFull = $ev->isFullyBooked();
                    @endphp
                    <div class="flex flex-col rounded-2xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-xs hover:shadow-md transition-all group">
                        
                        <!-- Thumbnail Image with Date Badge -->
                        <div class="relative h-48 sm:h-52 overflow-hidden bg-surface-container">
                            <img alt="{{ $ev->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                 src="{{ $ev->image_url ?? 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=600' }}">
                            
                            <!-- Date Badge Box -->
                            <div class="absolute top-3 left-3 px-3 py-2 rounded-xl bg-surface-container-lowest/95 backdrop-blur-xs border border-outline-variant/40 text-center shadow-sm">
                                <span class="text-base sm:text-lg font-black text-primary leading-none block">
                                    {{ $ev->start_date->format('d') }}
                                </span>
                                <span class="text-[10px] font-extrabold text-secondary uppercase tracking-wider block mt-0.5">
                                    {{ $ev->start_date->translatedFormat('M') }}
                                </span>
                            </div>

                            <!-- Category Badge -->
                            <span class="absolute top-3 right-3 px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-xs text-[10px] font-extrabold text-white uppercase tracking-wider">
                                {{ ucfirst($ev->category) }}
                            </span>

                            <!-- Status indicator bar -->
                            @if ($isPast)
                                <span class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded-full bg-slate-800/80 text-white text-[10px] font-bold">
                                    Kegiatan Selesai
                                </span>
                            @elseif ($isFull)
                                <span class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded-full bg-rose-600/90 text-white text-[10px] font-bold">
                                    Kuota Penuh
                                </span>
                            @else
                                <span class="absolute bottom-3 left-3 px-2.5 py-0.5 rounded-full bg-emerald-600/90 text-white text-[10px] font-bold">
                                    Pendaftaran Buka
                                </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-[11px] text-on-surface-variant">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px] text-secondary">schedule</span>
                                        <span>{{ $ev->start_date->format('H:i') }} WITA</span>
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1 truncate">
                                        <span class="material-symbols-outlined text-[15px] text-secondary">location_on</span>
                                        <span class="truncate">{{ Str::limit($ev->location, 28) }}</span>
                                    </span>
                                </div>

                                <h3 class="text-base sm:text-lg font-bold text-primary group-hover:text-secondary transition-colors line-clamp-2 leading-snug">
                                    <a href="{{ route('event.show', $ev->slug) }}">
                                        {{ $ev->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed">
                                    {{ $ev->description }}
                                </p>
                            </div>

                            <!-- Footer Info (Fee & RSVP Info) -->
                            <div class="pt-3 border-t border-outline-variant/30 space-y-3">
                                <div class="flex items-center justify-between text-xs">
                                    <div>
                                        <span class="text-[10px] text-on-surface-variant block">Biaya / Tiket</span>
                                        <span class="font-extrabold text-primary">
                                            @if($ev->fee > 0)
                                                Rp {{ number_format($ev->fee, 0, ',', '.') }}
                                            @else
                                                <span class="text-emerald-600">Gratis</span>
                                            @endif
                                        </span>
                                    </div>

                                    @if ($ev->max_participants)
                                        <div class="text-right">
                                            <span class="text-[10px] text-on-surface-variant block">Peserta RSVP</span>
                                            <span class="font-bold text-xs {{ $isFull ? 'text-rose-600 font-extrabold' : 'text-primary' }}">
                                                {{ $confirmedCount }} / {{ $ev->max_participants }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('event.show', $ev->slug) }}" 
                                   class="w-full py-2.5 rounded-xl bg-surface-container text-primary hover:bg-primary hover:text-white font-bold text-xs transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                                    <span>Lihat Detail &amp; RSVP</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pt-6">
                {{ $events->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="py-16 text-center space-y-4 max-w-md mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-surface-container-high text-on-surface-variant flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-3xl">event_busy</span>
                </div>
                <h3 class="text-lg font-bold text-primary">Tidak Ada Agenda Kegiatan</h3>
                <p class="text-xs text-on-surface-variant">
                    @if(request('search') || request('category'))
                        Tidak ada agenda yang cocok dengan filter atau kata kunci pencarian Anda. Silakan coba filter lainnya.
                    @else
                        Belum ada jadwal kegiatan untuk kategori ini saat ini.
                    @endif
                </p>
                @if(request('search') || request('category') || $timeframe !== 'upcoming')
                    <a href="{{ route('event.index') }}" 
                       class="inline-block px-4 py-2 rounded-xl bg-surface-container-low text-primary text-xs font-bold hover:bg-surface-container transition-all">
                        Reset Filter
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>
@endsection
