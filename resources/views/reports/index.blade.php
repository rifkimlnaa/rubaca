@extends('layouts.app')
@section('title', 'Laporan sirkulasi')
@section('content')
<section class="tb-module-page">
    <div class="tb-page-heading"><div><div class="tb-eyebrow">ANALITIK PERPUSTAKAAN</div><h1>Laporan sirkulasi</h1><p>Pantau ringkasan aktivitas peminjaman berdasarkan rentang tanggal.</p></div></div>
    <form class="tb-filter-bar" method="GET"><label class="tb-form-field"><span>Dari tanggal</span><input class="input-field" type="date" name="from" value="{{ $from->format('Y-m-d') }}"></label><label class="tb-form-field"><span>Sampai tanggal</span><input class="input-field" type="date" name="to" value="{{ $to->format('Y-m-d') }}"></label><button class="tb-primary-button" type="submit">Tampilkan laporan</button></form>
    <div class="tb-stat-grid tb-report-stat-grid">@foreach($summary as $label => $value)<article class="tb-stat-card"><div class="tb-stat-top"><span>{{ $label }}</span><span class="tb-stat-icon tone-blue">▥</span></div><div class="tb-stat-value">{{ str_contains(strtolower($label), 'denda') ? 'Rp '.number_format($value,0,',','.') : number_format($value,0,',','.') }}</div><div class="tb-stat-note">Dalam rentang tanggal terpilih</div></article>@endforeach</div>
    <div class="tb-report-grid">
        <div class="tb-card"><div class="tb-card-header"><div><h2>Buku paling sering diminta</h2><p>Judul dengan transaksi terbanyak</p></div></div><div class="tb-simple-list">@forelse($popular as $row)<div class="tb-simple-list-row"><span>{{ $row->book?->title ?? 'Buku dihapus' }}</span><strong>{{ number_format($row->total,0,',','.') }}×</strong></div>@empty<div class="tb-empty">Belum ada data pada periode ini.</div>@endforelse</div></div>
        <div class="tb-card"><div class="tb-card-header"><div><h2>Transaksi terbaru</h2><p>Aktivitas peminjaman terakhir dalam hasil laporan</p></div></div><div class="tb-simple-list">@forelse($loans->take(8) as $loan)<div class="tb-simple-list-row"><span><strong>{{ $loan->book?->title ?? 'Buku dihapus' }}</strong><small>{{ $loan->user?->name }} · {{ $loan->requested_at?->format('d M Y') }}</small></span><span class="tb-badge badge-{{ $loan->status === 'returned' ? 'green' : ($loan->status === 'rejected' ? 'red' : 'blue') }}">{{ ucfirst($loan->status) }}</span></div>@empty<div class="tb-empty">Belum ada transaksi terbaru.</div>@endforelse</div></div>
    </div>
</section>
@endsection
