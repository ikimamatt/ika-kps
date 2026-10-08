<!-- RICH BESPOKE PRE-FOOTER INTERACTIVE HUB -->
<section class="w-full py-16 bg-surface-container-lowest border-t border-outline-variant/30" id="kontak">
    <div class="max-w-[1280px] mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Col 1: Identity & Social -->
        <div class="lg:col-span-4 flex flex-col gap-5">
            <div class="flex items-center gap-3">
                <img alt="IKA KPS Balikpapan Logo" 
                     class="h-12 w-12 rounded-full object-contain shadow-sm ring-2 ring-secondary/30" 
                     src="https://lh3.googleusercontent.com/aida-public/AB6AXuCtHNgmphBmPGpNCntwuo76IlpJV5KbSyWUtMNB7JxVMm_mKTgzTjFLWy1QQkzBo9-yUl_ZykrdzIgFkxC-olBfF4UyW_t8dRFc3_DD0pyfzDkAqHgqPgEuj3ZByIUmoKcLRpPptmPV3wX3gzUAZqvByrsUVqHVNsstmoHNN_zb8r7AZMcm6UiawQAJQ17sBa5cYfnzSGbRhnpqolxxySE_NdAL5Aoyu4ZgQhO_0KyA2T1tF-NFwnFyqjebaBVbXlAbjtU">
                <div class="flex flex-col">
                    <span class="font-headline-sm text-title-md text-primary font-bold uppercase leading-none">{{ \App\Models\Setting::get('org_name', 'IKA KPS BALIKPAPAN') }}</span>
                    <span class="font-label-sm text-[11px] text-secondary font-semibold uppercase mt-1">{{ \App\Models\Setting::get('org_tagline', 'Sekolah Nasional KPS Balikpapan') }}</span>
                </div>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant">
                {{ \App\Models\Setting::get('org_description', 'Wadah persaudaraan, sinergi, dan bakti bagi Almamater Sekolah Nasional KPS Balikpapan serta kemajuan Kota Balikpapan dan Nusantara.') }}
            </p>
            @php
                $ig = \App\Models\Setting::get('social_instagram');
                $li = \App\Models\Setting::get('social_linkedin');
                $yt = \App\Models\Setting::get('social_youtube');
            @endphp
            <div class="flex items-center gap-3 pt-2">
                @if ($ig)
                    <a aria-label="Instagram Alumni KPS" href="{{ $ig }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-surface-container-low hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#dc2743] hover:to-[#bc1888] hover:text-white text-on-surface-variant flex items-center justify-center transition-all">
                        <x-icons.instagram class="w-4 h-4 fill-current" />
                    </a>
                @endif
                @if ($li)
                    <a aria-label="LinkedIn Komunitas KPS" href="{{ $li }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-surface-container-low hover:bg-[#0077B5] hover:text-white text-on-surface-variant flex items-center justify-center transition-all">
                        <x-icons.linkedin class="w-4 h-4 fill-current" />
                    </a>
                @endif
                @if ($yt)
                    <a aria-label="YouTube Channel KPS Balikpapan" href="{{ $yt }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-lg bg-surface-container-low hover:bg-[#FF0000] hover:text-white text-on-surface-variant flex items-center justify-center transition-all">
                        <x-icons.youtube class="w-4 h-4 fill-current" />
                    </a>
                @endif
            </div>
        </div>

        <!-- Col 2: Navigation Links -->
        <div class="lg:col-span-2 flex flex-col gap-3">
            <span class="font-label-lg text-label-lg text-primary font-bold uppercase tracking-wider">Akses Cepat</span>
            <ul class="flex flex-col gap-2 font-body-sm text-body-sm text-on-surface-variant">
                <li><a class="hover:text-secondary transition-colors" href="#tentang-kami">Profil &amp; AD/ART</a></li>
                <li><a class="hover:text-secondary transition-colors" href="{{ route('alumni.index') }}">Direktori Angkatan</a></li>
                <li><a class="hover:text-secondary transition-colors" href="{{ route('program.index') }}">Agenda &amp; Baksos</a></li>
                <li><a class="hover:text-secondary transition-colors" href="{{ route('business.index') }}">Katalog Usaha UMKM</a></li>
                <li><a class="hover:text-secondary transition-colors" href="{{ route('register') }}">Formulir Alumni Baru</a></li>
            </ul>
        </div>

        <!-- Col 3: Address & Contacts -->
        <div class="lg:col-span-3 flex flex-col gap-3">
            <span class="font-label-lg text-label-lg text-primary font-bold uppercase tracking-wider">Sekretariat IKA KPS</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                {{ \App\Models\Setting::get('contact_address', 'Gedung Alumni KPS, Jl. Sport No. 1, Prapatan, Kec. Balikpapan Kota, Kota Balikpapan, Kalimantan Timur 76111') }}
            </p>
            @php
                $contactEmail = \App\Models\Setting::get('contact_email', 'sekretariat@ika-kps.id');
                $waNum = \App\Models\Setting::get('contact_whatsapp', '08115401985');
                $waClean = preg_replace('/[^0-9]/', '', $waNum);
                if (str_starts_with($waClean, '0')) {
                    $waClean = '62' . substr($waClean, 1);
                }
            @endphp
            <div class="flex flex-col gap-1.5 pt-1 font-body-sm text-body-sm text-on-surface-variant">
                <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-2 hover:text-secondary">
                    <span class="material-symbols-outlined text-[18px] text-secondary">mail</span>
                    {{ $contactEmail }}
                </a>
                <a href="https://wa.me/{{ $waClean }}" class="flex items-center gap-2 hover:text-secondary" target="_blank" rel="noopener noreferrer">
                    <x-icons.whatsapp class="w-4 h-4 fill-current text-emerald-600 shrink-0" />
                    {{ $waNum }} (WhatsApp Center)
                </a>
            </div>
        </div>

        <!-- Col 4: Quick Action Box & Donation -->
        <div class="lg:col-span-3 p-5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex flex-col gap-3">
            <span class="font-title-md text-title-md text-primary font-bold">Kirim Saran &amp; Ide Program</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Punya inisiatif kolaborasi atau usulan kegiatan? Sampaikan ke pengurus pusat.
            </p>
            <div class="flex flex-col gap-2">
                <input class="h-9 px-3 rounded-lg bg-surface-container-lowest border border-outline-variant/30 text-body-sm focus:outline-none focus:border-secondary" 
                       placeholder="Email Anda..." 
                       type="email">
                <button class="h-9 rounded-lg bg-primary text-on-primary font-label-md font-bold hover:bg-primary-container transition-colors" 
                        type="button"
                        onclick="alert('Terima kasih! Pesan dan aspirasi Anda telah diterima pengurus IKA KPS.');">
                    Kirimkan Pesan
                </button>
            </div>
            <div class="pt-2 border-t border-outline-variant/30 flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-on-surface-variant">Dana Abadi KPS:</span>
                <a class="font-label-sm text-label-sm text-tertiary font-bold hover:underline inline-flex items-center gap-1" href="#program-kerja">
                    <span class="material-symbols-outlined text-[14px]">favorite</span> Donasi Kas IKA
                </a>
            </div>
        </div>
    </div>
</section>
