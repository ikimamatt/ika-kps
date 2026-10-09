@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-160px)] flex items-center justify-center py-12 px-6">
    <div class="w-full max-w-md bg-surface-container-lowest border border-outline-variant/40 rounded-2xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.06)] relative overflow-hidden">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/10 text-primary mb-4">
                <span class="material-symbols-outlined text-[32px]">lock</span>
            </div>
            <h1 class="font-headline-sm text-2xl font-extrabold text-primary tracking-tight">Masuk Portal IKA KPS</h1>
            <p class="font-body-md text-sm text-on-surface-variant mt-2">Masuk ke akun alumni Anda untuk mengelola profil dan berkolaborasi.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-error-container/20 border border-error/30 text-error">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-error text-[20px] shrink-0 mt-0.5">error</span>
                    <div class="text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider mb-2">Alamat Email</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">mail</span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-11 pr-4 py-3 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                           placeholder="nama@alumni.kps.id">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="block font-label-md text-xs font-bold text-on-surface uppercase tracking-wider">Kata Sandi</label>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[20px]">key</span>
                    <input type="password" id="password" name="password" required
                           class="w-full pl-11 pr-11 py-3 rounded-xl border border-outline-variant/60 bg-surface-container-low text-on-surface text-sm focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-all"
                           placeholder="••••••••">
                    <button type="button" 
                            onclick="togglePasswordVisibility('password', 'passwordToggleIcon')" 
                            aria-label="Lihat atau sembunyikan kata sandi"
                            class="absolute right-1 top-1/2 -translate-y-1/2 text-on-surface-variant/70 hover:text-primary transition-colors w-10 h-10 flex items-center justify-center rounded-lg focus:outline-none focus:ring-2 focus:ring-secondary/30 cursor-pointer">
                        <span id="passwordToggleIcon" class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-on-surface-variant">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-secondary">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" 
                    class="w-full py-3.5 rounded-xl bg-primary text-on-primary font-bold text-sm tracking-wide shadow-md hover:bg-primary-container transition-all active:scale-[0.99] flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">login</span>
                <span>Masuk Sekarang</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-outline-variant/40 text-center text-xs text-on-surface-variant">
            <span>Belum memiliki akun alumni? </span>
            <a href="{{ route('register') }}" class="font-bold text-secondary hover:underline">Daftar Akun Baru</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
        }
    }
</script>
@endpush
@endsection
