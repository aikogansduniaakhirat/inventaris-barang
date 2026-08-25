# REVISI BAB 1 — RUANG LINGKUP (Backend + Frontend)

> **Catatan dosen**: "Perbaiki BAB 1 ruang lingkup (buat point untuk backend dan front end)."
> Sub-bab ini ada di **BAB I §1.6 Ruang Lingkup** pada skripsi.

---

## 1.6 Ruang Lingkup (REVISI)

Untuk membatasi permasalahan agar penelitian lebih terarah, maka ruang lingkup penelitian ini adalah sebagai berikut:

### A. Ruang Lingkup Backend

1. **Stack teknologi server-side:**
   - PHP 8.2+ dengan framework Laravel 12 (berbasis arsitektur Model-View-Controller/MVC)
   - MySQL 8 sebagai Relational Database Management System (RDBMS)
   - Composer sebagai dependency manager PHP
   - Artisan CLI untuk automasi perintah Laravel (migrasi, seeding, queue, dll.)
   - Node.js + npm untuk kompilasi aset frontend dan manajemen paket

2. **Fitur backend:**
   - Autentikasi dan otorisasi berbasis peran (role-based access control) untuk Admin dan Staff/Guru
   - CRUD (Create, Read, Update, Delete) API untuk entitas `users`, `kategoris`, `barangs`, dan `peminjamans`
   - Validasi input sisi server menggunakan FormRequest (Laravel) untuk mencegah SQL injection dan data tidak valid
   - Penyaringan (filtering) dan pengurutan (sorting) data pada kolom-kolom yang diizinkan (whitelist) untuk mencegah SQL injection
   - Pagination untuk membatasi jumlah data yang ditampilkan per halaman
   - Transaksi basis data (DB transaction) untuk menjamin atomicity pada operasi yang memodifikasi lebih dari satu tabel
   - Pencatatan waktu otomatis (`created_at`, `updated_at`) untuk setiap entitas (audit trail)
   - Pengamanan kata sandi menggunakan algoritma hashing Bcrypt
   - Pengelolaan skema basis data melalui mekanisme migration dan seeding
   - Penerapan foreign key constraint dengan opsi `ON DELETE RESTRICT` untuk menjaga integritas referensial data historis

3. **Entitas dan relasi:**
   - Tabel `users` berelasi one-to-many dengan tabel `peminjamans`
   - Tabel `kategoris` berelasi one-to-many dengan tabel `barangs`
   - Tabel `barangs` berelasi one-to-many dengan tabel `peminjamans`

4. **Logika bisnis:**
   - Pembuatan kode unik otomatis dengan format `BRG-TAHUN-XXXX` untuk barang, `PMJ-TAHUN-XXXX` untuk peminjaman, dan `USR-XXX` untuk pengguna
   - Pengurangan otomatis nilai `jumlah_tersedia` ketika barang dipinjam
   - Pembaruan otomatis status peminjaman: `menunggu` → `dipinjam` → `dikembalikan` atau `terlambat` atau `ditolak`
   - Pencatatan otomatis catatan keterlambatan: *"Terlambat X hari dari rencana (dd/mm/yyyy)"* apabila tanggal pengembalian aktual melebihi tanggal rencana

### B. Ruang Lingkup Frontend

1. **Stack teknologi sisi klien:**
   - Blade Template Engine (server-side rendering bawaan Laravel) untuk membangun tampilan antarmuka
   - Bootstrap 5 sebagai framework CSS untuk tata letak (layout) dan komponen antarmuka
   - Bootstrap Icons sebagai pustaka ikon vektor
   - Vanilla JavaScript untuk interaktivitas sisi klien tanpa ketergantungan framework tambahan
   - Mermaid.js untuk pratinjau diagram pada dokumentasi pengembangan

2. **Fitur antarmuka pengguna:**
   - Halaman dasbor (dashboard) dengan ringkasan statistik data untuk Admin dan Staff/Guru
   - Formulir input dengan validasi umpan balik langsung (real-time validation feedback)
   - Tabel data dengan kemampuan pengurutan (sorting) melalui klik header kolom, penyaringan (filtering), dan penomoran halaman (pagination)
   - Modal Bootstrap untuk konfirmasi dan input data tambahan tanpa meninggalkan halaman aktif
   - Penanda status dengan kode warna (badge berwarna hijau, merah, kuning, abu-abu) untuk mempercepat identifikasi visual
   - Tampilan tanggal pengembalian aktual berwarna hijau untuk kondisi tepat waktu dan merah untuk kondisi terlambat
   - Kotak pencarian (search box) dengan pencocokan sebagian (partial match) pada halaman manajemen pengguna
   - Indikator arah pengurutan (ikon ▲/▼) pada header kolom yang sedang diurutkan
   - Pemformatan nilai barang dalam format Rupiah Indonesia
   - Desain responsif yang dapat diakses melalui berbagai perangkat (desktop, tablet, ponsel)

3. **Komponen antarmuka yang digunakan kembali (reusable component):**
   - `<x-sort>` sebagai Blade component untuk kolom tabel yang dapat diurutkan
   - `partials/telepon-handler.blade.php` untuk validasi dan pembatasan karakter pada input nomor telepon secara langsung
   - Sistem pesan notifikasi (toast notification) untuk umpan balik berhasil atau gagal

4. **Antarmuka pengguna berdasarkan peran:**
   - Admin: dasbor, kelola barang, kelola kategori, kelola pengguna, laporan stok, laporan transaksi, validasi peminjaman, cetak laporan
   - Staff/Guru: dasbor, lihat katalog barang, ajukan peminjaman, lihat riwayat peminjaman, lihat detail barang

### C. Batasan Sistem (Di Luar Ruang Lingkup)

1. Sistem yang dibangun hanya berfokus pada pengelolaan data inventaris barang di SMAN 117 Jakarta, meliputi pendataan, pembaruan, pencarian, dan pembuatan laporan inventaris.
2. Sistem informasi yang dirancang berbasis web menggunakan framework Laravel dan digunakan secara internal oleh pihak sekolah (admin/petugas inventaris dan manajemen), tidak mencakup akses publik.
3. Jenis aset yang dikelola dibatasi pada inventaris barang seperti perabot kelas, perangkat komputer, peralatan laboratorium, dan perlengkapan penunjang lainnya, **tidak mencakup pengelolaan tanah dan bangunan secara detail**.
4. **Fitur pengembalian barang hanya mendukung pengembalian penuh (full-return) dalam satu kali proses, tidak mendukung pengembalian sebagian (partial return) maupun pengembalian bertahap (multi-event return).**
5. **Sistem tidak menyediakan notifikasi otomatis melalui surel (email), pesan singkat (SMS), maupun push notification untuk keterlambatan pengembalian.**
6. **Sistem tidak menyediakan aplikasi móvil native (Android/iOS); akses móvil dilakukan melalui peramban web responsif.**
7. **Sistem tidak menyediakan fitur impor/ekspor data massal dari/ke format Excel/CSV, kecuali ekspor laporan yang telah tersedia.**
8. **Sistem tidak dirancang untuk multi-penyewa (multi-tenant) atau multi-lokasi; hanya digunakan untuk satu instansi yaitu SMAN 117 Jakarta.**
9. Pengujian sistem difokuskan pada pengujian fungsional terhadap fitur-fitur utama, seperti input data barang, pengelompokan, pencarian, dan pembuatan laporan inventaris.
10. Penelitian dilakukan pada satu objek studi, yaitu SMAN 117 Jakarta, sehingga hasil penelitian ini belum dapat digeneralisasi untuk seluruh sekolah, tetapi dapat menjadi referensi untuk pengembangan sistem sejenis di instansi pendidikan lainnya.

---

*Bagian ini menggantikan Sub-bab 1.6 Ruang Lingkup yang sebelumnya. Copy-paste ke dokumen Word skripsi dan sesuaikan nomor halaman.*
