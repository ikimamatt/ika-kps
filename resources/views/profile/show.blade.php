@extends('layouts.app')

@section('content')
<div class="max-w-[1280px] mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Profil Ringkas -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-sm flex flex-col items-center text-center">
            <div class="relative mb-4">
                <img alt="Foto Profil" 
                     class="w-28 h-28 rounded-full object-cover ring-4 ring-secondary/20 shadow-md"
                     src="{{ $user->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=002D62&color=fff&size=200' }}">
                <span class="absolute bottom-1 right-1 w-4 h-4 bg-emerald-500 rounded-full ring-2 ring-surface-container-lowest" title="Aktif"></span>
            </div>

            <h1 class="font-headline-sm text-xl font-extrabold text-primary">{{ $user->name }}</h1>
            <p class="font-body-md text-xs text-on-surface-variant mt-1">{{ $user->email }}</p>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary">
                    Peran: {{ ucfirst($user->role) }}
                </span>
                @if ($user->phone)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-surface-container text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">call</span>
                        {{ $user->phone }}
                    </span>
                @endif
            </div>

            <div class="w-full mt-6 pt-6 border-t border-outline-variant/40 flex flex-col gap-2">
                <a href="{{ route('profile.edit') }}" 
                   class="w-full py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs tracking-wider uppercase hover:bg-primary-container transition-all flex items-center justify-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                    Edit Profil & Sandi
                </a>

                @if ($user->status === 'active' && $alumnus && $alumnus->is_verified)
                    <a href="{{ route('profile.business.index') }}" 
                       class="w-full py-2.5 rounded-xl border border-secondary/50 text-secondary hover:bg-secondary/10 font-bold text-xs tracking-wider uppercase transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">storefront</span>
                        Bisnis Saya ({{ $businesses->count() }})
                    </a>

                    <a href="{{ route('profile.job.index') }}" 
                       class="w-full py-2.5 rounded-xl border border-secondary/50 text-secondary hover:bg-secondary/10 font-bold text-xs tracking-wider uppercase transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">work</span>
                        Lowongan Saya ({{ $jobVacancies->count() }})
                    </a>
                @endif

                @if ($user->isAdmin() || $user->isPengurus())
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full py-2.5 rounded-xl bg-secondary text-on-secondary font-bold text-xs tracking-wider uppercase hover:bg-secondary/90 transition-all flex items-center justify-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">dashboard</span>
                        Buka Dashboard Admin
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="w-full mt-2">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2.5 rounded-xl border border-error/30 text-error font-bold text-xs tracking-wider uppercase hover:bg-error-container/20 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">logout</span>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>

        <!-- Detail Profil & Data Alumnus -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40 mb-6">
                    <div>
                        <h2 class="font-headline-sm text-lg font-bold text-primary">Informasi Data Akun</h2>
                        <p class="font-body-md text-xs text-on-surface-variant">Detail identitas login dan kontak terdaftar Anda.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-xs text-on-surface-variant font-medium block">Nama Lengkap</span>
                        <span class="font-bold text-on-surface mt-1 block">{{ $user->name }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-xs text-on-surface-variant font-medium block">Email Akun</span>
                        <span class="font-bold text-on-surface mt-1 block">{{ $user->email }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-xs text-on-surface-variant font-medium block">Nomor Telepon</span>
                        <span class="font-bold text-on-surface mt-1 block">{{ $user->phone ?? 'Belum diatur' }}</span>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-xs text-on-surface-variant font-medium block">Status Akun</span>
                        <span class="font-bold text-emerald-600 mt-1 block capitalize">{{ $user->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Kartu Status Alumni Terkait -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40 mb-4">
                    <div>
                        <h2 class="font-headline-sm text-lg font-bold text-primary">Status Keanggotaan Alumni</h2>
                        <p class="font-body-md text-xs text-on-surface-variant">Status verifikasi keanggotaan dan penayangan di direktori publik.</p>
                    </div>
                </div>

                @if ($user->status === 'active' && $alumnus && $alumnus->is_verified)
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3">
                        <span class="material-symbols-outlined text-emerald-600 text-[24px]">verified</span>
                        <div>
                            <span class="font-bold text-sm block">Keanggotaan Terverifikasi &amp; Aktif</span>
                            <p class="text-xs mt-1">Profil Anda telah disetujui oleh pengurus dan tayang di direktori publik sebagai alumni <strong>{{ strtoupper($alumnus->level) }} Angkatan {{ $alumnus->full_year ?? $alumnus->class_year }}</strong>. Seluruh akses fitur portal terbuka penuh.</p>
                        </div>
                    </div>
                @elseif ($user->status === 'rejected')
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-start gap-3">
                        <span class="material-symbols-outlined text-rose-600 text-[24px]">cancel</span>
                        <div>
                            <span class="font-bold text-sm block">Pendaftaran Belum Disetujui</span>
                            <p class="text-xs mt-1">Pengurus belum menyetujui permohonan keanggotaan Anda. Catatan: {{ $user->rejection_reason ?? 'Identitas angkatan tidak dapat dikonfirmasi.' }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-start gap-3">
                        <span class="material-symbols-outlined text-amber-600 text-[24px]">hourglass_top</span>
                        <div>
                            <span class="font-bold text-sm block">Menunggu Verifikasi Pengurus</span>
                            <p class="text-xs mt-1">Data pendaftaran akun dan profil alumni Anda ({{ strtoupper($alumnus->level ?? 'SMA') }} Angkatan {{ $alumnus->full_year ?? '-' }}) sedang ditinjau oleh pengurus IKA KPS. Profil Anda akan otomatis tayang di direktori publik setelah disetujui.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Kartu Kolom Bisnis & Usaha Saya -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-outline-variant/40 mb-6">
                    <div>
                        <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">storefront</span>
                            <span>Katalog Bisnis &amp; UMKM Saya</span>
                        </h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Daftarkan usaha atau produk Anda ke direktori publik IKA KPS.</p>
                    </div>

                    @if ($user->status === 'active' && $alumnus && $alumnus->is_verified)
                        <a href="{{ route('profile.business.create') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            <span>+ Tambah Usaha</span>
                        </a>
                    @endif
                </div>

                @if (! ($user->status === 'active' && $alumnus && $alumnus->is_verified))
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface-variant flex items-center gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">lock</span>
                        <span>Fitur pendaftaran bisnis alumni akan aktif otomatis setelah akun keanggotaan Anda diverifikasi oleh pengurus.</span>
                    </div>
                @elseif ($businesses->isEmpty())
                    <div class="text-center py-8 px-4 rounded-2xl bg-surface-container-low/50 border border-dashed border-outline-variant/60">
                        <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[26px]">add_business</span>
                        </div>
                        <h3 class="font-bold text-sm text-primary">Belum Ada Usaha yang Didaftarkan</h3>
                        <p class="text-xs text-on-surface-variant max-w-md mx-auto mt-1 mb-4">
                            Miliki toko, kantor jasa, atau UMKM? Promosikan langsung ke ribuan rekan alumni lintas generasi di portal IKA KPS.
                        </p>
                        <a href="{{ route('profile.business.create') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Daftarkan Usaha Sekarang</span>
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($businesses as $biz)
                            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:border-secondary/50 transition-colors">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    @if ($biz->image_url)
                                        <img src="{{ $biz->image_url }}" alt="{{ $biz->name }}" class="w-12 h-12 rounded-xl object-cover shrink-0 ring-1 ring-outline-variant/40">
                                    @else
                                        <div class="w-12 h-12 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">storefront</span>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-primary truncate">{{ $biz->name }}</h4>
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $biz->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                {{ $biz->status === 'published' ? 'Tayang' : 'Menunggu Review' }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $biz->category }} &bull; {{ $biz->city }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                                    @if ($biz->status === 'published')
                                        <a href="{{ route('business.show', $biz->slug) }}" 
                                           class="px-3 py-1.5 rounded-lg border border-outline-variant text-[11px] font-bold text-on-surface hover:bg-surface-container transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">visibility</span>
                                            <span>Lihat Publik</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.business.edit', $biz->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-[11px] font-bold text-primary transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">edit</span>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-3 text-right">
                            <a href="{{ route('profile.business.index') }}" class="text-xs font-bold text-secondary hover:underline inline-flex items-center gap-1">
                                <span>Lihat Seluruh Daftar Usaha Saya</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Kartu Kolom Lowongan Kerja yang Saya Bagikan -->
            <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-outline-variant/40 mb-6">
                    <div>
                        <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">work</span>
                            <span>Lowongan Kerja yang Saya Bagikan</span>
                        </h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Berbagi kesempatan karir dari instansi/perusahaan Anda untuk sesama rekan alumni.</p>
                    </div>

                    @if ($user->status === 'active' && $alumnus && $alumnus->is_verified)
                        <a href="{{ route('profile.job.create') }}" 
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm shrink-0">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span>
                            <span>+ Pasang Loker</span>
                        </a>
                    @endif
                </div>

                @if (! ($user->status === 'active' && $alumnus && $alumnus->is_verified))
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface-variant flex items-center gap-3">
                        <span class="material-symbols-outlined text-on-surface-variant text-[20px]">lock</span>
                        <span>Fitur berbagi info lowongan alumni aktif setelah keanggotaan Anda diverifikasi.</span>
                    </div>
                @elseif ($jobVacancies->isEmpty())
                    <div class="text-center py-8 px-4 rounded-2xl bg-surface-container-low/50 border border-dashed border-outline-variant/60">
                        <div class="w-12 h-12 rounded-2xl bg-secondary/10 text-secondary flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[26px]">post_add</span>
                        </div>
                        <h3 class="font-bold text-sm text-primary">Belum Ada Lowongan yang Dibagikan</h3>
                        <p class="text-xs text-on-surface-variant max-w-md mx-auto mt-1 mb-4">
                            Perusahaan tempat Anda bekerja sedang membuka rekrutmen? Bagikan informasinya dan bantu adik kelas atau sesama alumni KPS berkarir.
                        </p>
                        <a href="{{ route('profile.job.create') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            <span>Pasang Info Loker Sekarang</span>
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($jobVacancies as $vac)
                            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:border-secondary/50 transition-colors">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="w-11 h-11 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[20px]">work</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-sm text-primary truncate">{{ $vac->title }}</h4>
                                            @if ($vac->status === 'active')
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                                                    Tayang
                                                </span>
                                            @elseif ($vac->status === 'pending')
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800">
                                                    Menunggu Review
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                                    {{ ucfirst($vac->status) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $vac->company }} &bull; {{ $vac->location }} ({{ $vac->job_type }})</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                                    @if ($vac->status === 'active')
                                        <a href="{{ route('career.show', $vac->slug) }}" 
                                           class="px-3 py-1.5 rounded-lg border border-outline-variant text-[11px] font-bold text-on-surface hover:bg-surface-container transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px]">visibility</span>
                                            <span>Lihat Publik</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.job.edit', $vac->id) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-[11px] font-bold text-primary transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">edit</span>
                                        <span>Edit</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-3 text-right">
                            <a href="{{ route('profile.job.index') }}" class="text-xs font-bold text-secondary hover:underline inline-flex items-center gap-1">
                                <span>Lihat Seluruh Lowongan Saya</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
