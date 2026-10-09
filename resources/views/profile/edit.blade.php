@extends('layouts.app')

@section('content')
<div class="max-w-[850px] mx-auto px-6 py-10 space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40">
        <div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary">Pengaturan Profil Alumni</h1>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Perbarui data almamater, karir, kontak, dan kata sandi akun Anda.</p>
        </div>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali ke Profil
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-error-container/20 border border-error/30 text-error">
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

    <!-- Form Data Profil Alumni & Pribadi -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <h2 class="font-headline-sm text-lg font-bold text-primary mb-6 pb-2 border-b border-outline-variant/30 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[22px]">badge</span>
            <span>Data Identitas &amp; Almamater Alumni</span>
        </h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Avatar File Upload with Live Preview -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 p-5 rounded-2xl bg-surface-container-low border border-outline-variant/40">
                <div class="relative shrink-0 group">
                    <img id="avatar-preview" 
                         src="{{ $user->avatar_url ?? ($alumnus->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=002D62&color=fff&size=200') }}" 
                         alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-2xl object-cover ring-2 ring-secondary/40 shadow-sm bg-surface-container">
                    <label for="avatar" 
                           class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-primary text-on-primary hover:bg-secondary transition-all shadow-md flex items-center justify-center cursor-pointer" 
                           title="Unggah Foto Baru">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </label>
                </div>
                <div class="flex-1 w-full text-center sm:text-left">
                    <label class="block font-label-md text-xs font-bold text-primary uppercase tracking-wider mb-1">
                        Foto Profil Alumni
                    </label>
                    <p class="text-xs text-on-surface-variant mb-3">
                        Format yang didukung: <strong>JPG, PNG, WEBP</strong>. Ukuran maksimal <strong>2 MB</strong>.
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <label for="avatar" 
                               class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant/60 hover:border-secondary text-primary font-bold text-xs cursor-pointer inline-flex items-center gap-1.5 shadow-sm transition-all hover:bg-surface-container">
                            <span class="material-symbols-outlined text-[16px]">upload_file</span>
                            <span id="avatar-file-name">Pilih File Foto...</span>
                        </label>
                        <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewAvatar(event)">
                        
                        <button type="button" 
                                onclick="const u = document.getElementById('avatar-url-wrapper'); u.classList.toggle('hidden');" 
                                class="text-[11px] text-secondary hover:underline font-semibold">
                            Gunakan URL Foto Eksternal
                        </button>
                    </div>

                    @php
                        $externalAvatarUrl = '';
                        if ($user->avatar_url && ! \Illuminate\Support\Str::startsWith($user->avatar_url, ['/storage/', 'storage/'])) {
                            $externalAvatarUrl = $user->avatar_url;
                        }
                    @endphp
                    <div id="avatar-url-wrapper" class="hidden mt-3 pt-3 border-t border-outline-variant/30">
                        <input type="text" id="avatar_url" name="avatar_url" 
                               value="{{ old('avatar_url', $externalAvatarUrl) }}"
                               placeholder="Atau tempel tautan URL gambar (https://...)" 
                               class="w-full px-3.5 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Gelar Akademik / Profesi -->
                <div>
                    <label for="title" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Gelar (Opsional)</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $alumnus->title ?? '') }}"
                           placeholder="Contoh: S.T., M.M., dr., Sp.A." 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Jenjang KPS -->
                <div>
                    <label for="level" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Jenjang Almamater KPS *</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">school</span>
                        <select id="level" name="level" 
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                            <option value="sma" {{ old('level', $alumnus->level ?? 'sma') === 'sma' ? 'selected' : '' }}>SMA Nasional KPS Balikpapan</option>
                            <option value="smp" {{ old('level', $alumnus->level ?? '') === 'smp' ? 'selected' : '' }}>SMP Nasional KPS Balikpapan</option>
                            <option value="sd" {{ old('level', $alumnus->level ?? '') === 'sd' ? 'selected' : '' }}>SD Nasional KPS Balikpapan</option>
                            <option value="tk" {{ old('level', $alumnus->level ?? '') === 'tk' ? 'selected' : '' }}>TK Nasional KPS Balikpapan</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                    </div>
                </div>

                <!-- Tahun Kelulusan / Angkatan -->
                <div>
                    <label for="graduation_year" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tahun Kelulusan / Angkatan</label>
                    <input type="text" id="graduation_year" name="graduation_year" value="{{ old('graduation_year', $alumnus->full_year ?? ($alumnus->class_year ?? '')) }}"
                           placeholder="Contoh: 2011"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Profesi / Pekerjaan -->
                <div>
                    <label for="profession" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Profesi / Jabatan Sekarang</label>
                    <input type="text" id="profession" name="profession" value="{{ old('profession', $alumnus->profession ?? '') }}"
                           placeholder="Contoh: Petroleum Engineer / Dokter / Wiraswasta"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Instansi / Perusahaan -->
                <div>
                    <label for="institution" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Instansi / Perusahaan</label>
                    <input type="text" id="institution" name="institution" value="{{ old('institution', $alumnus->institution ?? '') }}"
                           placeholder="Contoh: PT Pertamina / RS Pertamina Balikpapan"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Domisili -->
                <div>
                    <label for="domicile" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Kota Domisili</label>
                    <input type="text" id="domicile" name="domicile" value="{{ old('domicile', $alumnus->domicile ?? '') }}"
                           placeholder="Contoh: Balikpapan / Jakarta / Surabaya"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Kontak WhatsApp / Phone -->
                <div>
                    <label for="phone" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="081234567890"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- LinkedIn URL -->
                <div>
                    <label for="linkedin_url" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Tautan LinkedIn (Opsional)</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $alumnus->linkedin_url ?? '') }}"
                           placeholder="https://linkedin.com/in/username"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <!-- Instagram Handle -->
                <div>
                    <label for="instagram_handle" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Akun Instagram (Opsional)</label>
                    <input type="text" id="instagram_handle" name="instagram_handle" value="{{ old('instagram_handle', $alumnus->instagram_handle ?? '') }}"
                           placeholder="@username"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>
            </div>

            <!-- Bio / Summary -->
            <div>
                <label for="summary" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Bio / Catatan Karir Singkat</label>
                <textarea id="summary" name="summary" rows="3"
                          placeholder="Ceritakan bidang keahlian Anda, pengalaman berkesan di KPS, atau keterbukaan untuk kolaborasi bersama rekan alumni..."
                          class="w-full p-4 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">{{ old('summary', $alumnus->summary ?? '') }}</textarea>
            </div>

            <div>
                <label class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-1">Alamat Email (Akun)</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/40 bg-surface-container text-on-surface-variant text-sm cursor-not-allowed">
                <span class="text-[11px] text-on-surface-variant mt-1 block">Alamat email digunakan untuk login dan tidak dapat diubah secara langsung.</span>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs tracking-wider uppercase hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Perubahan Profil</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Form Ubah Kata Sandi -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <h2 class="font-headline-sm text-lg font-bold text-primary mb-4 pb-2 border-b border-outline-variant/30 flex items-center gap-2">
            <span class="material-symbols-outlined text-secondary text-[22px]">lock_reset</span>
            <span>Perbarui Kata Sandi</span>
        </h2>
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <div>
                    <label for="password_confirmation" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-secondary text-on-secondary font-bold text-xs tracking-wider uppercase hover:bg-secondary/90 transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">key</span>
                    <span>Ganti Kata Sandi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);

            const fileNameSpan = document.getElementById('avatar-file-name');
            if (fileNameSpan) {
                fileNameSpan.textContent = file.name;
            }
        }
    }
</script>
@endpush
