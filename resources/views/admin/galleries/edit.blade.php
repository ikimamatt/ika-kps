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
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px] pointer-events-none group-focus-within:text-secondary transition-colors">category</span>
                        <select id="category" name="category" required 
                            class="w-full pl-10 pr-9 py-2.5 appearance-none rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-medium cursor-pointer hover:border-secondary/50">
                        @foreach(['Nostalgia', 'Reuni', 'Kegiatan', 'Olahraga', 'Sosial', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $gallery->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                        <option value="draft" {{ old('status', $gallery->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $gallery->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/60 text-[18px] pointer-events-none group-focus-within:text-secondary group-focus-within:rotate-180 transition-transform duration-200">expand_more</span>
                    </div>
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

            @php
                $externalCoverUrl = '';
                if ($gallery->cover_image_url && ! \Illuminate\Support\Str::startsWith($gallery->cover_image_url, ['/storage/', 'storage/'])) {
                    $externalCoverUrl = $gallery->cover_image_url;
                }
            @endphp

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
                        <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                    </label>
                    <input type="text" id="cover_image_url" name="cover_image_url" value="{{ old('cover_image_url', $externalCoverUrl) }}" 
                           placeholder="https://images.unsplash.com/..."
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

                <div class="p-3 rounded-xl bg-surface-container text-2xs text-on-surface-variant flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[16px] shrink-0">info</span>
                    <span>Setelah berkas foto berhasil diunggah, Anda dapat langsung mengisi dan mengubah keterangan foto (caption) pada kartu foto di bagian 3 di bawah ini.</span>
                </div>
            </div>
        </form>
    </div>

    <!-- Section 3: Daftar & Kelola Foto Yang Sudah Ada (Input Keterangan / Caption) -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 space-y-6 shadow-xs">
        <form id="batch-photos-form" action="{{ route('admin.galleries.photos.batch-update', $gallery->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-outline-variant/30 gap-3">
                <div class="flex items-start sm:items-center gap-2.5">
                    <span class="material-symbols-outlined text-secondary text-[22px] mt-0.5 sm:mt-0">photo_library</span>
                    <div>
                        <h3 class="text-sm font-bold text-primary">3. Koleksi Foto &amp; Keterangan ({{ $gallery->photos->count() }} Foto)</h3>
                        <p class="text-2xs text-on-surface-variant">Input atau ubah teks keterangan (caption) pada masing-masing foto di bawah ini, lalu klik Simpan.</p>
                    </div>
                </div>

                @if ($gallery->photos->isNotEmpty())
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-xs flex items-center justify-center gap-1.5 shrink-0">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        <span>Simpan Keterangan Foto</span>
                    </button>
                @endif
            </div>

            @if ($gallery->photos->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 pt-4">
                    @foreach ($gallery->photos as $index => $photo)
                        <div class="group relative rounded-2xl overflow-hidden bg-surface-container-low border border-outline-variant/40 shadow-2xs flex flex-col justify-between">
                            <input type="hidden" name="photos[{{ $index }}][id]" value="{{ $photo->id }}">

                            <!-- Photo Image Preview Container -->
                            <div class="relative aspect-[16/10] overflow-hidden bg-surface-container">
                                <img src="{{ $photo->image_url }}" alt="Foto #{{ $index + 1 }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                
                                <!-- Delete Button (Triggers Separate Form) -->
                                <button type="submit" form="delete-photo-{{ $photo->id }}" title="Hapus Foto dari Album"
                                        class="absolute top-2 right-2 p-1.5 rounded-lg bg-black/60 hover:bg-rose-600 text-white transition-colors flex items-center justify-center shadow-xs">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>

                                <!-- Sort Order Badge -->
                                <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-white text-2xs font-bold flex items-center gap-1">
                                    <span>Foto #{{ $index + 1 }}</span>
                                </div>

                                <!-- External view link -->
                                <a href="{{ $photo->image_url }}" target="_blank" title="Lihat Foto Ukuran Penuh"
                                   class="absolute bottom-2 right-2 p-1 rounded-md bg-black/50 hover:bg-black/80 text-white transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>

                            <!-- Caption & Order Input Box -->
                            <div class="p-3 bg-surface-container-lowest border-t border-outline-variant/30 space-y-2.5 flex-1 flex flex-col justify-between">
                                <div class="space-y-1">
                                    <label class="block text-2xs font-bold text-primary flex items-center justify-between">
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-secondary">subtitles</span>
                                            <span>Keterangan Foto (Caption)</span>
                                        </span>
                                    </label>
                                    <textarea name="photos[{{ $index }}][caption]" rows="2" 
                                              placeholder="Tulis keterangan atau momen foto ini..."
                                              class="w-full px-3 py-2 text-xs rounded-xl border border-outline-variant/60 bg-surface-container-lowest focus:ring-2 focus:ring-secondary/50 focus:border-secondary resize-none placeholder:text-on-surface-variant/40 leading-snug">{{ old("photos.{$index}.caption", $photo->caption) }}</textarea>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-outline-variant/20 gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <label class="text-2xs font-bold text-on-surface-variant">Urutan:</label>
                                        <input type="number" name="photos[{{ $index }}][sort_order]" 
                                               value="{{ old("photos.{$index}.sort_order", $photo->sort_order) }}" min="1" 
                                               class="w-14 px-2 py-1 text-xs text-center rounded-lg border border-outline-variant/60 bg-surface-container-lowest focus:ring-1 focus:ring-secondary font-medium">
                                    </div>
                                    <span class="text-2xs text-on-surface-variant/60">ID #{{ $photo->id }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Bottom Toolbar for Batch Saving -->
                <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-end">
                    <button type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Perubahan Keterangan &amp; Urutan Foto</span>
                    </button>
                </div>
            @else
                <div class="text-center py-10 text-on-surface-variant space-y-2">
                    <span class="material-symbols-outlined text-4xl text-outline-variant">image_not_supported</span>
                    <p class="text-xs font-medium">Belum ada foto dalam album ini. Silakan unggah berkas foto pada bagian di atas.</p>
                </div>
            @endif
        </form>

        <!-- Hidden Delete Forms outside batch-photos-form to prevent nested forms -->
        @foreach ($gallery->photos as $photo)
            <form id="delete-photo-{{ $photo->id }}" method="POST" action="{{ route('admin.galleries.photos.delete', $photo->id) }}" 
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto ini dari album?');" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    </div>
</div>
@endsection
