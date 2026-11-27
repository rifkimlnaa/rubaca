@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@if($isBackoffice)
<div class="tb-dashboard-page">
            <div class="tb-page-heading">
                <div><div class="tb-eyebrow">RINGKASAN OPERASIONAL</div><h1>Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}!</h1><p>Berikut ringkasan aktivitas dan kondisi layanan perpustakaan.</p></div>
                <div class="flex flex-wrap gap-2"><a href="{{ route('books.create') }}" class="tb-primary-button"><span>＋</span> Buku fisik</a><a href="{{ route('admin.ebooks.create') }}" class="tb-secondary-button">＋ E-book</a></div>
            </div>

            <section class="tb-stat-grid" aria-label="Statistik perpustakaan">
                @php($statStyles = [
                    ['icon' => '▤', 'tone' => 'blue', 'note' => 'Koleksi terdata'],
                    ['icon' => '▣', 'tone' => 'green', 'note' => 'Stok siap dipinjam'],
                    ['icon' => '♙', 'tone' => 'orange', 'note' => 'Akun anggota aktif'],
                    ['icon' => '⇄', 'tone' => 'purple', 'note' => 'Transaksi berlangsung'],
                ])
                @foreach($stats as $label => $value)
                    @php($style = $statStyles[$loop->index % count($statStyles)])
                    <article class="tb-stat-card">
                        <div class="tb-stat-top"><span>{{ $label }}</span><span class="tb-stat-icon tone-{{ $style['tone'] }}">{{ $style['icon'] }}</span></div>
                        <div class="tb-stat-value">{{ number_format($value, 0, ',', '.') }}</div>
                        <div class="tb-stat-note">{{ $style['note'] }}</div>
                    </article>
                @endforeach
            </section>

            <section class="tb-dashboard-grid">
                <article class="tb-card tb-chart-card">
                    <div class="tb-card-header"><div><h2>Aktivitas peminjaman</h2><p>Permintaan peminjaman dalam enam bulan terakhir</p></div><span class="tb-card-chip">6 bulan</span></div>
                    @php($monthMax = max(1, (int) $monthlyLoans->max('count')))
                    <div class="tb-chart-area" role="img" aria-label="Grafik batang jumlah permintaan peminjaman enam bulan terakhir">
                        <div class="tb-chart-gridlines"><span></span><span></span><span></span><span></span></div>
                        <div class="tb-chart-bars">
                            @foreach($monthlyLoans as $month)
                                <div class="tb-chart-column"><div class="tb-chart-bar-wrap"><div class="tb-chart-bar" style="height: {{ max(5, (int) round(($month['count'] / $monthMax) * 100)) }}%" title="{{ $month['count'] }} permintaan"></div></div><span class="tb-chart-label">{{ $month['label'] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tb-chart-foot"><span><i class="tb-chart-legend"></i> Permintaan peminjaman</span><strong>{{ number_format($monthlyLoans->sum('count'), 0, ',', '.') }} transaksi</strong></div>
                </article>

                <article class="tb-card tb-quick-card">
                    <div class="tb-card-header"><div><h2>Perlu perhatian</h2><p>Ringkasan antrean layanan</p></div><span class="tb-header-icon">◷</span></div>
                    <a href="{{ route('loans.staff', ['status' => 'requested']) }}" class="tb-quick-row"><span class="tb-quick-icon quick-orange">↗</span><span><strong>Permintaan baru</strong><small>Menunggu persetujuan</small></span><b>{{ number_format($dashboardCounts['pending'] ?? 0, 0, ',', '.') }}</b></a>
                    <a href="{{ route('loans.staff') }}" class="tb-quick-row"><span class="tb-quick-icon quick-red">!</span><span><strong>Pinjaman terlambat</strong><small>Perlu tindak lanjut</small></span><b>{{ number_format($dashboardCounts['overdue'] ?? 0, 0, ',', '.') }}</b></a>
                    <a href="{{ route('loans.staff') }}" class="tb-quick-row"><span class="tb-quick-icon quick-blue">↻</span><span><strong>Perpanjangan</strong><small>Menunggu pemeriksaan</small></span><b>{{ number_format($dashboardCounts['renewals'] ?? 0, 0, ',', '.') }}</b></a>
                    <div class="tb-quick-footer"><span class="tb-online-dot"></span>{{ number_format($dashboardCounts['notifications_unread'] ?? 0, 0, ',', '.') }} notifikasi belum dibaca</div>
                </article>
            </section>

            <section class="tb-dashboard-grid tb-dashboard-grid-bottom">
                <article class="tb-card tb-activity-card">
                    <div class="tb-card-header"><div><h2>Peminjaman terbaru</h2><p>Aktivitas transaksi yang baru tercatat</p></div><a href="{{ route('loans.staff') }}" class="tb-text-link">Lihat semua <span>→</span></a></div>
                    <div class="tb-table-wrap">
                        <table class="tb-table">
                            <thead><tr><th>ANGGOTA</th><th>JUDUL BUKU</th><th>TANGGAL</th><th>STATUS</th></tr></thead>
                            <tbody>
                                @forelse($loans as $loan)
                                    @php($statusClass = $loan->status === 'borrowed' ? 'green' : ($loan->status === 'requested' ? 'orange' : ($loan->status === 'rejected' ? 'red' : 'gray')))
                                    @php($statusLabels = ['requested' => 'Menunggu', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'rejected' => 'Ditolak'])
                                    <tr>
                                        <td><div class="tb-member-cell"><span class="tb-mini-avatar">{{ strtoupper(substr($loan->user?->name ?? 'A', 0, 1)) }}</span><span><strong>{{ $loan->user?->name ?? 'Anggota' }}</strong><small>{{ $loan->user?->email ?? '—' }}</small></span></div></td>
                                        <td class="tb-book-cell">{{ $loan->book?->title ?? 'Buku dihapus' }}</td>
                                        <td class="tb-date-cell">{{ $loan->requested_at?->format('d M Y') ?? '—' }}</td>
                                        <td><span class="tb-badge badge-{{ $statusClass }}">{{ $statusLabels[$loan->status] ?? ucfirst($loan->status) }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="tb-empty">Belum ada aktivitas peminjaman.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="tb-card tb-category-card">
                    <div class="tb-card-header"><div><h2>Kategori koleksi</h2><p>Jumlah judul per kategori</p></div>@if(auth()->user()->isAdmin())<a href="{{ route('admin.master.index', 'categories') }}" class="tb-text-link">Kelola <span>→</span></a>@endif</div>
                    @php($categoryMax = max(1, (int) $categoryStats->max('books_count')))
                    <div class="tb-category-list">
                        @forelse($categoryStats as $category)
                            <div class="tb-category-item"><div class="tb-category-label"><span>{{ $category->name }}</span><strong>{{ number_format($category->books_count, 0, ',', '.') }}</strong></div><div class="tb-progress-track"><span style="width: {{ (int) round(($category->books_count / $categoryMax) * 100) }}%"></span></div></div>
                        @empty
                            <div class="tb-empty tb-empty-box">Data kategori belum tersedia.</div>
                        @endforelse
                    </div>
                </article>
            </section>

            <section class="tb-card tb-notification-card">
                <div class="tb-card-header"><div><h2>Notifikasi terbaru</h2><p>Pembaruan untuk akun operasional ini</p></div><form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button class="tb-text-link" type="submit">Tandai dibaca <span>→</span></button></form></div>
                <div class="tb-notification-list">
                    @forelse($notifications->take(4) as $notification)
                        <div class="tb-notification-item"><span class="tb-notification-dot {{ $notification->read_at ? 'is-read' : '' }}"></span><div><strong>{{ $notification->data['title'] ?? 'Pembaruan' }}</strong><p>{{ $notification->data['message'] ?? '' }}</p><small>{{ $notification->created_at->diffForHumans() }}</small></div></div>
                    @empty
                        <div class="tb-empty">Belum ada notifikasi baru.</div>
                    @endforelse
                </div>
            </section>

            @if(auth()->user()->isAdmin())
                <section id="aturan" class="tb-card tb-settings-card">
                    <div class="tb-card-header"><div><h2>Aturan perpustakaan</h2><p>Pengaturan kebijakan peminjaman dan denda</p></div><span class="tb-header-icon">⚙</span></div>
                    <form method="POST" action="{{ route('admin.settings.update') }}" class="tb-settings-form">@csrf
                        <label>Lama pinjam (hari)<input class="input-field" type="number" min="1" max="90" name="loan_days" value="{{ $settings['loan_days'] }}" required></label>
                        <label>Perpanjangan (hari)<input class="input-field" type="number" min="1" max="60" name="renewal_days" value="{{ $settings['renewal_days'] }}" required></label>
                        <label>Denda per hari (Rp)<input class="input-field" type="number" min="0" max="100000" name="fine_per_day" value="{{ $settings['fine_per_day'] }}" required></label>
                        <label>Maks. pinjaman aktif<input class="input-field" type="number" min="1" max="20" name="max_active_loans" value="{{ $settings['max_active_loans'] }}" required></label>
                        <div class="tb-settings-submit"><button class="tb-primary-button" type="submit">Simpan aturan</button></div>
                    </form>
                </section>
            @endif

</div>
@else
<section class="site-wrap py-12">
    <div class="eyebrow">{{ auth()->user()->role }} / Ringkasan</div>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-3 mb-9"><h1 class="font-editorial text-5xl">Halo, {{ explode(' ',auth()->user()->name)[0] }}.</h1><span class="text-xs text-[var(--muted)]">{{ now()->translatedFormat('l, d F Y') }}</span></div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-12">@foreach($stats as $label=>$value)<div class="panel p-5"><div class="eyebrow">{{ $label }}</div><div class="font-editorial text-4xl mt-4">{{ number_format($value,0,',','.') }}</div></div>@endforeach</div>
    <div class="flex items-end justify-between mb-4"><h2 class="font-editorial text-3xl">Aktivitas terbaru</h2><a class="text-xs text-[var(--accent)]" href="{{ route('loans.mine') }}">Lihat semua ↗</a></div>
    <div class="panel divide-y divide-stone-100">@forelse($loans as $loan)<div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3"><div><div class="font-semibold text-sm">{{ $loan->book?->title }}</div><div class="text-xs text-[var(--muted)] mt-1">{{ $loan->requested_at?->format('d M Y') }}</div></div><span class="status-pill {{ $loan->status==='borrowed'?'success':($loan->status==='requested'?'warning':'') }}">{{ strtoupper($loan->status) }}</span></div>@empty<div class="p-10 text-center text-sm text-[var(--muted)]">Belum ada aktivitas peminjaman.</div>@endforelse</div>
    <div class="mt-10"><div class="flex items-end justify-between mb-4"><h2 class="font-editorial text-3xl">Notifikasi.</h2><form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button class="text-xs text-[var(--accent)]">Tandai semua dibaca ↗</button></form></div><div class="panel divide-y divide-stone-100">@forelse($notifications as $notification)<div class="p-4 flex items-start gap-3"><span class="mt-1 w-2 h-2 rounded-full {{ $notification->read_at?'bg-stone-300':'bg-[var(--accent)]' }}"></span><div><div class="font-semibold text-sm">{{ $notification->data['title'] ?? 'Pembaruan' }}</div><p class="text-xs text-[var(--muted)] mt-1">{{ $notification->data['message'] ?? '' }}</p><span class="text-[10px] text-stone-400">{{ $notification->created_at->diffForHumans() }}</span></div></div>@empty<div class="p-6 text-xs text-[var(--muted)]">Belum ada notifikasi.</div>@endforelse</div></div>
    <div class="grid sm:grid-cols-2 gap-3 mt-8"><a class="panel p-5" href="{{ route('catalog.index') }}"><div class="eyebrow">Koleksi</div><div class="font-editorial text-2xl mt-2">Jelajahi buku ↗</div></a><a class="panel p-5" href="{{ route('reading-list.index') }}"><div class="eyebrow">Bacaan</div><div class="font-editorial text-2xl mt-2">Daftar bacaan ↗</div></a></div>
</section>
@endif
@endsection
