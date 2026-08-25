# BAB 1 — Pendahuluan (Outline)

> Catatan dosen: "Perbaiki BAB 1 ruang lingkup (buat point untuk backend dan front end)."

---

## 1.1 Latar Belakang

Sistem inventaris barang di instansi pendidikan masih sering dilakukan secara manual atau menggunakan spreadsheet sederhana. Masalah yang muncul:

- Kesulitan melacak barang yang sedang dipinjam
- Tidak ada pencatatan tanggal kembali yang jelas
- Laporan stok dan riwayat peminjaman sulit dibuat real-time
- Tidak ada audit trail untuk perubahan data

**Sistem Inventaris Barang** dikembangkan untuk menjawab masalah tersebut. Sistem ini berbasis web dengan arsitektur **client-server**, dibangun menggunakan framework **Laravel 11** (PHP 8.3) dan database **MySQL**.

## 1.2 Rumusan Masalah

1. Bagaimana merancang sistem inventaris barang yang dapat mencatat peminjaman dan pengembalian secara real-time?
2. Bagaimana menyediakan fitur pelaporan stok barang dan riwayat peminjaman yang dapat di-filter dan di-sort?
3. Bagaimana memisahkan hak akses antara admin dan staff?
4. Bagaimana menangani kasus peminjaman yang terlambat dikembalikan?

## 1.3 Tujuan

1. Mengimplementasikan sistem inventaris barang berbasis web
2. Menyediakan fitur CRUD untuk data master (barang, kategori, user)
3. Menyediakan fitur pencatatan transaksi peminjaman dan pengembalian
4. Menyediakan fitur laporan yang dapat di-filter dan di-sort
5. Mengimplementasikan notifikasi untuk peminjaman terlambat

## 1.4 Ruang Lingkup

> **Catatan dosen**: ruang lingkup harus dipisah menjadi 2 point — **backend** dan **frontend**.

### A. Ruang Lingkup (Backend)

1. **Stack teknologi**:
   - PHP 8.3 + Laravel 11 (MVC pattern)
   - MySQL 8 (relational database)
   - Composer (dependency manager)
   - Artisan CLI (Laravel command-line)

2. **Fitur backend**:
   - Autentikasi & otorisasi (role-based: admin, staff)
   - CRUD API untuk entitas `users`, `kategoris`, `barangs`, `peminjamans`
   - Validasi input (FormRequest + regex pattern)
   - Sorting & filtering (whitelist kolom untuk anti SQL injection)
   - Pagination (15 baris per halaman)
   - Transaksi database (`DB::transaction` untuk atomicity)
   - Audit log (created_at, updated_at)
   - Hashing password (bcrypt)
   - Migration & seeder (database schema versioning)
   - Foreign key constraint dengan `onDelete('restrict')`

3. **Entitas & relasi**:
   - `users` → `peminjamans` (1:N)
   - `kategoris` → `barangs` (1:N)
   - `barangs` → `peminjamans` (1:N)

4. **Logika bisnis**:
   - Generate kode otomatis: `BRG-YYYY-XXXX`, `PMJ-YYYY-XXXX`, `USR-XXX`
   - Auto-decrement `jumlah_tersedia` saat barang dipinjam
   - Auto-update status: `menunggu` → `dipinjam` → `dikembalikan`/`terlambat`
   - Catatan otomatis: "Terlambat X hari dari rencana (dd/mm/yyyy)"

### B. Ruang Lingkup (Frontend)

1. **Stack teknologi**:
   - Blade Template Engine (server-side rendering)
   - Bootstrap 5 (CSS framework)
   - Bootstrap Icons (icon library)
   - Vanilla JavaScript (interactivity)
   - Mermaid.js (diagram preview di dokumentasi)

2. **Fitur frontend**:
   - Halaman dashboard dengan ringkasan data
   - Form input dengan validasi real-time
   - Tabel data dengan sorting (klik header kolom) + filtering + pagination
   - Modal untuk konfirmasi / input data
   - Badge status dengan warna (hijau/merah/kuning)
   - Tampilan tanggal kembali: hijau (tepat waktu) / merah (terlambat)
   - Search box (partial match) di halaman manajemen user
   - Sort indicator (▲/▼) di header kolom yang aktif di-sort
   - Format rupiah untuk kolom nilai barang
   - Responsive design (mobile-friendly)

3. **Komponen reusable**:
   - `<x-sort>` Blade component — kolom sortable
   - `partials/telepon-handler.blade.php` — strip karakter non-angka real-time
   - `partials/audit-info.blade.php` — info created_at/updated_at

4. **User interface**:
   - Sidebar navigation dengan role-based menu
   - Toast notification (success/error message)
   - Form validation feedback (is-invalid class)
   - Empty state message (untuk tabel kosong)
   - Loading indicator (built-in Laravel pagination)

### C. Batasan Sistem (Di luar ruang lingkup)

- **Tidak ada** integrasi payment gateway
- **Tidak ada** notifikasi real-time (email/SMS/push notification)
- **Tidak ada** mobile app (khususnya Android/iOS) — hanya web responsive
- **Tidak ada** import/export data dari Excel/CSV (planned future feature)
- **Tidak ada** multi-tenant / multi-lokasi
- **Tidak ada** backup otomatis database (manual via phpMyAdmin)
- **Pengembalian**: full return only (tidak ada partial return / cicilan pengembalian)

---

## 1.5 Metodologi

Metodologi pengembangan: **Waterfall** (sequential)

1. **Analisis kebutuhan** — identifikasi aktor, use case, requirement
2. **Perancangan** — ERD, LRS, use case, activity, sequence, class diagram
3. **Implementasi** — coding (Laravel + Blade + MySQL)
4. **Pengujian** — black-box testing (functional), white-box (unit test)
5. **Pemeliharaan** — bug fix, enhancement

## 1.6 Sistematika Penulisan

- **BAB 1** — Pendahuluan (dokumen ini)
- **BAB 2** — Tinjauan Pustaka (landasan teori)
- **BAB 3** — Analisis & Perancangan (diagram UML, ERD, LRS)
- **BAB 4** — Implementasi & Pengujian
- **BAB 5** — Kesimpulan & Saran

---

*Outline ini bisa langsung di-copy ke dokumen Word skripsi BAB 1.*
