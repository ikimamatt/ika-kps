@extends('layouts.app')

@section('content')
<div class="max-w-[1000px] mx-auto px-6 py-10 space-y-8">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('profile.show') }}" class="hover:underline">Profil</a>
                <span>/</span>
                <span>Lowongan Kerja</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Lowongan Kerja yang Saya Bagikan</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Bagikan peluang karir dari tempat Anda bekerja untuk membantu sesama alumni KPS.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.show') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Kembali ke Profil
            </a>
            <a href="{{ route('profile.job.create') }}" class="px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>+ Pasang Lowongan Baru</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Jobs List Container -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
        @if ($jobs->isNotEmpty())
            <div class="divide-y divide-outline-variant/20">
                @foreach ($jobs as $job)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-surface-container-low/40 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-2xl">work</span>
                            </div>
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h2 class="font-bold text-sm sm:text-base text-primary truncate">{{ $job->title }}</h2>
                                    @if ($job->status === 'active')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                            Tayang Aktif
                                        </span>
                                    @elseif ($job->status === 'pending')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                                            Menunggu Review
                                        </span>
                                    @elseif ($job->status === 'closed')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                            Ditutup
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800">
                                            Kadaluarsa
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs font-semibold text-secondary truncate">{{ $job->company }} &bull; {{ $job->location }} ({{ $job->job_type }})</p>
                                <p class="text-xs text-on-surface-variant line-clamp-1 max-w-xl">{{ $job->description }}</p>
                                <div class="text-[11px] text-on-surface-variant/80 pt-0.5">
                                    Diajukan: {{ $job->created_at->format('d M Y') }}
                                    @if ($job->deadline)
                                        &bull; Batas: {{ $job->deadline->format('d M Y') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-center flex-wrap justify-end">
                            @if ($job->status === 'active')
                                <a href="{{ route('career.show', $job->slug) }}" target="_blank"
                                   class="px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors inline-flex items-center gap-1"
                                   title="Lihat Publik">
                                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                                    <span>Lihat</span>
                                </a>

                                <form action="{{ route('profile.job.toggle-status', $job->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-xl border border-outline-variant text-xs font-semibold text-on-surface-variant hover:bg-surface-container transition-colors"
                                            title="Tutup Lowongan">
                                        Tutup
                                    </button>
                                </form>
                            @elseif ($job->status === 'closed')
                                <form action="{{ route('profile.job.toggle-status', $job->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-xl bg-primary/10 text-primary text-xs font-bold hover:bg-primary/20 transition-colors"
                                            title="Buka Kembali Lowongan">
                                        Buka Loker
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('profile.job.edit', $job->id) }}" 
                               class="px-3 py-1.5 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-bold text-xs inline-flex items-center gap-1 transition-colors"
                               title="Edit Lowongan">
                                <span class="material-symbols-outlined text-[15px]">edit</span>
                                <span>Edit</span>
                            </a>

                            <form action="{{ route('profile.job.destroy', $job->id) }}" method="POST"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan kerja ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="p-1.5 rounded-xl text-on-surface-variant hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                        title="Hapus Lowongan">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($jobs->hasPages())
                <div class="p-4 border-t border-outline-variant/30 flex justify-center">
                    {{ $jobs->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-16 px-6">
                <div class="w-16 h-16 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl">work_outline</span>
                </div>
                <h3 class="font-headline-sm text-lg font-bold text-primary">Belum Ada Lowongan yang Anda Bagikan</h3>
                <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-md mx-auto mt-1 mb-6">
                    Ada info lowongan kerja di perusahaan atau instansi tempat Anda bekerja? Bagikan kesempatan ini kepada sesama rekan alumni KPS.
                </p>
                <a href="{{ route('profile.job.create') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">add</span>
                    <span>Pasang Lowongan Pertama Anda</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
