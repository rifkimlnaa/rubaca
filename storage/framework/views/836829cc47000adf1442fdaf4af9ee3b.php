<?php $__env->startSection('title','Lupa kata sandi'); ?>
<?php $__env->startSection('content'); ?>
<section class="site-wrap py-16 max-w-xl">
    <div class="eyebrow">Pemulihan akun</div>
    <h1 class="font-editorial text-5xl mt-3">Lupa kata sandi?</h1>
    <p class="text-sm text-[var(--muted)] leading-6 mt-4">Masukkan alamat email yang digunakan saat mendaftar. Jika email terdaftar, kami akan mengirim tautan untuk membuat kata sandi baru.</p>

    <form method="POST" action="<?php echo e(route('password.email')); ?>" class="panel p-6 mt-8 space-y-5" x-data="{ submitting: false }" @submit="submitting = true">
        <?php echo csrf_field(); ?>
        <label class="block">
            <span class="eyebrow block mb-2">Email terdaftar</span>
            <input class="input-field" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" required autofocus>
        </label>
        <button class="btn-primary w-full" type="submit" :disabled="submitting">
            <span x-show="!submitting">Kirim tautan pemulihan ↗</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-3 h-3 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Mengirim…</span>
        </button>
    </form>

    <p class="text-xs text-[var(--muted)] mt-5">Ingat kata sandi Anda? <a class="text-[var(--accent)] underline underline-offset-4" href="<?php echo e(route('login')); ?>">Kembali ke halaman masuk</a></p>
    <p class="text-[11px] text-[var(--muted)] mt-6 leading-5">Untuk keamanan, aplikasi selalu menampilkan pesan yang sama, baik alamat email ditemukan maupun tidak. Periksa juga folder spam apabila email pemulihan belum terlihat.</p>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>