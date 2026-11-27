<?php $__env->startSection('title','Masuk'); ?>
<?php $__env->startSection('auth'); ?>
    <h1 class="rb-h1 rb-h1-sm">Masuk ke ruang baca</h1>
    <p class="rb-sub">Lanjutkan membaca dan kelola koleksi Anda.</p>
    <form method="POST" action="<?php echo e(route('login.store')); ?>" class="rb-form" x-data="passwordVisibility" @submit="submitting = true">
        <?php echo csrf_field(); ?>
        <label><span class="rb-field-label">Email</span><input class="input-field" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="username" required autofocus></label>
        <div>
            <label for="login-password" class="rb-field-label">Kata sandi</label>
            <div class="relative">
                <input id="login-password" class="input-field pr-24" name="password" type="password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" required>
                <button type="button" class="password-toggle" @click="showPassword = !showPassword" :aria-pressed="showPassword" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" x-text="showPassword ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
            </div>
        </div>
        <div class="rb-form-row">
            <label class="rb-check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <a class="rb-link" href="<?php echo e(route('password.request')); ?>">Lupa kata sandi?</a>
        </div>
        <button class="rb-btn w-full" type="submit" :disabled="submitting">
            <span x-show="!submitting">Masuk</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-3 h-3 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Memproses…</span>
        </button>
    </form>
    <p class="rb-alt">Belum punya akun? <a class="rb-link" href="<?php echo e(route('register')); ?>">Daftar sebagai anggota</a></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/auth/login.blade.php ENDPATH**/ ?>