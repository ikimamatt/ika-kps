<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — {{ config('app.name', 'IKA KPS Balikpapan') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Local Material Symbols Font Preload -->
    <link rel="preload" href="{{ asset('fonts/material-symbols-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f0f4f8;
        }
        ::-webkit-scrollbar-thumb {
            background: #c4c6d0;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #747780;
        }
        body { overscroll-behavior: none; }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-secondary-fixed selection:text-on-secondary-fixed min-h-screen flex overflow-x-hidden">
    <!-- Skip to main content for keyboard accessibility (WCAG 2.4.1) -->
    <a href="#admin-main-content" 
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 z-50 px-4 py-2 bg-primary text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-xl focus:outline-none focus:ring-2 focus:ring-secondary transition-all">
        Lewati ke konten utama
    </a>

    <!-- Backdrop for mobile drawer -->
    <div id="admin-sidebar-backdrop" 
         onclick="toggleAdminSidebar(false)" 
         class="fixed inset-0 z-30 bg-primary/45 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 ease-out lg:hidden"
         aria-hidden="true"></div>

    <!-- Admin Sidebar -->
    <x-admin.sidebar />

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Topbar -->
        <header class="h-20 bg-surface-container-lowest border-b border-outline-variant/40 sticky top-0 z-20 px-4 sm:px-6 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3 sm:gap-4">
                <button type="button" 
                        id="admin-mobile-menu-btn"
                        onclick="toggleAdminSidebar()" 
                        class="lg:hidden w-11 h-11 min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl text-primary hover:bg-surface-container-low active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-secondary/50 focus-visible:ring-offset-2 transition-all duration-200 group" 
                        aria-label="Buka Menu Navigasi"
                        aria-expanded="false"
                        aria-controls="admin-sidebar">
                    <div class="w-5 h-4 relative flex flex-col justify-between items-center pointer-events-none">
                        <span class="hamburger-bar-top w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] origin-center"></span>
                        <span class="hamburger-bar-mid w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-200 ease-out"></span>
                        <span class="hamburger-bar-bot w-5 h-[2px] bg-primary group-hover:bg-secondary rounded-full transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] origin-center"></span>
                    </div>
                </button>
                <div>
                    <h2 class="font-headline-sm text-base font-bold text-primary flex items-center gap-2">
                        <span>Portal Administrasi Alumni</span>
                        <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Live
                        </span>
                    </h2>
                    <p class="text-[11px] text-on-surface-variant hidden sm:block">Ikatan Keluarga Alumni Sekolah Nasional KPS Balikpapan</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="hidden xl:flex items-center gap-1.5 text-xs font-medium text-on-surface-variant px-3 py-1.5 rounded-xl bg-surface-container-low border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[16px] text-secondary">calendar_today</span>
                    <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                </span>

                <a href="{{ route('home') }}" target="_blank" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-outline-variant/60 text-xs font-bold text-on-surface hover:bg-surface-container hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>Website Publik</span>
                </a>

                <div class="h-8 w-px bg-outline-variant/40 hidden sm:block"></div>

                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-surface-container transition-all" title="Buka Profil Saya">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=002D62&color=fff&size=80' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-9 h-9 rounded-xl object-cover ring-1 ring-outline-variant shrink-0 bg-surface-container">
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-bold text-primary leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[11px] text-secondary font-bold uppercase tracking-wider">{{ auth()->user()->role }}</div>
                    </div>
                </a>
            </div>
        </header>

        <!-- Flash Session Messages -->
        @if (session('success'))
            <div id="flash-banner" class="max-w-[1280px] w-full mx-auto px-4 sm:px-6 mt-4">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                        <span class="font-semibold text-sm">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-banner').remove()" class="text-emerald-700 hover:text-emerald-900 p-1">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif

        @if (session('warning'))
            <div id="flash-warning-banner" class="max-w-[1280px] w-full mx-auto px-4 sm:px-6 mt-4">
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-amber-600">warning</span>
                        <span class="font-semibold text-sm">{{ session('warning') }}</span>
                    </div>
                    <button type="button" onclick="document.getElementById('flash-warning-banner').remove()" class="text-amber-700 hover:text-amber-900 p-1">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- Main Content View -->
        <main id="admin-main-content" class="flex-1 w-full">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        let lastAdminActiveFocus = null;

        function toggleAdminSidebar(forceState) {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            const btn = document.getElementById('admin-mobile-menu-btn');
            if (!sidebar || !backdrop) return;

            const isClosed = sidebar.classList.contains('-translate-x-full');
            const shouldOpen = forceState !== undefined ? forceState : isClosed;

            if (shouldOpen) {
                lastAdminActiveFocus = document.activeElement;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');
                if (btn) {
                    btn.classList.add('is-active');
                    btn.setAttribute('aria-expanded', 'true');
                    btn.setAttribute('aria-label', 'Tutup Menu Navigasi');
                }
                document.body.style.overflow = 'hidden';

                // Shift focus into sidebar
                setTimeout(function () {
                    const firstFocusable = sidebar.querySelector('a, button, input');
                    if (firstFocusable) firstFocusable.focus();
                }, 60);
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100', 'pointer-events-auto');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                if (btn) {
                    btn.classList.remove('is-active');
                    btn.setAttribute('aria-expanded', 'false');
                    btn.setAttribute('aria-label', 'Buka Menu Navigasi');
                }
                document.body.style.overflow = '';

                // Restore focus
                if (lastAdminActiveFocus && typeof lastAdminActiveFocus.focus === 'function') {
                    lastAdminActiveFocus.focus();
                    lastAdminActiveFocus = null;
                } else if (btn) {
                    btn.focus();
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('admin-sidebar');
            const btn = document.getElementById('admin-mobile-menu-btn');

            // Keyboard Tab focus trap when mobile sidebar is open
            if (sidebar) {
                sidebar.addEventListener('keydown', function (e) {
                    if (e.key !== 'Tab' || window.innerWidth >= 1024 || sidebar.classList.contains('-translate-x-full')) return;

                    const focusables = sidebar.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])');
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

            // Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleAdminSidebar(false);
                }
            });

            // Close if resized to desktop viewport
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024 && sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    toggleAdminSidebar(false);
                }
            });

            // Auto-close on link navigation inside sidebar
            if (sidebar) {
                sidebar.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 1024) {
                            toggleAdminSidebar(false);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
