@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Manajemen Berita &amp; Artikel</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola rilis berita, catatan nostalgia masa sekolah, liputan kegiatan, dan dokumentasi prestasi alumni.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('article.index') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.articles.create') }}" 
               class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Tulis Artikel Baru</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-on-surface-variant font-medium block">Total Tulisan</span>
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
            <span class="text-xs text-secondary font-medium block">Total Pembaca</span>
            <span class="text-2xl font-extrabold text-secondary block mt-1">{{ number_format($stats['total_views']) }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
        <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
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
                    @foreach(['Kegiatan', 'Nostalgia', 'Prestasi', 'Sosial', 'Opini', 'Pengumuman'] as $c)
                        <option value="{{ $c }}" {{ request('category') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative w-full sm:w-72">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari judul atau isi artikel..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
            </div>
        </form>
    </div>

    <!-- Table of Articles -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/30 text-primary font-bold">
                        <th class="py-3.5 px-4 w-16">Cover</th>
                        <th class="py-3.5 px-4">Judul &amp; Ringkasan</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Penulis &amp; Tanggal</th>
                        <th class="py-3.5 px-4 text-center">Dibaca</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($articles as $art)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="py-3 px-4">
                                <div class="w-12 h-12 rounded-xl bg-surface-container overflow-hidden border border-outline-variant/30 shrink-0">
                                    <img src="{{ $art->image_url ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=200' }}" 
                                         alt="{{ $art->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td class="py-3 px-4 max-w-xs">
                                <span class="font-bold text-primary block truncate text-sm hover:text-secondary">
                                    {{ $art->title }}
                                </span>
                                <span class="text-[11px] text-on-surface-variant line-clamp-1 mt-0.5">
                                    {{ $art->summary }}
                                </span>
                                <span class="text-[10px] text-on-surface-variant/60 font-mono block mt-0.5 truncate">
                                    /artikel/{{ $art->slug }}
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold {{ $art->badge_bg_class ?: 'bg-secondary text-white' }}">
                                    {{ $art->category }}
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <form action="{{ route('admin.articles.toggle-status', $art->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    @if ($art->status === 'published')
                                        <button type="submit" 
                                                title="Klik untuk menarik kembali ke Draft"
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-amber-50 hover:text-amber-800 hover:border-amber-300 transition-all cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 group-hover:bg-amber-500 transition-colors"></span>
                                            <span class="group-hover:hidden">Terbit</span>
                                            <span class="hidden group-hover:inline">Tarik ke Draft</span>
                                            <span class="material-symbols-outlined text-[13px] opacity-50 group-hover:opacity-100">sync</span>
                                        </button>
                                    @else
                                        <button type="submit" 
                                                title="Klik untuk menerbitkan artikel ini ke publik"
                                                class="group inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-300 transition-all cursor-pointer">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 group-hover:bg-emerald-500 transition-colors"></span>
                                            <span class="group-hover:hidden">Draft</span>
                                            <span class="hidden group-hover:inline">Terbitkan</span>
                                            <span class="material-symbols-outlined text-[13px] opacity-50 group-hover:opacity-100">publish</span>
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-medium text-primary block">
                                    {{ $art->author_and_date ? Str::before($art->author_and_date, '·') : ($art->author->name ?? 'Redaksi') }}
                                </span>
                                <span class="text-[10px] text-on-surface-variant">
                                    {{ $art->published_at ? $art->published_at->translatedFormat('d M Y') : 'Belum dirilis' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap font-semibold text-secondary">
                                {{ number_format($art->views_count) }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Toggle Status Button -->
                                    <form action="{{ route('admin.articles.toggle-status', $art->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        @if ($art->status === 'published')
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition-all cursor-pointer" 
                                                    title="Tarik ke Draft (Sembunyikan dari Publik)">
                                                <span class="material-symbols-outlined text-[18px]">unpublished</span>
                                            </button>
                                        @else
                                            <button type="submit" 
                                                    class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-all cursor-pointer" 
                                                    title="Publikasikan Artikel Sekarang">
                                                <span class="material-symbols-outlined text-[18px]">publish</span>
                                            </button>
                                        @endif
                                    </form>

                                    @if($art->status === 'published')
                                        <a href="{{ route('article.show', $art->slug) }}" target="_blank"
                                           class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-all" title="Lihat di Web Publik">
                                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.articles.edit', $art->id) }}" 
                                       class="p-1.5 rounded-lg text-primary hover:bg-surface-container transition-all" title="Edit Artikel">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $art->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Data akan dipindahkan ke tempat sampah.')"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition-all cursor-pointer" title="Hapus Artikel">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-on-surface-variant/40">newspaper</span>
                                    <span class="font-semibold">Belum ada artikel / berita yang tercatat.</span>
                                    <a href="{{ route('admin.articles.create') }}" class="text-secondary font-bold hover:underline text-xs mt-1">
                                        Tulis artikel pertama sekarang &rarr;
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($articles->hasPages())
            <div class="p-4 border-t border-outline-variant/20">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
