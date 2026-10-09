<!-- ALUMNI BUSINESS & CAREER NETWORK ("Sinergi Usaha & Karier Alumni") -->
<section class="w-full py-12 sm:py-20 bg-surface" id="sinergi-bisnis">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 flex flex-col gap-8 sm:gap-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-2 max-w-2xl">
                <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Pemberdayaan Ekonomi &amp; Network</span>
                <h2 class="font-headline-lg text-2xl sm:text-3xl lg:text-headline-xl text-primary tracking-tight">Sinergi Usaha &amp; Karier Alumni</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Dukung usaha sesama alumni KPS dan temukan peluang karier profesional strategis di Kalimantan Timur serta kancah nasional.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                <a class="font-label-lg text-label-lg text-secondary font-bold hover:text-primary inline-flex items-center justify-end sm:justify-start gap-1.5 transition-colors shrink-0 order-2 sm:order-1" href="{{ route('business.index') }}">
                    <span>Semua Katalog ({{ $totalBusinessesCount ?? count($businesses) }}+)</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
                <!-- 2-Tab Navigation Buttons -->
                <div class="p-1 rounded-xl bg-surface-container-high flex items-center gap-1 w-full sm:w-auto order-1 sm:order-2">
                    <button class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md font-bold shadow-sm transition-all text-xs text-center min-h-[38px]" 
                            id="tab-business-btn" 
                            onclick="switchNetworkTab('business')" 
                            type="button">
                        Katalog Usaha (UMKM)
                    </button>
                    <button class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 rounded-lg text-on-surface-variant hover:text-primary font-label-md font-semibold transition-all text-xs text-center min-h-[38px]" 
                            id="tab-career-btn" 
                            onclick="switchNetworkTab('career')" 
                            type="button">
                        Bursa Kerja &amp; Magang
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Content 1: Business Catalog -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="tab-business-content">
            @forelse ($businesses as $biz)
                @php
                    $bizSvgFallback = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 240'%3E%3Crect width='400' height='240' fill='%23092b5a'/%3E%3Ccircle cx='200' cy='120' r='48' fill='%230084c7' opacity='0.25'/%3E%3Ctext x='50%25' y='52%25' dominant-baseline='middle' text-anchor='middle' fill='%23ffffff' font-family='sans-serif' font-size='16' font-weight='700'%3EUSAHA ALUMNI KPS%3C/text%3E%3C/svg%3E";
                    $bizImgSrc = $biz->image_url ?: $bizSvgFallback;
                @endphp
                <div class="rounded-2xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-md transition-all flex flex-col group">
                    <a href="{{ route('business.show', $biz->slug) }}" class="relative h-44 bg-surface-container-high block overflow-hidden">
                        <img src="{{ $bizImgSrc }}" 
                             alt="{{ $biz->name }}" 
                             loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                             onerror="this.onerror=null; this.src='{{ $bizSvgFallback }}';">
                        <span class="absolute top-3 right-3 px-2.5 py-1 bg-primary text-secondary-fixed-dim text-label-sm font-bold rounded shadow">
                            {{ $biz->category }}
                        </span>
                    </a>
                    <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="font-label-sm text-label-sm text-secondary font-bold">{{ $biz->owner_info }}</span>
                            <h3 class="font-title-md text-title-md text-primary font-bold hover:text-secondary transition-colors">
                                <a href="{{ route('business.show', $biz->slug) }}">{{ $biz->name }}</a>
                            </h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">
                                {{ $biz->description }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 pt-2 border-t border-outline-variant/20">
                            <a class="flex-1 py-2 rounded-xl bg-surface-container hover:bg-secondary/20 text-primary font-label-md font-bold text-center text-xs transition-colors inline-flex items-center justify-center gap-1.5" 
                               href="{{ $biz->action_link ?? 'https://wa.me/' }}" 
                               rel="noopener noreferrer" 
                               target="_blank">
                                @if($biz->action_type === 'whatsapp')
                                    <x-icons.whatsapp class="w-3.5 h-3.5 fill-current text-emerald-600 shrink-0" />
                                @elseif($biz->action_type === 'phone')
                                    <span class="material-symbols-outlined text-[16px]">call</span>
                                @else
                                    <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                                @endif
                                <span>{{ $biz->action_label }}</span>
                            </a>
                            <a href="{{ route('business.show', $biz->slug) }}" 
                               class="px-3 py-2 rounded-xl bg-surface-container-high hover:bg-primary hover:text-white text-primary text-xs font-bold transition-all"
                               title="Lihat Detail Profil Usaha">
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-center py-8 text-on-surface-variant">Belum ada katalog usaha terdaftar.</p>
            @endforelse

            <!-- View All Businesses Footer Bar -->
            <div class="col-span-1 md:col-span-3 pt-4 border-t border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-on-surface-variant font-medium text-center sm:text-left">
                    Menampilkan {{ min(count($businesses), 6) }} dari {{ $totalBusinessesCount ?? count($businesses) }} unit usaha alumni terdaftar
                </span>
                <a href="{{ route('business.index') }}" 
                   class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white font-bold text-xs transition-all inline-flex items-center justify-center gap-2 shadow-xs min-h-[44px]">
                    <span>Lihat Seluruh Katalog Usaha ({{ $totalBusinessesCount ?? count($businesses) }} UMKM)</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Tab Content 2: Career & Vacancy (Initially Hidden) -->
        <div class="hidden grid grid-cols-1 md:grid-cols-2 gap-6" id="tab-career-content">
            @forelse ($jobVacancies as $job)
                <div class="p-5 sm:p-6 rounded-2xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm flex flex-col justify-between gap-4 hover:shadow-md transition-shadow">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="font-label-sm text-label-sm {{ $job->type_badge_class }} px-2 py-0.5 rounded font-bold">
                                {{ $job->job_type }}
                            </span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $job->posted_time_info }}</span>
                        </div>
                        <h3 class="font-headline-sm text-base sm:text-headline-sm text-primary font-bold">{{ $job->title }}</h3>
                        <span class="font-title-md text-title-md text-secondary font-semibold">{{ $job->company_alumni_info }}</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">
                            {{ $job->description }}
                        </p>
                    </div>
                    <a href="{{ route('career.show', $job->slug) }}" class="w-full py-2.5 text-center rounded-xl bg-primary text-on-primary font-label-md font-bold hover:bg-primary-container transition-colors text-xs flex items-center justify-center gap-1.5 min-h-[44px]">
                        <span>{{ $job->cta_label ?? 'Lihat Rincian Loker' }}</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            @empty
                <p class="col-span-2 text-center py-8 text-on-surface-variant">Belum ada info loker yang aktif saat ini.</p>
            @endforelse

            <!-- View All Careers Footer Bar -->
            <div class="col-span-1 md:col-span-2 pt-4 border-t border-outline-variant/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <span class="text-xs text-on-surface-variant font-medium text-center sm:text-left">
                    Menampilkan info lowongan kerja dan magang aktif dari jejaring alumni
                </span>
                <a href="{{ route('career.index') }}" 
                   class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-white font-bold text-xs transition-all inline-flex items-center justify-center gap-2 shadow-xs min-h-[44px]">
                    <span>Lihat Seluruh Bursa Karier</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>
        </div>

        <!-- Teaser Career Banner -->
        <div class="p-5 sm:p-6 rounded-2xl bg-primary text-on-primary flex flex-col md:flex-row items-start md:items-center justify-between gap-4 sm:gap-6 shadow-md border border-white/15 relative overflow-hidden ring-1 ring-secondary/30">
            <div class="flex items-center gap-3.5 sm:gap-4">
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl bg-secondary text-on-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[24px]">work</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-md text-base sm:text-title-md font-bold">Peluang Karier &amp; Rekomendasi Alumni</span>
                    <span class="font-body-sm text-xs sm:text-body-sm text-surface-container-highest/80">Akses jaringan korporasi alumni KPS di Balikpapan, Jakarta, dan kawasan IKN Nusantara.</span>
                </div>
            </div>
            <a class="w-full md:w-auto text-center px-5 py-2.5 rounded-xl bg-secondary text-on-secondary font-label-lg font-bold hover:bg-secondary-container transition-colors shrink-0 shadow-sm min-h-[44px] flex items-center justify-center text-xs sm:text-sm" href="{{ route('career.index') }}">
                Buka Seluruh Bursa Karier
            </a>
        </div>
    </div>
</section>

@pushOnce('scripts')
<script>
    function switchNetworkTab(tabName) {
        const businessContent = document.getElementById('tab-business-content');
        const careerContent = document.getElementById('tab-career-content');
        const businessBtn = document.getElementById('tab-business-btn');
        const careerBtn = document.getElementById('tab-career-btn');

        if (!businessContent || !careerContent || !businessBtn || !careerBtn) return;

        const activeClass = 'flex-1 sm:flex-initial px-3 sm:px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md font-bold shadow-sm transition-all text-xs text-center min-h-[38px]';
        const inactiveClass = 'flex-1 sm:flex-initial px-3 sm:px-4 py-2 rounded-lg text-on-surface-variant hover:text-primary font-label-md font-semibold transition-all text-xs text-center min-h-[38px]';

        if (tabName === 'business') {
            businessContent.classList.remove('hidden');
            careerContent.classList.add('hidden');
            businessBtn.className = activeClass;
            careerBtn.className = inactiveClass;
        } else {
            businessContent.classList.add('hidden');
            careerContent.classList.remove('hidden');
            careerBtn.className = activeClass;
            businessBtn.className = inactiveClass;
        }
    }
</script>
@endPushOnce
