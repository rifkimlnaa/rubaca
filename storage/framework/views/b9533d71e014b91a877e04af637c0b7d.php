<?php $__env->startSection('title', 'Anggota & pengguna'); ?>
<?php $__env->startSection('content'); ?>
<section class="tb-module-page">
    <div class="tb-page-heading"><div><div class="tb-eyebrow">ADMINISTRASI AKUN</div><h1>Anggota & pengguna</h1><p>Daftarkan akun baru dan atur peran serta status akses pengguna.</p></div><span class="tb-card-chip"><?php echo e($users->total()); ?> akun</span></div>
    <div class="tb-card tb-form-card mb-5"><div class="tb-card-header"><div><h2>Buat akun pengguna</h2><p>Tambahkan anggota atau petugas ke sistem perpustakaan.</p></div></div>
        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="tb-user-create-form"><?php echo csrf_field(); ?>
            <label class="tb-form-field"><span>Nama lengkap</span><input class="input-field" name="name" placeholder="Nama pengguna" required></label>
            <label class="tb-form-field"><span>Alamat email</span><input class="input-field" type="email" name="email" placeholder="nama@email.id" required></label>
            <label class="tb-form-field"><span>Peran</span><select class="input-field" name="role"><option value="staff">Petugas</option><option value="member">Anggota</option><option value="admin">Admin</option></select></label>
            <label class="tb-form-field"><span>Kata sandi</span><input class="input-field" name="password" type="password" placeholder="Min. 10 karakter" required></label>
            <div class="tb-user-submit"><button class="tb-primary-button" type="submit"><span>＋</span> Buat akun</button></div>
        </form>
    </div>
    <div class="tb-card"><div class="tb-card-header"><div><h2>Daftar pengguna</h2><p>Perubahan peran dan status berlaku setelah disimpan.</p></div></div>
        <div class="tb-table-wrap"><table class="tb-table"><thead><tr><th>NAMA</th><th>EMAIL</th><th>PERAN</th><th>STATUS</th><th>AKSI</th></tr></thead><tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr><td><div class="tb-member-cell"><span class="tb-mini-avatar"><?php echo e(strtoupper(substr($user->name,0,1))); ?></span><span><strong><?php echo e($user->name); ?></strong><small>ID pengguna #<?php echo e($user->id); ?></small></span></div></td><td><?php echo e($user->email); ?></td>
                    <td><form id="u<?php echo e($user->id); ?>" method="POST" action="<?php echo e(route('admin.users.update',$user)); ?>"><?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?></form><select form="u<?php echo e($user->id); ?>" class="input-field tb-table-select" name="role"><option value="admin" <?php if($user->role==='admin'): echo 'selected'; endif; ?>>Admin</option><option value="staff" <?php if($user->role==='staff'): echo 'selected'; endif; ?>>Petugas</option><option value="member" <?php if($user->role==='member'): echo 'selected'; endif; ?>>Anggota</option></select></td>
                    <td><select form="u<?php echo e($user->id); ?>" class="input-field tb-table-select" name="is_active"><option value="1" <?php if($user->is_active): echo 'selected'; endif; ?>>Aktif</option><option value="0" <?php if(!$user->is_active): echo 'selected'; endif; ?>>Nonaktif</option></select></td><td><button form="u<?php echo e($user->id); ?>" class="tb-action-link" type="submit">Simpan perubahan</button></td></tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><tr><td colspan="5" class="tb-empty">Belum ada akun pengguna.</td></tr><?php endif; ?>
        </tbody></table></div><div class="tb-card-pagination"><?php echo e($users->links()); ?></div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/users/index.blade.php ENDPATH**/ ?>