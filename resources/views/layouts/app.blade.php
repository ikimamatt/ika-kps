<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::get('org_name', config('app.name', 'IKA KPS Balikpapan')) }} — {{ \App\Models\Setting::get('org_tagline', 'Satu Almamater, Seribu Cerita') }}</title>
    <meta name="description" content="{{ \App\Models\Setting::get('org_description', 'Wadah silaturahmi, sinergi, dan kontribusi nyata seluruh alumni Sekolah Nasional KPS Balikpapan lintas generasi di seluruh pelosok negeri dan dunia.') }}">
    
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
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-secondary-fixed selection:text-on-secondary-fixed overflow-x-hidden">
    <!-- Skip to main content for keyboard accessibility (WCAG 2.4.1) -->
    <a href="#main-content" 
       class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 z-50 px-4 py-2 bg-primary text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-xl focus:outline-none focus:ring-2 focus:ring-secondary transition-all">
        Lewati ke konten utama
    </a>

    <x-navbar />

    @if (session('success'))
        <div id="flash-banner" 
             class="max-w-[1280px] mx-auto px-4 sm:px-6 mt-4 transition-all duration-300"
             role="alert" 
             aria-live="polite">
            <div class="relative overflow-hidden p-4 sm:p-4.5 rounded-2xl bg-gradient-to-r from-emerald-50 via-white to-emerald-50/60 border border-emerald-200 text-emerald-950 flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[22px]">check_circle</span>
                    </div>
                    <div>
                        <span class="font-extrabold text-[11px] uppercase tracking-wider text-emerald-800 block">Kabar Baik! ✨</span>
                        <p class="font-bold text-xs sm:text-sm text-emerald-950 leading-snug">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="document.getElementById('flash-banner').remove()" 
                        aria-label="Tutup notifikasi"
                        class="p-1.5 rounded-xl hover:bg-emerald-100/60 text-emerald-800 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                <!-- Subtle auto-dismiss progress timer indicator -->
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-emerald-100">
                    <div class="h-full bg-emerald-500 flash-progress-bar"></div>
                </div>
            </div>
        </div>
        <script>
            (function () {
                const banner = document.getElementById('flash-banner');
                if (!banner) return;

                const progressBar = banner.querySelector('.flash-progress-bar');
                let remaining = 5000;
                let startTime = Date.now();
                let timer = null;

                const dismiss = () => {
                    banner.style.opacity = '0';
                    banner.style.transform = 'translateY(-6px)';
                    setTimeout(() => banner.remove(), 300);
                };

                const startTimer = () => {
                    startTime = Date.now();
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'running';
                    }
                    timer = setTimeout(dismiss, remaining);
                };

                const pauseTimer = () => {
                    clearTimeout(timer);
                    remaining = Math.max(0, remaining - (Date.now() - startTime));
                    if (progressBar) {
                        progressBar.style.animationPlayState = 'paused';
                    }
                };

                banner.addEventListener('mouseenter', pauseTimer);
                banner.addEventListener('mouseleave', () => {
                    if (remaining > 0) startTimer();
                });
                banner.addEventListener('focusin', pauseTimer);
                banner.addEventListener('focusout', () => {
                    if (remaining > 0) startTimer();
                });

                startTimer();
            })();
        </script>
    @endif

    <main id="main-content" class="w-full bg-surface min-h-[calc(100vh-80px)]">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
