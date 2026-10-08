<!-- ALUMNI DIRECTORY PREVIEW WIDGET ("Direktori Alumni") -->
<section class="w-full py-16 bg-surface-container-low" id="direktori-alumni">
    <div class="max-w-[1280px] mx-auto px-6 flex flex-col gap-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-2 max-w-2xl">
                <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Direktori &amp; Database Alumni</span>
                <h2 class="font-headline-lg text-headline-xl text-primary tracking-tight">Cari &amp; Terhubung Kembali dengan Teman Sekelas</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Cari teman lama, rekan bisnis baru, dan mentor alumni KPS terverifikasi dari berbagai angkatan dan profesi.
                </p>
            </div>
            <a class="font-label-lg text-label-lg text-secondary font-bold hover:text-primary inline-flex items-center gap-1.5 transition-colors shrink-0" href="{{ route('alumni.index') }}">
                <span>Buka Direktori Lengkap ({{ number_format($totalAlumniCount ?? $alumni->total()) }}+ Alumni)</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <!-- Search & Filter Bar Widget Form -->
        <form method="GET" action="{{ route('home') }}#direktori-alumni" class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm flex flex-col lg:flex-row items-center gap-3">
            <div class="relative w-full lg:flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                <input name="q" 
                       value="{{ $search ?? '' }}"
                       class="w-full h-11 pl-10 pr-4 rounded-lg bg-surface-container-low border border-outline-variant/30 text-on-surface text-body-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20" 
                       placeholder="Ketik Nama, Angkatan, atau Pekerjaan..." 
                       type="text">
            </div>
            <div class="w-full lg:w-48">
                <select name="level" class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-outline-variant/30 text-on-surface text-body-sm focus:outline-none focus:border-secondary">
                    <option value="">Jenjang: Semua (SD / SMP / SMA)</option>
                    <option value="sma" {{ ($level ?? '') === 'sma' ? 'selected' : '' }}>SMA KPS</option>
                    <option value="smp" {{ ($level ?? '') === 'smp' ? 'selected' : '' }}>SMP KPS</option>
                    <option value="sd" {{ ($level ?? '') === 'sd' ? 'selected' : '' }}>SD KPS</option>
                </select>
            </div>
            <div class="w-full lg:w-48">
                <select name="year" class="w-full h-11 px-3 rounded-lg bg-surface-container-low border border-outline-variant/30 text-on-surface text-body-sm focus:outline-none focus:border-secondary">
                    <option value="">Tahun Angkatan (1980 - 2024)</option>
                    <option value="2020-2024" {{ ($yearRange ?? '') === '2020-2024' ? 'selected' : '' }}>2020 - 2024</option>
                    <option value="2010-2019" {{ ($yearRange ?? '') === '2010-2019' ? 'selected' : '' }}>2010 - 2019</option>
                    <option value="2000-2009" {{ ($yearRange ?? '') === '2000-2009' ? 'selected' : '' }}>2000 - 2009</option>
                    <option value="1990-1999" {{ ($yearRange ?? '') === '1990-1999' ? 'selected' : '' }}>1990 - 1999</option>
                    <option value="1980-1989" {{ ($yearRange ?? '') === '1980-1989' ? 'selected' : '' }}>1980 - 1989</option>
                </select>
            </div>
            <div class="flex items-center gap-2 w-full lg:w-auto">
                <button class="w-full lg:w-auto h-11 px-6 rounded-lg bg-secondary hover:bg-secondary-container text-on-secondary font-label-lg font-bold flex items-center justify-center gap-2 transition-colors shrink-0 shadow-sm" type="submit">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    Cari Alumni
                </button>
                @if(!empty($search) || !empty($level) || !empty($yearRange))
                    <a href="{{ route('home') }}#direktori-alumni" class="h-11 px-3 rounded-lg bg-surface-container text-on-surface-variant hover:text-primary flex items-center justify-center text-sm font-semibold shrink-0">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Active Filter Notification --}}
        @if(!empty($search) || !empty($level) || !empty($yearRange))
            <div class="flex items-center justify-between px-4 py-2.5 rounded-xl bg-secondary/10 border border-secondary/20 text-xs text-primary font-medium">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[18px]">filter_list</span>
                    <span>Menemukan <strong class="font-bold text-secondary">{{ $alumni->total() }}</strong> alumni yang sesuai kriteria</span>
                </div>
                <a href="{{ route('home') }}#direktori-alumni" class="text-secondary font-bold hover:underline flex items-center gap-1">
                    <span>Hapus Filter</span>
                    <span class="material-symbols-outlined text-[14px]">close</span>
                </a>
            </div>
        @endif

        <!-- Alumni Profile Cards Grid (6 per page) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($alumni as $person)
                <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all flex flex-col justify-between relative group">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center gap-3.5">
                            <a href="{{ route('alumni.show', $person->slug) }}" class="relative shrink-0 block">
                                <img alt="{{ $person->name }}" 
                                     class="w-14 h-14 rounded-full object-cover ring-2 ring-secondary group-hover:scale-105 transition-transform" 
                                     src="{{ $person->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($person->name).'&background=092b5a&color=fff' }}">
                                @if($person->is_verified)
                                    <span class="material-symbols-outlined absolute -bottom-1 -right-1 text-[16px] text-tertiary bg-surface-container-lowest rounded-full p-0.5" style="font-variation-settings: 'FILL' 1;">stars</span>
                                @endif
                            </a>
                            <div class="flex flex-col min-w-0">
                                <h3 class="font-title-md text-title-md text-primary font-bold truncate group-hover:text-secondary transition-colors">
                                    <a href="{{ route('alumni.show', $person->slug) }}">
                                        {{ $person->name }}{{ $person->title ? ', '.$person->title : '' }}
                                    </a>
                                </h3>
                                <span class="font-label-sm text-label-sm bg-primary/10 text-primary px-2 py-0.5 rounded w-fit mt-0.5 font-bold">
                                    {{ strtoupper($person->level) }} KPS {{ $person->class_year }}
                                </span>
                            </div>
                        </div>
                        @if($person->profession || $person->institution)
                            <div class="flex flex-col gap-0.5 text-xs">
                                @if($person->profession)
                                    <span class="font-bold text-on-surface truncate">{{ $person->profession }}</span>
                                @endif
                                @if($person->institution)
                                    <span class="text-on-surface-variant flex items-center gap-1 truncate">
                                        <span class="material-symbols-outlined text-[14px] text-secondary shrink-0">business</span>
                                        <span class="truncate">{{ $person->institution }}</span>
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($person->summary)
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">
                                {{ $person->summary }}
                            </p>
                        @endif
                    </div>
                    <div class="pt-4 mt-4 border-t border-outline-variant/30 flex items-center justify-between">
                        <span class="font-body-sm text-body-sm text-emerald-600 flex items-center gap-1 font-semibold text-xs">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> 
                            {{ $person->is_verified ? 'Terverifikasi' : 'Anggota' }}
                        </span>
                        <a href="{{ route('alumni.show', $person->slug) }}" class="px-3.5 py-1.5 rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary font-label-md font-bold text-xs transition-colors inline-flex items-center gap-1">
                            <span>Lihat Profil</span>
                            <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12 bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant/50 p-6">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">person_search</span>
                    <p class="font-title-md text-primary font-bold mt-2">Tidak ada data alumni yang cocok dengan pencarian.</p>
                    <p class="text-xs text-on-surface-variant mt-1">Coba gunakan kata kunci lain atau daftarkan profil Anda ke direktori.</p>
                    <div class="flex items-center justify-center gap-3 mt-4">
                        <a href="{{ route('home') }}#direktori-alumni" class="px-4 py-2 rounded-xl bg-surface-container text-on-surface text-xs font-semibold">
                            Reset Pencarian
                        </a>
                        <a href="{{ route('alumni.register') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-semibold">
                            Daftarkan Profil Alumni
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination Controls -->
        @if($alumni->hasPages())
            <div class="mt-2">
                {{ $alumni->fragment('direktori-alumni')->links() }}
            </div>
        @endif

        <!-- Directory Full CTA Footer Bar -->
        <div class="p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[22px]">badge</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-sm text-sm font-bold text-primary">Ingin mencari teman seangkatan lainnya?</span>
                    <span class="text-xs text-on-surface-variant">Telusuri seluruh database alumni terverifikasi dengan filter angkatan, profesi, dan domisili.</span>
                </div>
            </div>
            <a href="{{ route('alumni.index') }}" 
               class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-bold text-xs transition-all shadow-xs flex items-center justify-center gap-2 shrink-0">
                <span>Buka Direktori Alumni Lengkap</span>
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
            </a>
        </div>
    </div>
</section>
