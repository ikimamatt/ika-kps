@extends('layouts.app')

@section('content')
<div class="bg-surface-container-lowest min-h-screen py-10">
    <div class="max-w-[1280px] mx-auto px-6">
        <!-- Header Section -->
        <div class="mb-10 text-center max-w-3xl mx-auto">
            <span class="inline-block px-3 py-1 mb-3 text-xs font-bold uppercase tracking-wider text-secondary bg-secondary/10 rounded-full">
                Jejaring Nasional KPS Balikpapan
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight font-headline-lg">
                Direktori Alumni IKA KPS
            </h1>
            <p class="mt-3 text-sm sm:text-base text-on-surface-variant leading-relaxed">
                Temukan dan terhubung kembali dengan ribuan rekan alumni lintas generasi, jenjang TK, SD, SMP, hingga SMA Nasional KPS Balikpapan di seluruh penjuru dunia.
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-6 mb-10 shadow-sm">
            <form action="{{ route('alumni.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">
                <!-- Search Keyword -->
                <div class="flex-1 relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari nama, profesi, instansi, atau domisili..." 
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Jenjang Selector -->
                <div class="w-full md:w-52">
                    <select name="level" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                        <option value="">Semua Jenjang</option>
                        <option value="sma" {{ request('level') === 'sma' ? 'selected' : '' }}>SMA Nasional KPS</option>
                        <option value="smp" {{ request('level') === 'smp' ? 'selected' : '' }}>SMP Nasional KPS</option>
                        <option value="sd" {{ request('level') === 'sd' ? 'selected' : '' }}>SD Nasional KPS</option>
                        <option value="tk" {{ request('level') === 'tk' ? 'selected' : '' }}>TK Nasional KPS</option>
                    </select>
                </div>

                <!-- Year Input -->
                <div class="w-full md:w-36">
                    <input type="text" 
                           name="year" 
                           value="{{ request('year') }}" 
                           placeholder="Tahun (2010)" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-sm hover:bg-primary-container transition-all flex items-center justify-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    <span>Cari</span>
                </button>

                @if (request()->hasAny(['q', 'level', 'year']))
                    <a href="{{ route('alumni.index') }}" 
                       class="px-4 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container-high font-semibold text-sm transition-all text-center">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Quick Level Tabs -->
            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-outline-variant/30 overflow-x-auto text-xs pb-1">
                <span class="font-bold text-on-surface-variant shrink-0 mr-1">Filter Cepat:</span>
                <a href="{{ route('alumni.index', array_merge(request()->except('level'), ['level' => ''])) }}" 
                   class="px-3 py-1 rounded-lg {{ !request('level') ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-highest/80' }} transition-colors">
                    Semua
                </a>
                <a href="{{ route('alumni.index', array_merge(request()->except('level'), ['level' => 'sma'])) }}" 
                   class="px-3 py-1 rounded-lg {{ request('level') === 'sma' ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-highest/80' }} transition-colors">
                    SMA KPS
                </a>
                <a href="{{ route('alumni.index', array_merge(request()->except('level'), ['level' => 'smp'])) }}" 
                   class="px-3 py-1 rounded-lg {{ request('level') === 'smp' ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-highest/80' }} transition-colors">
                    SMP KPS
                </a>
                <a href="{{ route('alumni.index', array_merge(request()->except('level'), ['level' => 'sd'])) }}" 
                   class="px-3 py-1 rounded-lg {{ request('level') === 'sd' ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-highest/80' }} transition-colors">
                    SD KPS
                </a>
                <a href="{{ route('alumni.index', array_merge(request()->except('level'), ['level' => 'tk'])) }}" 
                   class="px-3 py-1 rounded-lg {{ request('level') === 'tk' ? 'bg-primary text-on-primary font-bold' : 'bg-surface-container-highest text-on-surface-variant hover:bg-surface-container-highest/80' }} transition-colors">
                    TK KPS
                </a>
            </div>
        </div>

        <!-- Grid Cards Alumni -->
        @if ($alumni->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($alumni as $item)
                    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col justify-between group">
                        <div>
                            <!-- Header Card: Avatar + Level Badge -->
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <img src="{{ $item->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name) . '&background=002D62&color=fff&size=150' }}" 
                                     alt="{{ $item->name }}" 
                                     class="w-16 h-16 rounded-2xl object-cover ring-2 ring-outline-variant/30 group-hover:ring-secondary/50 transition-all">
                                
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold uppercase tracking-wider {{ $item->level === 'sma' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : ($item->level === 'smp' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                    {{ strtoupper($item->level) }} {{ $item->full_year ? "'".$item->class_year : '' }}
                                </span>
                            </div>

                            <!-- Name & Title -->
                            <h3 class="font-headline-sm text-base font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1">
                                {{ $item->name }}{{ $item->title ? ', '.$item->title : '' }}
                            </h3>

                            <!-- Profession & Institution -->
                            <p class="text-xs font-semibold text-on-surface mt-1 line-clamp-1">
                                {{ $item->profession ?? 'Alumni IKA KPS' }}
                            </p>
                            @if ($item->institution)
                                <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5 line-clamp-1">
                                    <span class="material-symbols-outlined text-[14px] shrink-0 text-secondary">business</span>
                                    <span>{{ $item->institution }}</span>
                                </p>
                            @endif

                            @if ($item->domicile)
                                <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-1 line-clamp-1">
                                    <span class="material-symbols-outlined text-[14px] shrink-0 text-secondary">location_on</span>
                                    <span>{{ $item->domicile }}</span>
                                </p>
                            @endif

                            @if ($item->summary)
                                <p class="text-xs text-on-surface-variant/80 mt-3 line-clamp-2 leading-relaxed">
                                    {{ $item->summary }}
                                </p>
                            @endif
                        </div>

                        <!-- Card Action Footer -->
                        <div class="mt-5 pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">
                                <span class="material-symbols-outlined text-[14px]">verified</span>
                                Terverifikasi
                            </span>

                            <a href="{{ route('alumni.show', $item->slug) }}" 
                               class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:text-secondary transition-colors">
                                <span>Lihat Profil</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Bar -->
            <div class="mt-10">
                {{ $alumni->links() }}
            </div>
        @else
            <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-12 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-[36px]">search_off</span>
                </div>
                <h3 class="font-headline-sm text-lg font-bold text-primary">Tidak Ada Alumni Ditemukan</h3>
                <p class="text-xs sm:text-sm text-on-surface-variant mt-2">
                    Coba sesuaikan kata kunci pencarian atau ubah filter jenjang sekolah di atas.
                </p>
                <div class="mt-6">
                    <a href="{{ route('alumni.index') }}" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all">
                        Tampilkan Semua Alumni
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
