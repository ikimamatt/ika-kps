@extends('layouts.app')

@section('content')
<div class="py-10 md:py-16 bg-surface">
    <div class="max-w-[1280px] mx-auto px-6 space-y-10">

        <!-- Breadcrumbs Navigation -->
        <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <a href="{{ route('event.index') }}" class="hover:text-primary transition-colors">Agenda &amp; Event</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-primary truncate max-w-xs font-bold">{{ $event->title }}</span>
        </nav>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 flex items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-emerald-600">check_circle</span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-800 flex items-center justify-between gap-3 animate-fade-in">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-rose-600">error</span>
                    <span class="text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        @endif

        <!-- Header Card -->
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs space-y-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-4 max-w-3xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        @php
                            $catColors = [
                                'reuni' => 'bg-secondary/10 text-secondary border-secondary/20',
                                'baksos' => 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
                                'seminar' => 'bg-amber-500/10 text-amber-700 border-amber-500/20',
                                'olahraga' => 'bg-sky-500/10 text-sky-700 border-sky-500/20',
                                'lainnya' => 'bg-purple-500/10 text-purple-700 border-purple-500/20',
                            ];
                            $colorClass = $catColors[$event->category] ?? 'bg-secondary/10 text-secondary border-secondary/20';

                            $isPast = $event->start_date->isPast();
                            $isToday = $event->start_date->isToday();
                        @endphp

                        <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $colorClass }} flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[15px]">label</span>
                            <span>{{ ucfirst($event->category) }}</span>
                        </span>

                        @if ($isToday)
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-700 border border-amber-500/30 flex items-center gap-1.5 animate-pulse">
                                <span class="material-symbols-outlined text-[15px]">live_tv</span>
                                <span>Berlangsung Hari Ini</span>
                            </span>
                        @elseif ($isPast)
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant border border-outline-variant/40 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]">history</span>
                                <span>Kegiatan Selesai</span>
                            </span>
                        @else
                            <span class="px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/30 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[15px]">event_available</span>
                                <span>Mendatang</span>
                            </span>
                        @endif

                        @auth
                            @if(in_array(auth()->user()->role, ['admin', 'pengurus']))
                                <a href="{{ route('admin.events.edit', $event->id) }}" 
                                   class="px-3 py-1 rounded-full text-xs font-bold bg-surface-container-high text-on-surface hover:text-primary transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">edit</span>
                                    <span>Edit Event</span>
                                </a>
                            @endif
                        @endauth
                    </div>

                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-primary tracking-tight leading-snug">
                        {{ $event->title }}
                    </h1>

                    <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed">
                        {{ $event->description }}
                    </p>
                </div>

                <!-- Back link -->
                <div class="shrink-0">
                    <a href="{{ route('event.index') }}" 
                       class="px-4 py-2.5 rounded-2xl border border-outline-variant/60 hover:bg-surface-container text-xs font-bold text-on-surface transition-colors inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                        <span>Kembali ke Agenda</span>
                    </a>
                </div>
            </div>

            <!-- Quick Metadata Bar -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 border-t border-outline-variant/30">
                <div class="space-y-1">
                    <span class="text-xs font-semibold text-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">calendar_today</span>
                        <span>Tanggal Pelaksanaan</span>
                    </span>
                    <p class="text-sm font-bold text-primary">
                        {{ $event->start_date->isoFormat('dddd, D MMMM Y') }}
                    </p>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-semibold text-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">schedule</span>
                        <span>Waktu Kegiatan</span>
                    </span>
                    <p class="text-sm font-bold text-primary">
                        {{ $event->start_date->format('H:i') }} 
                        @if($event->end_date)
                            - {{ $event->end_date->format('H:i') }} WITA
                        @else
                            WITA - Selesai
                        @endif
                    </p>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-semibold text-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">location_on</span>
                        <span>Tempat / Venue</span>
                    </span>
                    <p class="text-sm font-bold text-primary truncate" title="{{ $event->location }}">
                        {{ $event->location }}
                    </p>
                </div>

                <div class="space-y-1">
                    <span class="text-xs font-semibold text-on-surface-variant flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-secondary text-[16px]">confirmation_number</span>
                        <span>Biaya Partisipasi</span>
                    </span>
                    <p class="text-sm font-bold {{ $event->fee > 0 ? 'text-secondary' : 'text-emerald-700' }}">
                        {{ $event->fee > 0 ? 'Rp ' . number_format($event->fee, 0, ',', '.') : 'Gratis / Free RSVP' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column: Poster, Detail Body & Participants (2 Cols) -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Poster Banner Image -->
                @if ($event->image_url)
                    <div class="relative w-full aspect-[16/9] rounded-3xl overflow-hidden bg-surface-container border border-outline-variant/30 shadow-xs">
                        <img src="{{ $event->image_url }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Detailed Event Information -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <h2 class="text-lg sm:text-xl font-bold text-primary flex items-center gap-2.5 pb-4 border-b border-outline-variant/30">
                        <span class="material-symbols-outlined text-secondary text-[22px]">format_align_left</span>
                        <span>Deskripsi &amp; Susunan Acara</span>
                    </h2>

                    <div class="prose prose-sm sm:prose max-w-none text-on-surface-variant leading-relaxed">
                        @if ($event->body)
                            {!! nl2br(e($event->body)) !!}
                        @else
                            <p>{{ $event->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Location & Venue Details -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-xs space-y-4">
                    <h2 class="text-lg sm:text-xl font-bold text-primary flex items-center gap-2.5 pb-4 border-b border-outline-variant/30">
                        <span class="material-symbols-outlined text-secondary text-[22px]">map</span>
                        <span>Lokasi &amp; Akses Tempat</span>
                    </h2>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-secondary text-[24px] shrink-0 mt-0.5">pin_drop</span>
                            <div>
                                <span class="text-xs font-semibold text-on-surface-variant block">Alamat Pelaksanaan:</span>
                                <span class="text-sm font-bold text-primary block mt-0.5">{{ $event->location }}</span>
                            </div>
                        </div>

                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($event->location) }}" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="px-4 py-2.5 rounded-xl bg-surface-container-lowest border border-outline-variant/60 hover:border-primary text-xs font-bold text-primary transition-all shadow-2xs flex items-center justify-center gap-1.5 shrink-0">
                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            <span>Buka di Google Maps</span>
                        </a>
                    </div>
                </div>

                <!-- Confirmed Participants List -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
                        <h2 class="text-lg sm:text-xl font-bold text-primary flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-secondary text-[22px]">group</span>
                            <span>Alumni yang Telah RSVP</span>
                        </h2>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-secondary/10 text-secondary border border-secondary/20">
                            {{ $event->registrations_count }} Terdaftar
                        </span>
                    </div>

                    @if ($confirmedParticipants->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($confirmedParticipants as $reg)
                                <div class="flex items-center gap-3 p-3 rounded-2xl bg-surface-container-low/50 border border-outline-variant/30">
                                    <div class="w-10 h-10 rounded-full bg-secondary text-on-secondary font-bold text-sm flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($reg->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs sm:text-sm font-bold text-primary truncate">
                                            {{ $reg->user->name }}
                                        </p>
                                        <p class="text-2xs text-on-surface-variant">
                                            {{ $reg->user->alumnus?->graduation_year ? 'Angkatan ' . $reg->user->alumnus->graduation_year : 'Alumni KPS' }}
                                            &bull; {{ $reg->registered_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($event->registrations_count > 8)
                            <p class="text-xs text-center text-on-surface-variant pt-2">
                                dan {{ $event->registrations_count - 8 }} alumni lainnya telah mengonfirmasi kehadiran.
                            </p>
                        @endif
                    @else
                        <div class="text-center py-8 text-on-surface-variant space-y-2">
                            <span class="material-symbols-outlined text-4xl text-outline-variant">person_search</span>
                            <p class="text-xs sm:text-sm font-medium">Jadilah alumni pertama yang mengonfirmasi kehadiran pada kegiatan ini!</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: RSVP Actions & Sidebar Information (1 Col) -->
            <div class="space-y-6">
                
                <!-- Sticky RSVP Card -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-7 shadow-xs space-y-6 sticky top-24">
                    <div class="flex items-center justify-between pb-4 border-b border-outline-variant/30">
                        <span class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                            <span>Konfirmasi Hadir</span>
                        </span>

                        <span class="text-xs font-bold text-primary">
                            @if ($event->max_participants)
                                {{ $event->registrations_count }} / {{ $event->max_participants }} Kuota
                            @else
                                {{ $event->registrations_count }} Terdaftar
                            @endif
                        </span>
                    </div>

                    <!-- Quota Progress Indicator -->
                    @if ($event->max_participants)
                        @php
                            $percentage = min(100, round(($event->registrations_count / $event->max_participants) * 100));
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-2xs font-bold text-on-surface-variant">
                                <span>Kapasitas Terisi</span>
                                <span>{{ $percentage }}%</span>
                            </div>
                            <div class="w-full bg-surface-container rounded-full h-2.5 overflow-hidden">
                                <div class="bg-secondary h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            @if ($event->isFullyBooked())
                                <p class="text-2xs text-rose-600 font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px]">block</span>
                                    <span>Kuota telah mencapai batas maksimal.</span>
                                </p>
                            @else
                                <p class="text-2xs text-emerald-700 font-medium">
                                    Tersisa {{ $event->max_participants - $event->registrations_count }} kursi tersedia.
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- RSVP Interactivity States -->
                    <div class="space-y-4">
                        @guest
                            <!-- State 1: Guest -->
                            <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 space-y-3 text-center">
                                <span class="material-symbols-outlined text-secondary text-3xl">login</span>
                                <div class="space-y-1">
                                    <h4 class="text-xs font-bold text-primary">Ingin Hadir di Acara Ini?</h4>
                                    <p class="text-2xs text-on-surface-variant">Silakan masuk menggunakan akun alumni Anda untuk melakukan reservasi kehadiran instan.</p>
                                </div>
                                <a href="{{ route('login') }}" 
                                   class="w-full py-2.5 px-4 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-xs flex items-center justify-center gap-1.5">
                                    <span>Masuk untuk RSVP</span>
                                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                </a>
                            </div>
                        @else
                            @if ($userRegistration)
                                <!-- State 2: Already Registered -->
                                <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 space-y-4 text-center">
                                    <div class="w-12 h-12 mx-auto rounded-full bg-emerald-600 text-white flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[24px]">task_alt</span>
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="text-sm font-extrabold text-emerald-900">Anda Sudah Terdaftar!</h4>
                                        <p class="text-2xs text-emerald-800">
                                            Konfirmasi dicatat pada {{ $userRegistration->registered_at->isoFormat('D MMMM Y, H:i') }} WITA.
                                        </p>
                                        @if ($userRegistration->notes)
                                            <p class="text-2xs italic text-emerald-800/80 bg-white/60 p-2 rounded-lg border border-emerald-500/20 mt-2">
                                                &ldquo;{{ $userRegistration->notes }}&rdquo;
                                            </p>
                                        @endif
                                    </div>

                                    @if (! $isPast)
                                        <form method="POST" action="{{ route('event.rsvp.cancel', $event->slug) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan kehadiran pada kegiatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="w-full py-2 px-3 rounded-xl border border-rose-300 text-rose-700 hover:bg-rose-50 font-bold text-xs transition-colors flex items-center justify-center gap-1.5">
                                                <span class="material-symbols-outlined text-[15px]">event_busy</span>
                                                <span>Batalkan Kehadiran</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @elseif ($isPast)
                                <!-- State 3: Past Event -->
                                <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-center space-y-2">
                                    <span class="material-symbols-outlined text-outline-variant text-3xl">event_available</span>
                                    <h4 class="text-xs font-bold text-primary">Kegiatan Telah Selesai</h4>
                                    <p class="text-2xs text-on-surface-variant">Pendaftaran RSVP telah ditutup karena waktu pelaksanaan agenda telah lewat.</p>
                                </div>
                            @elseif ($event->isFullyBooked())
                                <!-- State 4: Fully Booked -->
                                <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-center space-y-2">
                                    <span class="material-symbols-outlined text-rose-600 text-3xl">sentiment_dissatisfied</span>
                                    <h4 class="text-xs font-bold text-rose-800">Kuota Peserta Penuh</h4>
                                    <p class="text-2xs text-rose-700">Mohon maaf, batas maksimal kuota peserta kegiatan ini telah terpenuhi.</p>
                                </div>
                            @else
                                <!-- State 5: Can RSVP Form -->
                                <form method="POST" action="{{ route('event.rsvp', $event->slug) }}" class="space-y-4">
                                    @csrf
                                    <div class="space-y-1.5">
                                        <label for="notes" class="text-xs font-bold text-primary flex items-center gap-1">
                                            <span>Catatan / Pesan</span>
                                            <span class="text-2xs font-normal text-on-surface-variant">(Opsional)</span>
                                        </label>
                                        <textarea id="notes" 
                                                  name="notes" 
                                                  rows="3" 
                                                  placeholder="Contoh: Hadir bersama rekan angkatan 2014, dll..."
                                                  class="w-full text-xs rounded-xl border border-outline-variant/60 focus:border-primary focus:ring-1 focus:ring-primary p-3 bg-surface-container-lowest text-on-surface">{{ old('notes') }}</textarea>
                                    </div>

                                    <button type="submit" 
                                            class="w-full py-3 px-4 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                        <span>Konfirmasi Kehadiran (RSVP)</span>
                                    </button>
                                </form>
                            @endif
                        @endguest
                    </div>

                    <!-- Share Section -->
                    <div class="pt-4 border-t border-outline-variant/30 space-y-3">
                        <span class="text-xs font-bold text-on-surface-variant block">Bagikan Agenda:</span>
                        <div class="flex items-center gap-2">
                            <button type="button" 
                                    onclick="copyEventUrl()" 
                                    id="btnCopyUrl"
                                    class="flex-1 py-2 px-3 rounded-xl border border-outline-variant/60 hover:bg-surface-container text-xs font-bold text-primary transition-colors flex items-center justify-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">link</span>
                                <span id="copyText">Salin Tautan</span>
                            </button>

                            @php
                                $shareText = urlencode("Yuk ikuti agenda IKA KPS Balikpapan: {$event->title} pada " . $event->start_date->isoFormat('D MMMM Y') . " di {$event->location}. Cek detail di: " . url()->current());
                            @endphp
                            <a href="https://api.whatsapp.com/send?text={{ $shareText }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="py-2 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center justify-center gap-1.5">
                                <x-icons.whatsapp class="w-3.5 h-3.5 fill-current shrink-0" />
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Kontak Panitia -->
                <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 shadow-xs space-y-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">support_agent</span>
                        <span>Narahubung &amp; Penyelenggara</span>
                    </h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Penyelenggara: <strong class="text-primary font-bold">Sekretariat IKA KPS</strong><br>
                        Butuh info tambahan atau kerjasama sponsorship? Hubungi pengurus kami melalui tautan kontak resmi IKA KPS.
                    </p>
                </div>
            </div>
        </div>

        <!-- Related Upcoming Events -->
        @if ($relatedEvents->isNotEmpty())
            <div class="pt-8 border-t border-outline-variant/30 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-secondary block">Agenda Lainnya</span>
                        <h2 class="text-xl sm:text-2xl font-extrabold text-primary">Kegiatan Mendatang Lainnya</h2>
                    </div>
                    <a href="{{ route('event.index') }}" class="text-xs font-bold text-primary hover:text-secondary flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedEvents as $rel)
                        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group">
                            <div class="relative aspect-[16/10] bg-surface-container-high overflow-hidden">
                                @if ($rel->image_url)
                                    <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-outline-variant">
                                        <span class="material-symbols-outlined text-4xl">calendar_today</span>
                                    </div>
                                @endif
                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-1 rounded-full text-2xs font-extrabold uppercase bg-surface-container-lowest/90 backdrop-blur-xs text-primary shadow-2xs">
                                        {{ $rel->category }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                                <div class="space-y-1.5">
                                    <span class="text-2xs font-semibold text-secondary flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px]">calendar_month</span>
                                        <span>{{ $rel->start_date->isoFormat('D MMM Y') }}</span>
                                    </span>
                                    <h3 class="text-sm font-bold text-primary line-clamp-2 group-hover:text-secondary transition-colors">
                                        <a href="{{ route('event.show', $rel->slug) }}">
                                            {{ $rel->title }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="pt-2 border-t border-outline-variant/30 flex items-center justify-between text-2xs font-medium text-on-surface-variant">
                                    <span class="truncate max-w-[150px]">{{ $rel->location }}</span>
                                    <a href="{{ route('event.show', $rel->slug) }}" class="font-bold text-primary hover:underline">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    function copyEventUrl() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const copyText = document.getElementById('copyText');
            copyText.textContent = 'Tersalin!';
            setTimeout(() => {
                copyText.textContent = 'Salin Tautan';
            }, 2500);
        }).catch(() => {
            alert('Gagal menyalin tautan.');
        });
    }
</script>
@endsection
