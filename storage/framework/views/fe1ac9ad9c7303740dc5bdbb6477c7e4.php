<?php $__env->startSection('title',$book->title); ?>
<?php $__env->startSection('content'); ?>
<section class="site-wrap py-12">
    <a href="<?php echo e(route('catalog.index')); ?>" class="text-xs text-[var(--muted)]">← Kembali ke katalog</a>
    <div class="grid md:grid-cols-12 gap-10 mt-8">
        <div class="md:col-span-4"><div class="book-cover max-w-sm"><div class="book-cover-label text-3xl"><?php echo e($book->title); ?></div></div></div>
        <div class="md:col-span-8 md:pt-3">
            <div class="eyebrow"><?php echo e($book->category?->name); ?></div>
            <h1 class="font-editorial text-5xl md:text-6xl mt-4 leading-tight"><?php echo e($book->title); ?></h1>
            <p class="text-sm text-[var(--muted)] mt-4"><?php echo e($book->author_name ?: ($book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan'))); ?> · <?php echo e($book->published_year ?? 'Tahun tidak diketahui'); ?></p>
            <div class="section-rule my-7"></div>
            <p class="text-sm leading-7 max-w-2xl"><?php echo e($book->description ?: 'Belum ada deskripsi untuk koleksi ini.'); ?></p>
            <div class="grid sm:grid-cols-3 gap-5 mt-8 text-xs">
                <div><div class="eyebrow">Ketersediaan fisik</div><strong class="block mt-2 text-base"><?php echo e($book->available_count); ?> eksemplar</strong></div>
                <div><div class="eyebrow">Penerbit</div><strong class="block mt-2 text-base"><?php echo e($book->publisher_name ?: ($book->publisher?->name ?? '—')); ?></strong></div>
                <div><div class="eyebrow">Lokasi rak</div><strong class="block mt-2 text-base"><?php echo e($book->shelf?->name ?? '—'); ?></strong></div>
            </div>
            <div class="flex flex-wrap gap-3 mt-9">
                <?php if($book->ebook_path): ?>
                    <?php if(auth()->guard()->guest()): ?><a href="<?php echo e(route('ebooks.access',$book)); ?>" class="btn-primary">Daftar / masuk untuk membaca ↗</a><?php else: ?> <?php if(auth()->user()->isMember()): ?><a href="<?php echo e(route('ebooks.access',$book)); ?>" class="btn-primary">Baca e-book ↗</a><?php else: ?><span class="btn-outline opacity-70">E-book khusus anggota</span><?php endif; ?> <?php endif; ?>
                <?php endif; ?>
                <?php if(auth()->guard()->check()): ?>
                    <?php if(auth()->user()->isMember()): ?>
                        <?php if($book->available_count): ?><span class="btn-outline opacity-60">Stok fisik habis</span><?php else: ?><form method="POST" action="<?php echo e(route('loans.request',$book)); ?>"><?php echo csrf_field(); ?><button class="btn-outline">Ajukan peminjaman</button></form><?php endif; ?>
                        <form method="POST" action="<?php echo e(route('reading-list.toggle',$book)); ?>"><?php echo csrf_field(); ?><button class="btn-outline">Simpan bacaan</button></form>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if($book->available_count): ?><a href="<?php echo e(route('login')); ?>" class="btn-outline">Masuk / daftar untuk meminjam</a><?php endif; ?>
                <?php endif; ?>
            </div>
            <?php if($book->ebook_path): ?>
                <p class="text-[11px] text-[var(--muted)] mt-5 max-w-xl leading-5">E-book hanya dapat dibaca oleh anggota yang sudah masuk. Setelah masuk, Anda akan diarahkan kembali ke pembaca digital buku ini.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/catalog/show.blade.php ENDPATH**/ ?>