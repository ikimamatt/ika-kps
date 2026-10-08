@extends('layouts.app')

@section('content')
<div class="bg-surface-container-lowest min-h-screen py-10">
    <div class="max-w-[1000px] mx-auto px-6">
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 sm:gap-2 text-xs text-on-surface-variant mb-6 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-on-surface-variant/60">chevron_right</span>
            <a href="{{ route('alumni.index') }}" class="hover:text-primary transition-colors">Direktori Alumni</a>
            <span class="material-symbols-outlined text-[14px] text-on-surface-variant/60">chevron_right</span>
            <span class="text-primary font-bold">{{ $alumnus->name }}</span>
        </nav>

        <!-- Main Profile Header Card -->
        <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-3xl overflow-hidden shadow-sm mb-8">
            <!-- Cover Banner Gradient -->
            <div class="h-40 sm:h-52 bg-gradient-to-r from-primary via-primary-container to-secondary relative">
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute bottom-4 right-4">
                    <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-black/25 backdrop-blur-md text-white border border-white/25">
                        {{ strtoupper($alumnus->level) }} {{ $alumnus->full_year ? 'Angkatan '.$alumnus->full_year : '' }}
                    </span>
                </div>
            </div>

            <!-- Profile Info Container -->
            <div class="px-5 sm:px-8 md:px-10 pb-8 relative">
                <!-- Avatar & Action Buttons Row -->
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-14 sm:-mt-20 mb-5">
                    <!-- Avatar -->
                    <div class="flex justify-center sm:justify-start">
                        <img src="{{ $alumnus->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($alumnus->name) . '&background=002D62&color=fff&size=200' }}" 
                             alt="{{ $alumnus->name }}" 
                             class="w-28 sm:w-36 h-28 sm:h-36 rounded-2xl sm:rounded-3xl object-cover ring-4 ring-white shadow-xl bg-surface-container-low shrink-0">
                    </div>

                    <!-- Action Social / Connect Buttons -->
                    <div class="flex items-center justify-center sm:justify-end gap-2.5 flex-wrap sm:mb-1">
                        @if ($alumnus->linkedin_url)
                            <a href="{{ $alumnus->linkedin_url }}" target="_blank" rel="noopener noreferrer" 
                               class="h-10 px-3.5 rounded-xl bg-surface-container-high hover:bg-[#0077B5] hover:text-white transition-all flex items-center gap-2 text-primary text-xs font-bold" title="LinkedIn Profile">
                                <x-icons.linkedin class="w-4 h-4 fill-current shrink-0" />
                                <span class="hidden sm:inline">LinkedIn</span>
                            </a>
                        @endif

                        @if ($alumnus->instagram_handle)
                            <a href="https://instagram.com/{{ ltrim($alumnus->instagram_handle, '@') }}" target="_blank" rel="noopener noreferrer" 
                               class="h-10 px-3.5 rounded-xl bg-surface-container-high hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:text-white transition-all flex items-center gap-2 text-primary text-xs font-bold" title="Instagram Profile">
                                <x-icons.instagram class="w-4 h-4 fill-current shrink-0" />
                                <span class="hidden sm:inline">{{ $alumnus->instagram_handle }}</span>
                            </a>
                        @endif

                        @if ($alumnus->phone)
                            @php
                                $waPhone = preg_replace('/[^0-9]/', '', $alumnus->phone);
                                if (str_starts_with($waPhone, '0')) {
                                    $waPhone = '62' . substr($waPhone, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $waPhone }}?text=Halo%20{{ urlencode($alumnus->name) }},%20salam%20hangat%20dari%20rekan%20alumni%20IKA%20KPS" target="_blank" rel="noopener noreferrer" 
                               class="h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 transition-all shadow-sm">
                                <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                                <span>Hubungi WhatsApp</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Name & Identity Information Block (Full Width, Never Clipped) -->
                <div class="space-y-1.5 text-center sm:text-left">
                    <div class="flex items-center justify-center sm:justify-start gap-2.5 flex-wrap">
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight font-headline-md leading-tight break-words">
                            {{ $alumnus->name }}{{ $alumnus->title ? ', '.$alumnus->title : '' }}
                        </h1>
                        @if ($alumnus->is_verified)
                            <span class="material-symbols-outlined text-emerald-600 text-[24px] shrink-0" title="Alumni Terverifikasi">verified</span>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-1 sm:gap-3 text-sm sm:text-base font-semibold text-secondary">
                        <span>{{ $alumnus->profession ?? 'Alumni Sekolah Nasional KPS' }}</span>
                        @if ($alumnus->institution)
                            <span class="hidden sm:inline text-outline-variant/60">•</span>
                            <span class="text-on-surface-variant font-medium flex items-center justify-center sm:justify-start gap-1 text-xs sm:text-sm">
                                <span class="material-symbols-outlined text-[16px] text-secondary">business</span>
                                <span>{{ $alumnus->institution }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Bio Summary -->
                @if ($alumnus->summary)
                    <div class="pt-6 border-t border-outline-variant/40">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">Tentang Alumni</h2>
                        <p class="text-sm text-on-surface leading-relaxed">
                            {{ $alumnus->summary }}
                        </p>
                    </div>
                @endif

                <!-- Meta Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 mt-6 border-t border-outline-variant/40 text-xs">
                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-on-surface-variant block mb-1">Almamater KPS</span>
                        <span class="font-bold text-primary">{{ strtoupper($alumnus->level) }} Nasional KPS Balikpapan</span>
                        @if ($alumnus->full_year)
                            <span class="text-on-surface-variant block mt-0.5">Lulus Tahun {{ $alumnus->full_year }}</span>
                        @endif
                    </div>

                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-on-surface-variant block mb-1">Domisili Sekarang</span>
                        <span class="font-bold text-primary">{{ $alumnus->domicile ?? 'Balikpapan, Kalimantan Timur' }}</span>
                    </div>

                    <div class="p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/30">
                        <span class="text-on-surface-variant block mb-1">Status Keanggotaan</span>
                        <span class="font-bold text-emerald-700 inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">verified</span>
                            Anggota Terverifikasi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Businesses Section (if any) -->
        @if ($alumnus->businesses && $alumnus->businesses->count() > 0)
            <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-3xl p-6 sm:p-8 shadow-sm mb-8">
                <div class="flex items-center justify-between pb-4 border-b border-outline-variant/40 mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-primary font-headline-sm">Usaha / Bisnis Alumni</h2>
                        <p class="text-xs text-on-surface-variant">Katalog usaha milik {{ $alumnus->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($alumnus->businesses as $biz)
                        <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/40 flex items-start gap-4">
                            @if ($biz->image_url)
                                <img src="{{ $biz->image_url }}" alt="{{ $biz->name }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">storefront</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <span class="text-[10px] uppercase font-bold text-secondary bg-secondary/10 px-2 py-0.5 rounded-md">{{ $biz->category }}</span>
                                <h3 class="font-bold text-sm text-primary mt-1 truncate">{{ $biz->name }}</h3>
                                <p class="text-xs text-on-surface-variant line-clamp-1 mt-0.5">{{ $biz->description }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('alumni.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-outline-variant text-primary font-bold text-sm hover:bg-surface-container-low transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Direktori Alumni</span>
            </a>
        </div>
    </div>
</div>
@endsection
