@extends('layouts.app')

@section('content')
<div class="max-w-[800px] mx-auto px-6 py-10 space-y-8">
    <div class="flex items-center justify-between gap-4 pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('profile.show') }}" class="hover:underline">Profil</a>
                <span>/</span>
                <a href="{{ route('profile.job.index') }}" class="hover:underline">Lowongan</a>
                <span>/</span>
                <span>Pasang Baru</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Pasang Info Lowongan Kerja</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Bagikan informasi peluang kerja untuk keluarga besar alumni KPS.</p>
        </div>
        <a href="{{ route('profile.job.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span>Mohon perbaiki data berikut:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('profile.job.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <h3 class="font-bold text-sm text-primary uppercase tracking-wider border-b border-outline-variant/30 pb-2">
                    Informasi Pekerjaan &amp; Instansi
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label for="title" class="block text-xs font-bold text-primary">Judul Posisi / Pekerjaan <span class="text-rose-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                               placeholder="Contoh: Mechanical Maintenance Engineer"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label for="company" class="block text-xs font-bold text-primary">Nama Perusahaan / Organisasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}" required 
                               placeholder="Contoh: PT Pertamina Hulu Mahakam"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label for="job_type" class="block text-xs font-bold text-primary">Tipe Pekerjaan <span class="text-rose-500">*</span></label>
                        <select id="job_type" name="job_type" required 
                                class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                            <option value="Full Time" {{ old('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time (Penuh Waktu)</option>
                            <option value="Part Time" {{ old('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time (Paruh Waktu)</option>
                            <option value="Magang" {{ old('job_type') === 'Magang' ? 'selected' : '' }}>Magang / Internship</option>
                            <option value="Kontrak" {{ old('job_type') === 'Kontrak' ? 'selected' : '' }}>Kontrak Proyek</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label for="location" class="block text-xs font-bold text-primary">Lokasi Kerja / Penempatan <span class="text-rose-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', 'Balikpapan') }}" required 
                               placeholder="Contoh: Balikpapan Kota / IKN Nusantara"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label for="salary_range" class="block text-xs font-bold text-primary">Perkiraan Gaji / Insentif (Opsional)</label>
                        <input type="text" id="salary_range" name="salary_range" value="{{ old('salary_range') }}" 
                               placeholder="Contoh: Rp 8.000.000 - Rp 12.000.000"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="font-bold text-sm text-primary uppercase tracking-wider border-b border-outline-variant/30 pb-2">
                    Rincian Kualifikasi &amp; Lamaran
                </h3>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-primary">Deskripsi Tanggung Jawab &amp; Pekerjaan <span class="text-rose-500">*</span></label>
                        <textarea id="description" name="description" rows="4" required 
                                  placeholder="Jelaskan peran pekerjaan dan lingkup tanggung jawab utama..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all leading-relaxed">{{ old('description') }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label for="requirements" class="block text-xs font-bold text-primary">Persyaratan &amp; Kriteria Pelamar (Opsional)</label>
                        <textarea id="requirements" name="requirements" rows="4" 
                                  placeholder="Contoh:&#10;1. S1 Teknik Mesin / Elektro&#10;2. Pengalaman minimal 2 tahun&#10;3. Memiliki sertifikat K3"
                                  class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all leading-relaxed">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="deadline" class="block text-xs font-bold text-primary">Batas Akhir Penerimaan Lamaran</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label for="apply_url" class="block text-xs font-bold text-primary">Link Lamaran / Email HRD <span class="text-rose-500">*</span></label>
                            <input type="text" id="apply_url" name="apply_url" value="{{ old('apply_url') }}" required 
                                   placeholder="Contoh: https://karir.perusahaan.com atau hrd@kantor.id"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                            <p class="text-[11px] text-on-surface-variant">Bisa berupa tautan website pendaftaran atau alamat email HRD (sistem otomatis mendeteksi email).</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="alumni_info" class="block text-xs font-bold text-primary">Catatan Rekomendasi Alumni (Opsional)</label>
                        <input type="text" id="alumni_info" name="alumni_info" value="{{ old('alumni_info') }}" 
                               placeholder="Otomatis diisi nama Anda jika dikosongkan (contoh: Direferensikan oleh Anda - SMA KPS '05)"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/40 flex items-center justify-end gap-3">
                <a href="{{ route('profile.job.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                    Kirim Lowongan untuk Direview
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
