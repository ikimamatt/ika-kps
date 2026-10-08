@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                <span>Area Administrasi IKA KPS</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Manajemen Bisnis Alumni</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Kelola direktori UMKM, kurasi publikasi, dan verifikasi usaha yang diajukan alumni.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Dashboard Overview
            </a>
            <a href="{{ route('admin.businesses.create') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add_business</span>
                <span>Tambah Bisnis</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filter & Search Controls -->
    <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.businesses.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama bisnis, pemilik, atau kata kunci..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="w-full sm:w-44">
                    <select name="category" class="w-full px-3 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:w-44">
                    <select name="status" class="w-full px-3 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Dipublikasikan ({{ $publishedCount }})</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review ({{ $pendingCount }})</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    <span>Filter</span>
                </button>
                @if (request()->hasAny(['q', 'category', 'status']))
                    <a href="{{ route('admin.businesses.index') }}" class="px-3.5 py-2 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant/40 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 sm:px-6">Bisnis &amp; Pemilik</th>
                        <th class="py-3.5 px-4">Kategori Usaha</th>
                        <th class="py-3.5 px-4">Kontak &amp; Kota</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($businesses as $biz)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $biz->image_url ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=100' }}" 
                                         alt="{{ $biz->name }}" 
                                         class="w-12 h-12 rounded-xl object-cover ring-1 ring-outline-variant shrink-0 bg-surface-container">
                                    <div class="min-w-0">
                                        <div class="font-bold text-primary truncate flex items-center gap-1.5">
                                            <span>{{ $biz->name }}</span>
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant truncate">
                                            {{ $biz->owner_info ?: ($biz->alumnus?->name ?? 'Alumni') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-surface-container text-primary">
                                    {{ $biz->category }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-xs space-y-0.5">
                                <div class="text-on-surface font-semibold truncate">{{ $biz->city }}</div>
                                @if ($biz->whatsapp_number || $biz->phone)
                                    <div class="text-[11px] text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] text-emerald-600">call</span>
                                        <span>{{ $biz->whatsapp_number ?? $biz->phone }}</span>
                                    </div>
                                @endif
                            </td>

                            <td class="py-4 px-4 text-xs">
                                @if ($biz->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="material-symbols-outlined text-[13px]">check</span>
                                        Tayang
                                    </span>
                                @elseif ($biz->status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="material-symbols-outlined text-[13px]">hourglass_top</span>
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                        <span class="material-symbols-outlined text-[13px]">close</span>
                                        {{ ucfirst($biz->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Toggle Publish -->
                                    <form action="{{ route('admin.businesses.publish', $biz->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="p-2 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant hover:text-primary transition-all"
                                                title="{{ $biz->status === 'published' ? 'Ubah ke Pending' : 'Publikasikan' }}">
                                            <span class="material-symbols-outlined text-[18px] block">
                                                {{ $biz->status === 'published' ? 'visibility_off' : 'visibility' }}
                                            </span>
                                        </button>
                                    </form>

                                    <!-- Edit -->
                                    <a href="{{ route('admin.businesses.edit', $biz->id) }}" 
                                       class="p-2 rounded-xl bg-surface-container-low text-on-surface-variant hover:text-primary hover:bg-surface-container transition-all"
                                       title="Edit Data">
                                        <span class="material-symbols-outlined text-[18px] block">edit</span>
                                    </a>

                                    <!-- Delete -->
                                    <form action="{{ route('admin.businesses.destroy', $biz->id) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data bisnis {{ addslashes($biz->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition-all border border-rose-200"
                                                title="Hapus Bisnis">
                                            <span class="material-symbols-outlined text-[18px] block">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">storefront</span>
                                <p class="text-sm font-semibold">Tidak ada data bisnis ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($businesses->hasPages())
            <div class="p-4 border-t border-outline-variant/30 bg-surface-container-low">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
