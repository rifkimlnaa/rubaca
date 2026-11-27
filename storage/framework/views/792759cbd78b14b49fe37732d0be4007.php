<?php ($isBackofficeArea = auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'], true) && request()->routeIs('dashboard', 'books.*', 'admin.ebooks.*', 'loans.staff', 'admin.*')); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="theme-color" content="#f5f4f2">
    <title><?php echo $__env->yieldContent('title', config('library.name')); ?> — <?php echo e(config('library.institution')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="<?php echo e($isBackofficeArea ? 'min-h-screen' : 'min-h-screen flex flex-col'); ?>">
<div id="navigation-progress" role="status" aria-live="polite" aria-hidden="true"><span class="sr-only">Memuat halaman…</span></div>
<?php if (! ($isBackofficeArea)): ?>
<header class="public-header border-b border-[var(--line)] bg-[var(--paper)]">
    <div class="site-wrap public-header-inner min-h-[76px] flex items-center justify-between gap-5">
        <a href="<?php echo e(route('home')); ?>#beranda" class="flex items-center gap-3 shrink-0" aria-label="<?php echo e(config('library.name')); ?> - Beranda">
            <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="h-10 w-24 object-contain object-left">
            <span class="border-l border-[var(--line)] pl-3">
                <span class="block font-semibold text-base leading-none"><?php echo e(config('library.name')); ?></span>
                <span class="block text-[9px] tracking-[.12em] uppercase text-[var(--muted)] mt-1"><?php echo e(config('library.institution')); ?></span>
            </span>
        </a>

        <nav class="public-nav hidden md:flex items-center gap-7" aria-label="Navigasi utama">
            <a class="nav-link" href="<?php echo e(route('home')); ?>#beranda">Beranda</a>
            <a class="nav-link" href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>">Buku</a>
            <a class="nav-link" href="<?php echo e(route('ebooks.index')); ?>">E-book</a>
            <a class="nav-link" href="<?php echo e(route('home')); ?>#tentang">Tentang Kami</a>
        </nav>

        <div class="flex items-center gap-3 shrink-0">
            <?php if(auth()->guard()->check()): ?>
                <div class="hidden sm:block text-right">
                    <div class="text-xs font-semibold"><?php echo e(auth()->user()->name); ?></div>
                    <div class="text-[10px] text-[var(--muted)] uppercase tracking-wider"><?php echo e(auth()->user()->role); ?></div>
                </div>
                <div class="relative" x-data="{open:false}">
                    <button type="button" @click="open=!open" aria-label="Buka menu akun" :aria-expanded="open" class="w-9 h-9 border border-[var(--line)] flex items-center justify-center text-sm"><?php echo e(strtoupper(substr(auth()->user()->name,0,1))); ?></button>
                    <div x-show="open" @click.outside="open=false" x-cloak class="absolute right-0 top-11 z-30 panel w-52 p-2 shadow-lg">
                        <a href="<?php echo e(route('dashboard')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Dashboard</a>
                        <?php if(auth()->user()->isMember()): ?>
                            <a href="<?php echo e(route('loans.mine')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Pinjaman saya</a>
                            <a href="<?php echo e(route('reading-list.index')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Daftar bacaan</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('books.index')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola buku fisik</a>
                            <a href="<?php echo e(route('admin.ebooks.index')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola e-book</a>
                            <a href="<?php echo e(route('loans.staff')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Sirkulasi</a>
                        <?php endif; ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola pengguna</a>
                            <a href="<?php echo e(route('admin.reports.index')); ?>" class="block px-3 py-2 text-xs hover:bg-stone-50">Laporan</a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('notifications.read-all')); ?>"><?php echo csrf_field(); ?><button class="block w-full text-left px-3 py-2 text-xs hover:bg-stone-50">Tandai notifikasi dibaca</button></form>
                        <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="block w-full text-left px-3 py-2 text-xs hover:bg-stone-50">Keluar</button></form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="nav-link">Masuk</a>
                <a href="<?php echo e(route('register')); ?>" class="btn-primary">Daftar anggota</a>
            <?php endif; ?>
        </div>
    </div>

    <nav class="md:hidden border-t border-[var(--line)]" aria-label="Navigasi utama seluler">
        <div class="site-wrap flex items-center gap-7 overflow-x-auto whitespace-nowrap py-3">
            <a class="nav-link" href="<?php echo e(route('home')); ?>#beranda">Beranda</a>
            <a class="nav-link" href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>">Buku</a>
            <a class="nav-link" href="<?php echo e(route('ebooks.index')); ?>">E-book</a>
            <a class="nav-link" href="<?php echo e(route('home')); ?>#tentang">Tentang Kami</a>
        </div>
    </nav>
</header>
<?php endif; ?>

<?php if($isBackofficeArea): ?>
<div class="tb-admin" x-data="{ sidebarOpen: false, profileOpen: false }">
    <div class="tb-sidebar-scrim" x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"></div>
    <aside class="tb-sidebar" :class="sidebarOpen ? 'is-open' : ''">
        <a href="<?php echo e(route('dashboard')); ?>" class="tb-brand" aria-label="Dashboard Ruang Baca Bawaslu">
            <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="tb-brand-logo">
            <span class="tb-brand-caption"><strong>RUANG BACA</strong><small><?php echo e(config('library.institution')); ?></small></span>
        </a>

        <div class="tb-sidebar-section">MENU UTAMA</div>
        <nav class="tb-nav" aria-label="Navigasi panel operasional">
            <a href="<?php echo e(route('dashboard')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('dashboard') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('dashboard')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">▦</span><span>Dashboard</span></a>
            <a href="<?php echo e(route('books.index')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('books.*') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('books.*')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">▤</span><span>Kelola Buku</span></a>
            <a href="<?php echo e(route('admin.ebooks.index')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('admin.ebooks.*') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('admin.ebooks.*')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">▣</span><span>Kelola E-book</span></a>
            <a href="<?php echo e(route('loans.staff')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('loans.staff') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('loans.staff')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">⇄</span><span>Sirkulasi</span></a>

            <?php if(auth()->user()->isAdmin()): ?>
                <div class="tb-sidebar-section tb-sidebar-section-spaced">ADMINISTRASI</div>
                <a href="<?php echo e(route('admin.users.index')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('admin.users.*')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">♙</span><span>Anggota & Pengguna</span></a>
                <div class="tb-nav-group-label">DATA MASTER</div>
                <?php $__currentLoopData = ['categories' => 'Kategori buku']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $masterType => $masterLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('admin.master.index', $masterType)); ?>" class="tb-nav-link tb-nav-sub-link <?php echo e(request()->routeIs('admin.master.*') && request()->route('type') === $masterType ? 'is-active' : ''); ?>" <?php if(request()->routeIs('admin.master.*') && request()->route('type') === $masterType): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-sub-dot"></span><span><?php echo e($masterLabel); ?></span></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('admin.reports.index')); ?>" class="tb-nav-link <?php echo e(request()->routeIs('admin.reports.*') ? 'is-active' : ''); ?>" <?php if(request()->routeIs('admin.reports.*')): ?> aria-current="page" <?php endif; ?>><span class="tb-nav-icon">▥</span><span>Laporan</span></a>
                <a href="<?php echo e(route('dashboard')); ?>#aturan" class="tb-nav-link"><span class="tb-nav-icon">⚙</span><span>Aturan layanan</span></a>
            <?php endif; ?>

            <div class="tb-sidebar-section tb-sidebar-section-spaced">AKSES SITUS</div>
            <a href="<?php echo e(route('home')); ?>" class="tb-nav-link"><span class="tb-nav-icon">⌂</span><span>Halaman Depan</span></a>
            <a href="<?php echo e(route('catalog.index')); ?>" class="tb-nav-link"><span class="tb-nav-icon">⌕</span><span>Katalog Publik</span></a>
            <a href="<?php echo e(route('ebooks.index')); ?>" class="tb-nav-link"><span class="tb-nav-icon">▣</span><span>Koleksi E-book</span></a>
        </nav>

        <div class="tb-sidebar-bottom">
            <div class="tb-sidebar-note"><span class="tb-online-dot"></span><span><strong>Sistem perpustakaan</strong><small>Panel operasional aktif</small></span></div>
            <div class="tb-sidebar-user">
                <div class="tb-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                <div class="min-w-0"><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(auth()->user()->isAdmin() ? 'Administrator' : 'Petugas'); ?></small></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="ml-auto"><?php echo csrf_field(); ?><button class="tb-logout" title="Keluar" aria-label="Keluar">⇥</button></form>
            </div>
        </div>
    </aside>

    <div class="tb-main">
        <header class="tb-topbar">
            <button type="button" class="tb-mobile-toggle" @click="sidebarOpen=!sidebarOpen" aria-label="Buka navigasi">☰</button>
            <div class="tb-topbar-heading">
                <div class="tb-breadcrumb">Ruang Baca <span>/</span> Panel Operasional <span>/</span> <?php echo e(trim($__env->yieldContent('title', 'Dashboard'))); ?></div>
                <div class="tb-topbar-title"><?php echo e(trim($__env->yieldContent('title', 'Dashboard'))); ?></div>
            </div>
            <div class="tb-topbar-actions">
                <span class="tb-today"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span>
                <div class="tb-profile-wrap">
                    <button type="button" class="tb-profile-button" @click="profileOpen=!profileOpen" :aria-expanded="profileOpen"><span class="tb-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></span><span class="tb-profile-name"><?php echo e(auth()->user()->name); ?></span><span class="tb-chevron">⌄</span></button>
                    <div class="tb-profile-menu" x-show="profileOpen" x-cloak @click.outside="profileOpen=false">
                        <a href="<?php echo e(route('home')); ?>">Lihat halaman depan</a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button type="submit">Keluar dari aplikasi</button></form>
                    </div>
                </div>
            </div>
        </header>

        <div class="tb-content">
            <?php if(session('success') || session('status') || $errors->any()): ?>
                <div class="tb-flash-stack">
                    <?php if(session('success')): ?><div class="tb-flash tb-flash-success" role="status"><?php echo e(session('success')); ?></div><?php endif; ?>
                    <?php if(session('status')): ?><div class="tb-flash tb-flash-success" role="status"><?php echo e(session('status')); ?></div><?php endif; ?>
                    <?php if($errors->any()): ?><div class="tb-flash tb-flash-error" role="alert"><?php echo e($errors->first()); ?></div><?php endif; ?>
                </div>
            <?php endif; ?>
            <main id="main-content"><?php echo $__env->yieldContent('content'); ?></main>
            <footer class="tb-footer"><span>© <?php echo e(now()->year); ?> <?php echo e(config('library.institution')); ?></span><span>Ruang Baca · Panel Operasional</span></footer>
        </div>
    </div>
</div>
<?php else: ?>
    <?php if(session('success') || session('status') || $errors->any()): ?>
        <div class="site-wrap pt-4">
            <?php if(session('success')): ?><div class="bg-[#e7eee3] text-[#385b3c] px-4 py-3 text-sm" role="status"><?php echo e(session('success')); ?></div><?php endif; ?>
            <?php if(session('status')): ?><div class="bg-[#e7eee3] text-[#385b3c] px-4 py-3 text-sm" role="status"><?php echo e(session('status')); ?></div><?php endif; ?>
            <?php if($errors->any()): ?><div class="bg-[#f6e3df] text-[#963f32] px-4 py-3 text-sm" role="alert"><?php echo e($errors->first()); ?></div><?php endif; ?>
        </div>
    <?php endif; ?>
    <main class="flex-1" id="main-content"><?php echo $__env->yieldContent('content'); ?></main>
    <?php if (! (trim($__env->yieldContent('hide_footer')))): ?>
    <footer class="mt-16 bg-[var(--ink)] text-[var(--paper)]">
        <div class="site-wrap py-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <div class="inline-flex items-center gap-3 bg-white rounded px-3 py-2">
                    <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="w-24 h-auto object-contain">
                    <span class="font-semibold text-sm text-[#252820]"><?php echo e(config('library.name')); ?></span>
                </div>
                <p class="text-xs text-[#c5c2b8] mt-3 leading-6">Layanan perpustakaan dan sumber informasi <?php echo e(config('library.institution')); ?> untuk mendukung budaya baca dan pembelajaran sepanjang hayat.</p>
                <p class="text-[10px] text-[#aaa99f] mt-5">Dikelola oleh <?php echo e(config('library.institution')); ?></p>
            </div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Navigasi</h2><div class="flex flex-col gap-3 text-xs text-[#eeeae0]"><a class="hover:text-white" href="<?php echo e(route('home')); ?>#beranda">Beranda</a><a class="hover:text-white" href="<?php echo e(route('catalog.index', ['format' => 'fisik'])); ?>">Koleksi Buku</a><a class="hover:text-white" href="<?php echo e(route('ebooks.index')); ?>">Koleksi E-book</a><a class="hover:text-white" href="<?php echo e(route('home')); ?>#tentang">Tentang Kami</a></div></div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Informasi layanan</h2><div class="space-y-3 text-xs text-[#eeeae0]"><?php if(config('library.service_hours')): ?><p><span class="block text-[#aaa99f] mb-1">Jam layanan</span><?php echo e(config('library.service_hours')); ?></p><?php endif; ?><p><span class="block text-[#aaa99f] mb-1">Layanan</span>Informasi koleksi, sirkulasi, dan akses bahan pustaka digital.</p></div></div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Alamat & kontak</h2><div class="space-y-3 text-xs text-[#eeeae0]"><?php if(config('library.address')): ?><p><?php echo e(config('library.address')); ?></p><?php endif; ?> <?php if(config('library.phone')): ?><p>Telepon: <?php echo e(config('library.phone')); ?></p><?php endif; ?> <?php if(config('library.email')): ?><p>Email: <a class="underline underline-offset-4" href="mailto:<?php echo e(config('library.email')); ?>"><?php echo e(config('library.email')); ?></a></p><?php endif; ?> <?php if (! (config('library.address') || config('library.phone') || config('library.email'))): ?><p class="text-[#c5c2b8] leading-6">Alamat dan kontak resmi instansi dapat diisi melalui konfigurasi aplikasi.</p><?php endif; ?></div></div>
        </div>
        <div class="border-t border-white/15"><div class="site-wrap py-5 flex flex-col sm:flex-row gap-2 justify-between text-[10px] text-[#c5c2b8]"><p>© <?php echo e(now()->year); ?> <?php echo e(config('library.institution')); ?>. Hak Cipta Dilindungi Undang-Undang.</p><p>Layanan Informasi Perpustakaan · <?php echo e(config('library.name')); ?></p></div></div>
    </footer>
    <?php endif; ?>
<?php endif; ?>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/layouts/app.blade.php ENDPATH**/ ?>