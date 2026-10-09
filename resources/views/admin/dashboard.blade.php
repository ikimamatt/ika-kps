@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-8">
    <!-- Top Welcome & Operations Banner -->
    <div class="bg-gradient-to-r from-primary via-[#07244c] to-[#004c6b] rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-lg shadow-primary/10 border border-white/10">
        <div class="relative z-10 max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wide bg-white/15 text-white/95 backdrop-blur-xs mb-3 border border-white/20">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Dashboard Pengurus &amp; Admin — IKA KPS</span>
            </div>
            
            <h1 class="font-headline-sm text-2xl sm:text-3xl font-extrabold leading-tight tracking-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            
            <p class="font-body-md text-xs sm:text-sm text-white/85 mt-2 leading-relaxed max-w-2xl">
                Pusat kontrol operasional portal IKA KPS. Kelola verifikasi keanggotaan alumni baru, pantau direktori, dan publikasikan konten untuk seluruh alumni di Indonesia dan mancanegara.
            </p>

            <!-- Quick Triage Action Pills -->
            <div class="flex flex-wrap items-center gap-2.5 mt-5">
                @if (($metrics['pending_registrations'] ?? 0) > 0)
                    <a href="{{ route('admin.verification.index') }}" 
                       class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-400 text-amber-950 font-bold text-xs hover:bg-amber-300 shadow-sm transition-all active:scale-[0.98]">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                        <span>{{ $metrics['pending_registrations'] }} Pendaftaran Menunggu Verifikasi</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                @endif

                <a href="{{ route('admin.alumni.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-xs border border-white/20 transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[16px]">person_add</span>
                    <span>+ Tambah Alumni</span>
                </a>

                <a href="{{ route('admin.articles.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-xs border border-white/20 transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[16px]">post_add</span>
                    <span>+ Tulis Artikel</span>
                </a>

                <a href="{{ route('admin.job-vacancies.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white/15 hover:bg-white/25 text-white text-xs font-semibold backdrop-blur-xs border border-white/20 transition-all active:scale-[0.98]">
                    <span class="material-symbols-outlined text-[16px]">work</span>
                    <span>+ Pasang Loker</span>
                </a>
            </div>
        </div>

        <!-- Ambient decorative shapes -->
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-secondary/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/4 -top-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Grid Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Alumni Terverifikasi -->
        <a href="{{ route('admin.alumni.index') }}" 
           class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-primary/50 transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider group-hover:text-primary transition-colors">
                        Alumni Terverifikasi
                    </span>
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-[20px]">group</span>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-primary tabular-nums mt-3">
                    {{ number_format($metrics['total_alumni'] ?? 0) }}
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between group-hover:text-primary transition-colors">
                <span>Tayang di direktori publik</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </p>
        </a>

        <!-- 2. Pendaftaran Pending -->
        <a href="{{ route('admin.verification.index') }}" 
           class="{{ ($metrics['pending_registrations'] ?? 0) > 0 ? 'bg-amber-50/50 border-amber-300 hover:border-amber-500' : 'bg-surface-container-lowest border-outline-variant/40 hover:border-amber-500/50' }} border rounded-2xl p-5 shadow-xs hover:shadow-md transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold {{ ($metrics['pending_registrations'] ?? 0) > 0 ? 'text-amber-800' : 'text-on-surface-variant' }} uppercase tracking-wider group-hover:text-amber-600 transition-colors flex items-center gap-1.5">
                        <span>Pendaftaran Pending</span>
                        @if (($metrics['pending_registrations'] ?? 0) > 0)
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        @endif
                    </span>
                    <div class="w-10 h-10 rounded-xl {{ ($metrics['pending_registrations'] ?? 0) > 0 ? 'bg-amber-500/20 text-amber-700' : 'bg-amber-500/10 text-amber-600' }} flex items-center justify-center group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 tabular-nums mt-3">
                    {{ number_format($metrics['pending_registrations'] ?? 0) }}
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between group-hover:text-amber-700 transition-colors">
                <span>{{ ($metrics['pending_registrations'] ?? 0) > 0 ? 'Butuh tinjauan segera' : 'Semua pendaftaran selesai' }}</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </p>
        </a>

        <!-- 3. Total Akun Pengguna -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">
                        Total Akun Pengguna
                    </span>
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-primary tabular-nums mt-3">
                    {{ number_format($metrics['total_users'] ?? 0) }}
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-3 pt-3 border-t border-outline-variant/20">
                Akun terdaftar dalam sistem
            </p>
        </div>

        <!-- 4. Bisnis & Karir Alumni -->
        <a href="{{ route('admin.businesses.index') }}" 
           class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs hover:shadow-md hover:border-emerald-500/50 transition-all group flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider group-hover:text-emerald-700 transition-colors">
                        Bisnis &amp; Karir
                    </span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-[20px]">storefront</span>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-primary mt-3 flex items-baseline gap-2">
                    <span class="tabular-nums">{{ $metrics['active_businesses'] ?? 0 }}</span>
                    <span class="text-xs font-semibold text-on-surface-variant">Bisnis /</span>
                    <span class="tabular-nums text-secondary">{{ $metrics['active_jobs'] ?? 0 }}</span>
                    <span class="text-xs font-semibold text-on-surface-variant">Loker</span>
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-3 pt-3 border-t border-outline-variant/20 flex items-center justify-between group-hover:text-emerald-700 transition-colors">
                <span>Peluang jejaring alumni</span>
                <span class="material-symbols-outlined text-[14px] group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
            </p>
        </a>
    </div>

    <!-- Quick Action Hub (Pusat Akses Pintas Pengurus) -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-headline-sm text-sm font-bold text-primary uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">bolt</span>
                <span>Pusat Navigasi Pintas</span>
            </h2>
            <span class="text-[11px] text-on-surface-variant">Operasional harian pengurus IKA KPS</span>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.alumni.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-primary transition-colors">Direktori Alumni</span>
            </a>

            <a href="{{ route('admin.verification.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">verified_user</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-amber-700 transition-colors">Verifikasi</span>
            </a>

            <a href="{{ route('admin.articles.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">newspaper</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-secondary transition-colors">Berita &amp; Artikel</span>
            </a>

            <a href="{{ route('admin.businesses.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-emerald-700 transition-colors">Bisnis Alumni</span>
            </a>

            <a href="{{ route('admin.job-vacancies.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">work</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-blue-700 transition-colors">Bursa Kerja</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" 
               class="p-3 rounded-xl border border-outline-variant/30 hover:border-secondary/50 bg-surface-container-low/50 hover:bg-surface-container-lowest flex flex-col items-center text-center gap-1.5 transition-all group">
                <div class="w-8 h-8 rounded-lg bg-stone-500/10 text-stone-700 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-[18px]">settings</span>
                </div>
                <span class="text-xs font-bold text-on-surface group-hover:text-stone-900 transition-colors">Pengaturan Web</span>
            </a>
        </div>
    </div>

    <!-- Dua Kolom: Antrean Verifikasi & Distribusi Jenjang -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Kolom Kiri: Antrean Pendaftaran Terbaru (2/3) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600 text-[20px]">pending_actions</span>
                        <span>Antrean Pendaftaran Terbaru</span>
                    </h2>
                    <p class="text-xs text-on-surface-variant">Permohonan keanggotaan baru yang membutuhkan verifikasi data almamater.</p>
                </div>
                <a href="{{ route('admin.verification.index') }}" class="text-xs font-bold text-secondary hover:underline flex items-center gap-1">
                    <span>Lihat Semua ({{ $metrics['pending_registrations'] ?? 0 }})</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
                @if ($latestPendingAlumni->isNotEmpty())
                    <div class="divide-y divide-outline-variant/20">
                        @foreach ($latestPendingAlumni as $user)
                            @php $alumnus = $user->alumnus; @endphp
                            <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-surface-container-low/40 transition-colors">
                                <div class="flex items-start sm:items-center gap-3.5 min-w-0">
                                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002D62&color=fff&size=80' }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-11 h-11 rounded-xl object-cover ring-1 ring-outline-variant/60 shrink-0 bg-surface-container">
                                    
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-sm text-primary truncate flex flex-wrap items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if ($alumnus?->level)
                                                <span class="px-2 py-0.5 rounded-md text-[11px] font-extrabold uppercase bg-secondary/10 text-secondary border border-secondary/20">
                                                    {{ strtoupper($alumnus->level) }} {{ $alumnus->full_year ? "'".substr($alumnus->full_year, -2) : '' }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        @if ($alumnus && ($alumnus->profession || $alumnus->institution))
                                            <div class="text-xs text-on-surface font-medium truncate mt-0.5">
                                                {{ $alumnus->profession ?? 'Alumni' }}
                                                @if ($alumnus->institution)
                                                    <span class="text-on-surface-variant font-normal">• {{ $alumnus->institution }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="text-[11px] text-on-surface-variant truncate mt-0.5 flex items-center gap-1.5">
                                            <span>{{ $user->email }}</span>
                                            <span>•</span>
                                            <span>Mendaftar {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-center shrink-0">
                                    @if ($alumnus)
                                        <form action="{{ route('admin.verification.approve', $alumnus->id) }}" method="POST" 
                                              onsubmit="return confirm('Setujui keanggotaan alumni {{ addslashes($user->name) }}?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all flex items-center gap-1 shadow-xs active:scale-[0.98]">
                                                <span class="material-symbols-outlined text-[16px]">check</span>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.verification.show', $alumnus->id) }}" 
                                           class="px-3.5 py-1.5 rounded-xl border border-outline-variant/60 text-on-surface hover:bg-surface-container font-semibold text-xs transition-all">
                                            Tinjau
                                        </a>
                                    @else
                                        <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" 
                                           class="px-3.5 py-1.5 rounded-xl border border-outline-variant/60 text-on-surface hover:bg-surface-container font-semibold text-xs transition-all">
                                            Tinjau
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 px-6 text-center text-on-surface-variant">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[24px]">task_alt</span>
                        </div>
                        <p class="text-sm font-bold text-on-surface">Tidak ada antrean pendaftaran tertunda!</p>
                        <p class="text-xs text-on-surface-variant/80 mt-1 max-w-sm mx-auto">Semua permohonan keanggotaan alumni telah disetujui. Data direktori alumni dalam kondisi terkini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Distribusi Jenjang (1/3) -->
        <div class="space-y-4">
            <div>
                <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[20px]">school</span>
                    <span>Distribusi Alumni KPS</span>
                </h2>
                <p class="text-xs text-on-surface-variant">Persebaran alumni terdaftar per jenjang sekolah.</p>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs space-y-5">
                @php
                    $totalDist = max(array_sum($alumniDistribution), 1);
                    $pctSMA = round((($alumniDistribution['sma'] ?? 0) / $totalDist) * 100);
                    $pctSMP = round((($alumniDistribution['smp'] ?? 0) / $totalDist) * 100);
                    $pctSD  = round((($alumniDistribution['sd'] ?? 0) / $totalDist) * 100);
                    $pctTK  = round((($alumniDistribution['tk'] ?? 0) / $totalDist) * 100);
                @endphp

                <!-- Multi-segment Aggregate Proportion Bar -->
                <div>
                    <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider mb-2 flex items-center justify-between">
                        <span>Proporsi Almamater</span>
                        <span class="tabular-nums font-semibold">{{ array_sum($alumniDistribution) }} Total Alumni</span>
                    </div>
                    <div class="w-full h-3 bg-surface-container rounded-full overflow-hidden flex">
                        @if ($pctSMA > 0)
                            <div class="h-full bg-primary" style="width: {{ $pctSMA }}%" title="SMA KPS: {{ $pctSMA }}%"></div>
                        @endif
                        @if ($pctSMP > 0)
                            <div class="h-full bg-secondary" style="width: {{ $pctSMP }}%" title="SMP KPS: {{ $pctSMP }}%"></div>
                        @endif
                        @if ($pctSD > 0)
                            <div class="h-full bg-emerald-600" style="width: {{ $pctSD }}%" title="SD KPS: {{ $pctSD }}%"></div>
                        @endif
                        @if ($pctTK > 0)
                            <div class="h-full bg-amber-500" style="width: {{ $pctTK }}%" title="TK KPS: {{ $pctTK }}%"></div>
                        @endif
                    </div>
                </div>

                <!-- Itemized Breakdown Rows with Direct Filter Links -->
                <div class="space-y-3.5">
                    <!-- SMA KPS -->
                    <a href="{{ route('admin.alumni.index', ['level' => 'sma']) }}" 
                       class="block p-2.5 -mx-2.5 rounded-xl hover:bg-surface-container-low/60 transition-colors group">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-primary flex items-center gap-2 group-hover:text-secondary transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-primary shrink-0"></span>
                                <span>SMA Nasional KPS (SMA KPS)</span>
                            </span>
                            <span class="tabular-nums text-on-surface font-extrabold">
                                {{ $alumniDistribution['sma'] ?? 0 }} <span class="text-[11px] text-on-surface-variant font-normal">({{ $pctSMA }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-500" style="width: {{ $pctSMA }}%"></div>
                        </div>
                    </a>

                    <!-- SMP KPS -->
                    <a href="{{ route('admin.alumni.index', ['level' => 'smp']) }}" 
                       class="block p-2.5 -mx-2.5 rounded-xl hover:bg-surface-container-low/60 transition-colors group">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-secondary flex items-center gap-2 group-hover:text-primary transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-secondary shrink-0"></span>
                                <span>SMP Nasional KPS (SMP KPS)</span>
                            </span>
                            <span class="tabular-nums text-on-surface font-extrabold">
                                {{ $alumniDistribution['smp'] ?? 0 }} <span class="text-[11px] text-on-surface-variant font-normal">({{ $pctSMP }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-secondary rounded-full transition-all duration-500" style="width: {{ $pctSMP }}%"></div>
                        </div>
                    </a>

                    <!-- SD KPS -->
                    <a href="{{ route('admin.alumni.index', ['level' => 'sd']) }}" 
                       class="block p-2.5 -mx-2.5 rounded-xl hover:bg-surface-container-low/60 transition-colors group">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-emerald-700 flex items-center gap-2 group-hover:text-emerald-800 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 shrink-0"></span>
                                <span>SD Nasional KPS (SD KPS)</span>
                            </span>
                            <span class="tabular-nums text-on-surface font-extrabold">
                                {{ $alumniDistribution['sd'] ?? 0 }} <span class="text-[11px] text-on-surface-variant font-normal">({{ $pctSD }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-600 rounded-full transition-all duration-500" style="width: {{ $pctSD }}%"></div>
                        </div>
                    </a>

                    <!-- TK KPS -->
                    <a href="{{ route('admin.alumni.index', ['level' => 'tk']) }}" 
                       class="block p-2.5 -mx-2.5 rounded-xl hover:bg-surface-container-low/60 transition-colors group">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-amber-700 flex items-center gap-2 group-hover:text-amber-800 transition-colors">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span>TK Nasional KPS (TK KPS)</span>
                            </span>
                            <span class="tabular-nums text-on-surface font-extrabold">
                                {{ $alumniDistribution['tk'] ?? 0 }} <span class="text-[11px] text-on-surface-variant font-normal">({{ $pctTK }}%)</span>
                            </span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $pctTK }}%"></div>
                        </div>
                    </a>
                </div>

                <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs text-on-surface-variant font-semibold">
                    <span>Total Terdaftar</span>
                    <span class="font-extrabold text-primary tabular-nums">{{ number_format(array_sum($alumniDistribution)) }} Alumni</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten & Publikasi Terbaru -->
    @if(isset($latestArticles) && $latestArticles->isNotEmpty())
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="font-headline-sm text-sm font-bold text-primary uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[18px]">newspaper</span>
                        <span>Publikasi Berita &amp; Artikel Terkini</span>
                    </h2>
                    <p class="text-xs text-on-surface-variant">Update artikel dan kegiatan yang telah dipublikasikan di website.</p>
                </div>
                <a href="{{ route('admin.articles.index') }}" class="text-xs font-bold text-secondary hover:underline flex items-center gap-1">
                    <span>Kelola Semua Artikel</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($latestArticles as $article)
                    <div class="p-3.5 rounded-xl border border-outline-variant/30 bg-surface-container-low/40 flex flex-col justify-between hover:bg-surface-container-low transition-colors">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase tracking-wider {{ $article->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $article->status === 'published' ? 'Tayang' : 'Draft' }}
                                </span>
                                <span class="text-[11px] text-on-surface-variant">{{ $article->created_at ? $article->created_at->format('d M Y') : '-' }}</span>
                            </div>
                            <h3 class="text-xs font-bold text-primary line-clamp-2 leading-snug">
                                {{ $article->title }}
                            </h3>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-outline-variant/20 flex items-center justify-between text-xs">
                            <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">visibility</span>
                                <span>{{ $article->views_count ?? 0 }} views</span>
                            </span>
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="font-semibold text-secondary hover:underline text-[11px]">
                                Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
