<!-- PROGRAM KERJA & BAKTI SOSIAL ("Program Unggulan") -->
<section class="w-full py-20 bg-surface-container-low" id="program-kerja">
    <div class="max-w-[1280px] mx-auto px-6 flex flex-col gap-10">
        <div class="flex flex-col items-center text-center gap-2 max-w-2xl mx-auto">
            <span class="font-label-lg text-label-lg text-secondary font-bold uppercase tracking-wider">Kontribusi Nyata</span>
            <h2 class="font-headline-lg text-headline-xl text-primary tracking-tight">Program Kerja &amp; Bakti Sosial</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Penyaluran energi positif alumni untuk memajukan almamater, membina adik-adik kelas, dan meringankan beban sesama warga Balikpapan.
            </p>
        </div>

        <!-- 4 Feature Cards Grid with badge counters and progress bars -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($programs as $prog)
                <div class="p-6 rounded-xl bg-surface-container-lowest border border-outline-variant/40 shadow-sm flex flex-col justify-between gap-6 hover:-translate-y-1 transition-transform">
                    <div class="flex flex-col gap-4">
                        <div class="w-12 h-12 rounded-xl {{ $prog->icon_bg_class }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-[28px]">{{ $prog->icon }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <h3 class="font-title-md text-title-md text-primary font-bold">{{ $prog->title }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                {{ $prog->description }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 pt-4 border-t border-outline-variant/30">
                        <div class="flex justify-between font-label-sm text-label-sm">
                            <span class="text-on-surface-variant">{{ $prog->progress_label }}</span>
                            <span class="font-bold text-primary">{{ $prog->progress_status }}</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container-highest rounded-full overflow-hidden">
                            <div class="h-full {{ $prog->bar_color_class }} rounded-full" style="width: {{ $prog->progress_percent }}%;"></div>
                        </div>
                        <span class="font-body-sm text-body-sm font-bold {{ $prog->bar_color_class == 'bg-secondary' ? 'text-secondary' : ($prog->bar_color_class == 'bg-tertiary' ? 'text-tertiary' : 'text-primary') }}">
                            {{ $prog->achievement_text }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="col-span-4 text-center py-8 text-on-surface-variant">Belum ada agenda program kerja terdaftar.</p>
            @endforelse
        </div>
    </div>
</section>
