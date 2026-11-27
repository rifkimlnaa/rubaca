# Panduan deployment production — Ruang Baca Bawaslu

Ikuti urutan ini. Jangan menaruh root project Laravel di folder yang bisa diakses publik; web root harus menunjuk ke folder `public`.

## 0. Backup
- Backup project dan database lama jika ada.
- Backup `storage/app/private` (terutama file PDF e-book).
- Jangan pernah upload atau commit file `.env` ke Git/publik.

## 1. Pastikan hosting memenuhi kebutuhan
- PHP 8.2 atau lebih baru (Laravel 12).
- Extension PHP yang diperlukan Laravel, PDO driver untuk database, fileinfo, mbstring, openssl, tokenizer, xml, ctype, curl, zip, dan GD.
- MySQL/MariaDB atau database yang didukung aplikasi; buat database dan user dengan password kuat, batasi user database hanya pada database aplikasi.
- SSL/HTTPS aktif pada domain.

## 2. Upload project
- Upload ZIP dan extract ke folder non-public (misalnya `~/apps/ruang-baca`).
- Jika hosting tidak mengizinkan document root custom, minta hosting mengarahkan domain/subdomain ke folder `perpustakaan-modern/public`. Jangan expose folder `app`, `config`, `storage`, `vendor`, atau file `.env`.
- Upload/build asset frontend sehingga folder `public/build` tersedia. Jika Node/npm ada di server, jalankan `npm ci` (jika lockfile tersedia) lalu `npm run build`; jika tidak, build di komputer lokal dengan versi Node kompatibel dan upload folder `public/build`.

## 3. Install dependencies
Dari root project (folder yang berisi `artisan`):

```bash
composer install --no-dev --optimize-autoloader
```

Jika hosting tidak menyediakan Composer/SSH, siapkan `vendor` pada komputer/server build dengan versi PHP kompatibel dan upload bersama project; jangan upload dependencies dari sumber tidak terpercaya.

## 4. Konfigurasi `.env`
Salin `.env.example` menjadi `.env`, lalu isi nilai production:

```dotenv
APP_NAME="Ruang Baca Bawaslu"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://DOMAIN-RESMI-ANDA
APP_KEY= # generate dengan artisan key:generate

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=NAMA_DATABASE
DB_USERNAME=USER_DATABASE
DB_PASSWORD=PASSWORD_DATABASE

SESSION_DRIVER=database
SESSION_LIFETIME=60
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=private

MAIL_MAILER=smtp
MAIL_HOST=HOST_SMTP
MAIL_PORT=587
MAIL_USERNAME=AKUN_SMTP
MAIL_PASSWORD=PASSWORD_SMTP
MAIL_SCHEME=tls
MAIL_FROM_ADDRESS=EMAIL_DOMAIN_ANDA
MAIL_FROM_NAME="Ruang Baca Bawaslu"
```

Sesuaikan host/port/transport SMTP dengan provider hosting/email. Gunakan email aktif yang dapat menerima pesan reset password. Jangan menyimpan kredensial pada source code atau membagikannya.

## 5. Siapkan database dan akun admin

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
```

Seeder production hanya membuat kategori default dan aturan sirkulasi; akun demo tidak dibuat pada environment production. Buat akun admin awal melalui `php artisan tinker`:


```php
\App\Models\User::create([
    'name' => 'Nama Admin',
    'email' => 'EMAIL-ASLI-ADMIN',
    'password' => \Illuminate\Support\Facades\Hash::make('PASSWORD-UNIK-KUAT'),
    'role' => 'admin',
    'is_active' => true,
]);
```

Ganti nilai placeholder. Pastikan email sudah benar dan SMTP dapat mengirim email sebelum mengandalkan reset password.

## 6. Permission, storage, cache, dan SSL
- `storage` dan `bootstrap/cache` harus writable oleh user PHP/web server; folder project lain tidak boleh writable tanpa kebutuhan.
- Pastikan disk `private` berada di `storage/app/private` dan tidak dipetakan ke URL publik. E-book disajikan melalui route yang memerlukan autentikasi/authorization.
- Setelah SSL aktif dan `.env` benar:

```bash
php artisan storage:link
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

`storage:link` hanya untuk file yang memang ditaruh pada disk public (misalnya cover). Jangan membuat link public ke private e-book.

## 7. Smoke test sebelum diumumkan
- Homepage, katalog buku fisik, katalog e-book dan halaman reader.
- Login/logout Admin, Petugas, Anggota; cek akses URL langsung untuk role lain (harus 403/redirect sesuai aturan).
- Nonaktifkan akun uji yang sedang login; request berikutnya harus mengeluarkan sesi.
- Tambah/edit buku dan e-book, upload sampul/PDF, cek file PDF tidak bisa diakses langsung tanpa login/izin.
- Peminjaman, persetujuan, pengembalian, perpanjangan, dan laporan.
- Email reset password: cek pesan sampai ke inbox; gunakan token sekali, token expired, dan token tidak dapat dipakai ulang.
- Tes desktop/mobile dan cek error log.

## 8. Operasional setelah live
- Backup database dan `storage/app/private` secara berkala; uji pemulihan backup.
- Update dependency dari rilis resmi, tinjau advisori keamanan.
- Monitor log, uptime, kapasitas storage, dan mail delivery.
- Jangan mengaktifkan `APP_DEBUG` di production.

> Panduan ini tidak menggantikan security review/test runtime. Lakukan pengujian di staging atau backup dulu sebelum deployment production.
