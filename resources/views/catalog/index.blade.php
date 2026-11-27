@extends('layouts.app')
@section('title', request('format') === 'fisik' ? 'Koleksi Buku' : 'Katalog koleksi')
@section('content')
<section class="rb-page rb-sec">
    <div class="site-wrap">
        <header class="rb-pagehead">
            <h1 class="rb-h1 rb-h1-sm">{{ request('format') === 'fisik' ? 'Koleksi buku' : 'Katalog koleksi' }}</h1>
            <p class="rb-sub">Temukan bacaan berdasarkan judul, penulis, kategori, atau format.</p>
        </header>
        <form class="rb-toolbar" method="GET" action="{{ route('catalog.index') }}" role="search">
            <input class="input-field rb-grow" type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, atau ISBN" aria-label="Cari koleksi">
            <select class="input-field" name="category" aria-label="Kategori"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select>
            <select class="input-field" name="format" aria-label="Format"><option value="">Semua format</option><option value="fisik" @selected(request('format') === 'fisik')>Buku fisik</option><option value="ebook" @selected(request('format') === 'ebook')>Memiliki e-book</option></select>
            <button class="rb-btn" type="submit">Terapkan</button>
            @if(request()->hasAny(['q', 'category', 'format']))<a class="rb-link" href="{{ route('catalog.index') }}">Atur ulang</a>@endif
        </form>
        <div class="rb-count"><span>{{ $books->total() }} koleksi ditemukan</span>@if($books->lastPage() > 1)<span>Halaman {{ $books->currentPage() }} dari {{ $books->lastPage() }}</span>@endif</div>
        <div class="rb-grid" data-reveal-stagger>
            @forelse($books as $i => $book)
                @include('partials.book-card', ['book' => $book, 'i' => $i])
            @empty
                <div class="rb-empty">Belum ada buku yang cocok. Coba kata kunci lain atau <a class="rb-link" href="{{ route('catalog.index') }}">atur ulang filter</a>.</div>
            @endforelse
        </div>
        <div class="mt-10">{{ $books->links() }}</div>
    </div>
</section>
@endsection
