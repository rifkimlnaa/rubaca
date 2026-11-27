<?php $__env->startSection('hide_footer', '1'); ?>
<?php $__env->startSection('content'); ?>
<section class="rb-page rb-auth">
    <aside class="rb-auth-aside">
        <img class="rb-auth-photo" src="<?php echo e(asset('images/ruang-baca-hero.png')); ?>" alt="" aria-hidden="true">
        <div class="rb-auth-copy">
            <p class="rb-label">Perpustakaan <?php echo e(config('library.institution')); ?></p>
            <h2 class="rb-h2">Akun anggota membuka seluruh koleksi.</h2>
            <ul class="rb-checks">
                <li>Baca e-book langsung lewat web reader</li>
                <li>Ajukan peminjaman buku fisik</li>
                <li>Simpan daftar bacaan dan halaman terakhir</li>
            </ul>
        </div>
        <div class="rb-mini" aria-hidden="true"><?php $__currentLoopData = [62,88,74,100,68,92,80]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><i class="c<?php echo e($k % 5); ?>" style="--i:<?php echo e($k); ?>;height:<?php echo e($h); ?>%"></i><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
    </aside>
    <div class="rb-auth-main"><div class="rb-auth-card"><?php echo $__env->yieldContent('auth'); ?></div></div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/layouts/auth.blade.php ENDPATH**/ ?>