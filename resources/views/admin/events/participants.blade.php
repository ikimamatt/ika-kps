@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.events.index') }}" class="hover:text-primary transition-colors">Agenda &amp; Event</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Peserta RSVP</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">
                Daftar Peserta RSVP: {{ $event->title }}
            </h1>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" 
                    class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">print</span>
                <span>Cetak Lembar Kehadiran</span>
            </button>
            <a href="{{ route('admin.events.index') }}" 
               class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Event Summary Card -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-xs">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="space-y-1">
                <span class="text-2xs font-bold uppercase text-on-surface-variant block">Jadwal &amp; Waktu</span>
                <span class="text-sm font-bold text-primary block">
                    {{ $event->start_date->isoFormat('dddd, D MMMM Y') }}
                </span>
                <span class="text-2xs text-on-surface-variant block">
                    {{ $event->start_date->format('H:i') }} WITA
                </span>
            </div>

            <div class="space-y-1">
                <span class="text-2xs font-bold uppercase text-on-surface-variant block">Tempat Pelaksanaan</span>
                <span class="text-sm font-bold text-primary block truncate" title="{{ $event->location }}">
                    {{ $event->location }}
                </span>
            </div>

            <div class="space-y-1">
                <span class="text-2xs font-bold uppercase text-on-surface-variant block">Status &amp; Kategori</span>
                <div class="flex items-center gap-2 pt-0.5">
                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-extrabold uppercase bg-secondary/10 text-secondary border border-secondary/20">
                        {{ ucfirst($event->category) }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold {{ $event->status === 'published' ? 'bg-emerald-500/10 text-emerald-700' : 'bg-amber-500/10 text-amber-700' }}">
                        {{ ucfirst($event->status) }}
                    </span>
                </div>
            </div>

            <div class="space-y-1">
                <span class="text-2xs font-bold uppercase text-on-surface-variant block">Total Terdaftar / Kuota</span>
                <span class="text-lg font-extrabold text-primary block">
                    {{ $participants->total() }} <span class="text-xs font-normal text-on-surface-variant">/ {{ $event->max_participants ?: 'Tak Terbatas' }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[20px]">how_to_reg</span>
                <h3 class="text-xs sm:text-sm font-bold text-primary">Daftar Kehadiran Alumni Terkonfirmasi</h3>
            </div>
            <span class="text-xs text-on-surface-variant font-medium">
                Menampilkan {{ $participants->count() }} dari {{ $participants->total() }} peserta
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/30 text-primary font-bold">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Alumni</th>
                        <th class="py-3 px-4">Angkatan</th>
                        <th class="py-3 px-4">Kontak (Email / No. HP)</th>
                        <th class="py-3 px-4">Tanggal RSVP</th>
                        <th class="py-3 px-4">Catatan Hadir</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($participants as $index => $item)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="py-3 px-4 text-center font-medium text-on-surface-variant">
                                {{ $participants->firstItem() + $index }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-secondary/15 text-secondary font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-primary">{{ $item->user->name }}</p>
                                        @if ($item->user->alumnus?->profession)
                                            <p class="text-2xs text-on-surface-variant">{{ $item->user->alumnus->profession }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if ($item->user->alumnus?->graduation_year)
                                    <span class="px-2 py-0.5 rounded-md bg-surface-container text-2xs font-bold text-primary">
                                        {{ $item->user->alumnus->graduation_year }}
                                    </span>
                                @else
                                    <span class="text-2xs text-on-surface-variant">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <p class="text-2xs text-primary">{{ $item->user->email }}</p>
                                @if ($item->user->alumnus?->phone)
                                    <p class="text-2xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[12px]">call</span>
                                        <span>{{ $item->user->alumnus->phone }}</span>
                                    </p>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="text-2xs text-primary font-semibold">
                                    {{ $item->registered_at->isoFormat('D MMM Y, H:i') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                @if ($item->notes)
                                    <p class="text-2xs text-on-surface-variant italic">&ldquo;{{ $item->notes }}&rdquo;</p>
                                @else
                                    <span class="text-2xs text-on-surface-variant/60">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-2xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">person_off</span>
                                    <p class="font-medium text-xs">Belum ada peserta yang mengonfirmasi kehadiran (RSVP) pada kegiatan ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($participants->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $participants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
