<?php $__env->startSection('title', 'Kategori Buku'); ?>
<?php $__env->startSection('content'); ?>
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
        <form method="POST" action="<?php echo e(route('admin.master.store', 'categories')); ?>" class="tb-master-form">
            <?php echo csrf_field(); ?>
            <label class="tb-form-field"><span>Nama kategori <b>*</b></span><input class="input-field" name="name" placeholder="Masukkan nama kategori" value="<?php echo e(old('name')); ?>" required maxlength="150"></label>
            <label class="tb-form-field"><span>Deskripsi (opsional)</span><input class="input-field" name="description" placeholder="Keterangan kategori" value="<?php echo e(old('description')); ?>" maxlength="1000"></label>
            <div class="tb-master-submit"><button class="tb-primary-button" type="submit"><span>＋</span> Tambah kategori</button></div>
        </form>
    </div>


    <div class="tb-card">
        <div class="tb-card-header">
            <div><h2>Daftar kategori buku</h2><p>Kategori yang sudah tersimpan</p></div>
            <span class="tb-card-chip"><?php echo e($items->total()); ?> kategori</span>
        </div>
        <div class="tb-table-wrap"><table class="tb-table"><thead><tr><th>NAMA KATEGORI</th><th>DESKRIPSI</th><th class="tb-align-right">TINDAKAN</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><span class="tb-book-title"><?php echo e($item->name); ?></span></td><td><?php echo e($item->description ?: '—'); ?></td><td class="tb-align-right"><form method="POST" action="<?php echo e(route('admin.master.destroy', ['categories', $item->id])); ?>" onsubmit="return confirm('Hapus kategori ini?')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?><button class="tb-action-link is-danger" type="submit">Hapus</button></form></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="3" class="tb-empty">Belum ada kategori. Tambahkan kategori pertama.</td></tr>
            <?php endif; ?>
        </tbody></table></div>
        <div class="tb-card-pagination"><?php echo e($items->links()); ?></div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/master/index.blade.php ENDPATH**/ ?>