@php
    $pendingBadge = \App\Models\User::where('status', 'pending')->count();
    $pendingJobsCount = \App\Models\JobVacancy::where('status', 'pending')->count();
    $unreadContactsCount = \App\Models\Contact::where('is_read', false)->count();
@endphp

<aside id="admin-sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-surface-container-lowest border-r border-outline-variant/40 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
    <!-- Brand Logo -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-outline-variant/40">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-primary text-on-primary font-black flex items-center justify-center text-base tracking-wider shadow-sm">
                KPS
            </div>
            <div>
                <span class="font-headline-sm font-extrabold text-sm tracking-tight text-primary block leading-none">
                    IKA KPS
                </span>
                <span class="text-[10px] font-bold tracking-widest text-secondary uppercase block mt-1">
                    Panel Admin
                </span>
            </div>
        </a>
        <button type="button" onclick="toggleAdminSidebar()" class="lg:hidden p-1.5 rounded-lg text-on-surface-variant hover:text-primary">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
        <!-- Main Section -->
        <div>
            <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-on-surface-variant/70 block mb-2">
                Menu Utama
            </span>
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">dashboard</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.alumni.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.alumni.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">group</span>
                    <span>Direktori Alumni</span>
                </a>

                <a href="{{ route('admin.verification.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.verification.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">verified_user</span>
                        <span>Verifikasi Pendaftaran</span>
                    </div>
                    @if ($pendingBadge > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('admin.verification.*') ? 'bg-amber-400 text-stone-900' : 'bg-amber-100 text-amber-800' }}">
                            {{ $pendingBadge }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Content Section -->
        <div>
            <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-on-surface-variant/70 block mb-2">
                Konten &amp; Aktivitas
            </span>
            <div class="space-y-1">
                <a href="{{ route('admin.businesses.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.businesses.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">storefront</span>
                    <span>Bisnis Alumni</span>
                </a>

                <a href="{{ route('admin.job-vacancies.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.job-vacancies.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">work</span>
                        <span>Bursa Kerja</span>
                    </div>
                    @if ($pendingJobsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('admin.job-vacancies.*') ? 'bg-amber-400 text-stone-900' : 'bg-amber-100 text-amber-800' }}">
                            {{ $pendingJobsCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.articles.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.articles.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">newspaper</span>
                    <span>Berita &amp; Artikel</span>
                </a>

                <a href="{{ route('admin.events.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.events.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">event</span>
                    <span>Event &amp; Agenda</span>
                </a>

                <a href="{{ route('admin.galleries.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.galleries.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">photo_library</span>
                    <span>Galeri Foto</span>
                </a>

                <a href="{{ route('admin.programs.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.programs.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[20px]">volunteer_activism</span>
                    <span>Program Kerja</span>
                </a>

                <a href="{{ route('admin.contacts.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->routeIs('admin.contacts.*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface hover:bg-surface-container hover:text-primary' }}">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[20px]">inbox</span>
                        <span>Kotak Masuk</span>
                    </div>
                    @if ($unreadContactsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ request()->routeIs('admin.contacts.*') ? 'bg-amber-400 text-stone-900' : 'bg-amber-100 text-amber-800' }}">
                            {{ $unreadContactsCount }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        <!-- System & Shortcuts -->
        <div>
            <span class="px-3 text-[10px] font-extrabold tracking-widest uppercase text-on-surface-variant/70 block mb-2">
                Pintasan
            </span>
            <div class="space-y-1">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold text-on-surface-variant hover:bg-surface-container hover:text-primary transition-all">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        <span>Lihat Web Publik</span>
                    </div>
                </a>
                <a href="{{ route('profile.show') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-on-surface-variant hover:bg-surface-container hover:text-primary transition-all">
                    <span class="material-symbols-outlined text-[18px]">person</span>
                    <span>Profil Pengurus</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-primary text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                    <span class="material-symbols-outlined text-[18px]">settings</span>
                    <span>Pengaturan Website</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Admin User Footer -->
    <div class="p-4 border-t border-outline-variant/40 bg-surface-container-low/40">
        <div class="flex items-center gap-3 mb-3">
            <img src="{{ auth()->user()->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=002D62&color=fff&size=80' }}" 
                 alt="{{ auth()->user()->name }}" 
                 class="w-10 h-10 rounded-xl object-cover ring-1 ring-outline-variant shrink-0">
            <div class="min-w-0 flex-1">
                <div class="text-xs font-bold text-primary truncate">{{ auth()->user()->name }}</div>
                <div class="text-[10px] text-secondary font-bold uppercase tracking-wider">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full py-2 rounded-xl border border-error/30 text-error hover:bg-error-container/20 text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">logout</span>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
