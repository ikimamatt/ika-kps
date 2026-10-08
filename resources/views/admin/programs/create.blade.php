@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant mb-1">
                <a href="{{ route('admin.programs.index') }}" class="hover:text-primary">Program Kerja</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Tambah Program Baru</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Formulir Program Kerja Baru</h1>
        </div>
        <a href="{{ route('admin.programs.index') }}" 
           class="px-4 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Container -->
    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" 
          class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 shadow-xs space-y-8">
        @csrf

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <div class="flex items-center gap-2 font-bold text-rose-900">
                    <span class="material-symbols-outlined text-[18px]">error</span>
                    <span>Terdapat kesalahan pada isian form:</span>
                </div>
                <ul class="list-disc list-inside pl-4 space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Informasi Utama -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">info</span>
                <span>1. Identitas &amp; Deskripsi Program</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2 space-y-1.5">
                    <label for="title" class="block text-xs font-bold text-primary">Nama Program Kerja <span class="text-rose-500">*</span></label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                           placeholder="Contoh: Beasiswa Pendidikan Putra/Putri KPS"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="status" class="block text-xs font-bold text-primary">Status Program <span class="text-rose-500">*</span></label>
                    <select id="status" name="status" required 
                            class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif Berjalan</option>
                        <option value="upcoming" {{ old('status') === 'upcoming' ? 'selected' : '' }}>Segera Dimulai</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Tuntas Terlaksana</option>
                    </select>
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-bold text-primary">Ringkasan Singkat (Highlight Tampilan Kartu) <span class="text-rose-500">*</span></label>
                <textarea id="description" name="description" rows="2" required 
                          placeholder="Penjelasan ringkas 1-2 kalimat untuk kartu ikhtisar program..."
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('description') }}</textarea>
            </div>

            <div class="space-y-1.5">
                <label for="body" class="block text-xs font-bold text-primary">Narasi Lengkap &amp; Rincian Program</label>
                <textarea id="body" name="body" rows="5" 
                          placeholder="Jelaskan latar belakang, mekanisme penyaluran, syarat penerima manfaat, dan narasi pendukung..."
                          class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">{{ old('body') }}</textarea>
            </div>

            <!-- Pilihan Visual Ikon Program -->
            @php
                $iconPresets = [
                    ['icon' => 'school', 'label' => 'Pendidikan', 'desc' => 'Beasiswa & Edukasi', 'bg' => 'bg-primary text-white'],
                    ['icon' => 'volunteer_activism', 'label' => 'Sosial & Donasi', 'desc' => 'Bansos & Kemanusiaan', 'bg' => 'bg-rose-600 text-white'],
                    ['icon' => 'diversity_3', 'label' => 'Alumni & Jaringan', 'desc' => 'Paguyuban & Temu Alumni', 'bg' => 'bg-secondary text-white'],
                    ['icon' => 'work', 'label' => 'Karir & Mentoring', 'desc' => 'Pelatihan & Karir', 'bg' => 'bg-blue-600 text-white'],
                    ['icon' => 'storefront', 'label' => 'UMKM & Bisnis', 'desc' => 'Wirausaha Alumni', 'bg' => 'bg-amber-600 text-white'],
                    ['icon' => 'health_and_safety', 'label' => 'Kesehatan', 'desc' => 'Baksos Medis & Bencana', 'bg' => 'bg-red-600 text-white'],
                    ['icon' => 'eco', 'label' => 'Lingkungan', 'desc' => 'Go Green & Mangrove', 'bg' => 'bg-emerald-600 text-white'],
                    ['icon' => 'sports_soccer', 'label' => 'Olahraga', 'desc' => 'Turnamen & Fun Walk', 'bg' => 'bg-orange-600 text-white'],
                    ['icon' => 'celebration', 'label' => 'Acara & Reuni', 'desc' => 'HUT & Reuni Akbar', 'bg' => 'bg-purple-600 text-white'],
                    ['icon' => 'campaign', 'label' => 'Publikasi', 'desc' => 'Sosialisasi & Kampanye', 'bg' => 'bg-indigo-600 text-white'],
                ];
                $currentIcon = old('icon', 'school');
                $currentBg = old('icon_bg_class', 'bg-primary text-white');
                $activePreset = collect($iconPresets)->firstWhere('icon', $currentIcon);
            @endphp

            <div class="space-y-2.5 pt-3 border-t border-outline-variant/30">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <label class="block text-xs font-bold text-primary">Ikon Visual Kategori Program</label>
                        <p class="text-[11px] text-on-surface-variant/70">Pilih salah satu ikon visual agar tampilan kartu di web publik terlihat menarik dan serasi:</p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container border border-outline-variant/40 text-xs font-bold text-primary shadow-2xs self-start sm:self-auto">
                        <span class="text-[10px] text-on-surface-variant font-normal">Ikon Terpilih:</span>
                        <span id="current-icon-preview" class="material-symbols-outlined text-[16px] text-secondary">{{ $currentIcon }}</span>
                        <span id="current-icon-name" class="text-xs text-primary font-bold">{{ $activePreset['label'] ?? $currentIcon }}</span>
                    </div>
                </div>

                <!-- Input Hidden untuk Form Submission -->
                <input type="hidden" id="icon" name="icon" value="{{ $currentIcon }}">
                <input type="hidden" id="icon_bg_class" name="icon_bg_class" value="{{ $currentBg }}">

                <!-- Grid Kartu Pilihan Ikon Visual -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-2.5" id="icon-picker-grid">
                    @foreach($iconPresets as $preset)
                        @php
                            $isSelected = $currentIcon === $preset['icon'];
                        @endphp
                        <button type="button" 
                                onclick="selectProgramIcon('{{ $preset['icon'] }}', '{{ $preset['bg'] }}', '{{ $preset['label'] }}', this)"
                                data-icon="{{ $preset['icon'] }}"
                                class="icon-preset-btn p-2.5 rounded-xl border text-left transition-all flex flex-col items-center sm:items-start gap-1.5 cursor-pointer group {{ $isSelected ? 'border-secondary ring-2 ring-secondary/30 bg-secondary/5' : 'border-outline-variant/50 bg-surface-container-lowest hover:border-secondary/60 hover:bg-surface-container-low' }}">
                            <div class="w-9 h-9 rounded-xl {{ $preset['bg'] }} flex items-center justify-center shadow-xs group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">{{ $preset['icon'] }}</span>
                            </div>
                            <div class="w-full text-center sm:text-left">
                                <div class="font-bold text-xs text-primary truncate">{{ $preset['label'] }}</div>
                                <div class="text-[10px] text-on-surface-variant/70 truncate">{{ $preset['desc'] }}</div>
                            </div>
                        </button>
                    @endforeach
                </div>

                <!-- Opsional: Kode Ikon Kustom -->
                <div class="pt-1">
                    <details class="text-xs text-on-surface-variant">
                        <summary class="cursor-pointer hover:text-primary font-medium select-none inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">tune</span>
                            <span>Gunakan kode simbol Material Symbols lainnya (Opsional)</span>
                        </summary>
                        <div class="mt-2 pl-4 border-l-2 border-outline-variant/30 space-y-1.5">
                            <p class="text-[11px] text-on-surface-variant/80">Ketik nama simbol Material Symbols resmi jika ingin ikon di luar 10 pilihan di atas:</p>
                            <div class="flex items-center gap-2 max-w-xs">
                                <input type="text" id="custom_icon_input" 
                                       placeholder="emergency, psychology..."
                                       oninput="handleCustomIconInput(this.value)"
                                       value="{{ !collect($iconPresets)->contains('icon', $currentIcon) ? $currentIcon : '' }}"
                                       class="w-full px-3 py-1.5 rounded-lg border border-outline-variant/60 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                                <div class="w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center shrink-0">
                                    <span id="custom-icon-preview" class="material-symbols-outlined text-[18px] text-primary">{{ $currentIcon }}</span>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <!-- Progres & Target Dana -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-outline-variant/30">
                <h2 class="text-sm font-bold text-primary uppercase tracking-wider flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary text-[18px]">trending_up</span>
                    <span>2. Target Dana &amp; Capaian Progres</span>
                </h2>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1 shadow-2xs">
                    <span class="material-symbols-outlined text-[13px]">auto_awesome</span>
                    <span>Persentase Otomatis Terhitung</span>
                </span>
            </div>

            <!-- Nominal Target & Terkumpul -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="target_amount" class="block text-xs font-bold text-primary">Target Nominal Donasi / Kebutuhan (Rp)</label>
                    <input type="number" id="target_amount" name="target_amount" value="{{ old('target_amount') }}" min="0" step="1000" 
                           placeholder="Contoh: 100000000"
                           oninput="autoCalculateProgress()"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="collected_amount" class="block text-xs font-bold text-primary">Nominal Dana Terkumpul (Rp)</label>
                    <input type="number" id="collected_amount" name="collected_amount" value="{{ old('collected_amount', 0) }}" min="0" step="1000" 
                           placeholder="Contoh: 85000000"
                           oninput="autoCalculateProgress()"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <!-- Persentase & Label -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="progress_percent" class="block text-xs font-bold text-primary">Persentase Capaian (%)</label>
                        <button type="button" onclick="autoCalculateProgress()" class="inline-flex items-center gap-1 text-[11px] text-emerald-600 hover:text-emerald-700 font-semibold cursor-pointer transition-colors" title="Hitung ulang otomatis berdasarkan nominal dana">
                            <span class="material-symbols-outlined text-[13px]">calculate</span>
                            <span>Hitung Otomatis</span>
                        </button>
                    </div>
                    <div class="relative">
                        <input type="number" id="progress_percent" name="progress_percent" value="{{ old('progress_percent', 0) }}" min="0" 
                               oninput="updatePreviewBar()"
                               class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary font-bold text-secondary">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 font-bold text-xs text-on-surface-variant">%</span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant/70">Terhitung otomatis dari (Dana Terkumpul ÷ Target) × 100%.</p>
                </div>

                <div class="space-y-1.5">
                    <label for="progress_label" class="block text-xs font-bold text-primary">Label Indikator</label>
                    <input type="text" id="progress_label" name="progress_label" value="{{ old('progress_label', 'Dana Terkumpul') }}" 
                           placeholder="Target Donasi, Bibit Tertanam, Adik Asuh..."
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="progress_status" class="block text-xs font-bold text-primary">Keterangan Singkat Status</label>
                    <input type="text" id="progress_status" name="progress_status" value="{{ old('progress_status', 'Sedang Berjalan') }}" 
                           placeholder="85% Terpenuhi, 120 Siswa Terbina..."
                           onchange="this.dataset.touched = 'true'"
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <!-- Teks Capaian Output -->
            <div class="space-y-1.5">
                <label for="achievement_text" class="block text-xs font-bold text-primary">Teks Capaian / Output Nyata (Otomatis dibuat dari nominal)</label>
                <input type="text" id="achievement_text" name="achievement_text" value="{{ old('achievement_text') }}" 
                       placeholder="Contoh: Terkumpul Rp 85.000.000 dari target Rp 100.000.000"
                       onchange="this.dataset.touched = 'true'"
                       class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
            </div>

            <!-- Live Progress Bar Preview -->
            <div class="p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">visibility</span>
                        <span>Pratinjau Bilah Progres (Live Preview)</span>
                    </span>
                    <span id="preview-progress-text" class="font-extrabold text-secondary">0%</span>
                </div>
                <div class="w-full h-2.5 bg-surface-container rounded-full overflow-hidden">
                    <div id="preview-progress-bar" class="h-full bg-secondary rounded-full transition-all duration-300" style="width: 0%;"></div>
                </div>
            </div>
        </div>

        <!-- Jadwal & Foto Dokumentasi -->
        <div class="space-y-4">
            <h2 class="text-sm font-bold text-primary uppercase tracking-wider pb-2 border-b border-outline-variant/30 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[18px]">calendar_month</span>
                <span>3. Periode &amp; Foto Dokumentasi</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label for="start_date" class="block text-xs font-bold text-primary">Tanggal Mulai Pelaksanaan</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>

                <div class="space-y-1.5">
                    <label for="end_date" class="block text-xs font-bold text-primary">Target Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" 
                           class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary">
                </div>
            </div>

            <div class="space-y-1.5">
                <label for="image" class="block text-xs font-bold text-primary">Unggah Foto Poster / Dokumentasi Kegiatan (Maks 2MB)</label>
                <input type="file" id="image" name="image" accept="image/*" 
                       class="w-full px-4 py-2 rounded-xl border border-outline-variant/60 bg-surface-container-lowest text-xs sm:text-sm file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary file:text-white hover:file:bg-primary-container">
            </div>
        </div>

        <!-- Action Submit Buttons -->
        <div class="pt-4 border-t border-outline-variant/30 flex items-center justify-end gap-3">
            <a href="{{ route('admin.programs.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-sm flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan &amp; Publikasikan Program</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function selectProgramIcon(icon, bgClass, label, btnEl) {
    document.getElementById('icon').value = icon;
    if (bgClass) {
        document.getElementById('icon_bg_class').value = bgClass;
    }
    
    const previewEl = document.getElementById('current-icon-preview');
    const nameEl = document.getElementById('current-icon-name');
    if (previewEl) previewEl.innerText = icon;
    if (nameEl) nameEl.innerText = label;

    document.querySelectorAll('.icon-preset-btn').forEach(btn => {
        btn.classList.remove('border-secondary', 'ring-2', 'ring-secondary/30', 'bg-secondary/5');
        btn.classList.add('border-outline-variant/50', 'bg-surface-container-lowest');
    });

    if (btnEl) {
        btnEl.classList.remove('border-outline-variant/50', 'bg-surface-container-lowest');
        btnEl.classList.add('border-secondary', 'ring-2', 'ring-secondary/30', 'bg-secondary/5');
    }
}

function handleCustomIconInput(val) {
    val = val.trim().toLowerCase();
    if (!val) return;
    
    document.getElementById('icon').value = val;
    const previewEl = document.getElementById('current-icon-preview');
    const nameEl = document.getElementById('current-icon-name');
    const customPreview = document.getElementById('custom-icon-preview');
    
    if (previewEl) previewEl.innerText = val;
    if (nameEl) nameEl.innerText = 'Kustom: ' + val;
    if (customPreview) customPreview.innerText = val;

    document.querySelectorAll('.icon-preset-btn').forEach(btn => {
        btn.classList.remove('border-secondary', 'ring-2', 'ring-secondary/30', 'bg-secondary/5');
        btn.classList.add('border-outline-variant/50', 'bg-surface-container-lowest');
    });
}

function autoCalculateProgress() {
    const targetInput = document.getElementById('target_amount');
    const collectedInput = document.getElementById('collected_amount');
    const percentInput = document.getElementById('progress_percent');
    const statusInput = document.getElementById('progress_status');
    const achievementInput = document.getElementById('achievement_text');

    const target = parseFloat(targetInput.value) || 0;
    const collected = parseFloat(collectedInput.value) || 0;

    if (target > 0) {
        const percent = Math.round((collected / target) * 100);
        percentInput.value = percent;

        if (!statusInput.dataset.touched || statusInput.dataset.touched === 'false') {
            statusInput.value = percent + '% Terpenuhi';
        }

        if (!achievementInput.dataset.touched || achievementInput.dataset.touched === 'false') {
            achievementInput.value = 'Terkumpul Rp ' + collected.toLocaleString('id-ID') + ' dari target Rp ' + target.toLocaleString('id-ID');
        }
    }

    updatePreviewBar();
}

function updatePreviewBar() {
    const percentInput = document.getElementById('progress_percent');
    const previewBar = document.getElementById('preview-progress-bar');
    const previewText = document.getElementById('preview-progress-text');
    const val = parseInt(percentInput.value) || 0;

    if (previewBar) {
        previewBar.style.width = Math.min(100, Math.max(0, val)) + '%';
    }
    if (previewText) {
        previewText.innerText = val + '%';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const targetInput = document.getElementById('target_amount');
    const percentInput = document.getElementById('progress_percent');
    if (targetInput && parseFloat(targetInput.value) > 0 && (!percentInput.value || percentInput.value === '0')) {
        autoCalculateProgress();
    } else {
        updatePreviewBar();
    }
});
</script>
@endpush
