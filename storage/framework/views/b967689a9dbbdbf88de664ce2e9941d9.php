<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<?php if($isBackoffice): ?>
<div class="tb-dashboard-page">
            <div class="tb-page-heading">
                <div><div class="tb-eyebrow">RINGKASAN OPERASIONAL</div><h1>Selamat datang, <?php echo e(explode(' ', auth()->user()->name)[0]); ?>!</h1><p>Berikut ringkasan aktivitas dan kondisi layanan perpustakaan.</p></div>
                <div class="flex flex-wrap gap-2"><a href="<?php echo e(route('books.create')); ?>" class="tb-primary-button"><span>＋</span> Buku fisik</a><a href="<?php echo e(route('admin.ebooks.create')); ?>" class="tb-secondary-button">＋ E-book</a></div>
            </div>

            <section class="tb-stat-grid" aria-label="Statistik perpustakaan">
                <?php ($statStyles = [
                    ['icon' => '▤', 'tone' => 'blue', 'note' => 'Koleksi terdata'],
                    ['icon' => '▣', 'tone' => 'green', 'note' => 'Stok siap dipinjam'],
                    ['icon' => '♙', 'tone' => 'orange', 'note' => 'Akun anggota aktif'],
                    ['icon' => '⇄', 'tone' => 'purple', 'note' => 'Transaksi berlangsung'],
                ]); ?>
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($style = $statStyles[$loop->index % count($statStyles)]); ?>
                    <article class="tb-stat-card">
                        <div class="tb-stat-top"><span><?php echo e($label); ?></span><span class="tb-stat-icon tone-<?php echo e($style['tone']); ?>"><?php echo e($style['icon']); ?></span></div>
                        <div class="tb-stat-value"><?php echo e(number_format($value, 0, ',', '.')); ?></div>
                        <div class="tb-stat-note"><?php echo e($style['note']); ?></div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>

            <section class="tb-dashboard-grid">
                <article class="tb-card tb-chart-card">
                    <div class="tb-card-header"><div><h2>Aktivitas peminjaman</h2><p>Permintaan peminjaman dalam enam bulan terakhir</p></div><span class="tb-card-chip">6 bulan</span></div>
                    <?php ($monthMax = max(1, (int) $monthlyLoans->max('count'))); ?>
                    <div class="tb-chart-area" role="img" aria-label="Grafik batang jumlah permintaan peminjaman enam bulan terakhir">
                        <div class="tb-chart-gridlines"><span></span><span></span><span></span><span></span></div>
                        <div class="tb-chart-bars">
                            <?php $__currentLoopData = $monthlyLoans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="tb-chart-column"><div class="tb-chart-bar-wrap"><div class="tb-chart-bar" style="height: <?php echo e(max(5, (int) round(($month['count'] / $monthMax) * 100))); ?>%" title="<?php echo e($month['count']); ?> permintaan"></div></div><span class="tb-chart-label"><?php echo e($month['label']); ?></span></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="tb-chart-foot"><span><i class="tb-chart-legend"></i> Permintaan peminjaman</span><strong><?php echo e(number_format($monthlyLoans->sum('count'), 0, ',', '.')); ?> transaksi</strong></div>
                </article>

                <article class="tb-card tb-quick-card">
                    <div class="tb-card-header"><div><h2>Perlu perhatian</h2><p>Ringkasan antrean layanan</p></div><span class="tb-header-icon">◷</span></div>
                    <a href="<?php echo e(route('loans.staff', ['status' => 'requested'])); ?>" class="tb-quick-row"><span class="tb-quick-icon quick-orange">↗</span><span><strong>Permintaan baru</strong><small>Menunggu persetujuan</small></span><b><?php echo e(number_format($dashboardCounts['pending'] ?? 0, 0, ',', '.')); ?></b></a>
                    <a href="<?php echo e(route('loans.staff')); ?>" class="tb-quick-row"><span class="tb-quick-icon quick-red">!</span><span><strong>Pinjaman terlambat</strong><small>Perlu tindak lanjut</small></span><b><?php echo e(number_format($dashboardCounts['overdue'] ?? 0, 0, ',', '.')); ?></b></a>
                    <a href="<?php echo e(route('loans.staff')); ?>" class="tb-quick-row"><span class="tb-quick-icon quick-blue">↻</span><span><strong>Perpanjangan</strong><small>Menunggu pemeriksaan</small></span><b><?php echo e(number_format($dashboardCounts['renewals'] ?? 0, 0, ',', '.')); ?></b></a>
                    <div class="tb-quick-footer"><span class="tb-online-dot"></span><?php echo e(number_format($dashboardCounts['notifications_unread'] ?? 0, 0, ',', '.')); ?> notifikasi belum dibaca</div>
                </article>
            </section>

            <section class="tb-dashboard-grid tb-dashboard-grid-bottom">
                <article class="tb-card tb-activity-card">
                    <div class="tb-card-header"><div><h2>Peminjaman terbaru</h2><p>Aktivitas transaksi yang baru tercatat</p></div><a href="<?php echo e(route('loans.staff')); ?>" class="tb-text-link">Lihat semua <span>→</span></a></div>
                    <div class="tb-table-wrap">
                        <table class="tb-table">
                            <thead><tr><th>ANGGOTA</th><th>JUDUL BUKU</th><th>TANGGAL</th><th>STATUS</th></tr></thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php ($statusClass = $loan->status === 'borrowed' ? 'green' : ($loan->status === 'requested' ? 'orange' : ($loan->status === 'rejected' ? 'red' : 'gray'))); ?>
                                    <?php ($statusLabels = ['requested' => 'Menunggu', 'borrowed' => 'Dipinjam', 'returned' => 'Dikembalikan', 'rejected' => 'Ditolak']); ?>
                                    <tr>
                                        <td><div class="tb-member-cell"><span class="tb-mini-avatar"><?php echo e(strtoupper(substr($loan->user?->name ?? 'A', 0, 1))); ?></span><span><strong><?php echo e($loan->user?->name ?? 'Anggota'); ?></strong><small><?php echo e($loan->user?->email ?? '—'); ?></small></span></div></td>
                                        <td class="tb-book-cell"><?php echo e($loan->book?->title ?? 'Buku dihapus'); ?></td>
                                        <td class="tb-date-cell"><?php echo e($loan->requested_at?->format('d M Y') ?? '—'); ?></td>
                                        <td><span class="tb-badge badge-<?php echo e($statusClass); ?>"><?php echo e($statusLabels[$loan->status] ?? ucfirst($loan->status)); ?></span></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr><td colspan="4" class="tb-empty">Belum ada aktivitas peminjaman.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="tb-card tb-category-card">
                    <div class="tb-card-header"><div><h2>Kategori koleksi</h2><p>Jumlah judul per kategori</p></div><?php if(auth()->user()->isAdmin()): ?><a href="<?php echo e(route('admin.master.index', 'categories')); ?>" class="tb-text-link">Kelola <span>→</span></a><?php endif; ?></div>
                    <?php ($categoryMax = max(1, (int) $categoryStats->max('books_count'))); ?>
                    <div class="tb-category-list">
                        <?php $__empty_1 = true; $__currentLoopData = $categoryStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="tb-category-item"><div class="tb-category-label"><span><?php echo e($category->name); ?></span><strong><?php echo e(number_format($category->books_count, 0, ',', '.')); ?></strong></div><div class="tb-progress-track"><span style="width: <?php echo e((int) round(($category->books_count / $categoryMax) * 100)); ?>%"></span></div></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="tb-empty tb-empty-box">Data kategori belum tersedia.</div>
                        <?php endif; ?>
                    </div>
                </article>
            </section>

            <section class="tb-card tb-notification-card">
                <div class="tb-card-header"><div><h2>Notifikasi terbaru</h2><p>Pembaruan untuk akun operasional ini</p></div><form method="POST" action="<?php echo e(route('notifications.read-all')); ?>"><?php echo csrf_field(); ?><button class="tb-text-link" type="submit">Tandai dibaca <span>→</span></button></form></div>
                <div class="tb-notification-list">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="tb-notification-item"><span class="tb-notification-dot <?php echo e($notification->read_at ? 'is-read' : ''); ?>"></span><div><strong><?php echo e($notification->data['title'] ?? 'Pembaruan'); ?></strong><p><?php echo e($notification->data['message'] ?? ''); ?></p><small><?php echo e($notification->created_at->diffForHumans()); ?></small></div></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="tb-empty">Belum ada notifikasi baru.</div>
                    <?php endif; ?>
                </div>
            </section>

            <?php if(auth()->user()->isAdmin()): ?>
                <section id="aturan" class="tb-card tb-settings-card">
                    <div class="tb-card-header"><div><h2>Aturan perpustakaan</h2><p>Pengaturan kebijakan peminjaman dan denda</p></div><span class="tb-header-icon">⚙</span></div>
                    <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="tb-settings-form"><?php echo csrf_field(); ?>
                        <label>Lama pinjam (hari)<input class="input-field" type="number" min="1" max="90" name="loan_days" value="<?php echo e($settings['loan_days']); ?>" required></label>
                        <label>Perpanjangan (hari)<input class="input-field" type="number" min="1" max="60" name="renewal_days" value="<?php echo e($settings['renewal_days']); ?>" required></label>
                        <label>Denda per hari (Rp)<input class="input-field" type="number" min="0" max="100000" name="fine_per_day" value="<?php echo e($settings['fine_per_day']); ?>" required></label>
                        <label>Maks. pinjaman aktif<input class="input-field" type="number" min="1" max="20" name="max_active_loans" value="<?php echo e($settings['max_active_loans']); ?>" required></label>
                        <div class="tb-settings-submit"><button class="tb-primary-button" type="submit">Simpan aturan</button></div>
                    </form>
                </section>
            <?php endif; ?>

</div>
<?php else: ?>
<section class="site-wrap py-12">
    <div class="eyebrow"><?php echo e(auth()->user()->role); ?> / Ringkasan</div>
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mt-3 mb-9"><h1 class="font-editorial text-5xl">Halo, <?php echo e(explode(' ',auth()->user()->name)[0]); ?>.</h1><span class="text-xs text-[var(--muted)]"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span></div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-12"><?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="panel p-5"><div class="eyebrow"><?php echo e($label); ?></div><div class="font-editorial text-4xl mt-4"><?php echo e(number_format($value,0,',','.')); ?></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    <div class="flex items-end justify-between mb-4"><h2 class="font-editorial text-3xl">Aktivitas terbaru</h2><a class="text-xs text-[var(--accent)]" href="<?php echo e(route('loans.mine')); ?>">Lihat semua ↗</a></div>
    <div class="panel divide-y divide-stone-100"><?php $__empty_1 = true; $__currentLoopData = $loans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3"><div><div class="font-semibold text-sm"><?php echo e($loan->book?->title); ?></div><div class="text-xs text-[var(--muted)] mt-1"><?php echo e($loan->requested_at?->format('d M Y')); ?></div></div><span class="status-pill <?php echo e($loan->status==='borrowed'?'success':($loan->status==='requested'?'warning':'')); ?>"><?php echo e(strtoupper($loan->status)); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="p-10 text-center text-sm text-[var(--muted)]">Belum ada aktivitas peminjaman.</div><?php endif; ?></div>
    <div class="mt-10"><div class="flex items-end justify-between mb-4"><h2 class="font-editorial text-3xl">Notifikasi.</h2><form method="POST" action="<?php echo e(route('notifications.read-all')); ?>"><?php echo csrf_field(); ?><button class="text-xs text-[var(--accent)]">Tandai semua dibaca ↗</button></form></div><div class="panel divide-y divide-stone-100"><?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="p-4 flex items-start gap-3"><span class="mt-1 w-2 h-2 rounded-full <?php echo e($notification->read_at?'bg-stone-300':'bg-[var(--accent)]'); ?>"></span><div><div class="font-semibold text-sm"><?php echo e($notification->data['title'] ?? 'Pembaruan'); ?></div><p class="text-xs text-[var(--muted)] mt-1"><?php echo e($notification->data['message'] ?? ''); ?></p><span class="text-[10px] text-stone-400"><?php echo e($notification->created_at->diffForHumans()); ?></span></div></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="p-6 text-xs text-[var(--muted)]">Belum ada notifikasi.</div><?php endif; ?></div></div>
    <div class="grid sm:grid-cols-2 gap-3 mt-8"><a class="panel p-5" href="<?php echo e(route('catalog.index')); ?>"><div class="eyebrow">Koleksi</div><div class="font-editorial text-2xl mt-2">Jelajahi buku ↗</div></a><a class="panel p-5" href="<?php echo e(route('reading-list.index')); ?>"><div class="eyebrow">Bacaan</div><div class="font-editorial text-2xl mt-2">Daftar bacaan ↗</div></a></div>
</section>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/dashboard/index.blade.php ENDPATH**/ ?>