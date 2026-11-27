@php($isBackofficeArea = auth()->check() && in_array(auth()->user()->role, ['admin', 'staff'], true) && request()->routeIs('dashboard', 'books.*', 'admin.ebooks.*', 'loans.staff', 'admin.*'))
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f5f4f2">

    <link rel="icon" type="image/png" href="{{ asset('favicon-bawaslu.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon-bawaslu.png') }}">

    <title>@yield('title', config('library.name')) — {{ config('library.institution') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="{{ $isBackofficeArea ? 'min-h-screen' : 'min-h-screen flex flex-col' }}">
<div id="navigation-progress" role="status" aria-live="polite" aria-hidden="true"><span class="sr-only">Memuat halaman…</span></div>
@unless($isBackofficeArea)
<header class="public-header border-b border-[var(--line)] bg-[var(--paper)]">
    <div class="site-wrap public-header-inner min-h-[76px] flex items-center justify-between gap-5">
        <a href="{{ route('home') }}#beranda" class="flex items-center gap-3 shrink-0" aria-label="{{ config('library.name') }} - Beranda">
            <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="h-10 w-24 object-contain object-left">
            <span class="border-l border-[var(--line)] pl-3">
                <span class="block font-semibold text-base leading-none">{{ config('library.name') }}</span>
                <span class="block text-[9px] tracking-[.12em] uppercase text-[var(--muted)] mt-1">{{ config('library.institution') }}</span>
            </span>
        </a>

        <nav class="public-nav hidden md:flex items-center gap-7" aria-label="Navigasi utama">
            <a class="nav-link" href="{{ route('home') }}#beranda">Beranda</a>
            <a class="nav-link" href="{{ route('catalog.index', ['format' => 'fisik']) }}">Buku</a>
            <a class="nav-link" href="{{ route('ebooks.index') }}">E-book</a>
            <a class="nav-link" href="{{ route('home') }}#tentang">Tentang Kami</a>
        </nav>

        <div class="flex items-center gap-3 shrink-0">
            @auth
                <div class="hidden sm:block text-right">
                    <div class="text-xs font-semibold">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-[var(--muted)] uppercase tracking-wider">{{ auth()->user()->role }}</div>
                </div>
                <div class="relative" x-data="{open:false}">
                    <button type="button" @click="open=!open" aria-label="Buka menu akun" :aria-expanded="open" class="w-9 h-9 border border-[var(--line)] flex items-center justify-center text-sm">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</button>
                    <div x-show="open" @click.outside="open=false" x-cloak class="absolute right-0 top-11 z-30 panel w-52 p-2 shadow-lg">
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Dashboard</a>
                        @if(auth()->user()->isMember())
                            <a href="{{ route('loans.mine') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Pinjaman saya</a>
                            <a href="{{ route('reading-list.index') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Daftar bacaan</a>
                        @else
                            <a href="{{ route('books.index') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola buku fisik</a>
                            <a href="{{ route('admin.ebooks.index') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola e-book</a>
                            <a href="{{ route('loans.staff') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Sirkulasi</a>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Kelola pengguna</a>
                            <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 text-xs hover:bg-stone-50">Laporan</a>
                        @endif
                        <form method="POST" action="{{ route('notifications.read-all') }}">@csrf<button class="block w-full text-left px-3 py-2 text-xs hover:bg-stone-50">Tandai notifikasi dibaca</button></form>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button class="block w-full text-left px-3 py-2 text-xs hover:bg-stone-50">Keluar</button></form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                <a href="{{ route('register') }}" class="btn-primary">Daftar anggota</a>
            @endauth
        </div>
    </div>

    <nav class="md:hidden border-t border-[var(--line)]" aria-label="Navigasi utama seluler">
        <div class="site-wrap flex items-center gap-7 overflow-x-auto whitespace-nowrap py-3">
            <a class="nav-link" href="{{ route('home') }}#beranda">Beranda</a>
            <a class="nav-link" href="{{ route('catalog.index', ['format' => 'fisik']) }}">Buku</a>
            <a class="nav-link" href="{{ route('ebooks.index') }}">E-book</a>
            <a class="nav-link" href="{{ route('home') }}#tentang">Tentang Kami</a>
        </div>
    </nav>
</header>
@endunless

@if($isBackofficeArea)
<div class="tb-admin" x-data="{ sidebarOpen: false, profileOpen: false }">
    <div class="tb-sidebar-scrim" x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"></div>
    <aside class="tb-sidebar" :class="sidebarOpen ? 'is-open' : ''">
        <a href="{{ route('dashboard') }}" class="tb-brand" aria-label="Dashboard Ruang Baca Bawaslu">
            <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="tb-brand-logo">
            <span class="tb-brand-caption"><strong>RUANG BACA</strong><small>{{ config('library.institution') }}</small></span>
        </a>

        <div class="tb-sidebar-section">MENU UTAMA</div>
            <nav class="tb-nav" aria-label="Navigasi panel operasional">

                <a href="{{ route('dashboard') }}" class="tb-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                    </span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('books.index') }}" class="tb-nav-link {{ request()->routeIs('books.*') ? 'is-active' : '' }}" @if(request()->routeIs('books.*')) aria-current="page" @endif>
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/><path d="M8 7h8M8 11h8"/></svg>
                    </span>
                    <span>Kelola Buku</span>
                </a>

                <a href="{{ route('admin.ebooks.index') }}" class="tb-nav-link {{ request()->routeIs('admin.ebooks.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.ebooks.*')) aria-current="page" @endif>
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M9 18h6"/></svg>
                    </span>
                    <span>Kelola E-book</span>
                </a>

                <a href="{{ route('loans.staff') }}" class="tb-nav-link {{ request()->routeIs('loans.staff') ? 'is-active' : '' }}" @if(request()->routeIs('loans.staff')) aria-current="page" @endif>
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M17 3l4 4-4 4"/><path d="M3 7h18"/><path d="M7 21l-4-4 4-4"/><path d="M21 17H3"/></svg>
                    </span>
                    <span>Sirkulasi</span>
                </a>

                @if(auth()->user()->isAdmin())

                    <div class="tb-sidebar-section tb-sidebar-section-spaced">ADMINISTRASI</div>

                    <a href="{{ route('admin.users.index') }}" class="tb-nav-link {{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.users.*')) aria-current="page" @endif>
                        <span class="tb-nav-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <span>Anggota & Pengguna</span>
                    </a>

                    <div class="tb-nav-group-label">DATA MASTER</div>

                    @foreach(['categories' => 'Kategori buku'] as $masterType => $masterLabel)
                        <a href="{{ route('admin.master.index', $masterType) }}" class="tb-nav-link tb-nav-sub-link {{ request()->routeIs('admin.master.*') && request()->route('type') === $masterType ? 'is-active' : '' }}" @if(request()->routeIs('admin.master.*') && request()->route('type') === $masterType) aria-current="page" @endif>
                            <span class="tb-nav-sub-dot"></span>
                            <span>{{ $masterLabel }}</span>
                        </a>
                    @endforeach

                    <a href="{{ route('admin.reports.index') }}" class="tb-nav-link {{ request()->routeIs('admin.reports.*') ? 'is-active' : '' }}" @if(request()->routeIs('admin.reports.*')) aria-current="page" @endif>
                        <span class="tb-nav-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-5 5"/><path d="M15 9h4v4"/></svg>
                        </span>
                        <span>Laporan</span>
                    </a>

                    <a href="{{ route('dashboard') }}#aturan" class="tb-nav-link">
                        <span class="tb-nav-icon">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="m19.4 15 .1.1 1.4 1.1-1.4 2.4-1.7-.7a8 8 0 0 1-1.7 1l-.3 1.8h-2.8l-.3-1.8a8 8 0 0 1-1.7-1l-1.7.7-1.4-2.4 1.4-1.1a8 8 0 0 1 0-2l-1.4-1.1 1.4-2.4 1.7.7a8 8 0 0 1 1.7-1l.3-1.8h2.8l.3 1.8a8 8 0 0 1 1.7 1l1.7-.7 1.4 2.4-1.4 1.1a8 8 0 0 1 0 2Z" transform="translate(-1 -1) scale(1.08)"/></svg>
                        </span>
                        <span>Aturan layanan</span>
                    </a>

                @endif

                <div class="tb-sidebar-section tb-sidebar-section-spaced">AKSES SITUS</div>

                <a href="{{ route('home') }}" class="tb-nav-link">
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7"/><path d="M5 9v12h14V9M9 21v-7h6v7"/></svg>
                    </span>
                    <span>Halaman Depan</span>
                </a>

                <a href="{{ route('catalog.index') }}" class="tb-nav-link">
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                    </span>
                    <span>Katalog Publik</span>
                </a>

                <a href="{{ route('ebooks.index') }}" class="tb-nav-link">
                    <span class="tb-nav-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v18H6.5A2.5 2.5 0 0 1 4 18.5Z"/><path d="M4 18.5A2.5 2.5 0 0 1 6.5 16H20M8 7h8M8 11h6"/></svg>
                    </span>
                    <span>Koleksi E-book</span>
                </a>

            </nav>


        <div class="tb-sidebar-bottom">
            <div class="tb-sidebar-note"><span class="tb-online-dot"></span><span><strong>Sistem perpustakaan</strong><small>Panel operasional aktif</small></span></div>
            <div class="tb-sidebar-user">
                <div class="tb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="min-w-0"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->isAdmin() ? 'Administrator' : 'Petugas' }}</small></div>
                <form method="POST" action="{{ route('logout') }}" class="ml-auto">@csrf<button class="tb-logout" title="Keluar" aria-label="Keluar">⇥</button></form>
            </div>
        </div>
    </aside>

    <div class="tb-main">
        <header class="tb-topbar">
            <button type="button" class="tb-mobile-toggle" @click="sidebarOpen=!sidebarOpen" aria-label="Buka navigasi">☰</button>
            <div class="tb-topbar-heading">
                <div class="tb-breadcrumb">Ruang Baca <span>/</span> Panel Operasional <span>/</span> {{ trim($__env->yieldContent('title', 'Dashboard')) }}</div>
                <div class="tb-topbar-title">{{ trim($__env->yieldContent('title', 'Dashboard')) }}</div>
            </div>
            <div class="tb-topbar-actions">
                <span class="tb-today">{{ now()->translatedFormat('l, d F Y') }}</span>
                <div class="tb-profile-wrap">
                    <button type="button" class="tb-profile-button" @click="profileOpen=!profileOpen" :aria-expanded="profileOpen"><span class="tb-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="tb-profile-name">{{ auth()->user()->name }}</span><span class="tb-chevron">⌄</span></button>
                    <div class="tb-profile-menu" x-show="profileOpen" x-cloak @click.outside="profileOpen=false">
                        <a href="{{ route('home') }}">Lihat halaman depan</a>
                        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar dari aplikasi</button></form>
                    </div>
                </div>
            </div>
        </header>

        <div class="tb-content">          
            @if(session('success') || session('status') || $errors->any())
            <div
                x-data="{ show: true }"
                x-init="setTimeout(() => show = false, 5000)"
                x-show="show"
                x-transition.opacity.duration.300ms
                class="fixed top-5 right-5 z-[9999] w-[calc(100%-2.5rem)] max-w-md"
            >
                <div class="tb-flash-stack shadow-lg">
                    @if(session('success'))
                        <div class="tb-flash tb-flash-success" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="tb-flash tb-flash-success" role="status">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="tb-flash tb-flash-error" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button
                        type="button"
                        @click="show = false"
                        class="absolute top-2 right-3 text-lg font-bold"
                        aria-label="Tutup notifikasi"
                    >&times;</button>
                </div>
            </div>
            @endif

            <main id="main-content">@yield('content')</main>
            <footer class="tb-footer"><span>© {{ now()->year }} {{ config('library.institution') }}</span><span>Ruang Baca · Panel Operasional</span></footer>
        </div>
    </div>
</div>
@else

    @if(session('success') || session('status') || $errors->any())
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition.opacity.duration.300ms
            class="fixed top-5 right-5 z-[9999] w-[calc(100%-2.5rem)] max-w-md shadow-lg rounded-lg overflow-hidden"
            role="{{ $errors->any() ? 'alert' : 'status' }}"
        >
            <div class="flex items-start justify-between gap-4 px-4 py-3 text-sm
                {{ $errors->any() ? 'bg-[#f6e3df] text-[#963f32]' : 'bg-[#e7eee3] text-[#385b3c]' }}">
                
                <div>
                    @if(session('success')){{ session('success') }}@endif
                    @if(session('status')){{ session('status') }}@endif
                    @if($errors->any()){{ $errors->first() }}@endif
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="font-bold text-lg leading-none opacity-70 hover:opacity-100"
                    aria-label="Tutup notifikasi"
                >&times;</button>
            </div>
        </div>
    @endif
    <main class="flex-1" id="main-content">@yield('content')</main>
    @unless(trim($__env->yieldContent('hide_footer')))
    <footer class="mt-16 bg-[var(--ink)] text-[var(--paper)]">
        <div class="site-wrap py-12 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-1">
                <div class="inline-flex items-center gap-3 bg-white rounded px-3 py-2">
                    <img src="https://mesuji.bawaslu.go.id/sites/mesuji/files/uploads/2023/01/Logo_Bawaslu-1.png" alt="Logo resmi Bawaslu" class="w-24 h-auto object-contain">
                    <span class="font-semibold text-sm text-[#252820]">{{ config('library.name') }}</span>
                </div>
                <p class="text-xs text-[#c5c2b8] mt-3 leading-6">Layanan perpustakaan dan sumber informasi {{ config('library.institution') }} untuk mendukung budaya baca dan pembelajaran sepanjang hayat.</p>
                <p class="text-[10px] text-[#aaa99f] mt-5">Dikelola oleh {{ config('library.institution') }}</p>
            </div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Navigasi</h2><div class="flex flex-col gap-3 text-xs text-[#eeeae0]"><a class="hover:text-white" href="{{ route('home') }}#beranda">Beranda</a><a class="hover:text-white" href="{{ route('catalog.index', ['format' => 'fisik']) }}">Koleksi Buku</a><a class="hover:text-white" href="{{ route('ebooks.index') }}">Koleksi E-book</a><a class="hover:text-white" href="{{ route('home') }}#tentang">Tentang Kami</a></div></div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Informasi layanan</h2><div class="space-y-3 text-xs text-[#eeeae0]">@if(config('library.service_hours'))<p><span class="block text-[#aaa99f] mb-1">Jam layanan</span>{{ config('library.service_hours') }}</p>@endif<p><span class="block text-[#aaa99f] mb-1">Layanan</span>Informasi koleksi, sirkulasi, dan akses bahan pustaka digital.</p></div></div>
            <div><h2 class="eyebrow text-[#c5c2b8] mb-4">Alamat & kontak</h2><div class="space-y-3 text-xs text-[#eeeae0]">@if(config('library.address'))<p>{{ config('library.address') }}</p>@endif @if(config('library.phone'))<p>Telepon: {{ config('library.phone') }}</p>@endif @if(config('library.email'))<p>Email: <a class="underline underline-offset-4" href="mailto:{{ config('library.email') }}">{{ config('library.email') }}</a></p>@endif @unless(config('library.address') || config('library.phone') || config('library.email'))<p class="text-[#c5c2b8] leading-6">Alamat dan kontak resmi instansi dapat diisi melalui konfigurasi aplikasi.</p>@endunless</div></div>
        </div>
        <div class="border-t border-white/15"><div class="site-wrap py-5 flex flex-col sm:flex-row gap-2 justify-between text-[10px] text-[#c5c2b8]"><p>© {{ now()->year }} {{ config('library.institution') }}. Hak Cipta Dilindungi Undang-Undang.</p><p>Layanan Informasi Perpustakaan · {{ config('library.name') }}</p></div></div>
    </footer>
    @endunless
@endif
@stack('scripts')
</body>
</html>
