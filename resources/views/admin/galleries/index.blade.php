@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Manajemen Galeri &amp; Dokumentasi Foto</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola arsip kenangan sejarah sekolah tempo doeloe, dokumentasi reuni akbar, dan liputan foto kegiatan alumni.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('gallery.index') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.galleries.create') }}" 
               class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_photo_alternate</span>
                <span>Buat Album Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-on-surface-variant font-medium block">Total Album</span>
            <span class="text-2xl font-extrabold text-primary block mt-1">{{ $stats['total'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-emerald-700 font-medium block">Dipublikasikan</span>
            <span class="text-2xl font-extrabold text-emerald-600 block mt-1">{{ $stats['published'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-amber-700 font-medium block">Draft / Disimpan</span>
            <span class="text-2xl font-extrabold text-amber-600 block mt-1">{{ $stats['draft'] }}</span>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-secondary font-medium block">Total Arsip Foto</span>
            <span class="text-2xl font-extrabold text-secondary block mt-1">{{ number_format($stats['total_photos']) }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
        <form action="{{ route('admin.galleries.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="flex items-center gap-2 w-full sm:w-auto flex-wrap">
                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/50">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>

                <select name="category" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/50">
                    <option value="">Semua Kategori</option>
                    @foreach(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial', 'Lainnya'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul album atau deskripsi..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
            </div>
        </form>
    </div>

    <!-- Table of Galleries -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/30 text-primary font-bold">
                        <th class="py-3.5 px-4 w-16">Cover</th>
                        <th class="py-3.5 px-4">Judul Album</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Tanggal Arsip</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Foto</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($galleries as $gallery)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <!-- Cover -->
                            <td class="py-3 px-4">
                                @if ($gallery->cover_image_url)
                                    <img src="{{ $gallery->cover_image_url }}" alt="{{ $gallery->title }}" 
                                         class="w-12 h-12 rounded-xl object-cover border border-outline-variant/40 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-outline-variant shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">collections</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Title -->
                            <td class="py-3 px-4 max-w-xs sm:max-w-sm">
                                <a href="{{ route('gallery.show', $gallery->slug) }}" target="_blank" 
                                   class="font-bold text-primary hover:text-secondary transition-colors line-clamp-1 block">
                                    {{ $gallery->title }}
                                </a>
                                @if ($gallery->description)
                                    <p class="text-2xs text-on-surface-variant line-clamp-1 mt-0.5">
                                        {{ $gallery->description }}
                                    </p>
                                @endif
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold uppercase bg-secondary/10 text-secondary border border-secondary/20">
                                    {{ $gallery->category ?? 'Galeri' }}
                                </span>
                            </td>

                            <!-- Date -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if ($gallery->event_date)
                                    <span class="font-semibold text-primary">
                                        {{ $gallery->event_date->isoFormat('D MMMM Y') }}
                                    </span>
                                @else
                                    <span class="text-on-surface-variant text-2xs">-</span>
                                @endif
                            </td>

                            <!-- Photos count -->
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.galleries.edit', $gallery->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors text-primary font-bold">
                                    <span class="material-symbols-outlined text-[15px] text-secondary">photo_camera</span>
                                    <span>{{ $gallery->photos_count }} Foto</span>
                                </a>
                            </td>

                            <!-- Status & Quick Toggle -->
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if ($gallery->status === 'published')
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20">
                                            Terbit
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-2xs font-bold bg-amber-500/10 text-amber-700 border border-amber-500/20">
                                            Draft
                                        </span>
                                    @endif

                                    <!-- Quick Toggle Form -->
                                    <form method="POST" action="{{ route('admin.galleries.toggle-status', $gallery->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="{{ $gallery->status === 'published' ? 'Tarik ke Draft' : 'Terbitkan Sekarang' }}"
                                                class="p-1 rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined text-[16px]">
                                                {{ $gallery->status === 'published' ? 'visibility_off' : 'visibility' }}
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('gallery.show', $gallery->slug) }}" target="_blank" 
                                       title="Lihat Publik"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.galleries.edit', $gallery->id) }}" 
                                       title="Kelola Foto &amp; Edit Album"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.galleries.destroy', $gallery->id) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus album ini beserta seluruh fotonya?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Hapus Album"
                                                class="p-1.5 rounded-lg text-on-surface-variant hover:text-rose-600 hover:bg-surface-container transition-colors">
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
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">photo_library</span>
                                    <p class="font-medium text-xs">Belum ada album galeri yang sesuai kriteria.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($galleries->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $galleries->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
