@extends('layouts.admin')

@section('content')
<div class="max-w-[900px] mx-auto px-6 py-10 space-y-8">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between pb-6 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('admin.alumni.index') }}" class="hover:underline">Manajemen Alumni</a>
                <span>/</span>
                <span>Edit Profil</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Edit Data Alumni: {{ $alumnus->name }}</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Perbarui informasi profil dan keanggotaan alumni.</p>
        </div>
        <div>
            <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Kembali
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
            <span class="font-bold block mb-1">Terdapat kesalahan input:</span>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Container -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.alumni.update', $alumnus->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $alumnus->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Gelar Akademik / Profesi -->
                <div>
                    <label for="title" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Gelar (Opsional)</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $alumnus->title) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Jenjang KPS -->
                <div>
                    <label for="level" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Jenjang Almamater *</label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">school</span>
                        <select id="level" name="level" required
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                            <option value="sma" {{ old('level', $alumnus->level) === 'sma' ? 'selected' : '' }}>SMA Nasional KPS</option>
                            <option value="smp" {{ old('level', $alumnus->level) === 'smp' ? 'selected' : '' }}>SMP Nasional KPS</option>
                            <option value="sd" {{ old('level', $alumnus->level) === 'sd' ? 'selected' : '' }}>SD Nasional KPS</option>
                            <option value="tk" {{ old('level', $alumnus->level) === 'tk' ? 'selected' : '' }}>TK Nasional KPS</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                    </div>
                </div>

                <!-- Tahun Kelulusan / Angkatan -->
                <div>
                    <label for="graduation_year" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Tahun Lulus / Angkatan</label>
                    <input type="text" id="graduation_year" name="graduation_year" value="{{ old('graduation_year', $alumnus->full_year) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Profesi / Posisi -->
                <div>
                    <label for="profession" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Profesi / Jabatan</label>
                    <input type="text" id="profession" name="profession" value="{{ old('profession', $alumnus->profession) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Instansi / Perusahaan -->
                <div>
                    <label for="institution" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Instansi / Perusahaan</label>
                    <input type="text" id="institution" name="institution" value="{{ old('institution', $alumnus->institution) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $alumnus->email) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Nomor HP / WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $alumnus->phone) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Domisili -->
                <div>
                    <label for="domicile" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kota Domisili</label>
                    <input type="text" id="domicile" name="domicile" value="{{ old('domicile', $alumnus->domicile) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Foto Profil (File Upload) -->
                <div>
                    <label for="avatar" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Unggah Foto Profil Baru</label>
                    <div class="flex items-center gap-3">
                        @if ($alumnus->avatar_url)
                            <img src="{{ $alumnus->avatar_url }}" alt="{{ $alumnus->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-outline-variant shrink-0">
                        @endif
                        <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="w-full text-xs text-on-surface file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-primary hover:file:bg-secondary hover:file:text-on-secondary cursor-pointer border border-outline-variant/60 rounded-xl bg-surface-container-low p-1.5 focus:outline-none">
                    </div>
                    <span class="text-[11px] text-on-surface-variant mt-1 block">JPG, PNG, WEBP (maks. 2 MB)</span>
                </div>

                <!-- LinkedIn URL -->
                <div>
                    <label for="linkedin_url" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Tautan LinkedIn (Opsional)</label>
                    <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $alumnus->linkedin_url) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Instagram Handle -->
                <div>
                    <label for="instagram_handle" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Akun Instagram (Opsional)</label>
                    <input type="text" id="instagram_handle" name="instagram_handle" value="{{ old('instagram_handle', $alumnus->instagram_handle) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <!-- Ringkasan / Bio Singkat -->
            <div>
                <label for="summary" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Ringkasan Profil / Catatan Karir</label>
                <textarea id="summary" name="summary" rows="4"
                          class="w-full p-4 rounded-xl border border-outline-variant/60 bg-surface-container-low text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('summary', $alumnus->summary) }}</textarea>
            </div>

            <!-- Checkbox Verifikasi Langsung -->
            <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-3">
                <input type="checkbox" id="is_verified" name="is_verified" value="1" {{ old('is_verified', $alumnus->is_verified) ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-secondary focus:ring-secondary">
                <label for="is_verified" class="text-xs font-bold text-primary cursor-pointer select-none">
                    Status terverifikasi (tampil di direktori publik)
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/40">
                <a href="{{ route('admin.alumni.index') }}" class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Perbarui Data Alumni</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
