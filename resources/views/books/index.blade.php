@extends('layouts.app')
@section('title', 'Kelola buku')
@section('content')
<section class="tb-module-page">
    <div class="tb-page-heading">
        <div><div class="tb-eyebrow">INVENTARIS KOLEKSI</div><h1>Kelola buku</h1><p>Kelola judul, ketersediaan eksemplar, dan format koleksi perpustakaan.</p></div>
        <a href="{{ route('books.create') }}" class="tb-primary-button"><span>＋</span> Tambah buku</a>
    </div>

    <div class="tb-card">
        <div class="tb-card-header"><div><h2>Daftar koleksi</h2><p>Informasi seluruh judul buku yang tercatat</p></div><span class="tb-card-chip">{{ $books->total() }} judul</span></div>
        <div class="tb-table-wrap">
            <table class="tb-table">
                <thead><tr><th>KODE</th><th>JUDUL & PENULIS</th><th>KATEGORI</th><th>STOK TERSEDIA</th><th>FORMAT</th><th>AKSI</th></tr></thead>
                <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>{{ $book->book_code }}</td><td><div class="tb-book-title">{{ $book->title }}</div><div class="tb-table-subtext">{{ $book->author_name ?: ($book->author?->name ?? 'Penulis belum diisi') }}</div></td>
                        <td>{{ $book->category?->name ?? '—' }}</td>
                        <td><span class="tb-inline-count">{{ number_format($book->available_count, 0, ',', '.') }}</span></td>
                        <td><span class="tb-badge {{ $book->ebook_path ? 'badge-blue' : 'badge-gray' }}">{{ $book->ebook_path ? 'Cetak + e-book' : 'Fisik' }}</span></td>
                        <td><div class="tb-row-actions">
                            <form method="POST" action="{{ route('books.copies.store', $book) }}" class="tb-stock-form">@csrf<input aria-label="Jumlah eksemplar tambahan" class="input-field" type="number" min="1" max="100" name="copies" value="1"><button type="submit" class="tb-action-link">+ Stok</button></form>
                            <a class="tb-action-link" href="{{ route('books.edit', $book) }}">Edit</a>
                            <form method="POST" action="{{ route('books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">@csrf @method('DELETE')<button class="tb-action-link is-danger" type="submit">Hapus</button></form>
                        </div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="tb-empty">Belum ada buku. Tambahkan koleksi pertama untuk memulai inventaris.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="tb-card-pagination">{{ $books->links() }}</div>
    </div>
</section>
@endsection
