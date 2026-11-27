@extends('layouts.app')
@section('title', $book->exists ? 'Edit buku fisik' : 'Tambah buku fisik')
@section('content')
<section class="tb-module-page">
 <div class="tb-page-heading"><div><div class="tb-eyebrow">INVENTARIS BUKU FISIK</div><h1>{{ $book->exists ? 'Perbarui buku fisik' : 'Tambah buku fisik' }}</h1><p>Input penulis dan penerbit langsung. Kategori dipilih dari data master.</p></div><a href="{{ route('books.index') }}" class="tb-secondary-button">← Kembali ke buku</a></div>
 <form method="POST" enctype="multipart/form-data" action="{{ $book->exists ? route('books.update', $book) : route('books.store') }}" class="tb-card tb-form-card">
 @csrf @if($book->exists)@method('PUT')@endif
 <div class="tb-card-header"><div><h2>Data bibliografi buku</h2><p>Kode buku dibuat otomatis oleh sistem.</p></div><span class="tb-card-chip">{{ $book->exists ? 'Edit data' : 'Buku baru' }}</span></div>
 <div class="tb-form-grid">
  @if($book->exists)<label class="tb-form-field"><span>Kode buku (otomatis)</span><input class="input-field" value="{{ $book->book_code }}" readonly></label>@endif
  <label class="tb-form-field tb-form-span-2"><span>Judul buku <b>*</b></span><input class="input-field" name="title" value="{{ old('title',$book->title) }}" required></label>
  <label class="tb-form-field"><span>Kategori <b>*</b></span><select class="input-field" name="category_id" required><option value="">Pilih kategori</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" @selected(old('category_id',$book->category_id)==$cat->id)>{{ $cat->name }}</option>@endforeach</select></label>
  <label class="tb-form-field"><span>Penulis</span><input class="input-field" name="author_name" value="{{ old('author_name',$book->author_name ?? $book->author?->name) }}" placeholder="Nama penulis"></label>
  <label class="tb-form-field"><span>Penerbit</span><input class="input-field" name="publisher_name" value="{{ old('publisher_name',$book->publisher_name ?? $book->publisher?->name) }}" placeholder="Nama penerbit"></label>
  <label class="tb-form-field"><span>Rak / lokasi simpan</span><input class="input-field" name="shelf_label" value="{{ old('shelf_label',$book->shelf_label ?? $book->shelf?->name) }}" placeholder="Contoh: Rak A-03"></label>
  <label class="tb-form-field"><span>ISBN</span><input class="input-field" name="isbn" value="{{ old('isbn',$book->isbn) }}" placeholder="Nomor ISBN"></label>
  <label class="tb-form-field"><span>Tahun terbit</span><input class="input-field" type="number" name="published_year" value="{{ old('published_year',$book->published_year) }}" placeholder="Contoh: 2025"></label>
  <label class="tb-form-field tb-form-span-2"><span>Deskripsi</span><textarea class="input-field" rows="4" name="description">{{ old('description',$book->description) }}</textarea></label>
  @if(!$book->exists)<label class="tb-form-field"><span>Jumlah eksemplar fisik awal</span><input class="input-field" type="number" min="1" max="500" name="copies" value="{{ old('copies',1) }}" required></label>@endif
  <label class="tb-form-field"><span>Sampul buku (opsional)</span><input class="input-field tb-file-input" type="file" accept="image/*" name="cover"></label>
  <label class="tb-checkbox-field tb-form-span-2"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured',$book->is_featured))><span>Tampilkan sebagai koleksi pilihan</span></label>
 </div><div class="tb-form-actions"><a href="{{ route('books.index') }}" class="tb-secondary-button">Batal</a><button class="tb-primary-button" type="submit">Simpan buku <span>→</span></button></div>
 </form></section>
@endsection
