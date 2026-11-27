<?php $__env->startSection('title', 'Beranda'); ?>
<?php $__env->startSection('content'); ?>
<?php ($spines = $featured->concat($featuredEbooks)->unique('id')->take(9)->values()); ?>
<main id="rb-home" class="rb-page">
    <section id="beranda" class="rb-hero">
        <div class="rb-hero-photo" aria-hidden="true"><img src="<?php echo e(asset('images/ruang-baca-hero.png')); ?>" alt="" fetchpriority="high"></div>
        <div class="site-wrap rb-hero-grid">
            <div class="rb-hero-copy">
                <p class="rb-label">Perpustakaan <?php echo e(config('library.institution')); ?></p>
                <h1 class="rb-h1">Bacaan tentang pemilu dan demokrasi, di satu tempat.</h1>
                <p class="rb-lead">Telusuri koleksi buku fisik dan e-book untuk memahami, mengawasi, dan ikut menjaga demokrasi.</p>
                <form class="rb-search" method="GET" action="<?php echo e(route('catalog.index')); ?>" role="search">
                    <input type="search" name="q" placeholder="Cari judul, penulis, atau ISBN" aria-label="Cari koleksi">
                    <button type="submit">Cari</button>
                </form>
                <p class="rb-quick">Atau langsung buka <a href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>">buku fisik</a> dan <a href="<?php echo e(route('ebooks.index')); ?>">e-book</a>.</p>
            </div>
            <?php if($spines->isNotEmpty()): ?>
                <div class="rb-shelf" role="list" aria-label="Koleksi pilihan">
                    <?php $__currentLoopData = $spines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a role="listitem" class="rb-spine c<?php echo e($i % 5); ?>" style="--i:<?php echo e($i); ?>;--h:<?php echo e(74 + (($i * 7) % 5) * 6); ?>%" href="<?php echo e(route('catalog.show', $b)); ?>" title="<?php echo e($b->title); ?>"><span><?php echo e($b->title); ?></span></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="site-wrap">
            <div class="rb-stats">
                <?php $__currentLoopData = [[$stats['titles'],'Judul koleksi'],[$stats['copies'],'Eksemplar tersedia'],[$stats['members'],'Anggota terdaftar'],[$stats['ebooks'],'E-book siap baca']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div><strong data-count="<?php echo e($s[0]); ?>"><?php echo e(number_format($s[0], 0, ',', '.')); ?></strong><span><?php echo e($s[1]); ?></span></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <section id="koleksi" class="rb-sec">
        <div class="site-wrap">
            <div class="rb-head" data-reveal>
                <h2 class="rb-h2">Buku fisik pilihan</h2>
                <a class="rb-link" href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>">Lihat semua buku</a>
            </div>
            <?php if($categories->isNotEmpty()): ?>
                <div class="rb-chips" data-reveal aria-label="Telusuri per kategori">
                    <?php $__currentLoopData = $categories->sortByDesc('books_count')->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('catalog.index', ['category' => $cat->id])); ?>"><?php echo e($cat->name); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
            <div class="rb-grid" data-reveal-stagger>
                <?php $__empty_1 = true; $__currentLoopData = $featured; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php echo $__env->make('partials.book-card', ['book' => $book, 'i' => $i], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="rb-empty">Koleksi buku fisik sedang disiapkan. Silakan kembali lagi nanti.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="digital" class="rb-sec rb-dark">
        <div class="site-wrap rb-split" data-reveal>
            <div>
                <h2 class="rb-h2">E-book, langsung dari browser</h2>
                <p class="rb-lead">Masuk sebagai anggota, pilih bacaan, dan lanjutkan dari halaman terakhir yang Anda buka.</p>
                <a href="<?php echo e(route('ebooks.index')); ?>" class="rb-btn rb-btn-light">Buka koleksi e-book</a>
            </div>
            <div class="rb-rows" data-reveal-stagger>
                <?php $__empty_1 = true; $__currentLoopData = $featuredEbooks->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('ebooks.index', ['q' => $b->title])); ?>"><span><b><?php echo e($b->title); ?></b><small><?php echo e($b->author_name ?: ($b->author?->name ?? 'Penulis tidak dicantumkan')); ?></small></span><em>Baca</em></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="rb-empty">Koleksi e-book akan hadir di sini.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="rb-sec">
        <div class="site-wrap">
            <h2 class="rb-h2" data-reveal>Cara memakai perpustakaan</h2>
            <ol class="rb-steps" data-reveal-stagger>
                <li><span>1</span><h3>Cari di katalog</h3><p>Telusuri judul, penulis, atau kategori. Katalog terbuka untuk semua pengunjung.</p></li>
                <li><span>2</span><h3>Daftar sebagai anggota</h3><p>Akun anggota diperlukan untuk meminjam buku dan membuka e-book.</p></li>
                <li><span>3</span><h3>Pinjam atau baca</h3><p>Pinjam buku fisik, atau baca e-book di web reader dengan posisi halaman tersimpan.</p></li>
            </ol>
        </div>
    </section>

    <section id="tentang" class="rb-sec rb-about">
        <div class="site-wrap rb-split" data-reveal>
            <img src="<?php echo e(asset('images/ruang-baca-hero.png')); ?>" alt="Suasana ruang baca" loading="lazy">
            <div>
                <h2 class="rb-h2">Tentang RUBACA</h2>
                <p class="rb-lead">RUBACA adalah layanan perpustakaan <?php echo e(config('library.institution')); ?> yang mendukung pembelajaran dan penelitian lewat koleksi fisik maupun digital.</p>
                <p class="rb-lead">Kami menyediakan akses informasi yang tertata, mudah dijangkau, dan bertanggung jawab.</p>
                <a href="<?php echo e(route('catalog.index')); ?>" class="rb-btn">Mulai menjelajah</a>
            </div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/welcome.blade.php ENDPATH**/ ?>