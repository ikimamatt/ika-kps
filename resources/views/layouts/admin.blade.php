<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — {{ config('app.name', 'IKA KPS Balikpapan') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Local Material Symbols Font Preload -->
    <link rel="preload" href="{{ asset('fonts/material-symbols-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar { display: none; }
        body { overscroll-behavior: none; }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-secondary-fixed selection:text-on-secondary-fixed min-h-screen flex">
    <!-- Backdrop for mobile drawer -->
    <div id="admin-sidebar-backdrop" onclick="toggleAdminSidebar()" class="fixed inset-0 z-30 bg-black/40 backdrop-blur-xs hidden lg:hidden"></div>

    <!-- Admin Sidebar -->
    <x-admin.sidebar />

    <!-- Main Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Topbar -->
        <header class="h-20 bg-surface-container-lowest border-b border-outline-variant/40 sticky top-0 z-20 px-6 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-4">
                <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-2 rounded-xl text-on-surface hover:bg-surface-container">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
                <div>
                    <h2 class="font-headline-sm text-base font-bold text-primary flex items-center gap-2">
                        <span>Portal Administrasi Alumni</span>
                    </h2>
                    <p class="text-[11px] text-on-surface-variant hidden sm:block">Ikatan Keluarga Alumni Sekolah Nasional KPS Balikpapan</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" 
                   class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl border border-outline-variant/60 text-xs font-bold text-on-surface hover:bg-surface-container transition-all">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    <span>Website Publik</span>
                </a>

                <div class="h-8 w-px bg-outline-variant/40 hidden sm:block"></div>

                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-surface-container transition-all">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=002D62&color=fff&size=80' }}" 
                         alt="{{ auth()->user()->name }}" 
                         class="w-9 h-9 rounded-xl object-cover ring-1 ring-outline-variant shrink-0">
                    <div class="hidden md:block text-left">
                        <div class="text-xs font-bold text-primary leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-secondary font-bold uppercase">{{ auth()->user()->role }}</div>
                    </div>
                </a>
            </div>
        </header>

        <!-- Flash Session Messages -->
        @if (session('success'))
            <div id="flash-banner" class="max-w-[1280px] w-full mx-auto px-6 mt-4">
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
            <div id="flash-warning-banner" class="max-w-[1280px] w-full mx-auto px-6 mt-4">
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
        <main class="flex-1 w-full">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('admin-sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
