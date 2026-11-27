<?php $__env->startSection('title', request('format') === 'fisik' ? 'Koleksi Buku' : 'Katalog koleksi'); ?>
<?php $__env->startSection('content'); ?>
<section class="rb-page rb-sec">
    <div class="site-wrap">
        <header class="rb-pagehead">
            <h1 class="rb-h1 rb-h1-sm"><?php echo e(request('format') === 'fisik' ? 'Koleksi buku' : 'Katalog koleksi'); ?></h1>
            <p class="rb-sub">Temukan bacaan berdasarkan judul, penulis, kategori, atau format.</p>
        </header>
        <form class="rb-toolbar" method="GET" action="<?php echo e(route('catalog.index')); ?>" role="search">
            <input class="input-field rb-grow" type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari judul, penulis, atau ISBN" aria-label="Cari koleksi">
            <select class="input-field" name="category" aria-label="Kategori"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if(request('category') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <select class="input-field" name="format" aria-label="Format"><option value="">Semua format</option><option value="fisik" <?php if(request('format') === 'fisik'): echo 'selected'; endif; ?>>Buku fisik</option><option value="ebook" <?php if(request('format') === 'ebook'): echo 'selected'; endif; ?>>Memiliki e-book</option></select>
            <button class="rb-btn" type="submit">Terapkan</button>
            <?php if(request()->hasAny(['q', 'category', 'format'])): ?><a class="rb-link" href="<?php echo e(route('catalog.index')); ?>">Atur ulang</a><?php endif; ?>
        </form>
        <div class="rb-count"><span><?php echo e($books->total()); ?> koleksi ditemukan</span><?php if($books->lastPage() > 1): ?><span>Halaman <?php echo e($books->currentPage()); ?> dari <?php echo e($books->lastPage()); ?></span><?php endif; ?></div>
        <div class="rb-grid" data-reveal-stagger>
            <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('partials.book-card', ['book' => $book, 'i' => $i], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rb-empty">Belum ada buku yang cocok. Coba kata kunci lain atau <a class="rb-link" href="<?php echo e(route('catalog.index')); ?>">atur ulang filter</a>.</div>
            <?php endif; ?>
        </div>
        <div class="mt-10"><?php echo e($books->links()); ?></div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/catalog/index.blade.php ENDPATH**/ ?>