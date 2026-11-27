@extends('layouts.app')
@section('title', 'Kategori Buku')
@section('content')
<section class="tb-module-page">
    <div class="tb-page-heading">
        <div>
            <div class="tb-eyebrow">ADMINISTRASI KATALOG</div>
            <h1>Data Master · Kategori Buku</h1>
            <p>Kelola kategori yang menjadi pilihan pada formulir Buku Fisik dan E-book.</p>
        </div>
    </div>

    <div class="tb-card tb-form-card mb-5">
        <div class="tb-card-header">
            <div><h2>Tambah kategori buku</h2><p>Kategori yang dibuat akan langsung tersedia pada formulir koleksi.</p></div>
        </div>
        <form method="POST" action="{{ route('admin.master.store', 'categories') }}" class="tb-master-form">
            @csrf
            <label class="tb-form-field"><span>Nama kategori <b>*</b></span><input class="input-field" name="name" placeholder="Masukkan nama kategori" value="{{ old('name') }}" required maxlength="150"></label>
            <label class="tb-form-field"><span>Deskripsi (opsional)</span><input class="input-field" name="description" placeholder="Keterangan kategori" value="{{ old('description') }}" maxlength="1000"></label>
            <div class="tb-master-submit"><button class="tb-primary-button" type="submit"><span>＋</span> Tambah kategori</button></div>
        </form>
    </div>


    <div class="tb-card">
        <div class="tb-card-header">
            <div><h2>Daftar kategori buku</h2><p>Kategori yang sudah tersimpan</p></div>
            <span class="tb-card-chip">{{ $items->total() }} kategori</span>
        </div>
        <div class="tb-table-wrap"><table class="tb-table"><thead><tr><th>NAMA KATEGORI</th><th>DESKRIPSI</th><th class="tb-align-right">TINDAKAN</th></tr></thead><tbody>
            @forelse($items as $item)
                <tr><td><span class="tb-book-title">{{ $item->name }}</span></td><td>{{ $item->description ?: '—' }}</td><td class="tb-align-right"><form method="POST" action="{{ route('admin.master.destroy', ['categories', $item->id]) }}" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="tb-action-link is-danger" type="submit">Hapus</button></form></td></tr>
            @empty
                <tr><td colspan="3" class="tb-empty">Belum ada kategori. Tambahkan kategori pertama.</td></tr>
            @endforelse
        </tbody></table></div>
        <div class="tb-card-pagination">{{ $items->links() }}</div>
    </div>
</section>
@endsection
