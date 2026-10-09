<!-- QUICK ACTION BANNER: DATABASE REGISTRATION -->
<section class="w-full py-12 sm:py-16 lg:py-20 bg-primary text-on-primary relative overflow-hidden" id="daftar-alumni">
    <!-- Subtle Ambient Glow -->
    <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-secondary/20 rounded-full filter blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -top-24 w-80 h-80 bg-secondary/10 rounded-full filter blur-3xl pointer-events-none"></div>

    <div class="max-w-[1280px] mx-auto px-4 sm:px-6 relative z-10">
        <!-- Call To Action Glass Card -->
        <div class="p-6 sm:p-10 lg:p-12 rounded-3xl bg-surface-container-lowest/[0.06] backdrop-blur-md border border-white/15 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-8">
            
            <!-- Left Narrative -->
            <div class="flex flex-col gap-3 max-w-2xl text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-secondary/20 border border-secondary/30 text-secondary w-fit mx-auto lg:mx-0 text-xs font-bold tracking-wide">
                    <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                    <span>Gerakan Pendataan Alumni</span>
                </div>
                
                <h2 class="font-headline-lg text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Belum Terdaftar di Direktori Alumni KPS?
                </h2>
                
                <p class="font-body-md text-xs sm:text-sm md:text-base text-surface-container-highest/85 leading-relaxed">
                    Luangkan 2 menit untuk mendaftarkan akun dan profil Anda. Nikmati kemudahan terhubung dengan sesama lulusan TK, SD, SMP, &amp; SMA KPS, mempromosikan bisnis UMKM, dan mengakses info lowongan karir.
                </p>
                
                <div class="pt-1 flex flex-wrap items-center justify-center lg:justify-start gap-3 sm:gap-4 text-xs text-surface-container-highest/70">
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
                        Verifikasi Resmi Pengurus
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">lock</span>
                        Data Aman &amp; Terlindungi
                    </span>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex flex-col sm:flex-row lg:flex-col items-center gap-3.5 shrink-0 w-full sm:w-auto">
                @auth
                    <a href="{{ route('profile.show') }}" 
                       class="w-full sm:w-auto lg:w-full px-7 py-3.5 rounded-xl bg-secondary hover:bg-secondary/90 text-on-secondary font-label-lg font-bold transition-all shadow-lg shadow-secondary/25 active:scale-95 flex items-center justify-center gap-2 text-sm min-h-[44px]">
                        <span class="material-symbols-outlined text-[20px]">account_circle</span>
                        <span>Lihat Profil Saya</span>
                    </a>
                    
                    <a href="{{ route('alumni.index') }}" 
                       class="w-full sm:w-auto lg:w-full px-6 py-3.5 rounded-xl border border-white/20 text-white hover:bg-white/10 font-label-lg font-semibold transition-colors flex items-center justify-center gap-2 text-sm min-h-[44px]">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                        <span>Jelajahi Direktori Alumni</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" 
                       class="w-full sm:w-auto lg:w-full px-7 py-3.5 rounded-xl bg-secondary hover:bg-secondary/90 text-on-secondary font-label-lg font-bold transition-all shadow-lg shadow-secondary/25 active:scale-95 flex items-center justify-center gap-2 text-sm min-h-[44px]">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        <span>Daftar Akun Alumni Sekarang</span>
                    </a>
                    
                    <a href="{{ route('login') }}" 
                       class="w-full sm:w-auto lg:w-full px-6 py-3.5 rounded-xl border border-white/20 text-white hover:bg-white/10 font-label-lg font-semibold transition-colors flex items-center justify-center gap-2 text-sm min-h-[44px]">
                        <span>Sudah Punya Akun? Masuk</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>

                    <a href="{{ route('alumni.index') }}" 
                       class="text-xs text-secondary hover:underline flex items-center gap-1 pt-1 min-h-[44px]">
                        <span class="material-symbols-outlined text-[14px]">search</span>
                        <span>Cek nama Anda di direktori alumni</span>
                    </a>
                @endauth
            </div>

        </div>
    </div>
</section>
