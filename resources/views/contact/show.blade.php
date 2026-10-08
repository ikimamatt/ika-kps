@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">

        <!-- Breadcrumbs Navigation -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary font-bold">Hubungi Kami</span>
        </nav>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 flex items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-800 flex items-center gap-3 text-xs">
                <span class="material-symbols-outlined text-rose-600 shrink-0">error</span>
                <div>
                    <span class="font-bold">Mohon periksa kembali isian form Anda:</span>
                    <ul class="list-disc list-inside mt-1 space-y-0.5">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Header Hero -->
        <div class="max-w-3xl space-y-3 pb-6 border-b border-outline-variant/30">
            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20 inline-flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">support_agent</span>
                <span>Pusat Komunikasi &amp; Bantuan</span>
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-primary tracking-tight">
                Hubungi Pengurus IKA KPS Balikpapan
            </h1>
            <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                Punya pertanyaan seputar keanggotaan, usulan program kerja, kerjasama sponsorship, atau ingin bersilaturahmi dengan pengurus? Kirimkan pesan Anda melalui formulir di bawah ini.
            </p>
        </div>

        <!-- Main Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Form Column (7 Cols) -->
            <div class="lg:col-span-7 bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">mail</span>
                        <span>Formulir Kirim Pesan</span>
                    </h2>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Setiap pesan yang masuk akan langsung diteruskan ke kotak masuk Sekretariat IKA KPS.
                    </p>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Honeypot anti-spam (tersembunyi untuk bot) -->
                    <div class="hidden" style="display: none !important;" aria-hidden="true">
                        <label for="hp_check">Jangan isi kolom ini jika Anda manusia:</label>
                        <input type="text" id="hp_check" name="hp_check" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="name" class="block text-xs font-bold text-primary">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', auth()->user()?->name) }}" required 
                                   placeholder="Contoh: Andi Pratama"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        </div>

                        <div class="space-y-1.5">
                            <label for="email" class="block text-xs font-bold text-primary">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', auth()->user()?->email) }}" required 
                                   placeholder="andi.pratama@example.com"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label for="phone" class="block text-xs font-bold text-primary">
                                <span>Nomor WhatsApp / HP</span>
                                <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                            </label>
                            <input type="tel" id="phone" name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="081234567890"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        </div>

                        <div class="space-y-1.5">
                            <label for="subject" class="block text-xs font-bold text-primary">Perihal Pesan <span class="text-rose-500">*</span></label>
                            <input type="text" id="subject" name="subject" 
                                   value="{{ old('subject') }}" required 
                                   placeholder="Contoh: Konfirmasi Keanggotaan Angkatan 2012"
                                   class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="message" class="block text-xs font-bold text-primary">Isi Pesan / Pertanyaan <span class="text-rose-500">*</span></label>
                        <textarea id="message" name="message" rows="5" required 
                                  placeholder="Tuliskan pesan, pertanyaan, atau usulan Anda dengan jelas di sini..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary leading-relaxed">{{ old('message') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            <span>Kirim Pesan Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Info & Secretariate Column (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Secretariate Info Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-7 shadow-xs space-y-5">
                    <h3 class="text-sm font-bold text-primary uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-outline-variant/30">
                        <span class="material-symbols-outlined text-secondary text-[20px]">account_balance</span>
                        <span>Sekretariat IKA KPS</span>
                    </h3>

                    <div class="space-y-4 text-xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-secondary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[18px]">location_on</span>
                            </div>
                            <div>
                                <strong class="text-primary block font-bold">Alamat Kantor:</strong>
                                <span class="text-on-surface-variant leading-relaxed block mt-0.5">
                                    {{ \App\Models\Setting::get('contact_address', 'Kompleks Sekolah Nasional KPS Balikpapan, Jl. Prapatan No. 01, Telaga Sari, Kec. Balikpapan Kota, Kota Balikpapan, Kalimantan Timur 76112') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-secondary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[18px]">mail</span>
                            </div>
                            <div>
                                <strong class="text-primary block font-bold">Email Resmi:</strong>
                                @php
                                    $contactEmail = \App\Models\Setting::get('contact_email', 'sekretariat@ika-kps.id');
                                @endphp
                                <a href="mailto:{{ $contactEmail }}" class="text-secondary font-semibold hover:underline block mt-0.5">
                                    {{ $contactEmail }}
                                </a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-secondary shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                            </div>
                            <div>
                                <strong class="text-primary block font-bold">Jam Layanan Pengurus:</strong>
                                <span class="text-on-surface-variant block mt-0.5">
                                    Senin – Jumat: 08.30 – 16.30 WITA<br>
                                    Sabtu: 09.00 – 14.00 WITA
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Direct WhatsApp Action -->
                    @php
                        $waNum = \App\Models\Setting::get('contact_whatsapp', '08115401985');
                        $waClean = preg_replace('/[^0-9]/', '', $waNum);
                        if (str_starts_with($waClean, '0')) {
                            $waClean = '62' . substr($waClean, 1);
                        }
                    @endphp
                    <div class="pt-4 border-t border-outline-variant/30">
                        <a href="https://api.whatsapp.com/send?phone={{ $waClean }}&text=Halo%20Pengurus%20{{ urlencode(\App\Models\Setting::get('org_name', 'IKA KPS Balikpapan')) }},%20saya%20ingin%20bertanya%20mengenai..." 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center gap-2">
                            <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                            <span>Chat Langsung via WhatsApp ({{ $waNum }})</span>
                        </a>
                    </div>
                </div>

                <!-- FAQ Quick Reference Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-7 shadow-xs space-y-4">
                    <h3 class="text-sm font-bold text-primary uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-outline-variant/30">
                        <span class="material-symbols-outlined text-secondary text-[20px]">help</span>
                        <span>Pertanyaan Umum (FAQ)</span>
                    </h3>

                    <div class="space-y-3 text-xs">
                        <div class="space-y-1">
                            <h4 class="font-bold text-primary">Bagaimana cara verifikasi akun alumni?</h4>
                            <p class="text-on-surface-variant leading-relaxed">
                                Cukup daftarkan diri pada menu Registrasi Akun dan sertakan tahun kelulusan Anda. Pengurus akan memvalidasi data Anda dalam 1x24 jam.
                            </p>
                        </div>

                        <div class="space-y-1 pt-2 border-t border-outline-variant/20">
                            <h4 class="font-bold text-primary">Apakah saya bisa mempromosikan bisnis di web ini?</h4>
                            <p class="text-on-surface-variant leading-relaxed">
                                Ya! Seluruh alumni terverifikasi dapat mendaftarkan profil usaha mereka melalui menu Katalog Bisnis Saya secara gratis.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
