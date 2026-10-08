<header class="sticky top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-md border-b border-outline-variant/40 shadow-[0_1px_8px_rgba(9,43,90,0.06)]">
    <div class="h-20 max-w-[1280px] mx-auto px-6 flex items-center justify-between gap-space-lg">
        <!-- Logo Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
            <img alt="IKA KPS Balikpapan Logo" 
                 class="h-11 w-11 rounded-full object-contain shadow-sm ring-2 ring-secondary/20" 
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhOhr--lV8imxPKdTS8bKYLp0QWuGXP6MYlEpTQEezQM2FMXiyRg_ak2cdwWVr6LgLwzeDss7cUNXJEdSIBLAILXsDQzpFJt1UF0Q8N7zUWoaDkHI8FbmKvkTd4Zi4yfy1bCgfdM4Q8oRO7CjdWVFsRUjnZ6jBxTDJSblWbAbInEYQSh43Nhz5Wq0WD3dFpefWbEpGN2j7kpIDx6BW2xT6v9VSfgaCXwdopJhr8jywbC2myMHBtdD4S5Rb5HvHmuThW2g">
            <div class="flex flex-col">
                <span class="font-headline-sm text-[17px] text-primary tracking-tight font-extrabold leading-tight uppercase">IKA KPS BALIKPAPAN</span>
                <span class="font-label-sm text-[11px] text-secondary font-semibold tracking-wider uppercase">Sekolah Nasional KPS Balikpapan</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden xl:flex items-center gap-space-md h-full">
            <a aria-current="page" class="transition-colors py-2 text-primary font-bold border-b-2 border-secondary" href="#beranda">Beranda</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#tentang-kami">Tentang Kami</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#direktori-alumni">Direktori Alumni</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#program-kerja">Program Kerja</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#sinergi-bisnis">Sinergi Bisnis</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#galeri-nostalgia">Galeri Nostalgia</a>
            <a class="font-label-lg text-label-lg text-on-surface-variant hover:text-primary transition-colors py-2" href="#kontak">Kontak</a>
        </nav>

        <!-- Right Action Items -->
        <div class="flex items-center gap-space-md shrink-0">
            <a href="#direktori-alumni" aria-label="Pencarian Direktori" class="w-10 h-10 flex items-center justify-center rounded-xl text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined text-[22px]">search</span>
            </a>
            <a class="hidden sm:inline-flex items-center justify-center font-label-lg text-label-lg px-space-lg py-2.5 rounded-xl bg-primary text-on-primary font-bold shadow-sm hover:bg-primary-container transition-all active:scale-[0.98]" href="#daftar-alumni">
                Daftar Alumni
            </a>
            <div class="relative flex items-center pl-space-xs">
                <div class="relative">
                    <img alt="Profile" class="w-8 h-8 rounded-full object-cover ring-2 ring-surface-container-lowest" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiHPYtyohXxS5uo9qqM8QRaQdubwqub3GWCw4zW4M8A6I8I8o_ba7-C6kptqTQvYU877p3k51QXz0dNdUq_4VK-Vno3kJCm6REkIqx67a3LtaDfMXz1wa_7ofanzPOlklhTMza48GntQ2V87bXPGcRWvKHAvayc7u7dSrEMQUJ2l2thTByBqJk9VKhJ6Si34NRpCYN1t4yNoZonpXFrJwRU0n-v1xCFPnuHfaYd-j6tP2XUXAmiDVRgA">
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 rounded-full ring-2 ring-surface-container-lowest" title="Status Aktif"></span>
                </div>
            </div>

            <!-- Mobile Hamburger Button -->
            <button type="button" 
                    onclick="const m = document.getElementById('mobile-menu'); m.classList.toggle('hidden');" 
                    class="xl:hidden w-10 h-10 flex items-center justify-center rounded-xl text-primary hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[26px]">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden xl:hidden bg-surface-container-lowest border-b border-outline-variant/40 px-6 py-4 space-y-3 shadow-lg">
        <a class="block font-label-lg text-primary font-bold py-2 border-b border-surface-container-low" href="#beranda">Beranda</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#tentang-kami">Tentang Kami</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#direktori-alumni">Direktori Alumni</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#program-kerja">Program Kerja</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#sinergi-bisnis">Sinergi Bisnis</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#galeri-nostalgia">Galeri Nostalgia</a>
        <a class="block font-label-lg text-on-surface-variant hover:text-primary py-2 border-b border-surface-container-low" href="#kontak">Kontak</a>
        <a class="block text-center font-label-lg py-2.5 rounded-xl bg-primary text-on-primary font-bold mt-2" href="#daftar-alumni">Daftar Alumni Sekarang</a>
    </div>
</header>
