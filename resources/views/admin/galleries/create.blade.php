@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.galleries.index') }}" class="hover:text-primary transition-colors">Galeri Foto</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Buat Album Baru</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Tambah Album Kenangan Baru</h1>
        </div>
        <a href="{{ route('admin.galleries.index') }}" 
           class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.galleries.store') }}" method="POST" enctype="multipart/form-data" 
          class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 space-y-8 shadow-xs">
        @csrf

        @if ($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <span>Terdapat kesalahan pada isian form:</span>
                </div>
                <ul class="list-disc list-inside pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Section 1: Informasi Album -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                <span>1. Identitas &amp; Klasifikasi Album</span>
            </h2>

            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-primary">Judul Album <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                       placeholder="Contoh: Reuni Akbar Lintas Generasi KPS Balikpapan 2026"
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-primary">Kategori <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        @foreach(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-bold text-primary">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="event_date" class="block text-xs font-bold text-primary">
                        <span>Tanggal Dokumentasi</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                    </label>
                    <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold text-primary">
                    <span>Deskripsi &amp; Narasi Album</span>
                    <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                </label>
                <textarea id="description" name="description" rows="3" 
                          placeholder="Ceritakan latar belakang momen acara atau catatan kenangan foto-foto dalam album ini..."
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">{{ old('description') }}</textarea>
            </div>
        </div>

        <!-- Section 2: Sampul Cover Album -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">image</span>
                <span>2. Foto Sampul (Cover Album)</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="cover" class="block text-xs font-bold text-primary">
                        <span>Unggah Foto Sampul</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Format: JPG, PNG, WEBP, maks 3MB)</span>
                    </label>
                    <input type="file" id="cover" name="cover" accept="image/*" 
                           class="w-full text-xs text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-primary hover:file:bg-surface-container-high cursor-pointer">
                </div>

                <div class="space-y-1.5">
                    <label for="cover_image_url" class="block text-xs font-bold text-primary">
                        <span>Atau URL Sampul Eksternal</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                    </label>
                    <input type="url" id="cover_image_url" name="cover_image_url" value="{{ old('cover_image_url') }}" 
                           placeholder="https://images.unsplash.com/..."
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>
        </div>

        <!-- Section 3: Unggah Foto-Foto Album (Multi-Upload) -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">photo_library</span>
                <span>3. Unggah Koleksi Foto (Sekaligus / Banyak File)</span>
            </h2>

            <div class="p-5 rounded-2xl bg-surface-container-low border border-dashed border-outline-variant/80 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">cloud_upload</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-primary">Pilih Beberapa Foto Sekaligus</h4>
                        <p class="text-2xs text-on-surface-variant">Tahan tombol Ctrl (atau Cmd di Mac) saat memilih file untuk memilih lebih dari 1 foto. Maksimal 5MB per berkas foto.</p>
                    </div>
                </div>

                <input type="file" id="photos" name="photos[]" multiple accept="image/*" 
                       class="w-full text-xs text-on-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary file:text-on-primary hover:file:bg-primary-container cursor-pointer">
                
                <p class="text-2xs text-on-surface-variant italic">
                    * Catatan: Anda juga dapat menambah, menata, dan memberi caption pada foto-foto ini setelah album tersimpan melalui halaman Edit Album.
                </p>
            </div>
        </div>

        <!-- Submit Button Toolbar -->
        <div class="pt-6 border-t border-outline-variant/30 flex items-center justify-end gap-3">
            <a href="{{ route('admin.galleries.index') }}" 
               class="px-5 py-2.5 rounded-xl border border-outline-variant/60 text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Album &amp; Unggah Foto</span>
            </button>
        </div>
    </form>
</div>
@endsection
