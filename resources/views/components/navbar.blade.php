<header class="sticky top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-md border-b border-outline-variant/40 shadow-[0_2px_12px_rgba(9,43,90,0.06)]">
    <div class="h-16 sm:h-20 max-w-[1280px] mx-auto px-3 sm:px-6 flex items-center justify-between gap-2 sm:gap-4">
        
        <!-- Logo Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1 sm:flex-initial group">
            <img alt="IKA KPS Balikpapan Logo" 
                 class="h-9 w-9 sm:h-11 sm:w-11 rounded-full object-contain shadow-xs ring-2 ring-secondary/25 group-hover:ring-secondary/60 transition-all shrink-0" 
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhOhr--lV8imxPKdTS8bKYLp0QWuGXP6MYlEpTQEezQM2FMXiyRg_ak2cdwWVr6LgLwzeDss7cUNXJEdSIBLAILXsDQzpFJt1UF0Q8N7zUWoaDkHI8FbmKvkTd4Zi4yfy1bCgfdM4Q8oRO7CjdWVFsRUjnZ6jBxTDJSblWbAbInEYQSh43Nhz5Wq0WD3dFpefWbEpGN2j7kpIDx6BW2xT6v9VSfgaCXwdopJhr8jywbC2myMHBtdD4S5Rb5HvHmuThW2g">
            <div class="flex flex-col min-w-0">
                <span class="font-headline-sm text-xs sm:text-[15px] xl:text-[16px] text-primary tracking-tight font-extrabold leading-tight uppercase group-hover:text-secondary transition-colors truncate">
                    {{ \App\Models\Setting::get('org_name', 'IKA KPS BALIKPAPAN') }}
                </span>
                <span class="hidden sm:block font-label-sm text-[10px] sm:text-[11px] text-secondary font-semibold tracking-wider uppercase truncate">
                    Sekolah Nasional KPS Balikpapan
                </span>
            </div>
        </a>

        <!-- Desktop Navigation (Grouped Hierarchical Layout to prevent collision) -->
        <nav class="hidden lg:flex items-center gap-1 xl:gap-2 h-full">
            
            <!-- 1. Beranda -->
            <a href="{{ route('home') }}" 
               class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition-all {{ request()->routeIs('home') ? 'text-primary font-bold bg-surface-container-low text-secondary' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                Beranda
            </a>

            <!-- 2. Tentang Kami -->
            <a href="{{ route('home') }}#tentang-kami" 
               class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold text-on-surface-variant hover:text-primary hover:bg-surface-container-low transition-all">
                Tentang Kami
            </a>

            <!-- 3. Dropdown: Jejaring Alumni -->
            <div class="relative group h-full flex items-center">
                <button type="button" 
                        id="nav-dropdown-alumni-btn"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-label="Menu dropdown Jejaring Alumni"
                        class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold inline-flex items-center gap-1 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary/50 focus-visible:ring-offset-2 {{ request()->routeIs('alumni.*', 'business.*', 'career.*') ? 'text-secondary font-bold bg-secondary/10' : 'text-on-surface-variant group-hover:text-primary group-hover:bg-surface-container-low group-focus-within:text-primary group-focus-within:bg-surface-container-low' }}">
                    <span>Jejaring Alumni</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant/70 group-hover:text-primary group-focus-within:text-primary transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180">expand_more</span>
                </button>

                <!-- Dropdown Menu Box -->
                <div class="invisible opacity-0 translate-y-2 pointer-events-none group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto transition-all duration-200 ease-out absolute top-[calc(100%-8px)] left-0 w-72 bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/8 border border-outline-variant/40 p-2 z-50">
                    <div class="px-3 pt-1 pb-1.5 text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-wider">
                        Komunitas &amp; Karir
                    </div>

                    <a href="{{ route('alumni.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('alumni.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 group-hover/item:bg-primary group-hover/item:text-white text-primary flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">group</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Direktori Alumni</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Database &amp; pencarian angkatan</span>
                        </div>
                    </a>

                    <a href="{{ route('business.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('business.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-secondary/15 group-hover/item:bg-secondary group-hover/item:text-white text-secondary flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">storefront</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Katalog Bisnis</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Promosi usaha &amp; UMKM alumni</span>
                        </div>
                    </a>

                    <a href="{{ route('career.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('career.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/15 group-hover/item:bg-emerald-600 group-hover/item:text-white text-emerald-700 flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">work</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Bursa Karir</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Peluang kerja &amp; rekrutmen alumni</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 4. Dropdown: Kiprah & Media -->
            <div class="relative group h-full flex items-center">
                <button type="button" 
                        id="nav-dropdown-media-btn"
                        aria-haspopup="true"
                        aria-expanded="false"
                        aria-label="Menu dropdown Kiprah dan Media"
                        class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold inline-flex items-center gap-1 transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary/50 focus-visible:ring-offset-2 {{ request()->routeIs('program.*', 'event.*', 'gallery.*', 'article.*') ? 'text-secondary font-bold bg-secondary/10' : 'text-on-surface-variant group-hover:text-primary group-hover:bg-surface-container-low group-focus-within:text-primary group-focus-within:bg-surface-container-low' }}">
                    <span>Kiprah &amp; Media</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant/70 group-hover:text-primary group-focus-within:text-primary transition-transform duration-200 group-hover:rotate-180 group-focus-within:rotate-180">expand_more</span>
                </button>

                <!-- Dropdown Menu Box -->
                <div class="invisible opacity-0 translate-y-2 pointer-events-none group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:pointer-events-auto transition-all duration-200 ease-out absolute top-[calc(100%-8px)] left-0 w-72 bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/8 border border-outline-variant/40 p-2 z-50">
                    <div class="px-3 pt-1 pb-1.5 text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-wider">
                        Aktivitas &amp; Publikasi
                    </div>

                    <a href="{{ route('event.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('event.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-secondary/15 group-hover/item:bg-secondary group-hover/item:text-white text-secondary flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">event</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Agenda &amp; Event</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Reuni, seminar &amp; RSVP kegiatan</span>
                        </div>
                    </a>

                    <a href="{{ route('program.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('program.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 group-hover/item:bg-primary group-hover/item:text-white text-primary flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">task_alt</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Program Kerja</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Inisiatif bakti &amp; donasi kas IKA</span>
                        </div>
                    </a>

                    <a href="{{ route('article.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('article.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/15 group-hover/item:bg-amber-600 group-hover/item:text-white text-amber-700 flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">newspaper</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Kabar &amp; Cerita</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Artikel berita &amp; nostalgia alumni</span>
                        </div>
                    </a>

                    <a href="{{ route('gallery.index') }}" 
                       class="flex items-center gap-3 p-2 rounded-xl hover:bg-surface-container-low transition-colors group/item {{ request()->routeIs('gallery.*') ? 'bg-surface-container-low' : '' }}">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/15 group-hover/item:bg-purple-600 group-hover/item:text-white text-purple-700 flex items-center justify-center shrink-0 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">photo_library</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="text-xs font-bold text-primary group-hover/item:text-secondary block transition-colors">Galeri Foto</span>
                            <span class="text-[11px] text-on-surface-variant block font-normal leading-tight">Album dokumentasi kenangan</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- 5. Kontak -->
            <a href="{{ route('contact.show') }}" 
               class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold transition-all {{ request()->routeIs('contact.*') ? 'text-primary font-bold bg-surface-container-low text-secondary' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-low' }}">
                Kontak
            </a>
        </nav>

        <!-- Right Action Items (Auth User / Guest CTAs & Mobile Toggle) -->
        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0 ml-auto">
            @auth
                <!-- Profile Avatar Dropdown Menu -->
                @php
                    $navInitials = urlencode(mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)));
                    $navAvatarFallback = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='32' fill='%23092b5a'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' fill='%23ffffff' font-family='sans-serif' font-size='22' font-weight='700'%3E{$navInitials}%3C/text%3E%3C/svg%3E";
                    $navAvatarSrc = auth()->user()->avatar_url ?: $navAvatarFallback;
                @endphp
                <div class="relative" id="profile-dropdown-container">
                    <button type="button" 
                            id="profile-dropdown-btn" 
                            aria-expanded="false" 
                            aria-haspopup="true" 
                            title="Menu Akun &amp; Profil"
                            class="flex items-center gap-2 p-1.5 sm:px-3 sm:py-1.5 rounded-full sm:rounded-2xl border border-outline-variant/60 bg-surface-container-lowest hover:bg-surface-container-low hover:border-secondary/50 focus:outline-none focus:ring-2 focus:ring-secondary/40 transition-all shadow-xs group">
                        <img src="{{ $navAvatarSrc }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-secondary/30 group-hover:ring-secondary transition-all" 
                             onerror="this.onerror=null; this.src='{{ $navAvatarFallback }}';">
                        <span class="hidden xl:inline font-label-md text-xs font-bold text-primary max-w-[110px] truncate">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="material-symbols-outlined text-[18px] text-on-surface-variant group-hover:text-primary transition-transform duration-200" id="profile-dropdown-arrow">
                            expand_more
                        </span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="profile-dropdown-menu" 
                         class="hidden absolute right-0 top-full mt-2 w-72 bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant/50 py-2 z-50 divide-y divide-outline-variant/30 transition-all origin-top-right">
                        
                        <!-- Header: User Identity & Status -->
                        <div class="px-4 py-3 flex items-center gap-3">
                            <img src="{{ $navAvatarSrc }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-secondary/30 shrink-0" 
                                 onerror="this.onerror=null; this.src='{{ $navAvatarFallback }}';">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-primary truncate leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                                <div class="mt-1">
                                    @if (auth()->user()->isAdmin())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-extrabold bg-secondary/15 text-secondary uppercase tracking-wider">
                                            Administrator
                                        </span>
                                    @elseif (auth()->user()->isPengurus())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-extrabold bg-secondary/15 text-secondary uppercase tracking-wider">
                                            Pengurus
                                        </span>
                                    @elseif (auth()->user()->isApproved())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                            Alumni Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-2xs font-extrabold bg-amber-100 text-amber-800 uppercase tracking-wider">
                                            Menunggu Verifikasi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1">
                            <a href="{{ route('profile.show') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-low hover:text-primary transition-colors group">
                                <span class="material-symbols-outlined text-[19px] text-on-surface-variant group-hover:text-primary transition-colors">account_circle</span>
                                <div class="flex-1">
                                    <span class="font-bold block">Profil Saya</span>
                                    <span class="text-[11px] text-on-surface-variant block font-normal">Data alumni &amp; status keanggotaan</span>
                                </div>
                            </a>

                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-low hover:text-primary transition-colors group">
                                <span class="material-symbols-outlined text-[19px] text-on-surface-variant group-hover:text-primary transition-colors">manage_accounts</span>
                                <div class="flex-1">
                                    <span class="font-bold block">Edit Profil</span>
                                    <span class="text-[11px] text-on-surface-variant block font-normal">Ubah biodata, foto &amp; sandi</span>
                                </div>
                            </a>

                            <a href="{{ route('profile.business.index') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-low hover:text-primary transition-colors group">
                                <span class="material-symbols-outlined text-[19px] text-on-surface-variant group-hover:text-primary transition-colors">storefront</span>
                                <div class="flex-1">
                                    <span class="font-bold block">Katalog Bisnis Saya</span>
                                    <span class="text-[11px] text-on-surface-variant block font-normal">Kelola promosi unit bisnis UMKM</span>
                                </div>
                            </a>

                            <a href="{{ route('profile.job.index') }}" 
                               class="flex items-center gap-3 px-4 py-2.5 text-xs text-on-surface hover:bg-surface-container-low hover:text-primary transition-colors group">
                                <span class="material-symbols-outlined text-[19px] text-on-surface-variant group-hover:text-primary transition-colors">work</span>
                                <div class="flex-1">
                                    <span class="font-bold block">Lowongan Kerja Saya</span>
                                    <span class="text-[11px] text-on-surface-variant block font-normal">Kelola info lowongan yang dibagikan</span>
                                </div>
                            </a>

                            @if (auth()->user()->isAdmin() || auth()->user()->isPengurus())
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2.5 text-xs text-secondary hover:bg-secondary/10 transition-colors group">
                                    <span class="material-symbols-outlined text-[19px] text-secondary">admin_panel_settings</span>
                                    <div class="flex-1">
                                        <span class="font-bold block">Panel Pengurus</span>
                                        <span class="text-[11px] text-on-surface-variant block font-normal">Verifikasi alumni &amp; manajemen portal</span>
                                    </div>
                                    <span class="material-symbols-outlined text-[16px] text-secondary">arrow_forward</span>
                                </a>
                            @endif
                        </div>

                        <!-- Footer: Logout -->
                        <div class="py-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-xs text-error hover:bg-error-container/25 transition-colors text-left group">
                                    <span class="material-symbols-outlined text-[19px] text-error group-hover:scale-110 transition-transform">logout</span>
                                    <span class="font-bold">Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" 
                   class="hidden sm:inline-flex font-label-lg text-xs sm:text-sm text-primary font-bold px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors">
                    Masuk
                </a>
                <a class="hidden sm:inline-flex items-center justify-center font-label-lg text-xs sm:text-sm px-4 py-2.5 rounded-xl bg-primary text-on-primary font-bold shadow-sm hover:bg-primary-container transition-all active:scale-[0.98]" 
                   href="{{ route('register') }}">
                    Daftar Akun
                </a>
            @endauth

            <!-- Mobile Hamburger Button -->
            <button type="button" 
                    id="mobile-menu-btn"
                    onclick="toggleMobileMenu()" 
                    class="lg:hidden w-11 h-11 min-w-[44px] min-h-[44px] shrink-0 flex items-center justify-center rounded-xl text-primary hover:bg-surface-container-low active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary/50 focus-visible:ring-offset-2 transition-all duration-200 group"
                    aria-label="Buka Menu Navigasi"
                    aria-expanded="false"
                    aria-controls="mobile-menu">
                <div class="w-5 h-4 relative flex flex-col justify-between items-center pointer-events-none">
                    <span class="hamburger-bar-top w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] origin-center"></span>
                    <span class="hamburger-bar-mid w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-200 ease-out"></span>
                    <span class="hamburger-bar-bot w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] origin-center"></span>
                </div>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Backdrop Overlay -->
<div id="mobile-menu-backdrop" 
     onclick="toggleMobileMenu(false)" 
     class="lg:hidden"
     aria-hidden="true">
</div>

<!-- Mobile Navigation Sidebar Drawer (Off-Canvas) -->
<aside id="mobile-menu" 
       class="lg:hidden flex flex-col border-l border-outline-variant/40"
       role="dialog" 
       aria-modal="true" 
       aria-label="Menu Navigasi Mobile">

    <!-- Sidebar Header with Brand & Tactile Close Button -->
    <div class="h-16 sm:h-20 px-5 flex items-center justify-between border-b border-outline-variant/30 bg-surface-container-low/60 shrink-0">
        <div class="flex items-center gap-2.5">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhOhr--lV8imxPKdTS8bKYLp0QWuGXP6MYlEpTQEezQM2FMXiyRg_ak2cdwWVr6LgLwzeDss7cUNXJEdSIBLAILXsDQzpFJt1UF0Q8N7zUWoaDkHI8FbmKvkTd4Zi4yfy1bCgfdM4Q8oRO7CjdWVFsRUjnZ6jBxTDJSblWbAbInEYQSh43Nhz5Wq0WD3dFpefWbEpGN2j7kpIDx6BW2xT6v9VSfgaCXwdopJhr8jywbC2myMHBtdD4S5Rb5HvHmuThW2g" 
                 alt="Logo IKA KPS" 
                 class="w-9 h-9 rounded-full object-contain bg-white p-0.5 ring-2 ring-secondary/25">
            <div>
                <span class="font-headline-sm text-xs font-black text-primary tracking-tight block">IKA KPS</span>
                <span class="text-2xs text-secondary font-bold uppercase tracking-wider block">Menu Navigasi</span>
            </div>
        </div>
        <button type="button" 
                id="mobile-menu-close-btn"
                onclick="toggleMobileMenu(false)" 
                class="w-10 h-10 rounded-xl flex items-center justify-center text-on-surface-variant hover:text-primary hover:bg-surface-container active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary/50 transition-all cursor-pointer"
                aria-label="Tutup Menu Navigasi">
            <span class="material-symbols-outlined text-[22px]">close</span>
        </button>
    </div>

        <!-- Sidebar Scrollable Body -->
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
            @guest
                <!-- Quick Login / Register for Mobile Guests -->
                <div class="menu-section grid grid-cols-2 gap-2 pb-3 border-b border-outline-variant/30">
                    <a class="w-full text-center font-bold py-2.5 rounded-xl border border-primary text-primary text-xs hover:bg-surface-container-low active:scale-[0.98] transition-all min-h-[42px] flex items-center justify-center" href="{{ route('login') }}">
                        Masuk
                    </a>
                    <a class="w-full text-center font-bold py-2.5 rounded-xl bg-primary text-white text-xs shadow-sm hover:bg-primary-container active:scale-[0.98] transition-all min-h-[42px] flex items-center justify-center" href="{{ route('register') }}">
                        Daftar Akun
                    </a>
                </div>
            @endguest
            
            <!-- Menu Utama -->
            <div class="menu-section space-y-1">
                <span class="text-2xs font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Menu Utama</span>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('home') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('home') }}">
                    <span class="material-symbols-outlined text-[18px]">home</span>
                    <span>Beranda</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs text-primary hover:bg-surface-container-low hover:translate-x-1 active:scale-[0.98] transition-all duration-150" href="{{ route('home') }}#tentang-kami">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                    <span>Tentang Kami</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('contact.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('contact.show') }}">
                    <span class="material-symbols-outlined text-[18px]">mail</span>
                    <span>Kontak Sekretariat</span>
                </a>
            </div>

            <!-- Jejaring Alumni -->
            <div class="menu-section space-y-1 pt-2 border-t border-outline-variant/20">
                <span class="text-2xs font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Jejaring Alumni</span>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('alumni.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('alumni.index') }}">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    <span>Direktori Alumni</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('business.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('business.index') }}">
                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                    <span>Katalog Bisnis</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('career.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('career.index') }}">
                    <span class="material-symbols-outlined text-[18px]">work</span>
                    <span>Bursa Karir</span>
                </a>
            </div>

            <!-- Kiprah & Media -->
            <div class="menu-section space-y-1 pt-2 border-t border-outline-variant/20">
                <span class="text-2xs font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Kiprah &amp; Media</span>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('event.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('event.index') }}">
                    <span class="material-symbols-outlined text-[18px]">event</span>
                    <span>Agenda &amp; Event</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('program.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('program.index') }}">
                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                    <span>Program Kerja</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('article.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('article.index') }}">
                    <span class="material-symbols-outlined text-[18px]">newspaper</span>
                    <span>Kabar &amp; Cerita</span>
                </a>
                <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs hover:translate-x-1 active:scale-[0.98] transition-all duration-150 {{ request()->routeIs('gallery.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('gallery.index') }}">
                    <span class="material-symbols-outlined text-[18px]">photo_library</span>
                    <span>Galeri Foto</span>
                </a>
            </div>
        </div>
    </aside>

<script>
    let lastNavActiveFocus = null;

    function toggleMobileMenu(forceState) {
        const menu = document.getElementById('mobile-menu');
        const btn = document.getElementById('mobile-menu-btn');
        const backdrop = document.getElementById('mobile-menu-backdrop');
        const closeBtn = document.getElementById('mobile-menu-close-btn');
        if (!menu || !btn) return;

        const isOpen = menu.classList.contains('menu-open');
        const shouldOpen = forceState !== undefined ? forceState : !isOpen;

        if (shouldOpen) {
            lastNavActiveFocus = document.activeElement;
            btn.classList.add('is-active');
            btn.setAttribute('aria-expanded', 'true');
            btn.setAttribute('aria-label', 'Tutup Menu Navigasi');

            menu.classList.add('menu-open');
            if (backdrop) {
                backdrop.classList.add('backdrop-open');
            }
            document.body.style.overflow = 'hidden';

            // Transfer focus inside drawer for accessibility
            setTimeout(function () {
                if (closeBtn) {
                    closeBtn.focus();
                } else {
                    const firstFocusable = menu.querySelector('a, button, input');
                    if (firstFocusable) firstFocusable.focus();
                }
            }, 60);
        } else {
            btn.classList.remove('is-active');
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('aria-label', 'Buka Menu Navigasi');

            menu.classList.remove('menu-open');
            if (backdrop) {
                backdrop.classList.remove('backdrop-open');
            }
            document.body.style.overflow = '';

            // Restore focus to previous element or hamburger button
            if (lastNavActiveFocus && typeof lastNavActiveFocus.focus === 'function') {
                lastNavActiveFocus.focus();
                lastNavActiveFocus = null;
            } else if (btn) {
                btn.focus();
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const menu = document.getElementById('mobile-menu');
        const btn = document.getElementById('mobile-menu-btn');

        // Trap keyboard Tab focus inside mobile drawer dialog
        if (menu) {
            menu.addEventListener('keydown', function (e) {
                if (e.key !== 'Tab' || !menu.classList.contains('menu-open')) return;

                const focusables = menu.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])');
                if (focusables.length === 0) return;

                const first = focusables[0];
                const last = focusables[focusables.length - 1];

                if (e.shiftKey) {
                    if (document.activeElement === first) {
                        e.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        e.preventDefault();
                        first.focus();
                    }
                }
            });
        }

        // Synchronize aria-expanded on desktop dropdowns
        ['nav-dropdown-alumni-btn', 'nav-dropdown-media-btn'].forEach(function (id) {
            const dropdownBtn = document.getElementById(id);
            if (!dropdownBtn) return;
            const parent = dropdownBtn.closest('.group');
            if (!parent) return;

            parent.addEventListener('focusin', function () {
                dropdownBtn.setAttribute('aria-expanded', 'true');
            });
            parent.addEventListener('focusout', function (e) {
                if (!parent.contains(e.relatedTarget)) {
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
            parent.addEventListener('mouseenter', function () {
                dropdownBtn.setAttribute('aria-expanded', 'true');
            });
            parent.addEventListener('mouseleave', function () {
                if (!parent.contains(document.activeElement)) {
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!menu || !btn) return;
            if (!menu.contains(e.target) && !btn.contains(e.target) && btn.classList.contains('is-active')) {
                toggleMobileMenu(false);
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                if (btn && btn.classList.contains('is-active')) {
                    toggleMobileMenu(false);
                }
            }
        });

        // Close if resized to desktop viewport
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1024 && btn && btn.classList.contains('is-active')) {
                toggleMobileMenu(false);
            }
        });

        // Close mobile drawer when clicking navigation links inside it
        if (menu) {
            menu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    toggleMobileMenu(false);
                });
            });
        }

        @auth
        const dropdownBtn = document.getElementById('profile-dropdown-btn');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');
        const dropdownArrow = document.getElementById('profile-dropdown-arrow');

        if (dropdownBtn && dropdownMenu) {
            function toggleDropdown(show) {
                const isVisible = show !== undefined ? show : dropdownMenu.classList.contains('hidden');
                if (isVisible) {
                    dropdownMenu.classList.remove('hidden');
                    dropdownBtn.setAttribute('aria-expanded', 'true');
                    if (dropdownArrow) dropdownArrow.classList.add('rotate-180');
                } else {
                    dropdownMenu.classList.add('hidden');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                    if (dropdownArrow) dropdownArrow.classList.remove('rotate-180');
                }
            }

            dropdownBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });

            document.addEventListener('click', function (e) {
                if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                    toggleDropdown(false);
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    toggleDropdown(false);
                }
            });
        }
        @endauth
    });
</script>
