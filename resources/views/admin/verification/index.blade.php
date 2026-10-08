@extends('layouts.admin')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10 space-y-8">
    <!-- Header Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <span class="material-symbols-outlined text-[16px]">verified_user</span>
                <span>Persetujuan Keanggotaan Alumni</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Antrean Verifikasi Pendaftaran</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Tinjau permohonan keanggotaan baru alumni KPS dan kelola status persetujuan akun.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Dashboard Overview
            </a>
            <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 rounded-xl bg-surface-container-low border border-outline-variant/60 text-xs font-bold text-primary hover:bg-surface-container transition-colors flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">group</span>
                <span>Direktori Alumni</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600 text-[20px]">check_circle</span>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
            <span class="font-bold block mb-1">Terdapat kesalahan:</span>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Status Tabs -->
    <div class="flex flex-wrap items-center gap-2 border-b border-outline-variant/40 pb-3">
        <a href="{{ route('admin.verification.index', array_merge(request()->query(), ['status' => 'pending'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'pending' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[16px]">hourglass_top</span>
            <span>Menunggu Verifikasi</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] {{ $status === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-800 font-extrabold' }}">
                {{ $pendingCount }}
            </span>
        </a>

        <a href="{{ route('admin.verification.index', array_merge(request()->query(), ['status' => 'approved'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'approved' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            <span>Telah Disetujui (Aktif)</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] {{ $status === 'approved' ? 'bg-white/20 text-white' : 'bg-emerald-100 text-emerald-800 font-extrabold' }}">
                {{ $approvedCount }}
            </span>
        </a>

        <a href="{{ route('admin.verification.index', array_merge(request()->query(), ['status' => 'rejected'])) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 {{ $status === 'rejected' ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-low text-on-surface hover:bg-surface-container' }}">
            <span class="material-symbols-outlined text-[16px]">cancel</span>
            <span>Ditolak</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] {{ $status === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-100 text-rose-800 font-extrabold' }}">
                {{ $rejectedCount }}
            </span>
        </a>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-surface-container-low border border-outline-variant/40 rounded-2xl p-4 shadow-sm">
        <form action="{{ route('admin.verification.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <input type="hidden" name="status" value="{{ $status }}">

            <div class="flex-1 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama pemohon, email, profesi, atau angkatan..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="w-full sm:w-48">
                    <select name="level" class="w-full px-3 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        <option value="">Semua Jenjang</option>
                        <option value="sma" {{ request('level') === 'sma' ? 'selected' : '' }}>SMA KPS</option>
                        <option value="smp" {{ request('level') === 'smp' ? 'selected' : '' }}>SMP KPS</option>
                        <option value="sd" {{ request('level') === 'sd' ? 'selected' : '' }}>SD KPS</option>
                        <option value="tk" {{ request('level') === 'tk' ? 'selected' : '' }}>TK KPS</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                    <span>Terapkan Filter</span>
                </button>
                @if (request()->hasAny(['q', 'level']))
                    <a href="{{ route('admin.verification.index', ['status' => $status]) }}" class="px-3.5 py-2 rounded-xl border border-outline-variant text-xs font-semibold hover:bg-surface-container">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Container -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-surface-container-low border-b border-outline-variant/40 text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-4 sm:px-6">Alumni / Pemohon</th>
                        <th class="py-3.5 px-4">Almamater KPS</th>
                        <th class="py-3.5 px-4">Kontak &amp; Domisili</th>
                        <th class="py-3.5 px-4">Profesi / Instansi</th>
                        <th class="py-3.5 px-4">Status &amp; Verifikator</th>
                        <th class="py-3.5 px-4 sm:px-6 text-right">Aksi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($alumni as $alumnus)
                        <tr class="hover:bg-surface-container-low/40 transition-colors">
                            <!-- Profil & Foto -->
                            <td class="py-4 px-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $alumnus->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($alumnus->name).'&background=002D62&color=fff&size=80' }}" 
                                         alt="{{ $alumnus->name }}" 
                                         class="w-11 h-11 rounded-xl object-cover ring-1 ring-outline-variant shrink-0 bg-surface-container">
                                    <div class="min-w-0">
                                        <div class="font-bold text-primary truncate flex items-center gap-1.5">
                                            <span>{{ $alumnus->name }}</span>
                                            @if ($alumnus->title)
                                                <span class="text-[11px] text-on-surface-variant font-normal">({{ $alumnus->title }})</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant truncate">
                                            Daftar: {{ $alumnus->created_at ? $alumnus->created_at->format('d M Y, H:i') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Almamater -->
                            <td class="py-4 px-4">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-surface-container text-primary">
                                    {{ strtoupper($alumnus->level) }}
                                </span>
                                <div class="text-xs font-semibold text-on-surface mt-1">
                                    Lulus: {{ $alumnus->full_year ?? ($alumnus->class_year ?? '-') }}
                                </div>
                            </td>

                            <!-- Kontak & Domisili -->
                            <td class="py-4 px-4 text-xs space-y-1">
                                <div class="text-on-surface font-medium truncate max-w-[200px]">
                                    {{ $alumnus->email ?? ($alumnus->user?->email ?? '-') }}
                                </div>
                                @if ($alumnus->phone ?? ($alumnus->user?->phone ?? null))
                                    <div class="text-on-surface-variant text-[11px] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] text-emerald-600">call</span>
                                        <span>{{ $alumnus->phone ?? $alumnus->user->phone }}</span>
                                    </div>
                                @endif
                                @if ($alumnus->domicile)
                                    <div class="text-on-surface-variant text-[11px] flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] text-secondary">location_on</span>
                                        <span>{{ $alumnus->domicile }}</span>
                                    </div>
                                @endif
                            </td>

                            <!-- Profesi -->
                            <td class="py-4 px-4 text-xs">
                                <div class="font-bold text-on-surface truncate max-w-[180px]">
                                    {{ $alumnus->profession ?? 'Belum dicantumkan' }}
                                </div>
                                <div class="text-on-surface-variant text-[11px] truncate max-w-[180px]">
                                    {{ $alumnus->institution ?? '-' }}
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 text-xs">
                                @if ($alumnus->is_verified)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                                        <span class="material-symbols-outlined text-[13px]">check</span>
                                        Disetujui
                                    </span>
                                    @if ($alumnus->verifier)
                                        <div class="text-[10px] text-on-surface-variant mt-1">
                                            Oleh: {{ $alumnus->verifier->name }}
                                        </div>
                                    @endif
                                @elseif ($alumnus->user && $alumnus->user->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800">
                                        <span class="material-symbols-outlined text-[13px]">close</span>
                                        Ditolak
                                    </span>
                                    @if ($alumnus->user->rejection_reason)
                                        <div class="text-[10px] text-rose-700 mt-1 max-w-[180px] truncate" title="{{ $alumnus->user->rejection_reason }}">
                                            Catatan: {{ $alumnus->user->rejection_reason }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800">
                                        <span class="material-symbols-outlined text-[13px]">schedule</span>
                                        Menunggu
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-4 sm:px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if (! $alumnus->is_verified)
                                        <!-- Tombol Approve -->
                                        <form action="{{ route('admin.verification.approve', $alumnus->id) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menyetujui keanggotaan {{ addslashes($alumnus->name) }}? Profil akan otomatis tayang di direktori publik.')">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-200" 
                                                    title="Setujui Keanggotaan">
                                                <span class="material-symbols-outlined text-[18px] block">check_circle</span>
                                            </button>
                                        </form>

                                        <!-- Tombol Reject (Buka Modal) -->
                                        <button type="button" 
                                                onclick="openRejectModal('{{ $alumnus->id }}', '{{ addslashes($alumnus->name) }}')"
                                                class="p-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white transition-all shadow-sm border border-rose-200" 
                                                title="Tolak Pendaftaran">
                                            <span class="material-symbols-outlined text-[18px] block">cancel</span>
                                        </button>
                                    @else
                                        <!-- Aksi Ketika Sudah Disetujui (Bisa Dibatalkan jika perlu) -->
                                        <button type="button" 
                                                onclick="openRejectModal('{{ $alumnus->id }}', '{{ addslashes($alumnus->name) }}')"
                                                class="px-2.5 py-1.5 rounded-lg border border-rose-200 text-rose-700 hover:bg-rose-50 text-[11px] font-bold transition-colors">
                                            Batalkan / Tolak
                                        </button>
                                    @endif

                                    <!-- Link Edit Langsung -->
                                    <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" 
                                       class="p-2 rounded-xl bg-surface-container-low text-on-surface-variant hover:text-primary hover:bg-surface-container transition-all"
                                       title="Lihat &amp; Edit Data">
                                        <span class="material-symbols-outlined text-[18px] block">visibility</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl text-outline mb-2 block">inbox</span>
                                <p class="text-sm font-semibold">Tidak ada permohonan keanggotaan pada tab ini.</p>
                                <p class="text-xs text-on-surface-variant/80 mt-1">Semua pendaftaran telah ditinjau atau tidak cocok dengan filter pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($alumni->hasPages())
            <div class="p-4 border-t border-outline-variant/30 bg-surface-container-low">
                {{ $alumni->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Tolak Permohonan -->
<div id="reject-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 border border-outline-variant/40 shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/30">
            <div class="flex items-center gap-2 text-rose-600 font-bold text-sm">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span>Tolak Permohonan Alumni</span>
            </div>
            <button type="button" onclick="closeRejectModal()" class="text-on-surface-variant hover:text-on-surface p-1">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <p class="text-xs text-on-surface-variant leading-relaxed">
            Anda akan menolak permohonan keanggotaan alumni atas nama: <strong id="reject-alumni-name" class="text-primary font-bold"></strong>. Masukkan alasan penolakan agar pengguna memahami apa yang perlu dilengkapi.
        </p>

        <form id="reject-form" action="" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="rejection_reason" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">
                    Alasan Penolakan *
                </label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                          placeholder="Contoh: Identitas jenjang atau tahun angkatan KPS tidak dapat diverifikasi oleh pengurus."
                          class="w-full p-3 rounded-xl border border-outline-variant/60 bg-surface-container-low text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-rose-500/40 focus:border-rose-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2 border-t border-outline-variant/30">
                <button type="button" onclick="closeRejectModal()" 
                        class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-700 transition-all flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    <span>Kirim Penolakan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openRejectModal(alumnusId, alumniName) {
        const modal = document.getElementById('reject-modal');
        const form = document.getElementById('reject-form');
        const nameSpan = document.getElementById('reject-alumni-name');
        
        form.action = '/admin/verifikasi/' + alumnusId + '/reject';
        nameSpan.textContent = alumniName;
        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        const modal = document.getElementById('reject-modal');
        modal.classList.add('hidden');
    }
</script>
@endpush
