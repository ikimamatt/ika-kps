@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <span class="material-symbols-outlined text-[16px]">work</span>
                <span>Area Administrasi IKA KPS</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Kelola Bursa Kerja &amp; Lowongan</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Kurasi dan verifikasi postingan lowongan kerja dari alumni dan korporasi mitra.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Dashboard Overview
            </a>
            <a href="{{ route('admin.job-vacancies.create') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Lowongan</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Metrics Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block">Total Lowongan</span>
            <span class="font-headline-sm text-2xl font-extrabold text-primary block mt-1">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-amber-200/60 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold text-amber-800 uppercase tracking-wider block">Menunggu Review</span>
            <span class="font-headline-sm text-2xl font-extrabold text-amber-600 block mt-1">{{ $stats['pending'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-emerald-200/60 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider block">Aktif Tayang</span>
            <span class="font-headline-sm text-2xl font-extrabold text-emerald-600 block mt-1">{{ $stats['active'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider block">Ditutup / Expired</span>
            <span class="font-headline-sm text-2xl font-extrabold text-slate-600 block mt-1">{{ $stats['closed'] }}</span>
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.job-vacancies.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari posisi, perusahaan, atau kota..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="relative w-full sm:w-52 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                    <select name="status" onchange="this.form.submit()"
                            class="w-full pl-9 pr-8 py-2 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review ({{ $stats['pending'] }})</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Tayang ({{ $stats['active'] }})</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.job-vacancies.index') }}" class="px-3 py-2 rounded-xl border border-outline-variant text-xs font-semibold text-on-surface-variant hover:bg-surface-container transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
        @if ($vacancies->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-surface-container-low border-b border-outline-variant/40 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Posisi &amp; Perusahaan</th>
                            <th class="py-3 px-4">Tipe &amp; Lokasi</th>
                            <th class="py-3 px-4">Referensi Alumni</th>
                            <th class="py-3 px-4">Batas Lamaran</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/20">
                        @foreach ($vacancies as $job)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-primary">{{ $job->title }}</div>
                                    <div class="text-xs text-secondary font-medium">{{ $job->company }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $job->getBadgeClass() }}">
                                        {{ $job->job_type }}
                                    </span>
                                    <div class="text-xs text-on-surface-variant mt-0.5">{{ $job->location }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-on-surface-variant max-w-[200px] truncate">
                                    {{ $job->alumni_info ?? $job->company_alumni_info ?? '-' }}
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    @if ($job->deadline)
                                        <span class="{{ $job->deadline->isPast() ? 'text-rose-600 font-bold' : 'text-on-surface-variant' }}">
                                            {{ $job->deadline->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-on-surface-variant/70">Tanpa Batas</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if ($job->status === 'active')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
                                    @elseif ($job->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                                            Pending
                                        </span>
                                    @elseif ($job->status === 'closed')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                            Ditutup
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Quick Status Toggle -->
                                        @if ($job->status === 'pending')
                                            <form action="{{ route('admin.job-vacancies.toggle-status', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" title="Setujui &amp; Aktifkan Loker"
                                                        class="px-2.5 py-1 rounded-lg bg-emerald-600 text-white font-bold text-[11px] hover:bg-emerald-700 transition-colors flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                                    <span>Setujui</span>
                                                </button>
                                            </form>
                                        @elseif ($job->status === 'active')
                                            <form action="{{ route('admin.job-vacancies.toggle-status', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="closed">
                                                <button type="submit" title="Tutup Loker"
                                                        class="px-2 py-1 rounded-lg border border-outline-variant text-[11px] font-bold text-on-surface-variant hover:bg-surface-container transition-colors">
                                                    Tutup
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.job-vacancies.toggle-status', $job->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" title="Buka Kembali Loker"
                                                        class="px-2 py-1 rounded-lg bg-primary/10 text-primary text-[11px] font-bold hover:bg-primary/20 transition-colors">
                                                    Buka
                                                </button>
                                            </form>
                                        @endif

                                        @if ($job->status === 'active')
                                            <a href="{{ route('career.show', $job->slug) }}" target="_blank" title="Lihat Tampilan Publik"
                                               class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.job-vacancies.edit', $job->id) }}" title="Edit Loker"
                                           class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>

                                        <form action="{{ route('admin.job-vacancies.destroy', $job->id) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Loker"
                                                    class="p-1.5 rounded-lg text-on-surface-variant hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($vacancies->hasPages())
                <div class="p-4 border-t border-outline-variant/30 flex justify-center">
                    {{ $vacancies->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16 px-4 text-on-surface-variant">
                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">work_off</span>
                <p class="font-bold text-primary">Tidak ada lowongan kerja ditemukan.</p>
                <p class="text-xs text-on-surface-variant mt-1">Coba gunakan filter lain atau tambahkan lowongan baru.</p>
            </div>
        @endif
    </div>
</div>
@endsection
