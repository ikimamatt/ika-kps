@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-10 space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('profile.show') }}" class="hover:underline">Profil</a>
                <span>/</span>
                <span>Bisnis Saya</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Daftar Usaha &amp; Bisnis Alumni Anda</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Kelola profil usaha mandiri Anda yang didaftarkan ke direktori resmi IKA KPS.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.show') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Kembali ke Profil
            </a>
            <a href="{{ route('profile.business.create') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Daftarkan Usaha Baru</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
        @if ($businesses->isNotEmpty())
            <div class="divide-y divide-outline-variant/20">
                @foreach ($businesses as $biz)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-surface-container-low/40 transition-colors">
                        <div class="flex items-center gap-4">
                            @if ($biz->image_url)
                                <img src="{{ $biz->image_url }}" alt="{{ $biz->name }}" class="w-14 h-14 rounded-xl object-cover ring-1 ring-outline-variant shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-2xl">storefront</span>
                                </div>
                            @endif
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h2 class="font-bold text-sm text-primary">{{ $biz->name }}</h2>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-surface-container text-on-surface-variant">
                                        {{ $biz->category }}
                                    </span>
                                </div>
                                <p class="text-xs text-on-surface-variant line-clamp-1 max-w-lg">{{ $biz->description }}</p>
                                <div class="text-[11px] text-on-surface-variant/80">
                                    Didaftarkan: {{ $biz->created_at->format('d M Y') }} • {{ $biz->city }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center flex-wrap justify-end">
                            @if ($biz->status === 'published')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">check</span>
                                    <span>Tayang</span>
                                </span>
                                <a href="{{ route('business.show', $biz->slug) }}" target="_blank" 
                                   class="p-2 rounded-xl border border-outline-variant text-on-surface-variant hover:text-primary hover:bg-surface-container"
                                   title="Lihat Halaman Publik">
                                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                                </a>
                            @elseif ($biz->status === 'pending')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">hourglass_top</span>
                                    <span>Menunggu Review</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    <span>Ditolak</span>
                                </span>
                            @endif

                            <a href="{{ route('profile.business.edit', $biz->id) }}" 
                               class="px-3 py-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-bold text-xs inline-flex items-center gap-1 transition-colors"
                               title="Edit Profil Usaha">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('profile.business.destroy', $biz->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus unit usaha ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 rounded-xl text-on-surface-variant hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                        title="Hapus Usaha">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-16 text-center text-on-surface-variant p-6">
                <span class="material-symbols-outlined text-5xl text-outline mb-2 block">storefront</span>
                <p class="text-base font-bold text-primary">Anda belum mendaftarkan unit usaha.</p>
                <p class="text-xs text-on-surface-variant mt-1 mb-4">Promosikan produk dan layanan usaha Anda kepada ribuan alumni KPS lainnya.</p>
                <a href="{{ route('profile.business.create') }}" 
                   class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Daftarkan Usaha Sekarang</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
