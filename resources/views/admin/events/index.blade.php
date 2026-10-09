@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-6 sm:py-8 space-y-6 sm:space-y-8">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight">Manajemen Agenda &amp; Event</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola kalender temu kangen alumni, agenda reuni akbar, turnamen olahraga, dan registrasi kehadiran (RSVP).
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <a href="{{ route('event.index') }}" target="_blank"
               class="px-3.5 py-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center justify-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.events.create') }}" 
               class="px-4 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Buat Event Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-on-surface-variant font-medium block">Total Event</span>
            <span class="text-xl sm:text-2xl font-extrabold text-primary block mt-1">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-emerald-700 font-medium block">Dipublikasikan</span>
            <span class="text-xl sm:text-2xl font-extrabold text-emerald-600 block mt-1">{{ $stats['published'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-amber-700 font-medium block">Draft / Disimpan</span>
            <span class="text-xl sm:text-2xl font-extrabold text-amber-600 block mt-1">{{ $stats['draft'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
            <span class="text-2xs sm:text-xs text-secondary font-medium block">Total Peserta RSVP</span>
            <span class="text-xl sm:text-2xl font-extrabold text-secondary block mt-1">{{ number_format($stats['total_participants']) }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-3.5 sm:p-4 shadow-xs">
        <form action="{{ route('admin.events.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2.5 w-full sm:w-auto">
                <div class="relative group w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[16px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full pl-8 pr-7 py-2 appearance-none rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>

                <div class="relative group w-full sm:w-auto">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[16px] pointer-events-none group-focus-within:text-secondary transition-colors">category</span>
                    <select name="category" onchange="this.form.submit()" 
                            class="w-full pl-8 pr-7 py-2 appearance-none rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                        <option value="">Semua Kategori</option>
                        @foreach(['reuni', 'baksos', 'seminar', 'olahraga', 'lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[16px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                </div>
            </div>

            <div class="relative w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul atau lokasi..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
            </div>
        </form>
    </div>

    <!-- Table of Events -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/30 text-primary font-bold">
                        <th class="py-3.5 px-4 w-16">Cover</th>
                        <th class="py-3.5 px-4">Judul &amp; Lokasi</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Waktu Pelaksanaan</th>
                        <th class="py-3.5 px-4 text-center">RSVP / Kuota</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($events as $event)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <!-- Thumbnail -->
                            <td class="py-3 px-4">
                                @if ($event->image_url)
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" 
                                         class="w-12 h-12 rounded-xl object-cover border border-outline-variant/40 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-outline-variant shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Title & Location -->
                            <td class="py-3 px-4 max-w-xs sm:max-w-sm">
                                <a href="{{ route('event.show', $event->slug) }}" target="_blank" 
                                   class="font-bold text-primary hover:text-secondary transition-colors line-clamp-1 block">
                                    {{ $event->title }}
                                </a>
                                <p class="text-2xs text-on-surface-variant flex items-center gap-1 mt-0.5 truncate">
                                    <span class="material-symbols-outlined text-[13px] shrink-0">location_on</span>
                                    <span class="truncate">{{ $event->location }}</span>
                                </p>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold uppercase bg-secondary/10 text-secondary border border-secondary/20">
                                    {{ ucfirst($event->category) }}
                                </span>
                            </td>

                            <!-- Date & Time -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-semibold text-primary">
                                    {{ $event->start_date->isoFormat('D MMMM Y') }}
                                </div>
                                <div class="text-2xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[12px]">schedule</span>
                                    <span>{{ $event->start_date->format('H:i') }} WITA</span>
                                </div>
                            </td>

                            <!-- RSVP / Quota -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.events.participants', $event->id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors text-primary font-bold">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">group</span>
                                    <span>{{ $event->registrations_count }}</span>
                                    <span class="text-on-surface-variant font-normal">/ {{ $event->max_participants ?: '∞' }}</span>
                                </a>
                            </td>

                            <!-- Status & Quick Toggle -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if ($event->status === 'published')
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                            Terbit
                                        </span>
                                    @elseif ($event->status === 'draft')
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                            Draft
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-rose-500/10 text-rose-700 border border-rose-500/20">
                                            Dibatalkan
                                        </span>
                                    @endif

                                    <!-- Quick Toggle Form -->
                                    <form method="POST" action="{{ route('admin.events.toggle-status', $event->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="{{ $event->status === 'published' ? 'Tarik ke Draft' : 'Terbitkan Sekarang' }}"
                                                class="p-1 rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-primary transition-colors cursor-pointer">
                                            <span class="material-symbols-outlined text-[16px]">
                                                {{ $event->status === 'published' ? 'visibility_off' : 'visibility' }}
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.events.participants', $event->id) }}" 
                                       title="Daftar Peserta RSVP"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-secondary hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->id) }}" 
                                       title="Edit Event"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda kegiatan ini?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Event"
                                                class="p-1.5 rounded-lg text-on-surface-variant hover:text-rose-600 hover:bg-surface-container transition-colors cursor-pointer">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">event_busy</span>
                                    <p class="font-medium text-xs">Belum ada agenda kegiatan yang sesuai kriteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
