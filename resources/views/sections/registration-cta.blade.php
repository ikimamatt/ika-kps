<!-- QUICK ACTION BANNER & FORM: DATABASE REGISTRATION -->
<section class="w-full py-16 bg-primary text-on-primary relative overflow-hidden" id="daftar-alumni">
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-secondary/20 rounded-full filter blur-3xl pointer-events-none"></div>
    <div class="max-w-[1280px] mx-auto px-6 relative z-10 flex flex-col gap-10">
        <!-- Call To Action Bar -->
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <div class="flex flex-col gap-2 max-w-2xl text-center lg:text-left">
                <span class="font-label-md text-label-md text-secondary-fixed uppercase tracking-widest font-bold">Gerakan Pendataan Alumni</span>
                <h2 class="font-headline-lg text-headline-xl text-on-primary">Belum Terdaftar di Buku Induk Alumni KPS?</h2>
                <p class="font-body-md text-body-md text-surface-container-highest/80">
                    Mari luangkan 2 menit untuk memperbarui data diri, profesi, dan kontak Anda agar jejaring alumni semakin solid dan berdaya guna.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-4 shrink-0">
                <button type="button" 
                        onclick="const f = document.getElementById('registration-form-container'); f.classList.toggle('hidden'); f.scrollIntoView({ behavior: 'smooth' });"
                        class="px-6 py-3.5 rounded-xl bg-secondary hover:bg-secondary-container text-on-secondary font-label-lg font-bold transition-all shadow-lg active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                    Isi Formulir Anggota Online
                </button>
                <a href="#direktori-alumni" class="px-6 py-3.5 rounded-xl border border-surface-container-highest/40 text-on-primary hover:bg-surface-container-highest/10 font-label-lg font-semibold transition-colors">
                    Cek Status Keanggotaan
                </a>
            </div>
        </div>

        <!-- Integrated Registration Form Box (Toggleable / Expanded) -->
        <div id="registration-form-container" class="bg-surface-container-lowest text-on-surface p-6 sm:p-10 rounded-2xl shadow-2xl border border-outline-variant/40 mt-4 transition-all">
            <div class="flex items-center justify-between border-b border-outline-variant/30 pb-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/15 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">assignment</span>
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-primary font-bold">Formulir Pendaftaran Database Alumni KPS</h3>
                        <p class="text-xs text-on-surface-variant">Lengkapi data diri Anda untuk divalidasi ke dalam direktori resmi IKA KPS.</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="p-4 mb-6 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                    <p class="font-bold">Mohon perbaiki isian form berikut:</p>
                    <ul class="list-disc pl-5 mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('alumni.register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <!-- Nama Lengkap -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="full_name">Nama Lengkap &amp; Gelar *</label>
                    <input type="text" 
                           id="full_name" 
                           name="full_name" 
                           required 
                           value="{{ old('full_name') }}"
                           placeholder="Contoh: Muhammad Irfan, S.T." 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Jenjang Sekolah -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="level">Jenjang Terakhir di KPS *</label>
                    <select id="level" 
                            name="level" 
                            required 
                            class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary">
                        <option value="sma" {{ old('level') == 'sma' ? 'selected' : '' }}>SMA Nasional KPS Balikpapan</option>
                        <option value="smp" {{ old('level') == 'smp' ? 'selected' : '' }}>SMP Nasional KPS Balikpapan</option>
                        <option value="sd" {{ old('level') == 'sd' ? 'selected' : '' }}>SD Nasional KPS Balikpapan</option>
                        <option value="tk" {{ old('level') == 'tk' ? 'selected' : '' }}>TK Nasional KPS Balikpapan</option>
                    </select>
                </div>

                <!-- Tahun Kelulusan / Angkatan -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="graduation_year">Tahun Kelulusan / Angkatan *</label>
                    <input type="text" 
                           id="graduation_year" 
                           name="graduation_year" 
                           required 
                           value="{{ old('graduation_year') }}"
                           placeholder="Contoh: 2011" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- No WhatsApp -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="phone_whatsapp">Nomor WhatsApp Aktif *</label>
                    <input type="text" 
                           id="phone_whatsapp" 
                           name="phone_whatsapp" 
                           required 
                           value="{{ old('phone_whatsapp') }}"
                           placeholder="Contoh: 081234567890" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="email">Alamat Email *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           required 
                           value="{{ old('email') }}"
                           placeholder="nama@domain.com" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Pekerjaan & Profesi -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="profession">Profesi / Jabatan Sekarang</label>
                    <input type="text" 
                           id="profession" 
                           name="profession" 
                           value="{{ old('profession') }}"
                           placeholder="Contoh: Civil Engineer, Pengusaha, ASN" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Perusahaan / Instansi -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="institution">Perusahaan / Instansi / Usaha</label>
                    <input type="text" 
                           id="institution" 
                           name="institution" 
                           value="{{ old('institution') }}"
                           placeholder="Contoh: PT Pertamina, Universitas Mulawarman" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Domisili -->
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-primary font-bold uppercase" for="domicile">Kota / Negara Domisili</label>
                    <input type="text" 
                           id="domicile" 
                           name="domicile" 
                           value="{{ old('domicile') }}"
                           placeholder="Contoh: Balikpapan / Jakarta / Singapura" 
                           class="h-11 px-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">
                </div>

                <!-- Catatan Tambahan -->
                <div class="flex flex-col gap-1.5 md:col-span-2">
                    <label class="font-label-sm text-primary font-bold uppercase" for="notes">Keterangan / Minat Kontribusi</label>
                    <textarea id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Tuliskan jika Anda bersedia menjadi mentor adik kelas, narasumber talkshow, atau memiliki penawaran kerja sama usaha..."
                              class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/40 text-sm focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20">{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-2 flex items-center justify-end gap-3 pt-2">
                    <button type="submit" class="px-8 py-3 rounded-xl bg-secondary hover:bg-secondary-container text-on-secondary font-label-lg font-bold shadow-md transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                        Kirim Pendaftaran Alumni
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
