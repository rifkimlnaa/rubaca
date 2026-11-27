<?php $__env->startSection('title', 'Koleksi E-book'); ?>
<?php $__env->startSection('content'); ?>
<section class="rb-page rb-sec">
    <div class="site-wrap">
        <header class="rb-pagehead">
            <h1 class="rb-h1 rb-h1-sm">Koleksi e-book</h1>
            <p class="rb-sub">Baca bahan pustaka digital langsung di browser. Anda perlu masuk sebagai anggota untuk membuka e-book.</p>
        </header>
        <form class="rb-toolbar" method="GET" action="<?php echo e(route('ebooks.index')); ?>" role="search">
            <input class="input-field rb-grow" type="search" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari judul, penulis, atau topik" aria-label="Cari e-book">
            <select class="input-field" name="category" aria-label="Kategori"><option value="">Semua kategori</option><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><option value="<?php echo e($category->id); ?>" <?php if(request('category') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></select>
            <button class="rb-btn" type="submit">Cari e-book</button>
            <?php if(request()->hasAny(['q', 'category'])): ?><a class="rb-link" href="<?php echo e(route('ebooks.index')); ?>">Atur ulang</a><?php endif; ?>
        </form>
        <div class="rb-count"><span><?php echo e(number_format($ebooks->total(), 0, ',', '.')); ?> e-book ditemukan</span><?php if($ebooks->lastPage() > 1): ?><span>Halaman <?php echo e($ebooks->currentPage()); ?> dari <?php echo e($ebooks->lastPage()); ?></span><?php endif; ?></div>

        <?php if($ebooks->isNotEmpty()): ?>
            <div class="rb-egrid" data-reveal-stagger>
                <?php $__currentLoopData = $ebooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="rb-ecard">
                        <div class="rb-cover c<?php echo e($i % 5); ?>" aria-hidden="true"><b><?php echo e($book->title); ?></b></div>
                        <div class="rb-ecard-body">
                            <?php if($book->category): ?><p class="rb-card-cat"><?php echo e($book->category->name); ?></p><?php endif; ?>
                            <h2><?php echo e($book->title); ?></h2>
                            <p class="rb-card-author"><?php echo e($book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan')); ?></p>
                            <?php if($book->description): ?><p class="rb-desc"><?php echo e($book->description); ?></p><?php endif; ?>
                            <?php if(auth()->guard()->guest()): ?>
                                <a href="<?php echo e(route('ebooks.access', $book)); ?>" class="rb-btn rb-btn-sm">Masuk untuk membaca</a>
                            <?php else: ?>
                                <?php if(auth()->user()->isMember()): ?><a href="<?php echo e(route('ebooks.access', $book)); ?>" class="rb-btn rb-btn-sm">Baca e-book</a><?php else: ?><span class="rb-card-cat">Akses khusus anggota</span><?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="mt-10"><?php echo e($ebooks->links()); ?></div>
        <?php else: ?>
            <div class="rb-empty">
                <h2 class="rb-h2">E-book belum tersedia</h2>
                <p>Koleksi muncul setelah petugas mengunggah berkas PDF. Sementara itu, Anda dapat menjelajahi buku fisik.</p>
                <a href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>" class="rb-btn">Lihat buku fisik</a>
            </div>
        <?php endif; ?>

        <div class="rb-note">
            <div><h2 class="rb-h3">Satu akun untuk melanjutkan bacaan</h2><p>Pengunjung bisa melihat katalog. Untuk membuka web reader dan menyimpan halaman terakhir, daftar atau masuk sebagai anggota.</p></div>
            <div class="rb-note-actions"><?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('register')); ?>" class="rb-btn">Daftar anggota</a><a href="<?php echo e(route('login')); ?>" class="rb-btn rb-btn-ghost">Masuk</a><?php else: ?><a href="<?php echo e(route('dashboard')); ?>" class="rb-btn rb-btn-ghost">Buka dashboard</a><?php endif; ?></div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/ebooks/index.blade.php ENDPATH**/ ?>