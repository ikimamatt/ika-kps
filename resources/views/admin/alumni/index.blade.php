@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
    <!-- Top Admin Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                <span>Area Administrasi IKA KPS</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Manajemen Data Alumni</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Kelola direktori publik alumni, verifikasi identitas, dan perbarui data keanggotaan.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Dashboard Overview
            </a>
            <a href="{{ route('admin.alumni.create') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Tambah Alumni</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filter Card -->
    <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('admin.alumni.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, profesi..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="relative w-full sm:w-48 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">school</span>
                    <select name="level" class="w-full pl-9 pr-8 py-2 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Jenjang</option>
                        <option value="sma" {{ request('level') === 'sma' ? 'selected' : '' }}>SMA KPS</option>
                        <option value="smp" {{ request('level') === 'smp' ? 'selected' : '' }}>SMP KPS</option>
                        <option value="sd" {{ request('level') === 'sd' ? 'selected' : '' }}>SD KPS</option>
                        <option value="tk" {{ request('level') === 'tk' ? 'selected' : '' }}>TK KPS</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>

                <div class="relative w-full sm:w-48 group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                    <select name="status" class="w-full pl-9 pr-8 py-2 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Belum Terverifikasi</option>
                        <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Arsip Terhapus</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="px-5 py-2 rounded-xl bg-secondary text-on-secondary font-bold text-xs hover:bg-secondary/90 transition-all">
                    Filter
                </button>
                @if (request()->hasAny(['q', 'level', 'status']))
                    <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-on-surface-variant font-semibold text-xs hover:bg-surface-container-high transition-all">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40 text-on-surface-variant text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Alumni</th>
                        <th class="py-3.5 px-4">Jenjang &amp; Angkatan</th>
                        <th class="py-3.5 px-4">Profesi / Instansi</th>
                        <th class="py-3.5 px-4">Kontak</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($alumni as $item)
                        <tr class="hover:bg-surface-container-low/50 transition-colors {{ $item->trashed() ? 'bg-rose-50/30 opacity-70' : '' }}">
                            <!-- Alumni Identity -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $item->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($item->name) . '&background=002D62&color=fff&size=100' }}" 
                                         alt="{{ $item->name }}" 
                                         class="w-10 h-10 rounded-xl object-cover shrink-0 bg-surface-container-high">
                                    <div>
                                        <div class="font-bold text-primary hover:text-secondary">
                                            <a href="{{ route('alumni.show', $item->slug) }}" target="_blank">
                                                {{ $item->name }}{{ $item->title ? ', '.$item->title : '' }}
                                            </a>
                                        </div>
                                        <span class="text-[11px] text-on-surface-variant">{{ $item->domicile ?? 'Balikpapan' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Jenjang & Angkatan -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-primary uppercase">{{ $item->level }}</span>
                                <span class="text-on-surface-variant text-[11px] block">Angkatan {{ $item->full_year ?? ($item->class_year ?? '-') }}</span>
                            </td>

                            <!-- Profesi -->
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-on-surface block">{{ $item->profession ?? '-' }}</span>
                                <span class="text-[11px] text-on-surface-variant block">{{ $item->institution ?? '-' }}</span>
                            </td>

                            <!-- Kontak -->
                            <td class="py-3.5 px-4">
                                <span class="block text-on-surface text-xs">{{ $item->email ?? '-' }}</span>
                                <span class="block text-on-surface-variant text-[11px]">{{ $item->phone ?? '-' }}</span>
                            </td>

                            <!-- Status Verifikasi -->
                            <td class="py-3.5 px-4 text-center">
                                @if ($item->trashed())
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                                        Terhapus
                                    </span>
                                @elseif ($item->is_verified)
                                    <form action="{{ route('admin.alumni.toggle-verification', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Klik untuk batalkan verifikasi" 
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 hover:bg-rose-100 hover:text-rose-800 transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">verified</span>
                                            Terverifikasi
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.alumni.toggle-verification', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Klik untuk verifikasi alumni ini" 
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 hover:bg-emerald-100 hover:text-emerald-800 transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">hourglass_top</span>
                                            Belum Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if ($item->trashed())
                                        <form action="{{ route('admin.alumni.restore', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold text-xs transition-colors flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[16px]">restore_from_trash</span>
                                                Pulihkan
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.alumni.edit', $item->id) }}" 
                                           class="w-8 h-8 rounded-lg bg-surface-container hover:bg-primary hover:text-white text-on-surface-variant flex items-center justify-center transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </a>

                                        <form action="{{ route('admin.alumni.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data alumni ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-surface-container hover:bg-rose-600 hover:text-white text-on-surface-variant flex items-center justify-center transition-colors" title="Hapus">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl text-on-surface-variant/40 block mb-2">person_search</span>
                                Tidak ada data alumni yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-outline-variant/30">
            {{ $alumni->links() }}
        </div>
    </div>
</div>
@endsection
