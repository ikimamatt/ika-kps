@extends('layouts.app')

@section('content')
<div class="max-w-[850px] mx-auto px-6 py-10 space-y-8">
    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('profile.show') }}" class="hover:underline">Profil</a>
                <span>/</span>
                <a href="{{ route('profile.business.index') }}" class="hover:underline">Bisnis Alumni</a>
                <span>/</span>
                <span>Edit Usaha</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Edit Informasi Usaha Alumni</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Perbarui profil unit bisnis atau informasi kontak usaha Anda: {{ $business->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if ($business->status === 'published')
                <a href="{{ route('business.show', $business->slug) }}" target="_blank"
                   class="px-4 py-2 rounded-xl border border-secondary text-secondary hover:bg-secondary/10 text-xs font-bold transition-colors inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">visibility</span>
                    <span>Lihat Publik</span>
                </a>
            @endif
            <a href="{{ route('profile.business.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
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

    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-6 sm:p-8 shadow-sm">
        <form action="{{ route('profile.business.update', $business->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Foto / Banner Usaha with Live Preview -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 p-5 rounded-2xl bg-surface-container-low border border-outline-variant/40">
                <div class="relative shrink-0 group">
                    <img id="biz-preview" 
                         src="{{ $business->image_url ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300' }}" 
                         alt="{{ $business->name }}" 
                         class="w-28 h-28 rounded-2xl object-cover ring-2 ring-secondary/40 shadow-sm bg-surface-container">
                    <label for="image" 
                           class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-primary text-on-primary hover:bg-secondary transition-all shadow-md flex items-center justify-center cursor-pointer" 
                           title="Ganti Foto Usaha">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </label>
                </div>
                <div class="flex-1 w-full text-center sm:text-left">
                    <label class="block font-label-md text-xs font-bold text-primary uppercase tracking-wider mb-1">
                        Foto / Banner Usaha
                    </label>
                    <p class="text-xs text-on-surface-variant mb-3">
                        Format: <strong>JPG, PNG, WEBP</strong>. Maksimal <strong>2 MB</strong>. Kosongkan jika tidak ingin mengganti foto yang sudah ada.
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <label for="image" 
                               class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant/60 hover:border-secondary text-primary font-bold text-xs cursor-pointer inline-flex items-center gap-1.5 shadow-sm transition-all hover:bg-surface-container">
                            <span class="material-symbols-outlined text-[16px]">upload_file</span>
                            <span id="biz-file-name">Pilih Foto Baru...</span>
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" class="hidden" 
                               onchange="previewBizImage(this)">
                    </div>
                </div>
            </div>

            <!-- Detail Informasi Bisnis -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="name" class="block font-label-md text-xs font-bold text-primary">
                        Nama Usaha / Brand <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $business->name) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <div class="space-y-1.5">
                    <label for="category" class="block font-label-md text-xs font-bold text-primary">
                        Kategori Usaha <span class="text-rose-500">*</span>
                    </label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $business->category) === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="whatsapp_number" class="block font-label-md text-xs font-bold text-primary">
                        Nomor WhatsApp Bisnis <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $business->whatsapp_number) }}" required
                           placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <div class="space-y-1.5">
                    <label for="city" class="block font-label-md text-xs font-bold text-primary">
                        Kota / Domisili Usaha <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="city" name="city" value="{{ old('city', $business->city) }}" required
                           placeholder="Contoh: Balikpapan / IKN Nusantara"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <div class="space-y-1.5">
                    <label for="address" class="block font-label-md text-xs font-bold text-primary">
                        Alamat Usaha / Toko (Opsional)
                    </label>
                    <input type="text" id="address" name="address" value="{{ old('address', $business->address) }}"
                           placeholder="Contoh: Ruko Bandar Balikpapan Blok A"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>

                <div class="sm:col-span-2 space-y-1.5">
                    <label for="description" class="block font-label-md text-xs font-bold text-primary">
                        Deskripsi Usaha &amp; Penawaran Produk <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all leading-relaxed">{{ old('description', $business->description) }}</textarea>
                </div>

                <div class="space-y-1.5">
                    <label for="website_url" class="block font-label-md text-xs font-bold text-primary">
                        Website / Katalog Online (Opsional)
                    </label>
                    <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $business->website_url) }}"
                           placeholder="https://toko-anda.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all">
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/40 flex items-center justify-end gap-3">
                <a href="{{ route('profile.business.index') }}" 
                   class="px-5 py-2.5 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm">
                    Simpan Perubahan Usaha
                </button>
            </div>
        </form>
    </div>
</div>

@pushOnce('scripts')
<script>
    function previewBizImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('biz-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
            document.getElementById('biz-file-name').innerText = input.files[0].name;
        }
    }
</script>
@endPushOnce
@endsection
