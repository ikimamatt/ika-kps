@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.articles.index') }}" class="hover:text-primary transition-colors">Berita &amp; Artikel</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Tulis Artikel Baru</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Tulis Artikel / Berita Baru</h1>
        </div>
        <a href="{{ route('admin.articles.index') }}" 
           class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" 
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

        <!-- Section 1: Informasi & Klasifikasi -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                <span>1. Informasi Utama &amp; Publikasi</span>
            </h2>

            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-primary">Judul Artikel / Berita <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                       placeholder="Contoh: Reuni Akbar Lintas Generasi KPS Balikpapan 2026"
                       oninput="updateArticleLivePreview()"
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-primary">Kategori <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">category</span>
                        <select id="category" name="category" required 
                                onchange="updateArticleLivePreview()"
                                class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-secondary/20 focus:border-secondary hover:border-secondary/50 transition-all cursor-pointer">
                            @foreach(['Kegiatan', 'Nostalgia', 'Prestasi', 'Sosial', 'Opini', 'Pengumuman'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', 'Kegiatan') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-bold text-primary">Status Publikasi <span class="text-rose-500">*</span></label>
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">verified_user</span>
                        <select id="status" name="status" required 
                            class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-medium cursor-pointer hover:border-secondary/50">
                        <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Langsung Terbitkan (Published)</option>
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="published_at" class="block text-xs font-bold text-primary">Tanggal Rilis (Opsional)</label>
                    <input type="date" id="published_at" name="published_at" value="{{ old('published_at', now()->toDateString()) }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="author_and_date" class="block text-xs font-bold text-primary">Label Penulis &amp; Keterangan (Opsional)</label>
                    <input type="text" id="author_and_date" name="author_and_date" value="{{ old('author_and_date') }}" 
                           placeholder="Contoh: Tim Humas IKA KPS · 15 Jan 2026"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="slug" class="block text-xs font-bold text-primary">Custom Slug URL (Opsional)</label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug') }}" 
                           placeholder="Kosongkan untuk otomatis dibuat dari judul"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-mono">
                </div>
            </div>
        </div>

        <!-- Section 2: Ringkasan & Narasi Lengkap -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">edit_note</span>
                <span>2. Ringkasan &amp; Isi Tulisan</span>
            </h2>

            <div class="space-y-1.5">
                <label for="summary" class="block text-xs font-bold text-primary">Ringkasan Singkat (Lead / Cuplikan Kartu) <span class="text-rose-500">*</span></label>
                <textarea id="summary" name="summary" rows="3" required 
                          placeholder="Jelaskan 1-3 kalimat intisari berita ini sebagai cuplikan pada kartu tampilan..."
                          oninput="updateArticleLivePreview()"
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary leading-relaxed">{{ old('summary') }}</textarea>
            </div>

            <div class="space-y-1.5">
                <label for="body" class="block text-xs font-bold text-primary">Isi Lengkap Artikel (Narasi Penuh)</label>
                <textarea id="body" name="body" rows="10" 
                          placeholder="Tuliskan isi berita atau kenangan nostalgia selengkapnya. Gunakan baris baru antar paragraf..."
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary leading-relaxed font-sans">{{ old('body') }}</textarea>
            </div>
        </div>

        <!-- Section 3: Gambar Sampul & Pratinjau -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">image</span>
                <span>3. Foto Sampul &amp; Pratinjau Tampilan</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 items-start">
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label for="image" class="block text-xs font-bold text-primary">Unggah Foto Sampul (Maks 2MB, JPG/PNG/WebP)</label>
                        <input type="file" id="image" name="image" accept="image/*" 
                               onchange="previewUploadedImage(event)"
                               class="w-full px-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-container">
                    </div>

                    <div class="space-y-1.5">
                        <label for="image_url" class="block text-xs font-bold text-primary">Atau Gunakan Tautan URL Gambar Eksternal</label>
                        <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" 
                               placeholder="https://images.unsplash.com/photo-..."
                               oninput="previewUrlImage(this.value)"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>
                </div>

                <!-- Live Card Preview -->
                <div class="space-y-2">
                    <span class="text-xs font-bold text-primary block">Pratinjau Kartu di Web Publik:</span>
                    <div class="rounded-2xl overflow-hidden border border-outline-variant/50 bg-surface-container-lowest shadow-xs">
                        <div class="relative h-36 bg-surface-container overflow-hidden">
                            <img id="live-preview-image" 
                                 src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600" 
                                 alt="Preview" 
                                 class="w-full h-full object-cover">
                            <span id="live-preview-badge" class="absolute top-2.5 left-2.5 px-2.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-500/10 text-blue-600">
                                Kegiatan
                            </span>
                        </div>
                        <div class="p-3.5 space-y-1.5">
                            <h4 id="live-preview-title" class="text-xs font-bold text-primary line-clamp-2">
                                Judul artikel akan muncul di sini...
                            </h4>
                            <p id="live-preview-summary" class="text-[10px] text-on-surface-variant line-clamp-2 leading-relaxed">
                                Ringkasan cuplikan berita akan ditampilkan di bagian ini...
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button Footer -->
        <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-end gap-3">
            <a href="{{ route('admin.articles.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan &amp; Publikasikan Artikel</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateArticleLivePreview() {
    const title = document.getElementById('title').value || 'Judul artikel akan muncul di sini...';
    const summary = document.getElementById('summary').value || 'Ringkasan cuplikan berita akan ditampilkan di bagian ini...';
    const category = document.getElementById('category').value;

    document.getElementById('live-preview-title').innerText = title;
    document.getElementById('live-preview-summary').innerText = summary;
    
    const badge = document.getElementById('live-preview-badge');
    badge.innerText = category;
}

function previewUploadedImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('live-preview-image').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function previewUrlImage(url) {
    if (url && url.startsWith('http')) {
        document.getElementById('live-preview-image').src = url;
    }
}
</script>
@endpush
