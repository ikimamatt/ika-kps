<footer class="w-full bg-primary-container text-on-primary border-t border-outline-variant/20 mt-space-xl">
    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 py-12 sm:py-space-xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-space-xl mb-8 sm:mb-space-xl">
            <!-- Brand & Summary -->
            <div class="sm:col-span-2 flex flex-col gap-space-md">
                <div class="flex items-center gap-3">
                    <img alt="IKA KPS Balikpapan Logo" 
                         class="h-11 w-11 rounded-full object-contain ring-2 ring-white/20 bg-white p-0.5" 
                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzjtuURyXptg_1z4oNFTSEZEHpaS8yTA0ibMB9QEySSnTKvCwCzvwyTTRICt6SAOvos7weZiarGuCpsONWYPEeF_wAYb9qmtnv860OwJpRO8lbzehLF4HGkOpyg5DPKH9kK5TckQ5K6UVmtfnduGRUDnT2Wj0cvDaS5xE6MTheRLO77XVlRmy692QCAf0A2WGdlzbMN2GzdOQvi6Mw8XBwWKe91BkL8AZOjy_EQHH5oYZPJ7m3t8xg3sO-sSzhFJDyFW8">
                    <span class="font-headline-sm text-title-md text-surface-container-lowest tracking-tight font-extrabold uppercase">
                        {{ \App\Models\Setting::get('org_name', 'IKA KPS BALIKPAPAN') }}
                    </span>
                </div>
                <p class="font-body-md text-body-md text-surface-container-highest/80 max-w-md">
                    {{ \App\Models\Setting::get('org_description', 'Wadah silaturahmi, sinergi, dan kontribusi nyata seluruh alumni Sekolah Nasional KPS Balikpapan lintas generasi di seluruh pelosok negeri dan dunia.') }}
                </p>
            </div>

            <!-- Quick Navigation -->
            <div class="flex flex-col gap-space-sm">
                <span class="font-label-lg text-label-lg text-secondary-fixed-dim uppercase tracking-wider font-bold">Navigasi Cepat</span>
                <ul class="flex flex-col gap-space-xs font-body-sm text-body-sm text-surface-container-highest/70">
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('home') }}">Beranda Utama</a></li>
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('home') }}#tentang-kami">Struktur Organisasi</a></li>
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('alumni.index') }}">Direktori Angkatan</a></li>
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('business.index') }}">Katalog Bisnis Alumni</a></li>
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('program.index') }}">Program Kerja</a></li>
                    <li class="hover:text-surface-container-lowest transition-colors"><a href="{{ route('contact.show') }}">Hubungi Sekretariat</a></li>
                </ul>
            </div>

            <!-- Secretariat Information -->
            <div class="flex flex-col gap-space-sm">
                <span class="font-label-lg text-label-lg text-secondary-fixed-dim uppercase tracking-wider font-bold">Sekretariat</span>
                <p class="font-body-sm text-body-sm text-surface-container-highest/70 leading-relaxed">
                    {{ \App\Models\Setting::get('contact_address', 'Kompleks Sekolah Nasional KPS Balikpapan, Jl. Sport No. 1, Prapatan, Kota Balikpapan, Kalimantan Timur 76111') }}
                </p>

                @php
                    $contactEmail = \App\Models\Setting::get('contact_email', 'sekretariat@ika-kps.id');
                    $waNum = \App\Models\Setting::get('contact_whatsapp', '08115401985');
                    $waClean = preg_replace('/[^0-9]/', '', $waNum);
                    if (str_starts_with($waClean, '0')) {
                        $waClean = '62' . substr($waClean, 1);
                    }
                @endphp
                <div class="flex flex-col gap-1.5 text-xs text-surface-container-highest/80 pt-1">
                    <a href="mailto:{{ $contactEmail }}" class="inline-flex items-center gap-1.5 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[15px]">mail</span>
                        <span>{{ $contactEmail }}</span>
                    </a>
                    @if ($waNum)
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 hover:text-white transition-colors">
                            <x-icons.whatsapp class="w-3.5 h-3.5 fill-current text-emerald-400 shrink-0" />
                            <span>{{ $waNum }} (WhatsApp)</span>
                        </a>
                    @endif
                </div>

                @php
                    $ig = \App\Models\Setting::get('social_instagram');
                    $li = \App\Models\Setting::get('social_linkedin');
                    $yt = \App\Models\Setting::get('social_youtube');
                @endphp
                @if ($ig || $li || $yt)
                    <div class="flex items-center gap-2 pt-2">
                        @if ($ig)
                            <a href="{{ $ig }}" target="_blank" rel="noopener noreferrer" 
                               class="w-10 h-10 rounded-xl bg-white/10 hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] text-white flex items-center justify-center transition-all" 
                               aria-label="Instagram Resmi IKA KPS"
                               title="Instagram">
                                <x-icons.instagram class="w-4 h-4 fill-current" />
                            </a>
                        @endif
                        @if ($li)
                            <a href="{{ $li }}" target="_blank" rel="noopener noreferrer" 
                               class="w-10 h-10 rounded-xl bg-white/10 hover:bg-[#0077B5] text-white flex items-center justify-center transition-all" 
                               aria-label="LinkedIn Resmi IKA KPS"
                               title="LinkedIn">
                                <x-icons.linkedin class="w-4 h-4 fill-current" />
                            </a>
                        @endif
                        @if ($yt)
                            <a href="{{ $yt }}" target="_blank" rel="noopener noreferrer" 
                               class="w-10 h-10 rounded-xl bg-white/10 hover:bg-[#FF0000] text-white flex items-center justify-center transition-all" 
                               aria-label="YouTube Resmi IKA KPS"
                               title="YouTube">
                                <x-icons.youtube class="w-4 h-4 fill-current" />
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="border-t border-surface-container-high/10 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left font-label-md text-xs sm:text-label-md text-surface-container-highest/60">
            <p>© {{ date('Y') }} Ikatan Keluarga Alumni KPS Balikpapan. Hak Cipta Dilindungi.</p>
            <div class="flex items-center gap-4 sm:gap-space-md">
                <a class="hover:text-surface-container-lowest transition-colors py-1" href="#">Kebijakan Privasi</a>
                <a class="hover:text-surface-container-lowest transition-colors py-1" href="#">Syarat &amp; Ketentuan</a>
            </div>
        </div>
    </div>
</footer>
