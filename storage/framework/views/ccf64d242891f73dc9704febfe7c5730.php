<?php $__env->startSection('title', 'Kelola buku'); ?>
<?php $__env->startSection('content'); ?>
<section class="tb-module-page">
    <div class="tb-page-heading">
        <div><div class="tb-eyebrow">INVENTARIS KOLEKSI</div><h1>Kelola buku</h1><p>Kelola judul, ketersediaan eksemplar, dan format koleksi perpustakaan.</p></div>
        <a href="<?php echo e(route('books.create')); ?>" class="tb-primary-button"><span>＋</span> Tambah buku</a>
    </div>

    <div class="tb-card">
        <div class="tb-card-header"><div><h2>Daftar koleksi</h2><p>Informasi seluruh judul buku yang tercatat</p></div><span class="tb-card-chip"><?php echo e($books->total()); ?> judul</span></div>
        <div class="tb-table-wrap">
            <table class="tb-table">
                <thead><tr><th>KODE</th><th>JUDUL & PENULIS</th><th>KATEGORI</th><th>STOK TERSEDIA</th><th>FORMAT</th><th>AKSI</th></tr></thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($book->book_code); ?></td><td><div class="tb-book-title"><?php echo e($book->title); ?></div><div class="tb-table-subtext"><?php echo e($book->author_name ?: ($book->author?->name ?? 'Penulis belum diisi')); ?></div></td>
                        <td><?php echo e($book->category?->name ?? '—'); ?></td>
                        <td><span class="tb-inline-count"><?php echo e(number_format($book->available_count, 0, ',', '.')); ?></span></td>
                        <td><span class="tb-badge <?php echo e($book->ebook_path ? 'badge-blue' : 'badge-gray'); ?>"><?php echo e($book->ebook_path ? 'Cetak + e-book' : 'Fisik'); ?></span></td>
                        <td><div class="tb-row-actions">
                            <form method="POST" action="<?php echo e(route('books.copies.store', $book)); ?>" class="tb-stock-form"><?php echo csrf_field(); ?><input aria-label="Jumlah eksemplar tambahan" class="input-field" type="number" min="1" max="100" name="copies" value="1"><button type="submit" class="tb-action-link">+ Stok</button></form>
                            <a class="tb-action-link" href="<?php echo e(route('books.edit', $book)); ?>">Edit</a>
                            <form method="POST" action="<?php echo e(route('books.destroy', $book)); ?>" onsubmit="return confirm('Hapus buku ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="tb-action-link is-danger" type="submit">Hapus</button></form>
                        </div></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="tb-empty">Belum ada buku. Tambahkan koleksi pertama untuk memulai inventaris.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="tb-card-pagination"><?php echo e($books->links()); ?></div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/books/index.blade.php ENDPATH**/ ?>