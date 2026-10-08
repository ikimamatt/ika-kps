@extends('layouts.app')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <a href="{{ route('career.index') }}" class="hover:text-primary transition-colors">Bursa Kerja</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-primary truncate max-w-xs">{{ $job->title }}</span>
    </nav>

    <!-- Header Card -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ $job->getBadgeClass() }}">
                        {{ $job->job_type }}
                    </span>
                    <span class="px-3 py-1 rounded-md text-xs font-bold bg-surface-container text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">location_on</span>
                        <span>{{ $job->location }}</span>
                    </span>
                    @if ($job->deadline)
                        <span class="px-3 py-1 rounded-md text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">event</span>
                            <span>Batas: {{ $job->deadline->format('d M Y') }}</span>
                        </span>
                    @endif
                </div>

                <h1 class="font-headline-sm text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight">
                    {{ $job->title }}
                </h1>

                <div class="flex items-center gap-2 text-sm sm:text-base font-bold text-secondary">
                    <span class="material-symbols-outlined text-[20px]">domain</span>
                    <span>{{ $job->company }}</span>
                </div>

                @if ($job->salary_range)
                    <div class="flex items-center gap-1.5 text-sm font-semibold text-emerald-700 pt-1">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        <span>Estimasi Kompensasi: {{ $job->salary_range }}</span>
                    </div>
                @endif
            </div>

            <!-- Apply Header Action -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                <a href="{{ $job->apply_url ?: '#' }}" target="_blank" rel="noopener noreferrer"
                   class="px-6 py-3.5 rounded-2xl bg-primary text-on-primary font-bold text-sm hover:bg-primary-container transition-all shadow-md flex items-center justify-center gap-2 text-center">
                    <span>{{ $job->cta_label ?? 'Lamar Lowongan Ini' }}</span>
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                </a>
                <a href="{{ route('career.index') }}" 
                   class="px-4 py-3 rounded-2xl border border-outline-variant/60 hover:bg-surface-container text-xs font-bold text-on-surface transition-colors text-center">
                    &larr; Kembali ke Direktori
                </a>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Content: Description & Requirements -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Job Description -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                <h2 class="font-headline-sm text-lg sm:text-xl font-bold text-primary flex items-center gap-2 pb-3 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-secondary text-[22px]">description</span>
                    <span>Deskripsi Pekerjaan &amp; Tanggung Jawab</span>
                </h2>
                <div class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed whitespace-pre-line">
                    {{ $job->description }}
                </div>
            </div>

            <!-- Qualifications / Requirements -->
            @if ($job->requirements)
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-sm space-y-4">
                    <h2 class="font-headline-sm text-lg sm:text-xl font-bold text-primary flex items-center gap-2 pb-3 border-b border-outline-variant/30">
                        <span class="material-symbols-outlined text-secondary text-[22px]">checklist</span>
                        <span>Persyaratan &amp; Kualifikasi</span>
                    </h2>
                    <div class="font-body-md text-sm sm:text-base text-on-surface-variant leading-relaxed whitespace-pre-line">
                        {{ $job->requirements }}
                    </div>
                </div>
            @endif

            <!-- Special Note for Alumni Network -->
            <div class="p-6 rounded-3xl bg-secondary/10 border border-secondary/20 text-on-surface space-y-2">
                <div class="flex items-center gap-2 text-secondary font-bold text-sm">
                    <span class="material-symbols-outlined text-[20px]">stars</span>
                    <span>Jalur Referensi Rekomendasi Alumni IKA KPS</span>
                </div>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                    Lowongan ini dipublikasikan dan direkomendasikan melalui jaringan keluarga alumni Sekolah Nasional KPS. Anda dianjurkan mencantumkan informasi latar belakang alumni saat mengirimkan curriculum vitae ke pihak perusahaan.
                </p>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="space-y-6">
            <!-- Quick Info Box -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="font-bold text-base text-primary pb-3 border-b border-outline-variant/30">
                    Informasi Lowongan
                </h3>

                <div class="space-y-3.5 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-1">
                        <span class="text-on-surface-variant">Tipe Pekerjaan</span>
                        <span class="font-bold text-primary">{{ $job->job_type }}</span>
                    </div>
                    <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                        <span class="text-on-surface-variant">Penempatan</span>
                        <span class="font-bold text-primary">{{ $job->location }}</span>
                    </div>
                    @if ($job->salary_range)
                        <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                            <span class="text-on-surface-variant">Gaji / Insentif</span>
                            <span class="font-bold text-emerald-700">{{ $job->salary_range }}</span>
                        </div>
                    @endif
                    @if ($job->deadline)
                        <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                            <span class="text-on-surface-variant">Batas Lamaran</span>
                            <span class="font-bold text-rose-600">{{ $job->deadline->format('d M Y') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-1 border-t border-outline-variant/20">
                        <span class="text-on-surface-variant">Dipublikasikan</span>
                        <span class="font-bold text-primary">{{ $job->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="pt-2 space-y-3">
                    <a href="{{ $job->formatted_apply_url }}" 
                       @if(!$job->is_email_application) target="_blank" rel="noopener noreferrer" @endif
                       class="w-full py-3 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center justify-center gap-1.5 text-center">
                        <span>{{ $job->application_action_label }}</span>
                        <span class="material-symbols-outlined text-[16px]">
                            {{ $job->is_email_application ? 'mail' : 'open_in_new' }}
                        </span>
                    </a>

                    @if($job->is_email_application && $job->application_email)
                        <div class="p-3.5 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-center space-y-2">
                            <span class="text-[11px] text-on-surface-variant block font-medium">Alamat Email Penerimaan:</span>
                            <div class="flex items-center justify-center gap-2">
                                <span id="job-app-email" class="font-mono font-semibold text-xs text-primary bg-surface-container-lowest px-2.5 py-1 rounded-lg border border-outline-variant/30 select-all">{{ $job->application_email }}</span>
                                <button type="button" 
                                        id="copy-email-btn"
                                        onclick="copyEmailToClipboard(this, '{{ $job->application_email }}')"
                                        class="px-3 py-1 rounded-lg bg-surface-container-lowest border border-outline-variant/40 text-[11px] font-bold text-secondary hover:bg-secondary hover:text-white transition-all cursor-pointer flex items-center gap-1 shadow-2xs">
                                    <span class="material-symbols-outlined text-[14px] copy-icon">content_copy</span>
                                    <span class="copy-text">Salin</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Referrer Alumnus Card -->
            @if ($job->alumni_info || $job->company_alumni_info)
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-3">
                    <h3 class="font-bold text-sm text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                        <span>Referensi Alumni</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        {{ $job->alumni_info ?? $job->company_alumni_info }}
                    </p>
                    @if ($job->alumnus && $job->alumnus->slug)
                        <a href="{{ route('alumni.show', $job->alumnus->slug) }}" 
                           class="inline-flex items-center gap-1 text-xs font-bold text-secondary hover:underline pt-1">
                            <span>Lihat Profil Pengusung</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    @endif
                </div>
            @endif

            <!-- Related Jobs -->
            @if ($relatedJobs->isNotEmpty())
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-sm space-y-4">
                    <h3 class="font-bold text-sm text-primary pb-2 border-b border-outline-variant/30">
                        Lowongan Terkait Lainnya
                    </h3>

                    <div class="space-y-3">
                        @foreach ($relatedJobs as $rel)
                            <a href="{{ route('career.show', $rel->slug) }}" 
                               class="block p-3 rounded-xl bg-surface-container-low hover:bg-surface-container hover:border-secondary/40 border border-transparent transition-all group">
                                <h4 class="font-bold text-xs text-primary group-hover:text-secondary transition-colors line-clamp-1">{{ $rel->title }}</h4>
                                <p class="text-[11px] text-on-surface-variant mt-0.5 truncate">{{ $rel->company }} &bull; {{ $rel->location }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
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
