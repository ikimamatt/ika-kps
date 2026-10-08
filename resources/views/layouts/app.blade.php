<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'IKA KPS Balikpapan') }} — Satu Almamater, Seribu Cerita</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        ::-webkit-scrollbar { display: none; }
        body { overscroll-behavior: none; }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-secondary-fixed selection:text-on-secondary-fixed">
    <x-navbar />

    @if (session('success'))
        <div id="flash-banner" class="max-w-[1280px] mx-auto px-6 mt-4">
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

    <main class="w-full bg-surface min-h-[calc(100vh-80px)]">
        @yield('content')
    </main>

    <x-footer />

    @stack('scripts')
</body>
</html>
