<!-- ALUMNI BUSINESS & CAREER NETWORK ("Sinergi Usaha & Karier Alumni") -->
<section class="w-full py-20 bg-surface" id="sinergi-bisnis">
    <div class="max-w-[1280px] mx-auto px-6 flex flex-col gap-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="flex flex-col gap-2 max-w-2xl">
                <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Pemberdayaan Ekonomi &amp; Network</span>
                <h2 class="font-headline-lg text-headline-xl text-primary tracking-tight">Sinergi Usaha &amp; Karier Alumni</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">
                    Dukung usaha sesama alumni KPS dan temukan peluang karier profesional strategis di Kalimantan Timur serta kancah nasional.
                </p>
            </div>
            <!-- 2-Tab Navigation Buttons -->
            <div class="p-1 rounded-xl bg-surface-container-high flex items-center gap-1 self-start md:self-auto">
                <button class="px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md font-bold shadow-sm transition-all" 
                        id="tab-business-btn" 
                        onclick="switchNetworkTab('business')" 
                        type="button">
                    Katalog Usaha (UMKM)
                </button>
                <button class="px-4 py-2 rounded-lg text-on-surface-variant hover:text-primary font-label-md font-semibold transition-all" 
                        id="tab-career-btn" 
                        onclick="switchNetworkTab('career')" 
                        type="button">
                    Bursa Kerja &amp; Magang
                </button>
            </div>
        </div>

        <!-- Tab Content 1: Business Catalog -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="tab-business-content">
            @forelse ($businesses as $biz)
                <div class="rounded-xl overflow-hidden bg-surface-container-lowest border border-outline-variant/40 shadow-sm hover:shadow-lg transition-all flex flex-col">
                    <div class="relative h-44 bg-surface-container-high">
                        <img alt="{{ $biz->name }}" 
                             class="w-full h-full object-cover" 
                             src="{{ $biz->image_url ?? 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?w=600&auto=format&fit=crop' }}">
                        <span class="absolute top-3 right-3 px-2.5 py-1 bg-primary text-secondary-fixed-dim text-label-sm font-bold rounded shadow">
                            {{ $biz->category }}
                        </span>
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-2">
                            <span class="font-label-sm text-label-sm text-secondary font-bold">{{ $biz->owner_info }}</span>
                            <h3 class="font-title-md text-title-md text-primary font-bold">{{ $biz->name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                {{ $biz->description }}
                            </p>
                        </div>
                        <a class="w-full py-2.5 rounded-lg bg-surface-container hover:bg-secondary/20 text-primary font-label-md font-bold text-center transition-colors inline-flex items-center justify-center gap-1.5" 
                           href="{{ $biz->action_link ?? 'https://wa.me/' }}" 
                           rel="noopener noreferrer" 
                           target="_blank">
                            @if($biz->action_type === 'whatsapp')
                                <span class="material-symbols-outlined text-[18px]">chat</span>
                            @elseif($biz->action_type === 'phone')
                                <span class="material-symbols-outlined text-[18px]">call</span>
                            @else
                                <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                            @endif
                            {{ $biz->action_label }}
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-3 text-center py-8 text-on-surface-variant">Belum ada katalog usaha terdaftar.</p>
            @endforelse
        </div>

        <!-- Tab Content 2: Career & Vacancy (Initially Hidden) -->
        <div class="hidden grid grid-cols-1 md:grid-cols-2 gap-6" id="tab-career-content">
            @forelse ($jobVacancies as $job)
                <div class="p-6 rounded-xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm flex flex-col justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <span class="font-label-sm text-label-sm {{ $job->type_badge_class }} px-2 py-0.5 rounded font-bold">
                                {{ $job->job_type }}
                            </span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $job->posted_time_info }}</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm text-primary font-bold">{{ $job->title }}</h3>
                        <span class="font-title-md text-title-md text-secondary font-semibold">{{ $job->company_alumni_info }}</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            {{ $job->description }}
                        </p>
                    </div>
                    <a href="#kontak" class="w-full py-2.5 text-center rounded-lg bg-primary text-on-primary font-label-md font-bold hover:bg-primary-container transition-colors">
                        {{ $job->cta_label }}
                    </a>
                </div>
            @empty
                <p class="col-span-2 text-center py-8 text-on-surface-variant">Belum ada info loker yang aktif saat ini.</p>
            @endforelse
        </div>

        <!-- Teaser Career Banner -->
        <div class="p-6 rounded-2xl bg-primary text-on-primary flex flex-col md:flex-row items-center justify-between gap-6 shadow-md border-l-4 border-secondary">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-secondary text-on-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px]">work</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-md text-title-md font-bold">18+ Peluang Karier Eksklusif Terdaftar</span>
                    <span class="font-body-sm text-body-sm text-surface-container-highest/80">Akses jaringan korporasi alumni KPS di Balikpapan, Jakarta, dan kawasan IKN Nusantara.</span>
                </div>
            </div>
            <a class="px-5 py-2.5 rounded-xl bg-secondary text-on-secondary font-label-lg font-bold hover:bg-secondary-container transition-colors shrink-0 shadow-sm" href="#daftar-alumni">
                Pasang Info Loker Alumni
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

        if (tabName === 'business') {
            businessContent.classList.remove('hidden');
            careerContent.classList.add('hidden');
            businessBtn.className = 'px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md font-bold shadow-sm transition-all';
            careerBtn.className = 'px-4 py-2 rounded-lg text-on-surface-variant hover:text-primary font-label-md font-semibold transition-all';
        } else {
            businessContent.classList.add('hidden');
            careerContent.classList.remove('hidden');
            careerBtn.className = 'px-4 py-2 rounded-lg bg-secondary text-on-secondary font-label-md font-bold shadow-sm transition-all';
            businessBtn.className = 'px-4 py-2 rounded-lg text-on-surface-variant hover:text-primary font-label-md font-semibold transition-all';
        }
    }
</script>
@endPushOnce
