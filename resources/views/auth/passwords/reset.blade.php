@extends('layouts.app')
@section('title','Atur ulang kata sandi')
@section('content')
<section class="site-wrap py-16 max-w-xl">
    <div class="eyebrow">Keamanan akun</div>
    <h1 class="font-editorial text-5xl mt-3">Buat kata sandi baru.</h1>
    <p class="text-sm text-[var(--muted)] leading-6 mt-4">Gunakan kata sandi baru yang belum pernah digunakan pada akun Ruang Baca Anda.</p>

    <form method="POST" action="{{ route('password.update') }}" class="panel p-6 mt-8 space-y-5" x-data="passwordRegistration" @submit="if (!isValid) { $event.preventDefault(); } else { submitting = true; }">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <label class="block">
            <span class="eyebrow block mb-2">Email akun</span>
            <input class="input-field" name="email" type="email" value="{{ old('email', $email) }}" autocomplete="email" required>
        </label>

        <div>
            <label for="reset-password" class="eyebrow block mb-2">Kata sandi baru</label>
            <div class="relative">
                <input id="reset-password" class="input-field pr-24" name="password" type="password" x-model="password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" minlength="8" required aria-describedby="password-rules">
                <button type="button" class="password-toggle" @click="showPassword = !showPassword" :aria-pressed="showPassword" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" x-text="showPassword ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
            </div>
            <div id="password-rules" class="mt-3 rounded-sm bg-[#f7f5f0] p-3" aria-live="polite">
                <p class="text-xs font-semibold mb-2">Kata sandi harus memenuhi:</p>
                <ul class="space-y-2 text-xs">
                    <li class="password-rule" :class="hasMinLength ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasMinLength ? '✓' : '○'"></span> Minimal 8 karakter</li>
                    <li class="password-rule" :class="hasUppercase ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasUppercase ? '✓' : '○'"></span> Memiliki minimal 1 huruf besar (A–Z)</li>
                    <li class="password-rule" :class="hasNumber ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasNumber ? '✓' : '○'"></span> Memiliki minimal 1 angka (0–9)</li>
                </ul>
                <p class="text-[11px] mt-3 text-[var(--muted)]" x-show="password.length === 0">Status persyaratan akan diperbarui saat Anda mengetik.</p>
                <p class="text-[11px] mt-3 text-[#3d6944]" x-show="isPasswordValid" x-cloak>Semua persyaratan kata sandi sudah terpenuhi.</p>
            </div>
        </div>

        <div>
            <label for="reset-password-confirmation" class="eyebrow block mb-2">Konfirmasi kata sandi baru</label>
            <div class="relative">
                <input id="reset-password-confirmation" class="input-field pr-24" name="password_confirmation" type="password" x-model="passwordConfirmation" :type="showConfirmation ? 'text' : 'password'" autocomplete="new-password" required>
                <button type="button" class="password-toggle" @click="showConfirmation = !showConfirmation" :aria-pressed="showConfirmation" :aria-label="showConfirmation ? 'Sembunyikan konfirmasi kata sandi' : 'Tampilkan konfirmasi kata sandi'" x-text="showConfirmation ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
            </div>
            <p class="text-xs mt-2" x-show="passwordConfirmation.length > 0 && passwordsMatch" x-cloak style="color:#3d6944">✓ Kata sandi cocok.</p>
            <p class="text-xs mt-2" x-show="passwordConfirmation.length > 0 && !passwordsMatch" x-cloak style="color:#a44738">Kata sandi belum cocok.</p>
        </div>

        <button class="btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed" type="submit" :disabled="!isValid || submitting">
            <span x-show="!submitting">Simpan kata sandi baru ↗</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-3 h-3 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Memperbarui…</span>
        </button>
    </form>
</section>
@endsection
