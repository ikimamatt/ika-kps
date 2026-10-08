@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Pengaturan Umum &amp; Konfigurasi Website</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola identitas resmi organisasi, nomor kontak sekretariat, dan tautan kanal media sosial tanpa menyentuh kode.
            </p>
        </div>
        <div>
            <a href="{{ route('home') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Lihat Website Publik</span>
            </a>
        </div>
    </div>

    <!-- Flash Alert -->
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

    <!-- Form Container -->
    <form action="{{ route('admin.settings.update') }}" method="POST" 
          class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs space-y-8">
        @csrf

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-2 border-b border-outline-variant/30 pb-3 overflow-x-auto scrollbar-none" role="tablist">
            <button type="button" onclick="switchSettingTab('general')" id="tab-btn-general"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-primary text-on-primary shadow-xs">
                <span class="material-symbols-outlined text-[18px]">corporate_fare</span>
                <span>Identitas Organisasi</span>
            </button>

            <button type="button" onclick="switchSettingTab('contact')" id="tab-btn-contact"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-on-surface-variant hover:text-primary hover:bg-surface-container">
                <span class="material-symbols-outlined text-[18px]">contact_mail</span>
                <span>Kontak &amp; Alamat</span>
            </button>

            <button type="button" onclick="switchSettingTab('social')" id="tab-btn-social"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-on-surface-variant hover:text-primary hover:bg-surface-container">
                <span class="material-symbols-outlined text-[18px]">share</span>
                <span>Media Sosial Resmi</span>
            </button>
        </div>

        <!-- Tab 1: Identitas Organisasi -->
        <div id="tab-panel-general" class="space-y-5">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-primary">Informasi Identitas &amp; Tagline</h3>
                <p class="text-2xs text-on-surface-variant">Konfigurasi nama lembaga yang ditampilkan pada header, footer, dan meta branding website.</p>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label for="org_name" class="block text-xs font-bold text-primary">Nama Resmi Organisasi</label>
                    <input type="text" id="org_name" name="settings[org_name]" 
                           value="{{ old('settings.org_name', $settings['org_name'] ?? 'IKA KPS Balikpapan') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="org_tagline" class="block text-xs font-bold text-primary">Slogan / Tagline Alumni</label>
                    <input type="text" id="org_tagline" name="settings[org_tagline]" 
                           value="{{ old('settings.org_tagline', $settings['org_tagline'] ?? 'Satu Almamater, Seribu Cerita') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="org_description" class="block text-xs font-bold text-primary">Deskripsi Ringkas Organisasi</label>
                    <textarea id="org_description" name="settings[org_description]" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary leading-relaxed">{{ old('settings.org_description', $settings['org_description'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tab 2: Kontak & Alamat -->
        <div id="tab-panel-contact" class="space-y-5 hidden">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-primary">Kontak Sekretariat &amp; Layanan Pengurus</h3>
                <p class="text-2xs text-on-surface-variant">Informasi ini otomatis tampil pada formulir kontak publik dan footer website.</p>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label for="contact_email" class="block text-xs font-bold text-primary">Email Resmi Pengurus</label>
                        <input type="email" id="contact_email" name="settings[contact_email]" 
                               value="{{ old('settings.contact_email', $settings['contact_email'] ?? 'sekretariat@ika-kps.id') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>

                    <div class="space-y-1.5">
                        <label for="contact_phone" class="block text-xs font-bold text-primary">Telepon Kantor</label>
                        <input type="text" id="contact_phone" name="settings[contact_phone]" 
                               value="{{ old('settings.contact_phone', $settings['contact_phone'] ?? '0542-731234') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="contact_whatsapp" class="block text-xs font-bold text-primary">Nomor Hotline WhatsApp Resmi</label>
                    <input type="text" id="contact_whatsapp" name="settings[contact_whatsapp]" 
                           value="{{ old('settings.contact_whatsapp', $settings['contact_whatsapp'] ?? '08115401985') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="contact_address" class="block text-xs font-bold text-primary">Alamat Fisik Kantor Sekretariat</label>
                    <textarea id="contact_address" name="settings[contact_address]" rows="3"
                              class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary leading-relaxed">{{ old('settings.contact_address', $settings['contact_address'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Tab 3: Media Sosial -->
        <div id="tab-panel-social" class="space-y-5 hidden">
            <div class="space-y-1">
                <h3 class="text-sm font-bold text-primary">Tautan Media Sosial Resmi IKA KPS</h3>
                <p class="text-2xs text-on-surface-variant">Tautan profil yang terhubung langsung di footer dan header website.</p>
            </div>

            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label for="social_instagram" class="block text-xs font-bold text-primary">Instagram URL</label>
                    <input type="url" id="social_instagram" name="settings[social_instagram]" 
                           value="{{ old('settings.social_instagram', $settings['social_instagram'] ?? 'https://instagram.com/ikakps_official') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="social_linkedin" class="block text-xs font-bold text-primary">LinkedIn Company URL</label>
                    <input type="url" id="social_linkedin" name="settings[social_linkedin]" 
                           value="{{ old('settings.social_linkedin', $settings['social_linkedin'] ?? 'https://linkedin.com/company/ika-kps') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="social_youtube" class="block text-xs font-bold text-primary">YouTube Channel URL</label>
                    <input type="url" id="social_youtube" name="settings[social_youtube]" 
                           value="{{ old('settings.social_youtube', $settings['social_youtube'] ?? 'https://youtube.com/@ikakpsbalikpapan') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>
        </div>

        <!-- Submit Toolbar -->
        <div class="pt-6 border-t border-outline-variant/30 flex items-center justify-between">
            <span class="text-2xs text-on-surface-variant flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px] text-emerald-600">cached</span>
                <span>Perubahan akan otomatis memperbarui cache konfigurasi sistem.</span>
            </span>

            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs sm:text-sm hover:bg-primary-container transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Perubahan Pengaturan</span>
            </button>
        </div>
    </form>
</div>

<script>
    function switchSettingTab(tabName) {
        const tabs = ['general', 'contact', 'social'];
        tabs.forEach(t => {
            const btn = document.getElementById(`tab-btn-${t}`);
            const panel = document.getElementById(`tab-panel-${t}`);

            if (t === tabName) {
                btn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 bg-primary text-on-primary shadow-xs';
                panel.classList.remove('hidden');
            } else {
                btn.className = 'px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 text-on-surface-variant hover:text-primary hover:bg-surface-container';
                panel.classList.add('hidden');
            }
        });
    }
</script>
@endsection
