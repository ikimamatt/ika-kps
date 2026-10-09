@extends('layouts.app')

@section('content')
<div class="max-w-[1100px] mx-auto px-6 py-10 space-y-8">
    <!-- Breadcrumbs & Back -->
    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40">
        <a href="{{ route('business.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Katalog Bisnis</span>
        </a>
        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-secondary/10 text-secondary border border-secondary/20">
            {{ $business->category }}
        </span>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-sm grid grid-cols-1 lg:grid-cols-12 gap-0">
        <!-- Media / Image Column -->
        <div class="lg:col-span-5 bg-surface-container relative min-h-[300px] lg:min-h-full">
            @if ($business->image_url)
                <img src="{{ $business->image_url }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full min-h-[300px] flex items-center justify-center bg-primary/5 text-primary">
                    <span class="material-symbols-outlined text-7xl">storefront</span>
                </div>
            @endif
        </div>

        <!-- Info Column -->
        <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between space-y-6">
            <div class="space-y-4">
                <div>
                    <h1 class="font-headline-sm text-2xl sm:text-3xl font-extrabold text-primary">
                        {{ $business->name }}
                    </h1>
                    @if ($business->owner_info)
                        <div class="flex items-center gap-2 mt-2 text-xs text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                            <span>Pemilik: <strong>{{ $business->owner_info }}</strong></span>
                            @if ($business->alumnus)
                                <a href="{{ route('alumni.show', $business->alumnus->slug) }}" class="text-secondary font-bold hover:underline">
                                    (Lihat Profil)
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="prose text-xs sm:text-sm text-on-surface leading-relaxed border-t border-outline-variant/30 pt-4">
                    {!! nl2br(e($business->description)) !!}
                </div>

                <!-- Info Kontak & Lokasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 text-xs">
                    @if ($business->city || $business->address)
                        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">location_on</span>
                            <div>
                                <span class="font-bold text-on-surface block">Lokasi &amp; Alamat</span>
                                <span class="text-on-surface-variant text-[11px] mt-0.5 block">
                                    {{ $business->address ? $business->address . ', ' : '' }}{{ $business->city }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($business->whatsapp_number || $business->phone)
                        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-start gap-2.5">
                            <span class="material-symbols-outlined text-emerald-600 text-[20px] shrink-0 mt-0.5">call</span>
                            <div>
                                <span class="font-bold text-on-surface block">Kontak Langsung</span>
                                <span class="text-on-surface-variant text-[11px] mt-0.5 block">
                                    {{ $business->whatsapp_number ?? $business->phone }}
                                </span>
                            </div>
                        </div>
                    @endif

                    @if ($business->website_url)
                        <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/30 flex items-start gap-2.5 sm:col-span-2">
                            <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">language</span>
                            <div>
                                <span class="font-bold text-on-surface block">Situs Web Resmi</span>
                                <a href="{{ $business->website_url }}" target="_blank" rel="noopener noreferrer" class="text-secondary hover:underline text-[11px] mt-0.5 block truncate">
                                    {{ $business->website_url }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-outline-variant/40 flex flex-wrap items-center gap-3">
                @php
                    $waNumber = $business->whatsapp_number ?: $business->phone;
                    $cleanNumber = $waNumber ? preg_replace('/[^0-9]/', '', $waNumber) : '';
                    if (str_starts_with($cleanNumber, '0')) {
                        $cleanNumber = '62' . substr($cleanNumber, 1);
                    }
                    $defaultWaLink = $cleanNumber ? 'https://wa.me/' . $cleanNumber . '?text=' . urlencode('Halo ' . $business->name . ', saya alumni KPS ingin bertanya mengenai layanan/produk.') : null;
                    $finalActionUrl = $business->action_link ?: $defaultWaLink;
                @endphp

                @if ($finalActionUrl)
                    <a href="{{ $finalActionUrl }}" target="_blank" rel="noopener noreferrer"
                       class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm">
                        <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                        <span>{{ $business->action_label ?: 'Hubungi via WhatsApp' }}</span>
                    </a>
                @endif

                @if ($business->website_url)
                    <a href="{{ $business->website_url }}" target="_blank" rel="noopener noreferrer"
                       class="px-5 py-3 rounded-xl border border-outline-variant text-primary font-bold text-xs uppercase tracking-wider hover:bg-surface-container transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        <span>Buka Website</span>
                    </a>
                @endif

                <button type="button" 
                        onclick="copyBusinessLink(this)"
                        class="px-5 py-3 rounded-xl border border-outline-variant text-primary font-bold text-xs uppercase tracking-wider hover:bg-surface-container transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px] copy-icon">share</span>
                    <span class="copy-text">Bagikan Usaha</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Related Businesses -->
    @if ($relatedBusinesses->isNotEmpty())
        <div class="pt-6 space-y-4">
            <h2 class="font-headline-sm text-lg font-bold text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[22px]">store</span>
                <span>Usaha Lain di Kategori {{ $business->category }}</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach ($relatedBusinesses as $rel)
                    <a href="{{ route('business.show', $rel->slug) }}" 
                       class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-sm hover:border-secondary/50 transition-all block group">
                        <div class="h-32 w-full bg-surface-container rounded-xl overflow-hidden mb-3">
                            @if ($rel->image_url)
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined text-3xl">storefront</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm text-primary group-hover:text-secondary truncate">{{ $rel->name }}</h3>
                        <p class="text-[11px] text-on-surface-variant line-clamp-1 mt-1">{{ $rel->description }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function copyBusinessLink(btn) {
    window.copyToClipboard(window.location.href).then(() => {
        const iconSpan = btn.querySelector('.copy-icon');
        const textSpan = btn.querySelector('.copy-text');
        
        if (iconSpan) iconSpan.innerText = 'check';
        if (textSpan) textSpan.innerText = 'Tersalin untuk Rekan! ✨';
        btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');

        setTimeout(() => {
            if (iconSpan) iconSpan.innerText = 'share';
            if (textSpan) textSpan.innerText = 'Bagikan Usaha';
            btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        }, 2500);
    }).catch(err => {
        alert('Gagal menyalin tautan: ' + err);
    });
}
</script>
@endpush
@endsection
