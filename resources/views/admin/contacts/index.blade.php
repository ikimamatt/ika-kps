@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-primary tracking-tight">Kotak Masuk Pesan &amp; Kontak</h1>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola pesan, pertanyaan, aspirasi program, dan usulan kerjasama yang dikirimkan oleh alumni maupun publik.
            </p>
        </div>
        <div>
            <a href="{{ route('contact.show') }}" target="_blank"
               class="px-3.5 py-2 rounded-xl bg-surface-container-low border border-outline-variant/40 text-primary font-bold text-xs hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                <span>Buka Formulir Kontak Publik</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-on-surface-variant font-medium block">Total Pesan Masuk</span>
            <span class="text-2xl font-extrabold text-primary block mt-1">{{ $stats['total'] }}</span>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-amber-700 font-bold block flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Belum Dibaca</span>
            </span>
            <span class="text-2xl font-extrabold text-amber-600 block mt-1">{{ $stats['unread'] }}</span>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
            <span class="text-xs text-emerald-700 font-medium block">Sudah Dibaca</span>
            <span class="text-2xl font-extrabold text-emerald-600 block mt-1">{{ $stats['read'] }}</span>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-4 shadow-xs">
        <form action="{{ route('admin.contacts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-secondary/50">
                    <option value="">Semua Status Pesan</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Hanya Belum Dibaca ({{ $stats['unread'] }})</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>

            <div class="relative w-full sm:w-80">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari pengirim, email, atau isi pesan..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-outline-variant/50 bg-surface-container-lowest text-xs focus:outline-none focus:ring-2 focus:ring-secondary/50">
                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
            </div>
        </form>
    </div>

    <!-- Inbox Table -->
    <div class="bg-surface-container-lowest border border-outline-variant/40 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/30 text-primary font-bold">
                        <th class="py-3.5 px-4 w-10 text-center">Status</th>
                        <th class="py-3.5 px-4">Pengirim &amp; Kontak</th>
                        <th class="py-3.5 px-4">Perihal &amp; Ringkasan</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Tanggal Masuk</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20">
                    @forelse ($contacts as $contact)
                        <tr class="hover:bg-surface-container-low/40 transition-colors {{ ! $contact->is_read ? 'bg-secondary/5 font-semibold' : '' }}">
                            <!-- Status Indicator -->
                            <td class="py-3 px-4 text-center">
                                @if (! $contact->is_read)
                                    <span class="w-2.5 h-2.5 rounded-full bg-secondary inline-block" title="Belum Dibaca"></span>
                                @else
                                    <span class="w-2.5 h-2.5 rounded-full bg-outline-variant inline-block" title="Sudah Dibaca"></span>
                                @endif
                            </td>

                            <!-- Sender -->
                            <td class="py-3 px-4 max-w-xs">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="font-bold text-primary hover:text-secondary block truncate">
                                    {{ $contact->name }}
                                </a>
                                <div class="text-2xs text-on-surface-variant flex items-center gap-2 mt-0.5">
                                    <span class="truncate">{{ $contact->email }}</span>
                                    @if ($contact->phone)
                                        <span>&bull; {{ $contact->phone }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Subject & Snippet -->
                            <td class="py-3 px-4 max-w-md">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                   class="block text-primary hover:text-secondary line-clamp-1 {{ ! $contact->is_read ? 'font-bold' : 'font-medium' }}">
                                    {{ $contact->subject }}
                                </a>
                                <p class="text-2xs text-on-surface-variant line-clamp-1 mt-0.5 font-normal">
                                    {{ $contact->message }}
                                </p>
                            </td>

                            <!-- Date -->
                            <td class="py-3 px-4 whitespace-nowrap text-on-surface-variant text-2xs">
                                <div>{{ $contact->created_at->diffForHumans() }}</div>
                                <div class="text-[11px] opacity-75">{{ $contact->created_at->isoFormat('D MMM Y, H:i') }}</div>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                       title="Buka &amp; Baca Pesan"
                                       class="p-1.5 rounded-lg text-on-surface-variant hover:text-primary hover:bg-surface-container transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">drafts</span>
                                    </a>

                                    <form method="POST" action="{{ route('admin.contacts.read', $contact->id) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                title="{{ $contact->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}"
                                                class="p-1.5 rounded-lg text-on-surface-variant hover:text-secondary hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">
                                                {{ $contact->is_read ? 'mark_email_unread' : 'mark_email_read' }}
                                            </span>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus Pesan"
                                                class="p-1.5 rounded-lg text-on-surface-variant hover:text-rose-600 hover:bg-surface-container transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-outline-variant">inbox</span>
                                    <p class="font-medium text-xs">Kotak masuk pesan kosong.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($contacts->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
