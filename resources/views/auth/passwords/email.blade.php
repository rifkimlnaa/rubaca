@extends('layouts.auth')
@section('title','Lupa kata sandi')
@section('auth')
    <h1 class="rb-h1 rb-h1-sm">Lupa kata sandi?</h1>
    <p class="rb-sub">Masukkan email yang Anda pakai saat mendaftar. Jika terdaftar, kami kirim tautan untuk membuat kata sandi baru.</p>
    <form method="POST" action="{{ route('password.email') }}" class="rb-form" x-data="{ submitting: false }" @submit="submitting = true">
        @csrf
        <label><span class="rb-field-label">Email terdaftar</span><input class="input-field" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
        <button class="rb-btn w-full" type="submit" :disabled="submitting">
            <span x-show="!submitting">Kirim tautan pemulihan</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-3 h-3 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Mengirim…</span>
        </button>
    </form>
    <p class="rb-alt">Ingat kata sandi? <a class="rb-link" href="{{ route('login') }}">Kembali ke halaman masuk</a></p>
    <p class="rb-fine">Aplikasi selalu menampilkan pesan yang sama, baik email ditemukan maupun tidak. Periksa juga folder spam jika email belum terlihat.</p>
@endsection
