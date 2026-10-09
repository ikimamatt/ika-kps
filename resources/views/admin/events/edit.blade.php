@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.events.index') }}" class="hover:text-primary transition-colors">Agenda &amp; Event</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Edit Event</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Perbarui Agenda Kegiatan</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('event.show', $event->slug) }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Publik</span>
            </a>
            <a href="{{ route('admin.events.index') }}" 
               class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" 
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

        <!-- Section 1: Informasi Utama & Klasifikasi -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                <span>1. Informasi Utama Kegiatan</span>
            </h2>

            <div class="space-y-1.5">
                <label for="title" class="block text-xs font-bold text-primary">Nama Kegiatan / Event <span class="text-rose-500">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <label for="category" class="block text-xs font-bold text-primary">Kategori <span class="text-rose-500">*</span></label>
                    <select id="category" name="category" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        <option value="reuni" {{ old('category', $event->category) === 'reuni' ? 'selected' : '' }}>Reuni &amp; Silaturahmi</option>
                        <option value="baksos" {{ old('category', $event->category) === 'baksos' ? 'selected' : '' }}>Bakti Sosial &amp; Charity</option>
                        <option value="seminar" {{ old('category', $event->category) === 'seminar' ? 'selected' : '' }}>Seminar &amp; Workshop</option>
                        <option value="olahraga" {{ old('category', $event->category) === 'olahraga' ? 'selected' : '' }}>Olahraga &amp; Komunitas</option>
                        <option value="lainnya" {{ old('category', $event->category) === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-bold text-primary">Status Publikasi <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                        <option value="draft" {{ old('status', $event->status) === 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                        <option value="published" {{ old('status', $event->status) === 'published' ? 'selected' : '' }}>Terbitkan Langsung</option>
                        <option value="cancelled" {{ old('status', $event->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="slug" class="block text-xs font-bold text-primary">
                        <span>Slug URL</span>
                    </label>
                    <input type="text" id="slug" name="slug" value="{{ old('slug', $event->slug) }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>
        </div>

        <!-- Section 2: Waktu, Lokasi & Partisipasi -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">event</span>
                <span>2. Jadwal, Tempat &amp; Batas Peserta</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="start_date" class="block text-xs font-bold text-primary">Waktu Mulai <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" id="start_date" name="start_date" 
                           value="{{ old('start_date', $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}" required 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>

                <div class="space-y-1.5">
                    <label for="end_date" class="block text-xs font-bold text-primary">
                        <span>Waktu Selesai</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                    </label>
                    <input type="datetime-local" id="end_date" name="end_date" 
                           value="{{ old('end_date', $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="location" class="block text-xs font-bold text-primary">Lokasi / Tempat Acara <span class="text-rose-500">*</span></label>
                <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" required 
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="max_participants" class="block text-xs font-bold text-primary">
                        <span>Kapasitas / Kuota Peserta</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Kosongkan jika tanpa batasan)</span>
                    </label>
                    <input type="number" id="max_participants" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>

                <div class="space-y-1.5">
                    <label for="fee" class="block text-xs font-bold text-primary">
                        <span>Biaya Partisipasi (Rp)</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(0 jika gratis)</span>
                    </label>
                    <input type="number" id="fee" name="fee" value="{{ old('fee', (int)$event->fee) }}" min="0" step="1000" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>
        </div>

        <!-- Section 3: Deskripsi & Pamflet Poster -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">article</span>
                <span>3. Deskripsi &amp; Pamflet Event</span>
            </h2>

            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold text-primary">Ringkasan Kegiatan <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="3" required 
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="space-y-1.5">
                <label for="body" class="block text-xs font-bold text-primary">
                    <span>Rincian &amp; Susunan Acara Lengkap</span>
                    <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                </label>
                <textarea id="body" name="body" rows="6" 
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">{{ old('body', $event->body) }}</textarea>
            </div>

            @if ($event->image_url)
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center gap-4">
                    <img src="{{ $event->image_url }}" alt="Poster saat ini" 
                         class="w-20 h-20 rounded-xl object-cover border border-outline-variant/40 shrink-0">
                    <div class="text-xs space-y-1">
                        <span class="font-bold text-primary block">Pamflet Poster Saat Ini</span>
                        <p class="text-on-surface-variant text-2xs truncate max-w-sm">{{ $event->image_url }}</p>
                        <span class="text-2xs text-secondary font-medium">Unggah file baru di bawah ini untuk menggantinya.</span>
                    </div>
                </div>
            @endif

            @php
                $externalEventUrl = '';
                if ($event->image_url && ! \Illuminate\Support\Str::startsWith($event->image_url, ['/storage/', 'storage/'])) {
                    $externalEventUrl = $event->image_url;
                }
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="image" class="block text-xs font-bold text-primary">
                        <span>Ganti Pamflet / Poster</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Format: JPG, PNG, WEBP, maks 2MB)</span>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" 
                           class="w-full text-xs text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-surface-container file:text-primary hover:file:bg-surface-container-high cursor-pointer">
                </div>

                <div class="space-y-1.5">
                    <label for="image_url" class="block text-xs font-bold text-primary">
                        <span>Atau URL Poster Banner Eksternal</span>
                        <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                    </label>
                    <input type="text" id="image_url" name="image_url" value="{{ old('image_url', $externalEventUrl) }}" 
                           placeholder="https://images.unsplash.com/photo-..."
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50">
                </div>
            </div>
        </div>

        <!-- Submit Button Toolbar -->
        <div class="pt-6 border-t border-outline-variant/30 flex items-center justify-end gap-3">
            <a href="{{ route('admin.events.index') }}" 
               class="px-5 py-2.5 rounded-xl border border-outline-variant/60 text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Perbarui Agenda Event</span>
            </button>
        </div>
    </form>
</div>
@endsection
