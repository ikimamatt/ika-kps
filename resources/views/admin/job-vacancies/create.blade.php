@extends('layouts.admin')

@section('content')
<div class="max-w-[860px] mx-auto px-6 py-10 space-y-8">
    <div class="flex items-center justify-between gap-4 pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('admin.job-vacancies.index') }}" class="hover:underline">Bursa Kerja</a>
                <span>/</span>
                <span>Tambah Lowongan Baru</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Tambah Lowongan Baru</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Publikasikan lowongan kerja atau program magang dari mitra/kurasi pengurus.</p>
        </div>
        <a href="{{ route('admin.job-vacancies.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm space-y-1">
            <div class="flex items-center gap-2 font-bold text-rose-900">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700 pl-6">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('admin.job-vacancies.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <h3 class="font-bold text-sm text-primary uppercase tracking-wider border-b border-outline-variant/30 pb-2">
                    Informasi Utama Pekerjaan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label for="title" class="block text-xs font-bold text-primary">Judul Posisi / Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                               placeholder="Contoh: Electrical Specialist (Offshore Project)"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>

                    <div class="space-y-1.5">
                        <label for="company" class="block text-xs font-bold text-primary">Nama Perusahaan / Organisasi <span class="text-rose-500">*</span></label>
                        <input type="text" id="company" name="company" value="{{ old('company') }}" required 
                               placeholder="Contoh: PT Pertamina Hulu Mahakam"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>

                    <div class="space-y-1.5">
                        <label for="job_type" class="block text-xs font-bold text-primary">Tipe Pekerjaan <span class="text-rose-500">*</span></label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">work</span>
                            <select id="job_type" name="job_type" required 
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-medium cursor-pointer hover:border-secondary/50">
                            <option value="Full Time" {{ old('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time (Penuh Waktu)</option>
                            <option value="Part Time" {{ old('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time (Paruh Waktu)</option>
                            <option value="Magang" {{ old('job_type') === 'Magang' ? 'selected' : '' }}>Magang / Internship</option>
                            <option value="Kontrak" {{ old('job_type') === 'Kontrak' ? 'selected' : '' }}>Kontrak Proyek</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="location" class="block text-xs font-bold text-primary">Lokasi Penempatan <span class="text-rose-500">*</span></label>
                        <input type="text" id="location" name="location" value="{{ old('location', 'Balikpapan') }}" required 
                               placeholder="Contoh: Balikpapan / IKN Nusantara"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>

                    <div class="space-y-1.5">
                        <label for="salary_range" class="block text-xs font-bold text-primary">Rentang Gaji / Kompensasi (Opsional)</label>
                        <input type="text" id="salary_range" name="salary_range" value="{{ old('salary_range') }}" 
                               placeholder="Contoh: Rp 9.000.000 - Rp 15.000.000"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>

                    <div class="space-y-1.5">
                        <label for="status" class="block text-xs font-bold text-primary">Status Tayang</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                            <select id="status" name="status" 
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-medium cursor-pointer hover:border-secondary/50">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif (Langsung Tayang)</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="alumnus_id" class="block text-xs font-bold text-primary">Tautkan ke Alumni Pengusung (Opsional)</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">person</span>
                            <select id="alumnus_id" name="alumnus_id" 
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-medium cursor-pointer hover:border-secondary/50">
                            <option value="">-- Kurasi Pengurus Pusat IKA KPS --</option>
                            @foreach ($alumni as $al)
                                <option value="{{ $al->id }}" {{ old('alumnus_id') == $al->id ? 'selected' : '' }}>
                                    {{ $al->name }} ({{ strtoupper($al->level) }} {{ $al->full_year ?? $al->class_year }})
                                </option>
                            @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="font-bold text-sm text-primary uppercase tracking-wider border-b border-outline-variant/30 pb-2">
                    Rincian Deskripsi &amp; Lamaran
                </h3>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="description" class="block text-xs font-bold text-primary">Deskripsi Pekerjaan <span class="text-rose-500">*</span></label>
                        <textarea id="description" name="description" rows="4" required 
                                  placeholder="Jelaskan peran pekerjaan dan lingkup tanggung jawab utama..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('description') }}</textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label for="requirements" class="block text-xs font-bold text-primary">Persyaratan &amp; Kualifikasi</label>
                        <textarea id="requirements" name="requirements" rows="4" 
                                  placeholder="Contoh kriteria dan persyaratan pelamar..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="deadline" class="block text-xs font-bold text-primary">Batas Akhir Lamaran</label>
                            <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        </div>

                        <div class="space-y-1.5">
                            <label for="apply_url" class="block text-xs font-bold text-primary">Link Lamaran / Email HRD <span class="text-rose-500">*</span></label>
                            <input type="text" id="apply_url" name="apply_url" value="{{ old('apply_url') }}" required 
                                   placeholder="Contoh: https://careers.company.com atau hrd@company.com"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                            <p class="text-[11px] text-on-surface-variant">Bisa berupa tautan website atau email HRD (sistem otomatis mendeteksi email).</p>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="alumni_info" class="block text-xs font-bold text-primary">Catatan / Referensi Alumni (Label Tampilan)</label>
                        <input type="text" id="alumni_info" name="alumni_info" value="{{ old('alumni_info') }}" 
                               placeholder="Contoh: Kurasi Pengurus Pusat IKA KPS atau Direferensikan oleh Alumni SMA KPS 2005"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/40 flex items-center justify-end gap-3">
                <a href="{{ route('admin.job-vacancies.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                    Simpan &amp; Publikasikan Lowongan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
