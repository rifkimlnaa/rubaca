@extends('layouts.app')
@section('title',$book->title)
@section('content')
<section class="site-wrap py-12">
    <a href="{{ route('catalog.index') }}" class="text-xs text-[var(--muted)]">← Kembali ke katalog</a>
    <div class="grid md:grid-cols-12 gap-10 mt-8">
        <div class="md:col-span-4"><div class="book-cover max-w-sm"><div class="book-cover-label text-3xl">{{ $book->title }}</div></div></div>
        <div class="md:col-span-8 md:pt-3">
            <div class="eyebrow">{{ $book->category?->name }}</div>
            <h1 class="font-editorial text-5xl md:text-6xl mt-4 leading-tight">{{ $book->title }}</h1>
            <p class="text-sm text-[var(--muted)] mt-4">{{ $book->author_name ?: ($book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan')) }} · {{ $book->published_year ?? 'Tahun tidak diketahui' }}</p>
            <div class="section-rule my-7"></div>
            <p class="text-sm leading-7 max-w-2xl">{{ $book->description ?: 'Belum ada deskripsi untuk koleksi ini.' }}</p>
            <div class="grid sm:grid-cols-3 gap-5 mt-8 text-xs">
                <div><div class="eyebrow">Ketersediaan fisik</div><strong class="block mt-2 text-base">{{ $book->available_count }} eksemplar</strong></div>
                <div><div class="eyebrow">Penerbit</div><strong class="block mt-2 text-base">{{ $book->publisher_name ?: ($book->publisher?->name ?? '—') }}</strong></div>
                <div><div class="eyebrow">Lokasi rak</div><strong class="block mt-2 text-base">{{ $book->shelf?->name ?? '—' }}</strong></div>
            </div>
            <div class="flex flex-wrap gap-3 mt-9">
                @if($book->ebook_path)
                    @guest<a href="{{ route('ebooks.access',$book) }}" class="btn-primary">Daftar / masuk untuk membaca ↗</a>@else @if(auth()->user()->isMember())<a href="{{ route('ebooks.access',$book) }}" class="btn-primary">Baca e-book ↗</a>@else<span class="btn-outline opacity-70">E-book khusus anggota</span>@endif @endguest
                @endif
                @auth
                    @if(auth()->user()->isMember())
                        @if($book->available_count)<span class="btn-outline opacity-60">Stok fisik habis</span>@else<form method="POST" action="{{ route('loans.request',$book) }}">@csrf<button class="btn-outline">Ajukan peminjaman</button></form>@endif
                        <form method="POST" action="{{ route('reading-list.toggle',$book) }}">@csrf<button class="btn-outline">Simpan bacaan</button></form>
                    @endif
                @else
                    @if($book->available_count)<a href="{{ route('login') }}" class="btn-outline">Masuk / daftar untuk meminjam</a>@endif
                @endauth
            </div>
            @if($book->ebook_path)
                <p class="text-[11px] text-[var(--muted)] mt-5 max-w-xl leading-5">E-book hanya dapat dibaca oleh anggota yang sudah masuk. Setelah masuk, Anda akan diarahkan kembali ke pembaca digital buku ini.</p>
            @endif
        </div>
    </div>
</section>
@endsection
