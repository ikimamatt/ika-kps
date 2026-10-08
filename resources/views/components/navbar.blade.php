<header class="sticky top-0 left-0 right-0 z-50 bg-surface-container-lowest/95 backdrop-blur-md border-b border-outline-variant/40 shadow-[0_2px_12px_rgba(9,43,90,0.06)]">
    <div class="h-20 max-w-[1280px] mx-auto px-4 sm:px-6 flex items-center justify-between gap-4">
        
        <!-- Logo Brand -->
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0 group">
            <img alt="IKA KPS Balikpapan Logo" 
                 class="h-10 w-10 sm:h-11 sm:w-11 rounded-full object-contain shadow-xs ring-2 ring-secondary/25 group-hover:ring-secondary/60 transition-all" 
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhOhr--lV8imxPKdTS8bKYLp0QWuGXP6MYlEpTQEezQM2FMXiyRg_ak2cdwWVr6LgLwzeDss7cUNXJEdSIBLAILXsDQzpFJt1UF0Q8N7zUWoaDkHI8FbmKvkTd4Zi4yfy1bCgfdM4Q8oRO7CjdWVFsRUjnZ6jBxTDJSblWbAbInEYQSh43Nhz5Wq0WD3dFpefWbEpGN2j7kpIDx6BW2xT6v9VSfgaCXwdopJhr8jywbC2myMHBtdD4S5Rb5HvHmuThW2g">
            <div class="flex flex-col">
                <span class="font-headline-sm text-sm sm:text-[16px] text-primary tracking-tight font-extrabold leading-tight uppercase group-hover:text-secondary transition-colors">
                    {{ \App\Models\Setting::get('org_name', 'IKA KPS BALIKPAPAN') }}
                </span>
                <span class="font-label-sm text-[10px] sm:text-[11px] text-secondary font-semibold tracking-wider uppercase">
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
                        class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold inline-flex items-center gap-1 transition-all focus:outline-none {{ request()->routeIs('alumni.*', 'business.*', 'career.*') ? 'text-secondary font-bold bg-secondary/10' : 'text-on-surface-variant group-hover:text-primary group-hover:bg-surface-container-low' }}">
                    <span>Jejaring Alumni</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant/70 group-hover:text-primary transition-transform duration-200 group-hover:rotate-180">expand_more</span>
                </button>

                <!-- Dropdown Menu Box -->
                <div class="invisible opacity-0 translate-y-2 pointer-events-none group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200 ease-out absolute top-[calc(100%-8px)] left-0 w-72 bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/8 border border-outline-variant/40 p-2 z-50">
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
                        class="px-3.5 py-2 rounded-xl text-xs xl:text-sm font-semibold inline-flex items-center gap-1 transition-all focus:outline-none {{ request()->routeIs('program.*', 'event.*', 'gallery.*', 'article.*') ? 'text-secondary font-bold bg-secondary/10' : 'text-on-surface-variant group-hover:text-primary group-hover:bg-surface-container-low' }}">
                    <span>Kiprah &amp; Media</span>
                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant/70 group-hover:text-primary transition-transform duration-200 group-hover:rotate-180">expand_more</span>
                </button>

                <!-- Dropdown Menu Box -->
                <div class="invisible opacity-0 translate-y-2 pointer-events-none group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-hover:pointer-events-auto transition-all duration-200 ease-out absolute top-[calc(100%-8px)] left-0 w-72 bg-surface-container-lowest rounded-2xl shadow-xl shadow-primary/8 border border-outline-variant/40 p-2 z-50">
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
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            @auth
                <!-- Profile Avatar Dropdown Menu -->
                <div class="relative" id="profile-dropdown-container">
                    <button type="button" 
                            id="profile-dropdown-btn" 
                            aria-expanded="false" 
                            aria-haspopup="true" 
                            title="Menu Akun &amp; Profil"
                            class="flex items-center gap-2 p-1.5 sm:px-3 sm:py-1.5 rounded-full sm:rounded-2xl border border-outline-variant/60 bg-surface-container-lowest hover:bg-surface-container-low hover:border-secondary/50 focus:outline-none focus:ring-2 focus:ring-secondary/40 transition-all shadow-xs group">
                        <img alt="{{ auth()->user()->name }}" 
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-secondary/30 group-hover:ring-secondary transition-all" 
                             src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=002D62&color=fff&size=100' }}">
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
                            <img alt="{{ auth()->user()->name }}" 
                                 class="w-10 h-10 rounded-full object-cover ring-2 ring-secondary/30 shrink-0" 
                                 src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=002D62&color=fff&size=100' }}">
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-bold text-primary truncate leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                                <div class="mt-1">
                                    @if (auth()->user()->isAdmin())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-secondary/15 text-secondary uppercase tracking-wider">
                                            Administrator
                                        </span>
                                    @elseif (auth()->user()->isPengurus())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-secondary/15 text-secondary uppercase tracking-wider">
                                            Pengurus
                                        </span>
                                    @elseif (auth()->user()->isApproved())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
                                            Alumni Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
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
                   class="font-label-lg text-xs sm:text-sm text-primary font-bold px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors">
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
                    onclick="const m = document.getElementById('mobile-menu'); m.classList.toggle('hidden');" 
                    class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl text-primary hover:bg-surface-container-low focus:outline-none"
                    aria-label="Toggle Mobile Menu">
                <span class="material-symbols-outlined text-[26px]">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-menu" class="hidden lg:hidden bg-surface-container-lowest border-b border-outline-variant/40 px-5 py-4 space-y-4 shadow-xl max-h-[85vh] overflow-y-auto">
        
        <!-- Menu Utama -->
        <div class="space-y-1">
            <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Menu Utama</span>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('home') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('home') }}">
                <span class="material-symbols-outlined text-[18px]">home</span>
                <span>Beranda</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs text-primary hover:bg-surface-container-low" href="{{ route('home') }}#tentang-kami">
                <span class="material-symbols-outlined text-[18px]">info</span>
                <span>Tentang Kami</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('contact.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('contact.show') }}">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                <span>Kontak Sekretariat</span>
            </a>
        </div>

        <!-- Jejaring Alumni -->
        <div class="space-y-1 pt-2 border-t border-outline-variant/20">
            <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Jejaring Alumni</span>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('alumni.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('alumni.index') }}">
                <span class="material-symbols-outlined text-[18px]">group</span>
                <span>Direktori Alumni</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('business.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('business.index') }}">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
                <span>Katalog Bisnis</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('career.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('career.index') }}">
                <span class="material-symbols-outlined text-[18px]">work</span>
                <span>Bursa Karir</span>
            </a>
        </div>

        <!-- Kiprah & Media -->
        <div class="space-y-1 pt-2 border-t border-outline-variant/20">
            <span class="text-[10px] font-bold text-on-surface-variant/60 uppercase tracking-wider px-2">Kiprah &amp; Media</span>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('event.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('event.index') }}">
                <span class="material-symbols-outlined text-[18px]">event</span>
                <span>Agenda &amp; Event</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('program.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('program.index') }}">
                <span class="material-symbols-outlined text-[18px]">task_alt</span>
                <span>Program Kerja</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('article.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('article.index') }}">
                <span class="material-symbols-outlined text-[18px]">newspaper</span>
                <span>Kabar &amp; Cerita</span>
            </a>
            <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl font-medium text-xs {{ request()->routeIs('gallery.*') ? 'bg-secondary/10 text-secondary font-bold' : 'text-primary hover:bg-surface-container-low' }}" href="{{ route('gallery.index') }}">
                <span class="material-symbols-outlined text-[18px]">photo_library</span>
                <span>Galeri Foto</span>
            </a>
        </div>

        <!-- Auth Section for Mobile -->
        @auth
            <div class="pt-3 border-t border-outline-variant/30 space-y-2">
                <div class="flex items-center gap-2.5 py-1 px-2">
                    <img alt="{{ auth()->user()->name }}" 
                         class="w-8 h-8 rounded-full object-cover ring-2 ring-secondary/30" 
                         src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=002D62&color=fff&size=100' }}">
                    <div class="min-w-0">
                        <span class="font-bold text-xs text-primary block truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-on-surface-variant block truncate">{{ auth()->user()->email }}</span>
                    </div>
                </div>
                <a class="flex items-center gap-2 px-3 py-2 rounded-xl font-bold text-xs text-primary hover:bg-surface-container-low" href="{{ route('profile.show') }}">
                    <span class="material-symbols-outlined text-[18px]">account_circle</span>
                    <span>Profil Saya</span>
                </a>
                <a class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium text-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low" href="{{ route('profile.edit') }}">
                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                    <span>Edit Profil &amp; Sandi</span>
                </a>
                <a class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium text-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low" href="{{ route('profile.business.index') }}">
                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                    <span>Katalog Bisnis Saya</span>
                </a>
                <a class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium text-xs text-on-surface-variant hover:text-primary hover:bg-surface-container-low" href="{{ route('profile.job.index') }}">
                    <span class="material-symbols-outlined text-[18px]">work</span>
                    <span>Lowongan Kerja Saya</span>
                </a>
                @if (auth()->user()->isAdmin() || auth()->user()->isPengurus())
                    <a class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium text-xs text-secondary bg-secondary/10" href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                        <span>Panel Pengurus</span>
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded-xl font-medium text-xs text-error hover:bg-error-container/20 w-full text-left transition-colors">
                        <span class="material-symbols-outlined text-[18px]">logout</span>
                        <span>Keluar Akun</span>
                    </button>
                </form>
            </div>
        @else
            <div class="pt-3 border-t border-outline-variant/30 flex flex-col gap-2">
                <a class="block text-center font-label-lg py-2 rounded-xl border border-primary text-primary font-bold text-xs" href="{{ route('login') }}">Masuk</a>
                <a class="block text-center font-label-lg py-2 rounded-xl bg-primary text-on-primary font-bold text-xs shadow-sm" href="{{ route('register') }}">Daftar Akun</a>
            </div>
        @endauth
    </div>
</header>

@auth
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownBtn = document.getElementById('profile-dropdown-btn');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');
        const dropdownArrow = document.getElementById('profile-dropdown-arrow');

        if (!dropdownBtn || !dropdownMenu) return;

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

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                toggleDropdown(false);
            }
        });

        // Close dropdown when pressing Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                toggleDropdown(false);
            }
        });
    });
</script>
@endauth
