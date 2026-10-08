@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('program.index') }}" class="hover:text-primary transition-colors">Program Kerja</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-bold truncate max-w-xs sm:max-w-md">{{ $program->title }}</span>
        </nav>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Main Content (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Main Header Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-xs">
                    @if($program->image_url)
                        <div class="relative h-64 sm:h-80 md:h-96 w-full bg-surface-container overflow-hidden">
                            <img src="{{ $program->image_url }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent"></div>
                            
                            <!-- Badges on image -->
                            <div class="absolute bottom-6 left-6 right-6 flex items-center justify-between gap-3 text-white">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 rounded-xl {{ $program->icon_bg_class ?: 'bg-primary text-white' }} flex items-center justify-center shadow-md">
                                        <span class="material-symbols-outlined text-[22px]">{{ $program->icon ?: 'school' }}</span>
                                    </div>
                                    <span class="font-extrabold text-sm sm:text-base drop-shadow-md">Inisiatif IKA KPS</span>
                                </div>

                                <div>
                                    @if($program->status === 'active')
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-emerald-500 text-white shadow-md">
                                            Aktif Berjalan
                                        </span>
                                    @elseif($program->status === 'completed')
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-sky-600 text-white shadow-md">
                                            Tuntas Terlaksana
                                        </span>
                                    @else
                                        <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-amber-500 text-white shadow-md">
                                            Segera Dimulai
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="space-y-3">
                            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary leading-tight">
                                {{ $program->title }}
                            </h1>
                            <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed font-normal">
                                {{ $program->description }}
                            </p>
                        </div>

                        <!-- Progress Highlight Mobile & Desktop -->
                        <div class="p-5 rounded-2xl bg-surface-container-low border border-outline-variant/30 space-y-3">
                            <div class="flex items-center justify-between text-xs sm:text-sm">
                                <span class="font-bold text-primary flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-secondary text-[18px]">trending_up</span>
                                    <span>{{ $program->progress_label ?? 'Realisasi Target Program' }}</span>
                                </span>
                                <span class="font-black text-secondary text-base">{{ $program->progress_percent }}%</span>
                            </div>

                            <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full {{ $program->bar_color_class ?: 'bg-secondary' }} rounded-full transition-all duration-700"
                                     style="width: {{ min(100, $program->progress_percent) }}%;"></div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-2 text-xs text-on-surface-variant">
                                @if($program->achievement_text)
                                    <span class="font-semibold text-primary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-secondary text-[16px]">check_circle</span>
                                        <span>{{ $program->achievement_text }}</span>
                                    </span>
                                @endif

                                @if($program->progress_status)
                                    <span class="px-2.5 py-0.5 rounded-full bg-surface-container text-primary font-bold text-[11px]">
                                        {{ $program->progress_status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Narrative / Body Content -->
                        <div class="space-y-4 pt-4 border-t border-outline-variant/30">
                            <h2 class="text-lg font-bold text-primary flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary text-[20px]">description</span>
                                <span>Rincian &amp; Narasi Pelaksanaan Program</span>
                            </h2>
                            <div class="text-sm sm:text-base text-on-surface leading-relaxed space-y-4">
                                {!! nl2br(e($program->body ?: $program->description)) !!}
                            </div>
                        </div>

                        <!-- Organization Commitment Note -->
                        <div class="p-6 rounded-2xl bg-primary/5 border border-primary/10 text-on-surface space-y-2">
                            <div class="flex items-center gap-2 text-primary font-bold text-sm">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                                <span>Prinsip Transparansi &amp; Akuntabilitas IKA KPS</span>
                            </div>
                            <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                                Seluruh dana dan partisipasi yang dihimpun dalam program kerja ini dikelola secara transparan oleh Pengurus IKA KPS Balikpapan dan dipertanggungjawabkan pada Laporan Tahunan Organisasi.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar (1 Col) -->
            <div class="space-y-6">
                
                <!-- Donation / Support Box -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-5">
                    <h3 class="font-bold text-base text-primary pb-3 border-b border-outline-variant/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[20px]">volunteer_activism</span>
                        <span>Dukungan &amp; Saluran Donasi</span>
                    </h3>

                    <!-- Financial Metrics -->
                    @if($program->target_amount)
                        <div class="space-y-3 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs sm:text-sm">
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Terkumpul:</span>
                                <span class="font-extrabold text-emerald-700">Rp {{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-outline-variant/20">
                                <span class="text-on-surface-variant">Target Kebutuhan:</span>
                                <span class="font-bold text-primary">Rp {{ number_format($program->target_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Bank Account Info Card -->
                    <div class="p-4 rounded-2xl bg-primary/5 border border-primary/15 space-y-3">
                        <span class="text-[11px] font-bold text-primary uppercase tracking-wider block">Rekening Resmi IKA KPS</span>
                        
                        <div class="space-y-1">
                            <span class="text-xs text-on-surface-variant block">Bank Mandiri KC Balikpapan</span>
                            <div class="flex items-center justify-between gap-2">
                                <span class="font-mono font-extrabold text-sm text-primary">149-00-1122334-5</span>
                                <button type="button" 
                                        onclick="copyEmailToClipboard(this, '1490011223345')"
                                        class="px-2.5 py-1 rounded-md bg-surface-container-lowest border border-outline-variant/40 text-[10px] font-bold text-secondary hover:bg-secondary hover:text-white transition-all cursor-pointer shadow-2xs flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px] copy-icon">content_copy</span>
                                    <span class="copy-text">Salin</span>
                                </button>
                            </div>
                            <span class="text-[11px] text-on-surface-variant block">a.n. IKA Sekolah Nasional KPS</span>
                        </div>
                    </div>

                    <!-- Direct Contact / WhatsApp Action -->
                    <div class="pt-2 space-y-2.5">
                        <a href="https://wa.me/?text={{ rawurlencode('Halo Pengurus IKA KPS Balikpapan, saya ingin berpartisipasi dan mendukung program: ' . $program->title) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all shadow-sm flex items-center justify-center gap-2 text-center">
                            <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                            <span>Konfirmasi / Tanya Program via WA</span>
                        </a>

                        <a href="{{ route('program.index') }}" 
                           class="w-full py-2.5 rounded-xl bg-surface-container-low hover:bg-surface-container text-primary font-bold text-xs transition-all flex items-center justify-center gap-1 text-center">
                            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                            <span>Kembali ke Daftar Program</span>
                        </a>
                    </div>
                </div>

                <!-- Schedule & Details Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-primary pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px]">event</span>
                        <span>Informasi Pelaksanaan</span>
                    </h3>

                    <div class="space-y-3 text-xs sm:text-sm">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-on-surface-variant">Status Saat Ini</span>
                            <span class="font-bold {{ $program->status === 'active' ? 'text-emerald-600' : ($program->status === 'completed' ? 'text-sky-600' : 'text-amber-600') }}">
                                {{ ucfirst($program->status) }}
                            </span>
                        </div>
                        @if($program->start_date)
                            <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                                <span class="text-on-surface-variant">Mulai Kegiatan</span>
                                <span class="font-bold text-primary">{{ $program->start_date->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if($program->end_date)
                            <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                                <span class="text-on-surface-variant">Target Selesai</span>
                                <span class="font-bold text-primary">{{ $program->end_date->format('d M Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Related Programs -->
                @if($relatedPrograms->isNotEmpty())
                    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-4">
                        <h3 class="font-bold text-sm text-primary pb-2 border-b border-outline-variant/30">
                            Program Inisiatif Lainnya
                        </h3>

                        <div class="space-y-3">
                            @foreach($relatedPrograms as $rel)
                                <a href="{{ route('program.show', $rel->slug) }}" 
                                   class="block p-3 rounded-xl bg-surface-container-low hover:bg-surface-container hover:border-secondary/40 border border-transparent transition-all group">
                                    <h4 class="font-bold text-xs text-primary group-hover:text-secondary transition-colors line-clamp-1">{{ $rel->title }}</h4>
                                    <div class="flex items-center justify-between text-[11px] text-on-surface-variant mt-1.5">
                                        <span>Progres: {{ $rel->progress_percent }}%</span>
                                        <span class="text-secondary font-bold group-hover:underline">Lihat &rarr;</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyEmailToClipboard(btn, text) {
    function onCopied() {
        const textSpan = btn.querySelector('.copy-text');
        const iconSpan = btn.querySelector('.copy-icon');
        
        if (textSpan) textSpan.innerText = 'Tersalin!';
        if (iconSpan) iconSpan.innerText = 'check';
        
        btn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
        btn.classList.remove('bg-surface-container-lowest', 'text-secondary');
        
        setTimeout(() => {
            if (textSpan) textSpan.innerText = 'Salin';
            if (iconSpan) iconSpan.innerText = 'content_copy';
            btn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
            btn.classList.add('bg-surface-container-lowest', 'text-secondary');
        }, 2000);
    }

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text)
            .then(onCopied)
            .catch(() => fallbackCopy(text, onCopied));
    } else {
        fallbackCopy(text, onCopied);
    }

    function fallbackCopy(val, callback) {
        const tempInput = document.createElement('textarea');
        tempInput.value = val;
        tempInput.style.position = 'fixed';
        tempInput.style.top = '0';
        tempInput.style.left = '0';
        tempInput.style.width = '2em';
        tempInput.style.height = '2em';
        tempInput.style.padding = '0';
        tempInput.style.border = 'none';
        tempInput.style.outline = 'none';
        tempInput.style.boxShadow = 'none';
        tempInput.style.background = 'transparent';
        document.body.appendChild(tempInput);
        tempInput.focus();
        tempInput.select();
        
        try {
            document.execCommand('copy');
            callback();
        } catch (err) {
            console.error('Fallback copy error:', err);
        }
        
        document.body.removeChild(tempInput);
    }
}
</script>
@endpush
