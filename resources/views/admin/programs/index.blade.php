@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight">Manajemen Program Kerja</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola program kerja inisiatif organisasi, pantau progres capaian, dan pencatatan dana terkumpul.
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <a href="{{ route('program.index') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.programs.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tambah Program</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-on-surface-variant font-medium block">Total Program</span>
            <span class="text-xl sm:text-2xl font-extrabold text-primary block mt-1">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-emerald-700 font-medium block">Aktif Berjalan</span>
            <span class="text-xl sm:text-2xl font-extrabold text-emerald-600 block mt-1">{{ $stats['active'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-amber-700 font-medium block">Segera Dimulai</span>
            <span class="text-xl sm:text-2xl font-extrabold text-amber-600 block mt-1">{{ $stats['upcoming'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-sky-700 font-medium block">Tuntas Terlaksana</span>
            <span class="text-xl sm:text-2xl font-extrabold text-sky-600 block mt-1">{{ $stats['completed'] }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
        <form action="{{ route('admin.programs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative group w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[16px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full pl-8 pr-7 py-2 appearance-none rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Berjalan</option>
                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Segera Dimulai</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai / Tuntas</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>
            </div>

            <div class="relative w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama program..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                <span class="material-symbols-outlined text-outline absolute left-2.5 top-1/2 -translate-y-1/2 text-[18px]">search</span>
            </div>
        </form>
    </div>

    <!-- Table of Programs -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[700px]">
                <thead class="bg-surface-container-low text-primary uppercase text-[10px] font-bold tracking-wider border-b border-outline-variant/30">
                    <tr>
                        <th class="px-5 py-3.5">Program Kerja</th>
                        <th class="px-5 py-3.5">Status</th>
                        <th class="px-5 py-3.5">Progres Capaian</th>
                        <th class="px-5 py-3.5">Dana Terkumpul</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-on-surface">
                    @forelse($programs as $prog)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $prog->icon_bg_class ?: 'bg-primary text-white' }} flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">{{ $prog->icon ?: 'school' }}</span>
                                    </div>
                                    <div class="space-y-0.5">
                                        <span class="font-bold text-primary block text-sm">{{ $prog->title }}</span>
                                        <span class="text-on-surface-variant block text-[11px] line-clamp-1 max-w-sm">{{ $prog->description }}</span>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                @if($prog->status === 'active')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                                        Aktif
                                    </span>
                                @elseif($prog->status === 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-sky-100 text-sky-800">
                                        Tuntas
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800">
                                        Segera
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <div class="w-36 space-y-1.5">
                                    <div class="flex justify-between items-center text-[11px]">
                                        <span class="text-on-surface-variant font-medium">Progres:</span>
                                        <span class="font-bold text-primary">{{ $prog->progress_percent }}%</span>
                                    </div>
                                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                                        <div class="h-full {{ $prog->bar_color_class ?: 'bg-secondary' }} rounded-full" 
                                             style="width: {{ min(100, $prog->progress_percent) }}%;"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="space-y-0.5">
                                    <span class="font-bold text-primary block">
                                        Rp {{ number_format($prog->collected_amount, 0, ',', '.') }}
                                    </span>
                                    @if($prog->target_amount)
                                        <span class="text-on-surface-variant text-[11px] block">
                                            dari Rp {{ number_format($prog->target_amount, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('program.show', $prog->slug) }}" target="_blank" title="Lihat di Web"
                                       class="p-1.5 rounded-lg bg-surface-container-low text-on-surface-variant hover:text-primary hover:bg-surface-container transition-all">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.programs.edit', $prog) }}" title="Edit Program"
                                       class="p-1.5 rounded-lg bg-secondary/10 text-secondary hover:bg-secondary hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.programs.destroy', $prog) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus program kerja ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Program"
                                                class="p-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all cursor-pointer">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant space-y-2">
                                <span class="material-symbols-outlined text-4xl text-outline mb-1 block">assignment_late</span>
                                <p class="text-sm font-bold text-primary">Belum ada data program kerja.</p>
                                <p class="text-xs">Klik tombol "Tambah Program" di pojok kanan atas untuk mempublikasikan kegiatan baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($programs->hasPages())
            <div class="p-4 border-t border-outline-variant/30 flex justify-center">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
