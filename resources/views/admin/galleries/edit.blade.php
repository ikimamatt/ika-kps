@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.galleries.index') }}" class="hover:text-primary transition-colors">Galeri Foto</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Edit Album &amp; Foto</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Perbarui Album: {{ $gallery->title }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('gallery.show', $gallery->slug) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}" 
               class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Section 1: Form Edit Informasi Album -->
    <form action="{{ route('admin.galleries.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" 
          class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 space-y-8 shadow-xs">
        @csrf
        @method('PUT')

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

        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                <span>1. Informasi Utama Album</span>
            </h2>

            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-primary">Judul Album <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $gallery->title) }}" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-primary">Kategori <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        @foreach(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $gallery->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-bold text-primary">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        <option value="draft" {{ old('status', $gallery->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $gallery->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="event_date" class="block text-xs font-bold text-primary">
                        <span>Tanggal Dokumentasi</span>
                    </label>
                    <input type="date" id="event_date" name="event_date" 
                           value="{{ old('event_date', $gallery->event_date ? $gallery->event_date->format('Y-m-d') : '') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold text-primary">
                    <span>Deskripsi &amp; Narasi Album</span>
                </label>
                <textarea id="description" name="description" rows="3" 
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">{{ old('description', $gallery->description) }}</textarea>
            </div>
        </div>

        <!-- Cover Sampul -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">image</span>
                <span>2. Foto Sampul Album (Cover)</span>
            </h2>

            @if ($gallery->cover_image_url)
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-4">
                    <img src="{{ $gallery->cover_image_url }}" alt="Cover saat ini" 
                         class="w-20 h-20 rounded-xl object-cover border border-outline-variant/40 shrink-0">
                    <div class="text-xs space-y-1">
                        <span class="font-bold text-primary block">Foto Sampul Saat Ini</span>
                        <p class="text-on-surface-variant text-2xs truncate max-w-sm">{{ $gallery->cover_image_url }}</p>
                        <span class="text-2xs text-secondary font-medium">Unggah file baru di bawah ini untuk menggantinya.</span>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="cover" class="block text-xs font-bold text-primary">
                        <span>Ganti Foto Sampul</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Format: JPG, PNG, WEBP, maks 3MB)</span>
                    </label>
                    <input type="file" id="cover" name="cover" accept="image/*" 
                           class="w-full text-xs text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-primary hover:file:bg-surface-container-high cursor-pointer">
                </div>

                <div class="space-y-1.5">
                    <label for="cover_image_url" class="block text-xs font-bold text-primary">
                        <span>Atau URL Sampul Eksternal</span>
                    </label>
                    <input type="url" id="cover_image_url" name="cover_image_url" value="{{ old('cover_image_url', $gallery->cover_image_url) }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-end">
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Perubahan Informasi Album</span>
            </button>
        </div>
    </form>

    <!-- Section 2: Upload Tambahan Foto Baru -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xs">
        <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">cloud_upload</span>
                <span>Tambah Foto Baru ke Album Ini</span>
            </div>
            <span class="text-xs font-bold text-secondary">
                {{ $gallery->photos->count() }} Foto Tersimpan
            </span>
        </h2>

        <form action="{{ route('admin.galleries.photos.upload', $gallery->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="p-5 rounded-2xl bg-surface-container-low border border-dashed border-outline-variant/80 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[24px]">add_photo_alternate</span>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-primary">Pilih Berkas Foto Baru</h4>
                        <p class="text-2xs text-on-surface-variant">Dapat memilih banyak foto sekaligus (Maksimal 5MB per foto).</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <input type="file" name="photos[]" multiple required accept="image/*" 
                           class="flex-1 text-xs text-on-surface file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-container-high file:text-primary hover:file:bg-surface-container cursor-pointer">
                    
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-secondary text-on-secondary font-bold text-xs hover:bg-secondary-container transition-all shadow-xs flex items-center justify-center gap-2 shrink-0">
                        <span class="material-symbols-outlined text-[18px]">upload</span>
                        <span>Unggah Foto Sekarang</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Section 3: Daftar & Kelola Foto Yang Sudah Ada -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xs">
        <div class="flex items-center justify-between pb-2 border-b border-outline-variant/30">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[20px]">photo_library</span>
                <h3 class="text-sm font-bold text-primary">Koleksi Foto Saat Ini ({{ $gallery->photos->count() }})</h3>
            </div>
        </div>

        @if ($gallery->photos->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($gallery->photos as $index => $photo)
                    <div class="group relative rounded-2xl overflow-hidden bg-surface-container-high border border-outline-variant/40 shadow-2xs flex flex-col justify-between">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $photo->image_url }}" alt="Foto #{{ $index + 1 }}" 
                                 class="w-full h-full object-cover">
                            
                            <!-- Delete Button Overlay -->
                            <form method="POST" action="{{ route('admin.galleries.photos.delete', $photo->id) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini dari album?');"
                                  class="absolute top-2 right-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Foto"
                                        class="p-1.5 rounded-lg bg-black/60 hover:bg-rose-600 text-white transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>

                            <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 text-white text-2xs font-bold">
                                #{{ $photo->sort_order }}
                            </div>
                        </div>

                        <div class="p-2.5 bg-surface-container-lowest text-2xs border-t border-outline-variant/20">
                            @if ($photo->caption)
                                <p class="text-primary font-medium line-clamp-2" title="{{ $photo->caption }}">{{ $photo->caption }}</p>
                            @else
                                <span class="text-on-surface-variant/70 italic">Tanpa keterangan</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-on-surface-variant space-y-2">
                <span class="material-symbols-outlined text-4xl text-outline-variant">image_not_supported</span>
                <p class="text-xs font-medium">Belum ada foto dalam album ini. Silakan unggah berkas foto di atas.</p>
            </div>
        @endif
    </div>
</div>
@endsection
