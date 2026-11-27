<?php $__env->startSection('title','Daftar anggota'); ?>
<?php $__env->startSection('auth'); ?>
    <h1 class="rb-h1 rb-h1-sm">Buat akun anggota</h1>
    <p class="rb-sub">Setelah mendaftar, Anda kembali ke koleksi yang tadi dipilih.</p>
    <form method="POST" action="<?php echo e(route('register.store')); ?>" class="rb-form" x-data="passwordRegistration" @submit="if (!isValid) { $event.preventDefault(); } else { submitting = true; }">
        <?php echo csrf_field(); ?>
        <div class="rb-form-2">
            <label><span class="rb-field-label">Nama lengkap</span><input class="input-field" name="name" value="<?php echo e(old('name')); ?>" autocomplete="name" required></label>
            <label><span class="rb-field-label">Telepon (opsional)</span><input class="input-field" name="phone" value="<?php echo e(old('phone')); ?>" autocomplete="tel"></label>
        </div>
        <label><span class="rb-field-label">Email</span><input class="input-field" name="email" type="email" value="<?php echo e(old('email')); ?>" autocomplete="email" required></label>
        <div>
            <label for="register-password" class="rb-field-label">Kata sandi</label>
            <div class="relative">
                <input id="register-password" class="input-field pr-24" name="password" type="password" x-model="password" :type="showPassword ? 'text' : 'password'" autocomplete="new-password" minlength="8" required aria-describedby="password-rules">
                <button type="button" class="password-toggle" @click="showPassword = !showPassword" :aria-pressed="showPassword" :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'" x-text="showPassword ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
            </div>
            <ul id="password-rules" class="rb-rules" aria-live="polite">
                <li class="password-rule" :class="hasMinLength ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasMinLength ? '✓' : '○'"></span> Minimal 8 karakter</li>
                <li class="password-rule" :class="hasUppercase ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasUppercase ? '✓' : '○'"></span> Satu huruf besar (A–Z)</li>
                <li class="password-rule" :class="hasNumber ? 'is-valid' : ''"><span class="password-rule-icon" x-text="hasNumber ? '✓' : '○'"></span> Satu angka (0–9)</li>
            </ul>
        </div>
        <div>
            <label for="register-password-confirmation" class="rb-field-label">Konfirmasi kata sandi</label>
            <div class="relative">
                <input id="register-password-confirmation" class="input-field pr-24" name="password_confirmation" type="password" x-model="passwordConfirmation" :type="showConfirmation ? 'text' : 'password'" autocomplete="new-password" required>
                <button type="button" class="password-toggle" @click="showConfirmation = !showConfirmation" :aria-pressed="showConfirmation" :aria-label="showConfirmation ? 'Sembunyikan konfirmasi kata sandi' : 'Tampilkan konfirmasi kata sandi'" x-text="showConfirmation ? 'Sembunyikan' : 'Tampilkan'">Tampilkan</button>
            </div>
            <p class="rb-fine" x-show="passwordConfirmation.length > 0 && passwordsMatch" x-cloak style="color:#2b6a3b">✓ Kata sandi cocok.</p>
            <p class="rb-fine" x-show="passwordConfirmation.length > 0 && !passwordsMatch" x-cloak style="color:#a44738">Kata sandi belum cocok.</p>
        </div>
        <button class="rb-btn w-full disabled:opacity-50 disabled:cursor-not-allowed" type="submit" :disabled="!isValid || submitting">
            <span x-show="!submitting">Buat akun</span>
            <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><span class="inline-block w-3 h-3 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Memproses…</span>
        </button>
    </form>
    <p class="rb-alt">Sudah punya akun? <a class="rb-link" href="<?php echo e(route('login')); ?>">Masuk di sini</a></p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/auth/register.blade.php ENDPATH**/ ?>