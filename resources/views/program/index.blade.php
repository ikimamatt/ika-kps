@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">
        
        <!-- Header & Breadcrumbs -->
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Program Kerja Alumni</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-outline-variant/30">
                <div class="max-w-2xl space-y-2">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20 inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">diversity_3</span>
                        <span>Inisiatif &amp; Aksi Nyata IKA KPS</span>
                    </span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight">
                        Program Kerja &amp; Kontribusi Alumni
                    </h1>
                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        Mewujudkan dampak nyata bagi kemajuan Sekolah Nasional KPS, penguatan jejaring keluarga alumni lintas angkatan, dan kepedulian sosial untuk Kota Balikpapan.
                    </p>
                </div>

                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                        <div class="shrink-0">
                            <a href="{{ route('admin.programs.create') }}" 
                               class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                <span>Kelola di Admin Panel</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px] sm:text-[24px]">assignment</span>
                </div>
                <div class="min-w-0">
                    <span class="text-xl sm:text-2xl font-extrabold text-primary block leading-tight tabular-nums">{{ $stats['total_programs'] }}</span>
                    <span class="text-[11px] sm:text-xs text-on-surface-variant font-medium mt-0.5 block">Total Program</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px] sm:text-[24px]">autorenew</span>
                </div>
                <div class="min-w-0">
                    <span class="text-xl sm:text-2xl font-extrabold text-emerald-600 block leading-tight tabular-nums">{{ $stats['active_programs'] }}</span>
                    <span class="text-[11px] sm:text-xs text-on-surface-variant font-medium mt-0.5 block">Sedang Berjalan</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px] sm:text-[24px]">verified</span>
                </div>
                <div class="min-w-0">
                    <span class="text-xl sm:text-2xl font-extrabold text-sky-600 block leading-tight tabular-nums">{{ $stats['completed_programs'] }}</span>
                    <span class="text-[11px] sm:text-xs text-on-surface-variant font-medium mt-0.5 block">Tuntas Terlaksana</span>
                </div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-4">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px] sm:text-[24px]">volunteer_activism</span>
                </div>
                <div class="min-w-0">
                    <span class="text-base sm:text-xl font-extrabold text-primary block leading-tight tabular-nums">Rp {{ number_format($stats['total_funds'] / 1000000, 1, ',', '.') }} Jt</span>
                    <span class="text-[11px] sm:text-xs text-on-surface-variant font-medium mt-0.5 block">Dana Terhimpun</span>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 sm:p-5 shadow-xs space-y-4">
            <form action="{{ route('program.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between">
                <!-- Status Filter Pills -->
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('program.index', array_merge(request()->except(['status', 'page']))) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('status') ? 'bg-primary text-on-primary shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                        Semua Status
                    </a>
                    <a href="{{ route('program.index', array_merge(request()->except(['page']), ['status' => 'active'])) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                        Sedang Berjalan
                    </a>
                    <a href="{{ route('program.index', array_merge(request()->except(['page']), ['status' => 'upcoming'])) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'upcoming' ? 'bg-amber-600 text-white shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                        Segera Dimulai
                    </a>
                    <a href="{{ route('program.index', array_merge(request()->except(['page']), ['status' => 'completed'])) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('status') === 'completed' ? 'bg-sky-600 text-white shadow-xs' : 'bg-surface-container-low text-on-surface-variant hover:bg-surface-container' }}">
                        Selesai / Tuntas
                    </a>
                </div>

                <!-- Keyword Search -->
                <div class="relative min-w-[260px] md:w-80">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama program atau kegiatan..."
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    <span class="material-symbols-outlined text-outline absolute left-3 top-1/2 -translate-y-1/2 text-[18px]">search</span>
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </div>
            </form>
        </div>

        <!-- Program Card Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($programs as $program)
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group">
                    <!-- Thumbnail / Banner -->
                    <div class="relative h-48 bg-surface-container overflow-hidden">
                        @if($program->image_url)
                            <img src="{{ $program->image_url }}" alt="{{ $program->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary to-primary-container flex items-center justify-center text-on-primary/60">
                                <span class="material-symbols-outlined text-6xl">{{ $program->icon ?? 'volunteer_activism' }}</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            @if($program->status === 'active')
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-500 text-white shadow-xs">
                                    Aktif Berjalan
                                </span>
                            @elseif($program->status === 'completed')
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-sky-600 text-white shadow-xs">
                                    Tuntas
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[11px] font-extrabold bg-amber-500 text-white shadow-xs">
                                    Segera
                                </span>
                            @endif
                        </div>

                        <!-- Icon Circle -->
                        <div class="absolute -bottom-5 left-6">
                            <div class="w-12 h-12 rounded-2xl {{ $program->icon_bg_class ?: 'bg-primary text-white' }} shadow-md border-2 border-surface-container-lowest flex items-center justify-center">
                                <span class="material-symbols-outlined text-[24px]">{{ $program->icon ?: 'school' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6 pt-8 flex-1 flex flex-col justify-between space-y-5">
                        <div class="space-y-2.5">
                            <h3 class="font-bold text-lg text-primary group-hover:text-secondary transition-colors line-clamp-2">
                                <a href="{{ route('program.show', $program->slug) }}">
                                    {{ $program->title }}
                                </a>
                            </h3>
                            <p class="text-xs sm:text-sm text-on-surface-variant line-clamp-3 leading-relaxed">
                                {{ $program->description }}
                            </p>
                        </div>

                        <!-- Progress Bar & Metrics -->
                        <div class="space-y-3 pt-3 border-t border-outline-variant/30">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-primary">{{ $program->progress_label ?? 'Capaian Progres' }}</span>
                                <span class="font-extrabold text-secondary">{{ $program->progress_percent }}%</span>
                            </div>

                            <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full {{ $program->bar_color_class ?: 'bg-secondary' }} rounded-full transition-all duration-700"
                                     style="width: {{ min(100, $program->progress_percent) }}%;"></div>
                            </div>

                            @if($program->achievement_text)
                                <p class="text-[11px] text-on-surface-variant flex items-center gap-1.5 font-medium">
                                    <span class="material-symbols-outlined text-[15px] text-secondary">check_circle</span>
                                    <span class="truncate">{{ $program->achievement_text }}</span>
                                </p>
                            @endif
                        </div>

                        <!-- Card Footer Action -->
                        <div class="pt-2">
                            <a href="{{ route('program.show', $program->slug) }}" 
                               class="w-full py-2.5 rounded-xl bg-surface-container-low hover:bg-primary hover:text-white text-primary font-bold text-xs transition-all flex items-center justify-center gap-1.5 group-hover:bg-primary group-hover:text-white shadow-2xs">
                                <span>Lihat Rincian &amp; Dukung</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-on-surface-variant bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-8 space-y-2">
                    <span class="material-symbols-outlined text-5xl text-outline mb-2 block">assignment_late</span>
                    <p class="text-base font-bold text-primary">Tidak ada program kerja yang sesuai.</p>
                    <p class="text-xs text-on-surface-variant">Coba gunakan filter status lain atau ubah kata kunci pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($programs->hasPages())
            <div class="pt-6 flex justify-center">
                {{ $programs->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
