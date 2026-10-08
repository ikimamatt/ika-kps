@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-on-surface-variant">
                <a href="{{ route('admin.contacts.index') }}" class="hover:text-primary transition-colors">Kotak Masuk</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary font-bold">Detail Pesan</span>
            </nav>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Baca Surat &amp; Pesan Masuk</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.contacts.index') }}" 
               class="px-4 py-2 rounded-xl bg-surface-container-low text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Inbox</span>
            </a>
        </div>
    </div>

    <!-- Message Content Card -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-3xl p-6 sm:p-8 md:p-10 shadow-xs space-y-8">
        
        <!-- Message Subject & Status -->
        <div class="space-y-3 pb-6 border-b border-outline-variant/30">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full text-2xs font-extrabold uppercase bg-secondary/10 text-secondary border border-secondary/20">
                    Pesan Kontak
                </span>
                <span class="px-3 py-1 rounded-full text-2xs font-bold bg-emerald-500/10 text-emerald-700 border border-emerald-500/20 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">done_all</span>
                    <span>Telah Dibaca ({{ $contact->read_at ? $contact->read_at->diffForHumans() : 'Baru saja' }})</span>
                </span>
            </div>

            <h2 class="text-xl sm:text-2xl font-extrabold text-primary tracking-tight">
                {{ $contact->subject }}
            </h2>
        </div>

        <!-- Sender Meta Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-surface-container-low border border-outline-variant/30 text-xs">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-secondary text-on-secondary font-extrabold text-base flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="font-extrabold text-primary text-sm">{{ $contact->name }}</h3>
                    <p class="text-on-surface-variant flex items-center gap-1 mt-0.5">
                        <span class="material-symbols-outlined text-[14px]">mail</span>
                        <span>{{ $contact->email }}</span>
                    </p>
                    @if ($contact->phone)
                        <p class="text-on-surface-variant flex items-center gap-1 mt-0.5">
                            <span class="material-symbols-outlined text-[14px]">call</span>
                            <span>{{ $contact->phone }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <div class="text-right sm:self-center text-2xs text-on-surface-variant">
                <span class="block font-bold text-primary">{{ $contact->created_at->isoFormat('dddd, D MMMM Y') }}</span>
                <span class="block">{{ $contact->created_at->format('H:i') }} WITA ({{ $contact->created_at->diffForHumans() }})</span>
            </div>
        </div>

        <!-- Body Message Content -->
        <div class="space-y-3">
            <h4 class="text-xs font-bold uppercase tracking-wider text-secondary flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">notes</span>
                <span>Isi Pesan:</span>
            </h4>
            <div class="p-6 rounded-2xl bg-surface-container-low/40 border border-outline-variant/30 text-sm text-on-surface leading-relaxed whitespace-pre-line">
                {{ $contact->message }}
            </div>
        </div>

        <!-- Reply & Management Action Toolbar -->
        <div class="pt-6 border-t border-outline-variant/30 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2 flex-wrap">
                <!-- Reply via Email -->
                @php
                    $mailSubject = urlencode("Re: {$contact->subject}");
                    $mailBody = urlencode("\n\n---\nPada {$contact->created_at->isoFormat('D MMMM Y')}, {$contact->name} menulis:\n{$contact->message}");
                @endphp
                <a href="mailto:{{ $contact->email }}?subject={{ $mailSubject }}&body={{ $mailBody }}" 
                   class="px-5 py-2.5 rounded-xl bg-primary text-on-primary font-bold text-xs hover:bg-primary-container transition-all shadow-xs flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">reply</span>
                    <span>Balas via Email</span>
                </a>

                <!-- Reply via WhatsApp -->
                @if ($contact->phone)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $contact->phone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $waText = urlencode("Halo {$contact->name}, menanggapi pesan Anda ke Sekretariat IKA KPS mengenai '{$contact->subject}'...");
                    @endphp
                    <a href="https://api.whatsapp.com/send?phone={{ $cleanPhone }}&text={{ $waText }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-colors shadow-xs flex items-center justify-center gap-2">
                        <x-icons.whatsapp class="w-4 h-4 fill-current shrink-0" />
                        <span>Balas via WhatsApp</span>
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-2 justify-end">
                <!-- Toggle Unread Form -->
                <form method="POST" action="{{ route('admin.contacts.read', $contact->id) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl border border-outline-variant/60 hover:bg-surface-container text-xs font-bold text-on-surface transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">mark_email_unread</span>
                        <span>Tandai Belum Dibaca</span>
                    </button>
                </form>

                <!-- Delete Message Form -->
                <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50 text-xs font-bold transition-colors flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                        <span>Hapus</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
