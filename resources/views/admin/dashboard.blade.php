@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-8 space-y-8">
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-primary via-primary-container to-secondary rounded-3xl p-6 sm:p-8 text-white relative overflow-hidden shadow-md">
        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-widest bg-white/20 text-white backdrop-blur-xs inline-block mb-3">
                Dashboard Pengurus &amp; Admin — IKA KPS
            </span>
            <h1 class="font-headline-sm text-2xl sm:text-3xl font-extrabold leading-tight">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-white/80 mt-2 leading-relaxed">
                Ini adalah pusat kontrol operasional portal IKA KPS. Kelola verifikasi keanggotaan alumni baru, pantau direktori, dan publikasikan konten untuk seluruh alumni di Indonesia dan mancanegara.
            </p>
        </div>
        <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
    </div>

    <!-- Grid Metrik Utama -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Alumni Terverifikasi -->
        <a href="{{ route('admin.alumni.index') }}" class="block bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-sm hover:border-primary/50 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider group-hover:text-primary transition-colors">Alumni Terverifikasi</span>
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-primary mt-3">{{ $metrics['total_alumni'] ?? 0 }}</div>
            <p class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1">
                <span>Tayang di direktori publik</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </p>
        </a>

        <!-- Pendaftaran Pending -->
        <a href="{{ route('admin.verification.index') }}" class="block bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-sm hover:border-amber-500/50 transition-all group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider group-hover:text-amber-600 transition-colors">Pendaftaran Pending</span>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">pending_actions</span>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-amber-600 mt-3">{{ $metrics['pending_registrations'] ?? 0 }}</div>
            <p class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1">
                <span>Menunggu tinjauan pengurus</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </p>
        </a>

        <!-- Total Akun Pengguna -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Akun Pengguna</span>
                <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">manage_accounts</span>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-primary mt-3">{{ $metrics['total_users'] ?? 0 }}</div>
            <p class="text-[11px] text-on-surface-variant mt-1">Akun terdaftar dalam sistem</p>
        </div>

        <!-- Bisnis & Karir Alumni -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Bisnis &amp; Karir</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">work</span>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-primary mt-3 flex items-center gap-2">
                <span>{{ $metrics['active_businesses'] ?? 0 }}</span>
                <span class="text-xs font-normal text-on-surface-variant">Bisnis /</span>
                <span>{{ $metrics['active_jobs'] ?? 0 }}</span>
                <span class="text-xs font-normal text-on-surface-variant">Loker</span>
            </div>
            <p class="text-[11px] text-on-surface-variant mt-1">Peluang jejaring alumni</p>
        </div>
    </div>

    <!-- Dua Kolom: Quick Review & Distribusi Jenjang -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Antrean Pendaftaran Terbaru (2/3) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-600 text-[22px]">pending_actions</span>
                        <span>Antrean Pendaftaran Terbaru</span>
                    </h2>
                    <p class="text-xs text-on-surface-variant">Permohonan keanggotaan baru yang membutuhkan persetujuan.</p>
                </div>
                <a href="{{ route('admin.verification.index') }}" class="text-xs font-bold text-secondary hover:underline flex items-center gap-1">
                    <span>Lihat Semua ({{ $metrics['pending_registrations'] ?? 0 }})</span>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                </a>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
                @if ($latestPendingAlumni->isNotEmpty())
                    <div class="divide-y divide-outline-variant/20">
                        @foreach ($latestPendingAlumni as $user)
                            @php $alumnus = $user->alumnus; @endphp
                            <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-surface-container-low/40 transition-colors">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002D62&color=fff&size=60' }}" 
                                         alt="{{ $user->name }}" 
                                         class="w-10 h-10 rounded-xl object-cover ring-1 ring-outline-variant shrink-0 bg-surface-container">
                                    <div class="min-w-0">
                                        <div class="font-bold text-xs text-primary truncate flex items-center gap-1.5">
                                            <span>{{ $user->name }}</span>
                                            @if ($alumnus?->level)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-surface-container text-primary">
                                                    {{ strtoupper($alumnus->level) }} {{ $alumnus->full_year ? "'".substr($alumnus->full_year, -2) : '' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant truncate">
                                            {{ $user->email }} • {{ $user->created_at ? $user->created_at->diffForHumans() : '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-end sm:self-center">
                                    @if ($alumnus)
                                        <form action="{{ route('admin.verification.approve', $alumnus->id) }}" method="POST" 
                                              onsubmit="return confirm('Setujui keanggotaan {{ addslashes($user->name) }}?')">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1.5 rounded-xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition-all flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-[16px]">check</span>
                                                <span>Setujui</span>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.verification.index', ['status' => 'pending']) }}" 
                                           class="px-3 py-1.5 rounded-xl border border-outline-variant text-on-surface hover:bg-surface-container font-semibold text-xs transition-all">
                                            Tinjau
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-10 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-3xl text-emerald-500 mb-1 block">task_alt</span>
                        <p class="text-xs font-bold text-on-surface">Tidak ada antrean pendaftaran tertunda!</p>
                        <p class="text-[11px] text-on-surface-variant/80 mt-0.5">Semua permohonan keanggotaan alumni telah disetujui.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan: Distribusi Jenjang (1/3) -->
        <div class="space-y-4">
            <div>
                <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[22px]">school</span>
                    <span>Distribusi Alumni KPS</span>
                </h2>
                <p class="text-xs text-on-surface-variant">Persebaran alumni terdaftar per jenjang sekolah.</p>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-5 shadow-sm space-y-4">
                @php
                    $totalDist = max(array_sum($alumniDistribution), 1);
                @endphp

                <!-- SMA -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-primary">SMA Nasional KPS (SMA KPS)</span>
                        <span class="text-on-surface">{{ $alumniDistribution['sma'] ?? 0 }} alumni</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full transition-all duration-500" 
                             style="width: {{ round((($alumniDistribution['sma'] ?? 0) / $totalDist) * 100) }}%"></div>
                    </div>
                </div>

                <!-- SMP -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-secondary">SMP Nasional KPS (SMP KPS)</span>
                        <span class="text-on-surface">{{ $alumniDistribution['smp'] ?? 0 }} alumni</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-secondary rounded-full transition-all duration-500" 
                             style="width: {{ round((($alumniDistribution['smp'] ?? 0) / $totalDist) * 100) }}%"></div>
                    </div>
                </div>

                <!-- SD -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-emerald-700">SD Nasional KPS (SD KPS)</span>
                        <span class="text-on-surface">{{ $alumniDistribution['sd'] ?? 0 }} alumni</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-600 rounded-full transition-all duration-500" 
                             style="width: {{ round((($alumniDistribution['sd'] ?? 0) / $totalDist) * 100) }}%"></div>
                    </div>
                </div>

                <!-- TK -->
                <div>
                    <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                        <span class="text-amber-700">TK Nasional KPS (TK KPS)</span>
                        <span class="text-on-surface">{{ $alumniDistribution['tk'] ?? 0 }} alumni</span>
                    </div>
                    <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full transition-all duration-500" 
                             style="width: {{ round((($alumniDistribution['tk'] ?? 0) / $totalDist) * 100) }}%"></div>
                    </div>
                </div>

                <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-between text-xs text-on-surface-variant font-semibold">
                    <span>Total Keseluruhan</span>
                    <span class="font-extrabold text-primary">{{ array_sum($alumniDistribution) }} Alumni</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
