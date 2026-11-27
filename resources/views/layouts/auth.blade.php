@extends('layouts.app')
@section('hide_footer', '1')
@section('content')
<section class="rb-page rb-auth">
    <aside class="rb-auth-aside">
        <img class="rb-auth-photo" src="{{ asset('images/ruang-baca-hero.png') }}" alt="" aria-hidden="true">
        <div class="rb-auth-copy">
            <p class="rb-label">Perpustakaan {{ config('library.institution') }}</p>
            <h2 class="rb-h2">Akun anggota membuka seluruh koleksi.</h2>
            <ul class="rb-checks">
                <li>Baca e-book langsung lewat web reader</li>
                <li>Ajukan peminjaman buku fisik</li>
                <li>Simpan daftar bacaan dan halaman terakhir</li>
            </ul>
        </div>
        <div class="rb-mini" aria-hidden="true">@foreach([62,88,74,100,68,92,80] as $k => $h)<i class="c{{ $k % 5 }}" style="--i:{{ $k }};height:{{ $h }}%"></i>@endforeach</div>
    </aside>
    <div class="rb-auth-main"><div class="rb-auth-card">@yield('auth')</div></div>
</section>
@endsection
