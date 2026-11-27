# Ruang Baca — Sistem Perpustakaan Laravel 12

Starter project sistem perpustakaan dengan PHP 8.4, Laravel 12, Blade, Alpine.js, Tailwind CSS, Eloquent, autentikasi session, middleware role, Policy, dan PDF.js.

## Fitur yang sudah disediakan

- **Role** `admin`, `staff`, `member`; registrasi publik hanya menghasilkan role anggota. Form registrasi dapat menampilkan/menyembunyikan kata sandi, memeriksa minimal 8 karakter, satu huruf besar, satu angka, dan kecocokan konfirmasi secara real-time. Backend memvalidasi aturan yang sama. Akun admin/petugas dibuat oleh admin atau seeder.
- **Lupa kata sandi** dengan Laravel Password Broker, email pemulihan berbahasa Indonesia, token sekali pakai yang kedaluwarsa dalam 60 menit, rate limit, respons publik yang tidak membocorkan keberadaan akun, dan validasi kata sandi baru yang sama seperti registrasi.
- **Landing page publik** dengan navigasi Beranda, Buku, E-book, Tentang Kami; hero image editorial (`public/images/ruang-baca-hero.png`); koleksi pilihan; statistik; footer formal untuk instansi Indonesia; animasi reveal saat scroll, hover kartu buku (lift, tilt sampul, dan shadow), smooth anchor scrolling, dan indikator transisi halaman.
- **Katalog** dengan pencarian AJAX/debounce, filter kategori/format, detail buku, dan paginasi. Halaman buku fisik tersedia di `/katalog?format=fisik`.
- **Katalog E-book** publik di `/e-book`, dengan pencarian, filter kategori, paginasi, dan keterangan akses anggota. Pencarian AJAX menampilkan skeleton shimmer selama data dimuat.
- **Inventaris** buku, penulis, penerbit, kategori, rak, eksemplar/barcode, unggah sampul, dan unggah PDF e-book. Seluruh halaman kerja admin/petugas (dashboard, daftar/tambah/edit buku, sirkulasi, anggota/pengguna, data master, dan laporan) memakai satu shell back-office bergaya Tabler dengan sidebar responsif, topbar, kartu, tabel, formulir, filter, dan status yang konsisten.
- **Sirkulasi** permintaan pinjam, persetujuan/penolakan petugas, alokasi eksemplar dengan transaksi + row lock, pengembalian, denda otomatis dan verifikasi denda oleh petugas, serta permintaan perpanjangan.
- **Pembaca PDF** PDF.js di browser, zoom, navigasi halaman, mode gelap/terang, penyimpanan progres per anggota, watermark ringan.
- **Daftar bacaan**, dashboard ringkas, notifikasi database, laporan sirkulasi, serta pengaturan masa pinjam/denda/batas pinjaman.
- **File privat** disimpan pada disk `private`, bukan `public/storage`; route file memerlukan login + policy dan mendukung HTTP Range untuk PDF.js.

## Instalasi lokal

Persyaratan: PHP **8.4**, Composer 2, Node.js 20+, npm, dan ekstensi PHP Laravel (ctype, curl, dom, fileinfo, filter, hash, mbstring, openssl, pcre, PDO, session, tokenizer, xml; plus `pdo_sqlite` untuk database default).

**Windows PowerShell:** jalankan helper instalasi, kemudian start server.

```powershell
Set-ExecutionPolicy -Scope Process Bypass
.\setup.ps1
php artisan serve
```

Atau jalankan perintah berikut secara manual:

```powershell
Copy-Item .env.example .env
New-Item database/database.sqlite -ItemType File -Force
composer install
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

**macOS / Linux:**

```bash
cp .env.example .env
touch database/database.sqlite
composer install
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

### Uji coba email reset kata sandi

Secara default `.env.example` menggunakan `MAIL_MAILER=log`. Ini mode percobaan yang aman: **tidak mengirim email sungguhan**. Ketika pengguna meminta reset kata sandi untuk email yang terdaftar, isi pesan dan tautan reset ditulis ke:

```text
storage/logs/laravel.log
```

Cari teks `Atur ulang kata sandi — Ruang Baca` atau `reset-password` di file tersebut, lalu salin URL reset ke browser. Tautan berlaku 60 menit dan hanya dapat dipakai sekali. Pastikan `APP_URL` pada `.env` sesuai alamat server, misalnya `http://127.0.0.1:8000`, agar URL di email mengarah ke aplikasi lokal.

### Opsi inbox lokal dengan Mailpit

Mailpit memberi tampilan inbox email di browser tanpa mengirim email ke internet. Jika Docker Desktop tersedia, jalankan:

```powershell
docker run -d --name ruang-baca-mailpit -p 8025:8025 -p 1025:1025 axllent/mailpit
```

Atur bagian mail di `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_SCHEME=null
MAIL_EHLO_DOMAIN=localhost
MAIL_FROM_ADDRESS=no-reply@ruangbaca.test
MAIL_FROM_NAME="Ruang Baca"
```

Jalankan `php artisan config:clear`, kemudian buka inbox Mailpit di `http://127.0.0.1:8025`. Minta tautan pemulihan dari halaman `/lupa-kata-sandi`; email percobaan akan muncul di inbox Mailpit. Karena Mailpit hanya menangkap pesan secara lokal, email tidak dikirim ke alamat sungguhan.

Untuk deployment, jangan gunakan driver `log` atau Mailpit sebagai layanan email produksi. Minta SMTP resmi instansi dan simpan kredensial SMTP di environment server, bukan di Git.

### Identitas visual Bawaslu

Logo Bawaslu ditampilkan pada navigasi publik dan sidebar back-office menggunakan aset PNG yang disediakan pada halaman unduhan Bawaslu Mesuji: https://mesuji.bawaslu.go.id/index.php/berita/download-logo-bawaslu-png. Aset saat ini dimuat dari URL resmi tersebut, sehingga browser memerlukan koneksi internet untuk menampilkannya. Untuk deployment tanpa ketergantungan jaringan eksternal, simpan file PNG resmi ke `public/images/logo-bawaslu.png` lalu ubah `src` logo pada `resources/views/layouts/app.blade.php` menjadi `{{ asset('images/logo-bawaslu.png') }}`.

### Konfigurasi identitas instansi dan footer

Isi nilai berikut pada file `.env` agar footer menampilkan identitas resmi perpustakaan. Nilai alamat, telepon, email, dan jam layanan bersifat opsional dan tidak akan ditampilkan jika kosong.

```dotenv
LIBRARY_NAME="Ruang Baca"
LIBRARY_INSTITUTION="Badan Pengawas Pemilihan Umum"
LIBRARY_ADDRESS="Alamat lengkap instansi"
LIBRARY_PHONE="Nomor telepon layanan"
LIBRARY_EMAIL="perpustakaan@instansi.go.id"
LIBRARY_SERVICE_HOURS="Senin–Jumat, 08.00–16.00 WIB"
```

Setelah mengubah konfigurasi, jalankan `php artisan config:clear` jika konfigurasi pernah di-cache. Jam layanan dan kontak di atas hanya contoh format; sesuaikan dengan data resmi instansi.

### Akun demo (ubah sebelum dipublikasikan)

| Role | Email | Password |
|---|---|---|
| Admin | `admin@ruangbaca.test` | `Admin12345!` |
| Petugas | `petugas@ruangbaca.test` | `Petugas123!` |
| Anggota | `anggota@ruangbaca.test` | `Anggota123!` |

## Catatan teknis penting

1. **Laravel 12 & autentikasi.** Proyek ini memakai autentikasi session custom yang ringkas. Pemulihan kata sandi sudah menggunakan Laravel Password Broker, tabel `password_reset_tokens`, notifikasi email khusus Bahasa Indonesia, dan SMTP/log mailer. Verifikasi email, MFA/passkey belum disertakan.
2. **Aturan kata sandi.** Registrasi anggota mensyaratkan minimal 8 karakter, paling sedikit satu huruf besar A–Z, dan satu angka 0–9. Syarat ditampilkan saat pengguna mengetik; backend tetap memvalidasi ulang agar tidak bergantung pada JavaScript. Pengguna dapat menampilkan atau menyembunyikan kata sandi melalui tombol di samping field.
3. **Animasi antarmuka.** Pencarian katalog menampilkan skeleton shimmer; navigasi internal menampilkan garis progres; bagian landing page muncul bertahap saat masuk viewport. Animasi menghormati `prefers-reduced-motion` untuk pengguna yang memilih pengurangan animasi.
4. **Alur akses e-book.** Pengunjung dapat melihat daftar e-book di `/e-book`, tetapi route reader dan route file hanya tersedia bagi anggota yang sudah login. Klik baca saat belum login akan membawa pengunjung langsung ke formulir pendaftaran. Setelah registrasi berhasil, anggota diarahkan kembali ke e-book yang diminta. Registrasi publik selalu membuat role `member`.
5. **PDF dan batasan anti-unduh.** File PDF tidak punya URL publik dan endpoint file memerlukan sesi anggota aktif serta Policy. Ini menghalangi hotlink/akses langsung tanpa izin, tetapi **tidak mungkin menjamin file tidak dapat disimpan** setelah konten dikirim ke browser (misalnya melalui DevTools, cache, atau tangkapan layar). Untuk proteksi lebih kuat gunakan DRM/vendor viewer, signed short-lived token, logging, rate limit, serta watermark per pengguna. PDF.js adalah viewer, bukan DRM.
6. **E-book saat ini PDF.** Validasi unggahan dibatasi PDF. Dukungan EPUB memerlukan `epub.js`, route konten/asset yang aman, dan penanganan arsip ZIP yang tervalidasi.
7. **Kebijakan produksi.** Set `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, secrets environment, backup database dan storage privat, antivirus scanning upload, batas ukuran di web server, rate limit yang sesuai, logging/audit, dan queue/notifications email. Jangan commit `.env` atau file e-book ke Git.
8. **MySQL.** Ubah `DB_CONNECTION=mysql` serta `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` pada `.env`. Jalankan `php artisan migrate --seed`.
9. **Pengelolaan aturan.** Admin dapat mengubah aturan pinjaman, perpanjangan, denda, dan batas pinjaman aktif melalui panel Dashboard pada bagian Aturan perpustakaan. Nilai awal ada di seeder: 14 hari pinjam, 7 hari perpanjangan, Rp1.000/hari, maksimal 3 pinjaman aktif.

## Alur pengujian manual

1. Masuk sebagai anggota, pilih buku yang memiliki eksemplar tersedia, lalu ajukan peminjaman.
2. Masuk sebagai petugas, buka **Sirkulasi**, setujui permintaan; eksemplar akan dialokasikan dan tenggat dihitung dari aturan.
3. Kembalikan buku dari akun petugas untuk menutup pinjaman dan menghitung denda bila terlambat.
4. Tambahkan PDF dari **Kelola buku**. Anggota bisa membuka **Baca e-book**, mengganti halaman/zoom/tema, lalu membuka ulang untuk memulihkan halaman terakhir.


## Pengujian otomatis

Jalankan setelah dependensi Composer terpasang dan database testing SQLite tersedia:

```bash
php artisan test
```

`tests/Feature/PasswordRegistrationTest.php` mencakup kata sandi kurang dari 8 karakter, tanpa huruf besar, tanpa angka, serta pendaftaran valid. `tests/Feature/PasswordResetFlowTest.php` mencakup halaman lupa kata sandi, notifikasi ke alamat terdaftar, respons generik untuk email yang tidak terdaftar, reset kata sandi, pemertahanan role, dan validasi aturan kata sandi baru.

## Struktur penting

- `app/Models` — model domain dan relasi Eloquent
- `app/Http/Controllers` — auth, katalog, buku, peminjaman, reader, laporan
- `app/Policies` — otorisasi per-resource
- `app/Http/Middleware/RoleMiddleware.php` — pembatasan role
- `database/migrations` — skema perpustakaan
- `database/seeders` — data contoh Indonesia dan akun demo
- `resources/views` — Blade layout, landing page, dashboard, katalog, operasional
- `resources/js/app.js` — komponen Alpine, validasi langsung kata sandi, skeleton shimmer, dan animasi scroll
- `resources/js/reader.js` — integrasi PDF.js

> Starter ini merupakan fondasi aplikasi yang dapat dijalankan setelah dependensi dipasang. Sebelum dipakai untuk transaksi perpustakaan sungguhan, lakukan code review, test integrasi tiap role, audit keamanan upload/akses file, serta sesuaikan aturan sirkulasi dengan kebijakan institusi.

## Versi v7 — pemisahan Buku Fisik dan E-book
- Menu back-office memisahkan Kelola Buku Fisik dan Kelola E-book.
- Penulis dan penerbit diinput sebagai teks langsung di form koleksi; hanya kategori yang memakai dropdown master.
- Rak/lokasi disimpan sebagai teks langsung, bukan dropdown master.
- Kode buku otomatis dibuat dengan format `RB-XXXXXXXX` dan bersifat unik. Database masih memakai kolom `id` sebagai primary key internal agar relasi pinjaman, eksemplar, dan daftar bacaan tetap aman; kode buku adalah identifier katalog unik.
- Migration baru menambahkan kode buku, nama penulis/penerbit, dan lokasi rak teks, sekaligus mengisi kode untuk data lama.

Jalankan migrasi setelah backup database: `php artisan migrate`.

## Revisi v8 — Data Master kategori saja
- Halaman Data Master kini hanya mengelola Kategori Buku.
- Tab dan form Penulis, Penerbit, dan Rak dihapus dari antarmuka Data Master.
- Endpoint Data Master dibatasi hanya untuk `categories`; jenis lain menghasilkan 404.
- Penulis, penerbit, dan lokasi rak tetap berupa input teks pada form koleksi; struktur/model lama yang mungkin masih ada di database tidak dihapus dalam revisi ini agar data/relasi lama tidak rusak.

## Revisi v8 - Data Master kategori saja
- Halaman Data Master kini hanya mengelola Kategori Buku.
- Tab/form Penulis, Penerbit, dan Rak dihapus dari antarmuka Data Master.
- Endpoint Data Master dibatasi hanya untuk categories; jenis lain menghasilkan 404.
- Penulis, penerbit, dan lokasi rak tetap berupa input teks pada form koleksi; model/tabel legacy tidak dihapus agar data dan relasi lama tidak rusak.


## Checklist hardening awal (v9)

- Middleware `active-account` memutus sesi jika akun sudah dinonaktifkan; dipasang pada route terautentikasi dashboard, admin, dan notifikasi.
- Seeder demo akun dan koleksi contoh hanya berjalan pada environment `local` atau `testing`. Production seeder hanya membuat kategori default dan aturan peminjaman.
- `.env.example` memakai nilai aman untuk debug, HTTPS cookie, SMTP, dan placeholder domain. Nilai ini harus disesuaikan dengan hosting sebelum digunakan.

### Buat akun Admin production (jangan gunakan akun demo)
Setelah database/migrasi siap, gunakan `php artisan tinker` di server dan jalankan:

```php
\App\Models\User::create([
  'name' => 'Nama Admin',
  'email' => 'email-asli-yang-aktif@example.com',
  'password' => \Illuminate\Support\Facades\Hash::make('GANTI-PASSWORD-KUAT-UNIK'),
  'role' => 'admin',
  'is_active' => true,
]);
```

Ganti nama, email, dan password dengan nilai asli. Jangan bagikan password melalui chat atau simpan di Git. Pastikan email SMTP sudah diuji agar pemulihan password dapat diterima.

**Deployment:** set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` ke domain HTTPS, `SESSION_SECURE_COOKIE=true`, DB production (MySQL/MariaDB sesuai hosting), SMTP aktif, document root hanya ke folder `public`, serta backup database dan `storage/app/private` sebelum update.
#   r u b a c a  
 