@extends('layouts.app')
@section('title', 'Koleksi E-book')
@section('content')
<section class="rb-page rb-sec">
    <div class="site-wrap">
        <header class="rb-pagehead">
            <h1 class="rb-h1 rb-h1-sm">Koleksi e-book</h1>
            <p class="rb-sub">Baca bahan pustaka digital langsung di browser. Anda perlu masuk sebagai anggota untuk membuka e-book.</p>
        </header>
        <form class="rb-toolbar" method="GET" action="{{ route('ebooks.index') }}" role="search">
            <input class="input-field rb-grow" type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, atau topik" aria-label="Cari e-book">
            <select class="input-field" name="category" aria-label="Kategori"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select>
            <button class="rb-btn" type="submit">Cari e-book</button>
            @if(request()->hasAny(['q', 'category']))<a class="rb-link" href="{{ route('ebooks.index') }}">Atur ulang</a>@endif
        </form>
        <div class="rb-count"><span>{{ number_format($ebooks->total(), 0, ',', '.') }} e-book ditemukan</span>@if($ebooks->lastPage() > 1)<span>Halaman {{ $ebooks->currentPage() }} dari {{ $ebooks->lastPage() }}</span>@endif</div>

        @if($ebooks->isNotEmpty())
            <div class="rb-egrid" data-reveal-stagger>
                @foreach($ebooks as $i => $book)
                    <article class="rb-ecard">
                        <div class="rb-cover c{{ $i % 5 }}" aria-hidden="true"><b>{{ $book->title }}</b></div>
                        <div class="rb-ecard-body">
                            @if($book->category)<p class="rb-card-cat">{{ $book->category->name }}</p>@endif
                            <h2>{{ $book->title }}</h2>
                            <p class="rb-card-author">{{ $book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan') }}</p>
                            @if($book->description)<p class="rb-desc">{{ $book->description }}</p>@endif
                            @guest
                                <a href="{{ route('ebooks.access', $book) }}" class="rb-btn rb-btn-sm">Masuk untuk membaca</a>
                            @else
                                @if(auth()->user()->isMember())<a href="{{ route('ebooks.access', $book) }}" class="rb-btn rb-btn-sm">Baca e-book</a>@else<span class="rb-card-cat">Akses khusus anggota</span>@endif
                            @endguest
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $ebooks->links() }}</div>
        @else
            <div class="rb-empty">
                <h2 class="rb-h2">E-book belum tersedia</h2>
                <p>Koleksi muncul setelah petugas mengunggah berkas PDF. Sementara itu, Anda dapat menjelajahi buku fisik.</p>
                <a href="{{ route('catalog.index', ['format' => 'fisik']) }}" class="rb-btn">Lihat buku fisik</a>
            </div>
        @endif

        <div class="rb-note">
            <div><h2 class="rb-h3">Satu akun untuk melanjutkan bacaan</h2><p>Pengunjung bisa melihat katalog. Untuk membuka web reader dan menyimpan halaman terakhir, daftar atau masuk sebagai anggota.</p></div>
            <div class="rb-note-actions">@guest<a href="{{ route('register') }}" class="rb-btn">Daftar anggota</a><a href="{{ route('login') }}" class="rb-btn rb-btn-ghost">Masuk</a>@else<a href="{{ route('dashboard') }}" class="rb-btn rb-btn-ghost">Buka dashboard</a>@endguest</div>
        </div>
    </div>
</section>
@endsection
