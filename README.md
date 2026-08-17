<p align="center">
  <img src="https://img.icons8.com/3d-fluency/94/box.png" width="80" alt="Inventaris Logo">
</p>

<h1 align="center">📦 Inventaris Barang</h1>

<p align="center">
  Sistem Informasi Manajemen Inventaris Barang berbasis Web — dibangun dengan <strong>Laravel 12</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Vite-7-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

---

## 📋 Deskripsi

Aplikasi web untuk mengelola data inventaris barang secara digital. Mendukung pengelolaan barang, kategori, peminjaman dengan sistem approval, manajemen pengguna dengan role-based access, serta pembuatan laporan dalam format PDF & Excel.

Cocok digunakan oleh instansi, sekolah, kampus, atau organisasi yang membutuhkan pencatatan aset/inventaris secara terstruktur.

---

## ✨ Fitur Utama

| Modul | Fitur |
|---|---|
| **🔐 Autentikasi** | Login, Register, Lupa Password (Laravel Breeze) |
| **📊 Dashboard** | Ringkasan statistik barang, peminjaman, dan aktivitas terkini |
| **📦 Manajemen Barang** | CRUD barang, pencarian, upload foto, tracking jumlah & kondisi (baik/rusak) |
| **🏷️ Kategori** | CRUD kategori untuk pengelompokan barang |
| **🔄 Peminjaman** | Ajukan peminjaman, sistem approval (disetujui/ditolak), pengembalian barang |
| **👥 Manajemen User** | CRUD user, role-based access (Admin & User biasa) |
| **📑 Laporan** | Cetak/Export laporan barang & peminjaman ke **PDF** dan **Excel** |
| **📝 Activity Log** | Pencatatan aktivitas pengguna menggunakan Spatie Activity Log |

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12 · PHP 8.2+
- **Frontend:** Blade · Tailwind CSS 4 · Alpine.js · Vite 7
- **Database:** MySQL / SQLite
- **Package Utama:**
  - `laravel/breeze` — Autentikasi
  - `yajra/laravel-datatables` — Tabel data interaktif (server-side)
  - `barryvdh/laravel-dompdf` — Export PDF
  - `maatwebsite/excel` — Export Excel
  - `spatie/laravel-activitylog` — Pencatatan log aktivitas

---

## 🚀 Instalasi Lokal (XAMPP / Development)

### Prasyarat

- PHP >= 8.2 (dengan ekstensi `gd`, `pdo_mysql`, `mbstring`, `zip`)
- Composer
- Node.js >= 18 & npm
- MySQL / MariaDB (atau SQLite untuk penggunaan sederhana)

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/aikoons/inventaris-barang.git
cd inventaris-barang

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di file .env
#    Buka file .env lalu sesuaikan:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=inventaris_barang
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Jalankan migrasi database
php artisan migrate

# 8. (Opsional) Jalankan seeder untuk data awal
php artisan db:seed

# 9. Buat symlink storage
php artisan storage:link

# 10. Jalankan aplikasi
composer dev
```

> **Catatan:** Perintah `composer dev` akan menjalankan Laravel server, Vite dev server, Queue listener, dan Pail (log viewer) secara bersamaan menggunakan `concurrently`.

Atau jalankan manual secara terpisah:
```bash
# Terminal 1 — Server Laravel
php artisan serve

# Terminal 2 — Vite (kompilasi aset)
npm run dev
```

Akses aplikasi di: **http://localhost:8000**

---

## ☁️ Panduan Deploy

### Opsi 1: Railway (Docker) — Rekomendasi ⭐

[Railway](https://railway.app) mendukung deploy otomatis via Dockerfile yang sudah disediakan di project ini.

#### Langkah-langkah:

1. **Buat Akun Railway** — Daftar di [railway.app](https://railway.app) (bisa login via GitHub).

2. **Buat Project Baru**
   - Klik **"New Project"** → Pilih **"Deploy from GitHub Repo"**.
   - Hubungkan akun GitHub Anda dan pilih repo `inventaris-barang`.

3. **Tambahkan Database MySQL**
   - Di dalam project Railway, klik **"New"** → **"Database"** → **"MySQL"**.
   - Railway akan otomatis membuat database dan menyediakan environment variables.

4. **Set Environment Variables**
   Di bagian **Variables** pada service aplikasi, tambahkan:
   ```
   APP_NAME=Inventaris Barang
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=        ← jalankan `php artisan key:generate --show` di lokal, lalu salin hasilnya
   APP_URL=        ← URL dari Railway (akan muncul setelah deploy pertama)

   DB_CONNECTION=mysql
   DB_HOST=        ← dari variabel MySQL Railway
   DB_PORT=3306
   DB_DATABASE=    ← dari variabel MySQL Railway
   DB_USERNAME=    ← dari variabel MySQL Railway
   DB_PASSWORD=    ← dari variabel MySQL Railway
   ```

   > **Tips:** Railway menyediakan variable reference seperti `${{MySQL.MYSQL_HOST}}`. Anda bisa menggunakannya untuk mengisi `DB_HOST`, `DB_DATABASE`, dll secara otomatis.

5. **Deploy**
   - Railway akan otomatis build menggunakan `Dockerfile` dan deploy.
   - Migrasi database dijalankan otomatis oleh `docker-entrypoint.sh`.

6. **Generate Domain**
   - Klik **Settings** → **Networking** → **Generate Domain** untuk mendapatkan URL publik.

---

### Opsi 2: Heroku

> ⚠️ **Perhatian:** Heroku sudah tidak menyediakan paket gratis sejak 28 November 2022. Diperlukan kartu kredit/debit yang terhubung ke akun Heroku.

#### Langkah-langkah:

1. **Install Heroku CLI** & login:
   ```bash
   heroku login
   ```

2. **Buat file `Procfile`** di root project (jika belum ada):
   ```
   web: vendor/bin/heroku-php-apache2 public/
   ```

3. **Buat Aplikasi & Set Buildpacks:**
   ```bash
   heroku create nama-app-kamu
   heroku buildpacks:add heroku/nodejs
   heroku buildpacks:add heroku/php
   ```

4. **Tambahkan Database MySQL (JawsDB):**
   ```bash
   heroku addons:create jawsdb:kitefin
   heroku config:get JAWSDB_URL
   ```
   Parse URL yang diberikan (`mysql://user:pass@host:3306/dbname`) lalu set:
   ```bash
   heroku config:set DB_CONNECTION=mysql
   heroku config:set DB_HOST=host-server.com
   heroku config:set DB_PORT=3306
   heroku config:set DB_DATABASE=namadatabasenya
   heroku config:set DB_USERNAME=namauser
   heroku config:set DB_PASSWORD=passwordnya
   ```

5. **Set Environment Laravel:**
   ```bash
   heroku config:set APP_KEY=masukkan_app_key_kamu
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set APP_URL=https://nama-app-kamu.herokuapp.com
   ```

6. **Deploy:**
   ```bash
   git push heroku main
   ```

7. **Migrasi Database:**
   ```bash
   heroku run php artisan migrate --force
   heroku run php artisan db:seed --force    # opsional
   ```

8. **Buka Aplikasi:**
   ```bash
   heroku open
   ```

> **Troubleshooting:** Jalankan `heroku logs --tail` untuk melihat log error.

---

### Opsi 3: cPanel (Shared Hosting)

Cocok untuk hosting murah seperti Anymhost, Niagahoster, Hostinger, dll.

#### Langkah-langkah:

1. **Build aset di lokal:**
   ```bash
   npm run build
   ```

2. **Kompres project** menjadi file `.zip` (tanpa folder `node_modules` dan `.git`).

3. **Export database lokal** dari phpMyAdmin → file `.sql`.

4. **Upload ke cPanel:**
   - Buka **File Manager** di cPanel.
   - Buat folder `inventaris_core` di root (`/home/username/`), sejajar dengan `public_html`.
   - Upload dan extract file `.zip` ke dalam `inventaris_core`.

5. **Pindahkan isi folder `public`:**
   - Pindahkan semua isi `inventaris_core/public/` ke `public_html/`.

6. **Edit `public_html/index.php`:**
   Ubah path autoload dan bootstrap:
   ```php
   require __DIR__.'/../inventaris_core/vendor/autoload.php';
   $app = require_once __DIR__.'/../inventaris_core/bootstrap/app.php';
   ```

7. **Buat database di cPanel:**
   - Buka **MySQL® Databases** → Buat database, user, dan hubungkan keduanya.
   - Buka **phpMyAdmin** → Import file `.sql` yang di-export tadi.

8. **Edit file `.env` di `inventaris_core/`:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://namadomainkamu.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=usercpanel_inventaris
   DB_USERNAME=usercpanel_user
   DB_PASSWORD=password_database
   ```

9. **Buka domain Anda** di browser — aplikasi siap digunakan! 🎉

---

## 📂 Struktur Project

```
inventaris-barang/
├── app/
│   ├── Exports/            # Export logic (PDF & Excel)
│   ├── Http/
│   │   ├── Controllers/    # Controller (Barang, Kategori, Peminjaman, dll)
│   │   ├── Middleware/      # Custom middleware
│   │   └── Requests/        # Form request validation
│   ├── Models/              # Eloquent models (Barang, Kategori, Peminjaman, User)
│   └── View/                # View composers
├── database/
│   ├── migrations/          # Migrasi tabel database
│   └── seeders/             # Data awal (seeder)
├── resources/views/         # Blade templates
│   ├── barang/              # Halaman CRUD barang
│   ├── kategori/            # Halaman CRUD kategori
│   ├── peminjaman/          # Halaman peminjaman & approval
│   ├── laporan/             # Halaman cetak laporan
│   ├── user/                # Halaman manajemen user
│   ├── dashboard.blade.php  # Halaman dashboard
│   └── layouts/             # Layout utama aplikasi
├── routes/
│   ├── web.php              # Route utama aplikasi
│   └── auth.php             # Route autentikasi
├── Dockerfile               # Docker config (untuk Railway/Docker deploy)
├── docker-entrypoint.sh     # Script startup container
├── railway.json             # Konfigurasi Railway
├── composer.json            # Dependensi PHP
└── package.json             # Dependensi Node.js
```

---

## 📄 Lisensi

Project ini dibuat untuk keperluan akademik (Tugas Akhir / Skripsi).

---

<p align="center">
  Dibuat dengan ❤️ menggunakan <a href="https://laravel.com">Laravel</a>
</p>
