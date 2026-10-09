@extends('layouts.app')

@section('content')
<div class="w-full bg-surface">
    <!-- Hero Header -->
    <section class="bg-gradient-to-b from-surface-container-low to-surface border-b border-outline-variant/30 py-12 md:py-16 px-6">
        <div class="max-w-[1280px] mx-auto text-center space-y-4">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-widest bg-secondary/10 text-secondary border border-secondary/20">
                <span class="material-symbols-outlined text-[16px]">work</span>
                <span>Peluang Karir &amp; Sinergi Profesional</span>
            </span>
            <h1 class="font-headline-sm text-3xl sm:text-4xl md:text-5xl font-extrabold text-primary tracking-tight max-w-3xl mx-auto">
                Bursa Kerja &amp; Peluang Karir Alumni
            </h1>
            <p class="font-body-md text-sm sm:text-base text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
                Temukan lowongan kerja, program magang, dan posisi strategis di Balikpapan, kawasan IKN Nusantara, serta kancah nasional dari jejaring keluarga alumni Sekolah Nasional KPS.
            </p>
        </div>
    </section>

    <!-- Filter & Content Section -->
    <div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
        <!-- Search & Filter Bar -->
        <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-5 shadow-sm space-y-4">
            <form action="{{ route('career.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           aria-label="Cari posisi, keahlian, atau nama perusahaan"
                           placeholder="Cari posisi, keahlian, atau nama perusahaan..." 
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all min-h-[44px]">
                </div>

                <div class="relative w-full sm:w-52 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">work</span>
                    <select name="job_type" 
                            aria-label="Filter tipe pekerjaan"
                            onchange="this.form.submit()"
                            class="w-full pl-10.5 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer min-h-[44px]">
                        <option value="">Semua Tipe Kerja</option>
                        @foreach ($jobTypes as $type)
                            <option value="{{ $type }}" {{ request('job_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>

                <div class="relative w-full sm:w-52 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">location_on</span>
                    <select name="location" 
                            aria-label="Filter lokasi pekerjaan"
                            onchange="this.form.submit()"
                            class="w-full pl-10.5 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer min-h-[44px]">
                        <option value="">Semua Lokasi</option>
                        @foreach ($locations as $loc)
                            <option value="{{ $loc }}" {{ request('location') === $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm flex items-center justify-center gap-1.5 min-h-[44px]">
                        <span class="material-symbols-outlined text-[16px]">filter_list</span>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['q', 'job_type', 'location']))
                        <a href="{{ route('career.index') }}" 
                           class="px-3.5 py-2.5 rounded-xl border border-outline-variant text-on-surface-variant hover:bg-surface-container text-xs font-semibold transition-colors min-h-[44px] flex items-center justify-center"
                           title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Job Vacancy Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($jobs as $job)
                <div class="rounded-2xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md hover:border-secondary/50 transition-all p-6 flex flex-col justify-between gap-5 group">
                    <div class="space-y-3.5">
                        <!-- Top Meta Badges -->
                        <div class="flex items-center justify-between gap-2 flex-wrap">
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $job->getBadgeClass() }}">
                                {{ $job->job_type }}
                            </span>
                            <div class="flex items-center gap-1 text-[11px] text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span>{{ $job->posted_time_info ?? $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <!-- Title and Company -->
                        <div>
                            <h2 class="font-headline-sm text-lg sm:text-xl font-bold text-primary group-hover:text-secondary transition-colors">
                                <a href="{{ route('career.show', $job->slug) }}">{{ $job->title }}</a>
                            </h2>
                            <div class="flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-secondary mt-1">
                                <span class="material-symbols-outlined text-[16px]">domain</span>
                                <span>{{ $job->company }}</span>
                            </div>
                        </div>

                        <!-- Attributes Pill Row -->
                        <div class="flex items-center gap-3 text-xs text-on-surface-variant flex-wrap pt-1">
                            <div class="flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-on-surface-variant">location_on</span>
                                <span>{{ $job->location }}</span>
                            </div>
                            @if ($job->salary_range)
                                <div class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">payments</span>
                                    <span class="font-medium text-emerald-700">{{ $job->salary_range }}</span>
                                </div>
                            @endif
                            @if ($job->deadline)
                                <div class="flex items-center gap-1 text-[11px] text-rose-600">
                                    <span class="material-symbols-outlined text-[15px]">event</span>
                                    <span>Hingga {{ $job->deadline->format('d M Y') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Description Snippet -->
                        <p class="font-body-md text-xs sm:text-sm text-on-surface-variant line-clamp-2 leading-relaxed">
                            {{ $job->description }}
                        </p>

                        <!-- Referrer Info Badge -->
                        @if ($job->alumni_info || $job->company_alumni_info)
                            <div class="px-3 py-1.5 rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-center gap-2 text-[11px] text-on-surface-variant">
                                <span class="material-symbols-outlined text-secondary text-[16px]">verified_user</span>
                                <span class="truncate">{{ $job->alumni_info ?? $job->company_alumni_info }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-between gap-3">
                        <a href="{{ route('career.show', $job->slug) }}" 
                           class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1 transition-colors">
                            <span>Rincian Lowongan</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </a>

                        <a href="{{ $job->formatted_apply_url ?: route('career.show', $job->slug) }}" 
                           @if(!$job->is_email_application && $job->formatted_apply_url !== '#') target="_blank" rel="noopener noreferrer" @endif
                           class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-xs flex items-center gap-1.5">
                            <span>{{ $job->application_action_label }}</span>
                            <span class="material-symbols-outlined text-[14px]">
                                {{ $job->is_email_application ? 'mail' : 'open_in_new' }}
                            </span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-on-surface-variant bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-8">
                    <span class="material-symbols-outlined text-5xl text-outline mb-2 block">work_off</span>
                    <p class="text-base font-bold text-primary">Belum ada info lowongan kerja pada filter ini.</p>
                    <p class="text-xs text-on-surface-variant mt-1">Gunakan kata kunci lain atau lihat seluruh peluang karier yang tersedia.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($jobs->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
