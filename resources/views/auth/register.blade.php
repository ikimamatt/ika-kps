@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-12 px-6">
    <div class="w-full max-w-2xl bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.06)] relative overflow-hidden">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-secondary/10 text-secondary mb-3">
                <span class="material-symbols-outlined text-[32px]">person_add</span>
            </div>
            <h1 class="font-headline-sm text-2xl sm:text-3xl font-extrabold text-primary tracking-tight">Daftar Akun Alumni KPS</h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant mt-2 max-w-md mx-auto">
                Daftarkan akun login Anda sekaligus profil direktori alumni Sekolah Nasional KPS Balikpapan dalam satu langkah mudah.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-error-container/20 border border-error/30 text-error">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[20px] shrink-0 mt-0.5">error</span>
                    <div class="text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Bagian 1: Akun & Identitas -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-2 mb-3 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                    <span>1. Data Akun &amp; Kontak</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Nama Lengkap &amp; Gelar *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="Contoh: Muhammad Irfan, S.T.">
                    </div>

                    <div>
                        <label for="email" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Alamat Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="nama@domain.com">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="phone" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Nomor WhatsApp / HP Aktif *</label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="Contoh: 081234567890">
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Almamater KPS -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-2 mb-3 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-[16px]">school</span>
                    <span>2. Riwayat Almamater Sekolah KPS</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="level" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Jenjang Terakhir di KPS *</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">school</span>
                            <select id="level" name="level" required
                                    class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                                <option value="sma" {{ old('level', 'sma') === 'sma' ? 'selected' : '' }}>SMA Nasional KPS Balikpapan</option>
                                <option value="smp" {{ old('level') === 'smp' ? 'selected' : '' }}>SMP Nasional KPS Balikpapan</option>
                                <option value="sd" {{ old('level') === 'sd' ? 'selected' : '' }}>SD Nasional KPS Balikpapan</option>
                                <option value="tk" {{ old('level') === 'tk' ? 'selected' : '' }}>TK Nasional KPS Balikpapan</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                        </div>
                    </div>

                    <div>
                        <label for="graduation_year" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Tahun Lulus / Angkatan *</label>
                        <input type="text" id="graduation_year" name="graduation_year" value="{{ old('graduation_year') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="Contoh: 2011">
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Karir & Domisili -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-2 mb-3 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-[16px]">work</span>
                    <span>3. Profesi &amp; Domisili Saat Ini</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="profession" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Profesi / Jabatan</label>
                        <input type="text" id="profession" name="profession" value="{{ old('profession') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="Petroleum Engineer">
                    </div>

                    <div>
                        <label for="institution" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Perusahaan / Instansi</label>
                        <input type="text" id="institution" name="institution" value="{{ old('institution') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="PT Pertamina">
                    </div>

                    <div>
                        <label for="domicile" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Kota Domisili</label>
                        <input type="text" id="domicile" name="domicile" value="{{ old('domicile') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                               placeholder="Balikpapan">
                    </div>
                </div>
            </div>

            <!-- Bagian 4: Kata Sandi -->
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-2 mb-3 pb-2 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-[16px]">lock</span>
                    <span>4. Keamanan Akun</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Kata Sandi *</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                                   placeholder="Min. 8 karakter">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password', 'passwordToggleIcon1')" 
                                    aria-label="Lihat atau sembunyikan kata sandi"
                                    class="absolute right-1 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-primary transition-colors w-9 h-9 flex items-center justify-center rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/30 cursor-pointer">
                                <span id="passwordToggleIcon1" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1.5">Ulangi Kata Sandi *</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full pl-4 pr-11 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                                   placeholder="Konfirmasi sandi">
                            <button type="button" 
                                    onclick="togglePasswordVisibility('password_confirmation', 'passwordToggleIcon2')" 
                                    aria-label="Lihat atau sembunyikan konfirmasi kata sandi"
                                    class="absolute right-1 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-primary transition-colors w-9 h-9 flex items-center justify-center rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/30 cursor-pointer">
                                <span id="passwordToggleIcon2" class="material-symbols-outlined text-[18px]">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 rounded-xl bg-primary text-on-primary font-bold text-sm tracking-wide shadow-md hover:bg-primary-container transition-all active:scale-[0.99] flex items-center justify-center gap-2 pt-3">
                <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                <span>Daftar Sebagai Anggota Alumni</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-outline-variant/40 text-center text-xs text-on-surface-variant">
            <span>Sudah pernah mendaftar akun? </span>
            <a href="{{ route('login') }}" class="font-bold text-secondary hover:underline">Masuk ke Portal</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush
@endsection
