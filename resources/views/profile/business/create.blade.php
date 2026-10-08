@extends('layouts.app')

@section('content')
<div class="max-w-[850px] mx-auto px-6 py-10 space-y-8">
    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-secondary">
                <a href="{{ route('profile.business.index') }}" class="hover:underline">Bisnis Alumni</a>
                <span>/</span>
                <span>Pendaftaran Mandiri</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary mt-1">Daftarkan Usaha / Bisnis Alumni</h1>
            <p class="font-body-md text-xs text-on-surface-variant">Publikasikan bisnis atau UMKM Anda ke dalam direktori resmi IKA KPS.</p>
        </div>
        <a href="{{ route('profile.business.index') }}" class="px-4 py-2 rounded-xl border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container transition-colors">
            Kembali
        </a>
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
        <form action="{{ route('profile.business.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Foto / Banner Usaha with Live Preview -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 p-5 rounded-2xl bg-surface-container-low border border-outline-variant/40">
                <div class="relative shrink-0 group">
                    <img id="biz-preview" 
                         src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=300" 
                         alt="Pratinjau Foto Usaha" 
                         class="w-28 h-28 rounded-2xl object-cover ring-2 ring-secondary/40 shadow-sm bg-surface-container">
                    <label for="image" 
                           class="absolute -bottom-2 -right-2 w-8 h-8 rounded-xl bg-primary text-on-primary hover:bg-secondary transition-all shadow-md flex items-center justify-center cursor-pointer" 
                           title="Pilih Foto Usaha">
                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                    </label>
                </div>
                <div class="flex-1 w-full text-center sm:text-left">
                    <label class="block font-label-md text-xs font-bold text-primary uppercase tracking-wider mb-1">
                        Foto / Banner Usaha
                    </label>
                    <p class="text-xs text-on-surface-variant mb-3">
                        Format: <strong>JPG, PNG, WEBP</strong>. Maksimal <strong>2 MB</strong>. Tampilkan foto produk, tempat usaha, atau logo terbaik Anda.
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                        <label for="image" 
                               class="px-4 py-2 rounded-xl bg-surface-container-lowest border border-outline-variant/60 hover:border-secondary text-primary font-bold text-xs cursor-pointer inline-flex items-center gap-1.5 shadow-sm transition-all hover:bg-surface-container">
                            <span class="material-symbols-outlined text-[16px]">upload_file</span>
                            <span id="biz-file-name">Pilih File Gambar...</span>
                        </label>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewBizImage(event)">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nama Usaha -->
                <div>
                    <label for="name" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nama Usaha / Brand *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Kopi Prapatan Roastery"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <!-- Kategori Usaha -->
                <div>
                    <label for="category" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kategori Industri *</label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        <option value="">Pilih Kategori...</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <label for="whatsapp_number" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nomor WhatsApp Pemesanan *</label>
                    <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', auth()->user()->phone ?? '') }}" required
                           placeholder="Contoh: 081234567890"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    <span class="text-[11px] text-on-surface-variant mt-1 block">Akan otomatis menjadi tombol WhatsApp di katalog publik.</span>
                </div>

                <!-- Kota Domisili Usaha -->
                <div>
                    <label for="city" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Kota Operasional</label>
                    <input type="text" id="city" name="city" value="{{ old('city', 'Balikpapan') }}"
                           placeholder="Balikpapan / Samarinda / Jakarta..."
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <!-- Alamat Fisik Usaha -->
            <div>
                <label for="address" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Alamat Usaha (Opsional)</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}"
                       placeholder="Contoh: Jl. MT Haryono No. 45, Balikpapan"
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <!-- Website / Portofolio URL -->
            <div>
                <label for="website_url" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Tautan Website / Instagram Bisnis (Opsional)</label>
                <input type="url" id="website_url" name="website_url" value="{{ old('website_url') }}"
                       placeholder="https://instagram.com/namabisnis atau https://namabisnis.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <!-- Deskripsi Usaha -->
            <div>
                <label for="description" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Deskripsi Usaha &amp; Penawaran Khusus Alumni *</label>
                <textarea id="description" name="description" rows="4" required
                          placeholder="Jelaskan produk atau jasa yang ditawarkan. Sertakan jika ada diskon atau perlakuan khusus untuk sesama alumni KPS!"
                          class="w-full p-4 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('description') }}</textarea>
            </div>

            <div class="pt-4 border-t border-outline-variant/40 flex items-center justify-between">
                <span class="text-[11px] text-on-surface-variant">
                    Usaha yang diajukan akan ditinjau oleh pengurus IKA KPS sebelum tayang publik.
                </span>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs uppercase tracking-wider hover:bg-primary-container transition-all shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Ajukan Usaha</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewBizImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('biz-preview');
                if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);

            const fileNameSpan = document.getElementById('biz-file-name');
            if (fileNameSpan) {
                fileNameSpan.textContent = file.name;
            }
        }
    }
</script>
@endpush
