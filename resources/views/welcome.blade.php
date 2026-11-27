@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
@php($spines = $featured->concat($featuredEbooks)->unique('id')->take(9)->values())
<main id="rb-home" class="rb-page">
    <section id="beranda" class="rb-hero">
        <div class="rb-hero-photo" aria-hidden="true"><img src="{{ asset('images/ruang-baca-hero.png') }}" alt="" fetchpriority="high"></div>
        <div class="site-wrap rb-hero-grid">
            <div class="rb-hero-copy">
                <p class="rb-label">Perpustakaan {{ config('library.institution') }}</p>
                <h1 class="rb-h1">Bacaan tentang pemilu dan demokrasi, di satu tempat.</h1>
                <p class="rb-lead">Telusuri koleksi buku fisik dan e-book untuk memahami, mengawasi, dan ikut menjaga demokrasi.</p>
                <form class="rb-search" method="GET" action="{{ route('catalog.index') }}" role="search">
                    <input type="search" name="q" placeholder="Cari judul, penulis, atau ISBN" aria-label="Cari koleksi">
                    <button type="submit">Cari</button>
                </form>
                <p class="rb-quick">Atau langsung buka <a href="{{ route('catalog.index', ['format' => 'fisik']) }}">buku fisik</a> dan <a href="{{ route('ebooks.index') }}">e-book</a>.</p>
            </div>
            @if($spines->isNotEmpty())
                <div class="rb-shelf" role="list" aria-label="Koleksi pilihan">
                    @foreach($spines as $i => $b)
                        <a role="listitem" class="rb-spine c{{ $i % 5 }}" style="--i:{{ $i }};--h:{{ 74 + (($i * 7) % 5) * 6 }}%" href="{{ route('catalog.show', $b) }}" title="{{ $b->title }}"><span>{{ $b->title }}</span></a>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="site-wrap">
            <div class="rb-stats">
                @foreach([[$stats['titles'],'Judul koleksi'],[$stats['copies'],'Eksemplar tersedia'],[$stats['members'],'Anggota terdaftar'],[$stats['ebooks'],'E-book siap baca']] as $s)
                    <div><strong data-count="{{ $s[0] }}">{{ number_format($s[0], 0, ',', '.') }}</strong><span>{{ $s[1] }}</span></div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="koleksi" class="rb-sec">
        <div class="site-wrap">
            <div class="rb-head" data-reveal>
                <h2 class="rb-h2">Buku fisik pilihan</h2>
                <a class="rb-link" href="{{ route('catalog.index', ['format' => 'fisik']) }}">Lihat semua buku</a>
            </div>
            @if($categories->isNotEmpty())
                <div class="rb-chips" data-reveal aria-label="Telusuri per kategori">
                    @foreach($categories->sortByDesc('books_count')->take(6) as $cat)
                        <a href="{{ route('catalog.index', ['category' => $cat->id]) }}">{{ $cat->name }}</a>
                    @endforeach
                </div>
            @endif
            <div class="rb-grid" data-reveal-stagger>
                @forelse($featured as $i => $book)
                    @include('partials.book-card', ['book' => $book, 'i' => $i])
                @empty
                    <p class="rb-empty">Koleksi buku fisik sedang disiapkan. Silakan kembali lagi nanti.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="digital" class="rb-sec rb-dark">
        <div class="site-wrap rb-split" data-reveal>
            <div>
                <h2 class="rb-h2">E-book, langsung dari browser</h2>
                <p class="rb-lead">Masuk sebagai anggota, pilih bacaan, dan lanjutkan dari halaman terakhir yang Anda buka.</p>
                <a href="{{ route('ebooks.index') }}" class="rb-btn rb-btn-light">Buka koleksi e-book</a>
            </div>
            <div class="rb-rows" data-reveal-stagger>
                @forelse($featuredEbooks->take(3) as $b)
                    <a href="{{ route('ebooks.index', ['q' => $b->title]) }}"><span><b>{{ $b->title }}</b><small>{{ $b->author_name ?: ($b->author?->name ?? 'Penulis tidak dicantumkan') }}</small></span><em>Baca</em></a>
                @empty
                    <p class="rb-empty">Koleksi e-book akan hadir di sini.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="rb-sec">
        <div class="site-wrap">
            <h2 class="rb-h2" data-reveal>Cara memakai perpustakaan</h2>
            <ol class="rb-steps" data-reveal-stagger>
                <li><span>1</span><h3>Cari di katalog</h3><p>Telusuri judul, penulis, atau kategori. Katalog terbuka untuk semua pengunjung.</p></li>
                <li><span>2</span><h3>Daftar sebagai anggota</h3><p>Akun anggota diperlukan untuk meminjam buku dan membuka e-book.</p></li>
                <li><span>3</span><h3>Pinjam atau baca</h3><p>Pinjam buku fisik, atau baca e-book di web reader dengan posisi halaman tersimpan.</p></li>
            </ol>
        </div>
    </section>

    <section id="tentang" class="rb-sec rb-about">
        <div class="site-wrap rb-split" data-reveal>
            <img src="{{ asset('images/ruang-baca-hero.png') }}" alt="Suasana ruang baca" loading="lazy">
            <div>
                <h2 class="rb-h2">Tentang RUBACA</h2>
                <p class="rb-lead">RUBACA adalah layanan perpustakaan {{ config('library.institution') }} yang mendukung pembelajaran dan penelitian lewat koleksi fisik maupun digital.</p>
                <p class="rb-lead">Kami menyediakan akses informasi yang tertata, mudah dijangkau, dan bertanggung jawab.</p>
                <a href="{{ route('catalog.index') }}" class="rb-btn">Mulai menjelajah</a>
            </div>
        </div>
    </section>
</main>
@endsection
