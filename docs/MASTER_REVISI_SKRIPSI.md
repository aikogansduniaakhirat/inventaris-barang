# MASTER REVISI SKRIPSI — Semua Catatan Dosen Sidang

> **File panduan**: Revisi lengkap untuk semua catatan dosen (21-08-2026)
> **Cakupan**: BAB I, II, III, IV, V, Daftar Pustaka, Abstrak, Daftar Isi
> **Format**: Narasi siap tempel ke Word + before/after per item
> **Update terakhir**: 26-08-2026

---

## RINGKASAN CATATAN DOSEN

| No | Catatan | BAB/File | Status |
|---|---|---|---|
| 1 | Penulisan abstract/Inggris italic | Abstrak | Perlu revisi |
| 2 | Lengkapi daftar isi | Daftar Isi | Perlu revisi (section baru) |
| 3 | Semua istilah asing italic | Seluruh bab | Perlu revisi besar (80+ istilah) |
| 4 | Kutipan APA style | BAB II–V + Dapus | Perlu revisi (8 inkonsistensi) |
| 5 | Usecase Fish Level | BAB III | ✅ Sudah (commit 7d87f51) |
| 6 | Activity simbol masuk swimlane | BAB III | ✅ Sudah |
| 7 | Activity CRUD fork node, no decision | BAB III | ✅ Sudah |
| 8 | Activity validasi: 1 start only | BAB III | ✅ Sudah |
| 9 | Activity laporan: reduce decision | BAB III | ✅ Sudah |
| 10 | Sequence sesuai teori | BAB III | ✅ Sudah |
| 11 | Component diagram pakai tools UML | BAB III | ✅ Sudah |
| 12 | ERD & LRS redundancy (kode_kategori, password) | BAB IV | ✅ Sudah |
| 13 | BAB 1 ruang lingkup backend+frontend | BAB I §1.6 | ✅ Sudah (commit ddd3990) |

**Total item**: 13 catatan | **Selesai**: 7 (diagram + ruang lingkup) | **Tersisa**: 6 (penulisan + daftar pustaka)

---

## BAGIAN 1: REVISI ABSTRAK (Catatan #1)

### 1.1 Format Abstrak Bahasa Indonesia

**Tetap roman (tegak)**. Tidak perlu diubah.

> **ABSTRAKSI**
>
> [Isi abstrak Bahasa Indonesia...]
>
> Kata kunci: kata1, kata2, kata3, kata4, kata5.

### 1.2 Format Abstract Bahasa Inggris (BARU — bold italic)

> ***ABSTRACT*** *(bold, kapital, di tengah)*
>
> *[Isi abstract Bahasa Inggris — bold italic]*
>
> *Keywords: kata1, kata2, kata3, kata4, kata5.*

**Cara setting di Word**:
1. Buka halaman abstract
2. Block judul "ABSTRACT" + semua isinya
3. Tekan **Ctrl+I** (italic) dan **Ctrl+B** (bold) secara bersamaan
4. Pastikan berbeda dengan abstrak Indonesia (yang roman)

---

## BAGIAN 2: REVISI DAFTAR ISI (Catatan #2)

### 2.1 Section Baru yang Harus Ditambahkan

**Section baru hasil revisi sidang**:

| BAB | Section Baru | Keterangan |
|---|---|---|
| BAB I | **1.6 Ruang Lingkup** | Sudah dipecah jadi A (Backend) + B (Frontend) + C (Batasan) |
| BAB III | **3.6 Component Diagram** | Diagram baru (lihat revisi diagram) |
| BAB IV | **4.1.A Entity Relationship Diagram (ERD)** | Sudah direvisi dengan PK eksplisit |
| BAB IV | **4.1.B Logical Record Structure (LRS)** | Sudah direvisi format standar |

### 2.2 Cara Update Daftar Isi di Word

1. Buka dokumen skripsi
2. Klik di bagian daftar isi
3. **References → Table of Contents → Update Table**
4. Pilih **Update entire table**
5. Word akan otomatis:
   - Tambah section baru (1.6, 3.6)
   - Update nomor halaman
   - Fix entry yang berubah

### 2.3 Format Daftar Isi

```
BAB I  PENDAHULUAN ............................................ 18
   1.1 Latar Belakang .......................................... 18
   1.2 Identifikasi Masalah .................................... 20
   1.3 Perumusan Masalah ....................................... 21
   1.4 Maksud dan Tujuan ....................................... 22
       1.4.1 Maksud Penelitian ................................. 22
       1.4.2 Tujuan Penelitian ................................. 22
   1.5 Metode Penelitian ....................................... 23
       1.5.1 Teknik Pengumpulan Data .......................... 23
       1.5.2 Model Pengembangan Software ...................... 24
   1.6 Ruang Lingkup ........................................... 25 ← BARU (Backend + Frontend)

BAB II  LANDASAN TEORI ........................................ 27
   2.1 Tinjauan Pustaka ........................................ 27
   2.2 ...dst
```

---

## BAGIAN 3: REVISI ITALIC ISTILAH ASING (Catatan #3)

### 3.1 Daftar 80 Istilah Asing Wajib Italic

**KATEGORI A: Framework & Tools (15 istilah)**
| Istilah | Konteks |
|---|---|
| *Framework* | Laravel, CodeIgniter, dll |
| *Blade* | Template engine Laravel |
| *Eloquent* | ORM Laravel |
| *Artisan* | CLI Laravel |
| *Migration* | Versi skema DB |
| *Seeder* | Pengisian data awal |
| *Middleware* | Filter request |
| *Routing* | Penerjemahan URL |
| *Library* | Pustaka kode |
| *DomPDF* | Generator PDF |
| *Maatwebsite/Excel* | Generator Excel |
| *Bcrypt* | Hashing password |
| *Token* | Token autentikasi |
| *Session* | Sesi pengguna |
| *Hashing* | Proses enkripsi |

**KATEGORI B: Arsitektur & Pattern (10 istilah)**
| Istilah | Konteks |
|---|---|
| MVC (*Model-View-Controller*) | Pola Laravel |
| *Class* | OOP |
| *Object* | OOP |
| *Abstract* | Abstrak/OOP |
| *Method* | Fungsi OOP |
| *Function* | Fungsi prosedural |
| *View* | Komponen MVC |
| *Controller* | Komponen MVC |
| *Model* | Komponen MVC |
| *Template* | Berkas template |
| *Engine* | Mesin |
| *Interface* | Kontrak OOP |

**KATEGORI C: UML & Diagram (15 istilah)**
| Istilah | Konteks |
|---|---|
| *Use Case* | Diagram use case |
| *Activity Diagram* | Diagram activity |
| *Sequence Diagram* | Diagram sequence |
| *Class Diagram* | Diagram class |
| *Component Diagram* | Diagram component |
| *Deployment Diagram* | Diagram deployment |
| ERD (*Entity Relationship Diagram*) | Diagram ER |
| LRS (*Logical Record Structure*) | Struktur logis record |
| *Fish Level* | Level use case |
| *Fork Node* | Activity paralel |
| *Join Node* | Activity paralel |
| *Decision Node* | Activity percabangan |
| *Swimlane* | Jalur activity |
| *Pseudocode* | Algoritma |
| *Unified Modeling Language* (UML) | Bahasa pemodelan |

**KATEGORI D: Database (10 istilah)**
| Istilah | Konteks |
|---|---|
| *Database* | Basis data |
| *Query* | Permintaan data |
| *Primary Key* (PK) | Kunci utama |
| *Foreign Key* (FK) | Kunci tamu |
| *Unique Key* (UK) | Kunci unik |
| *Alternate Key* (AK) | Kunci alternatif |
| *Auto Increment* | Properti kolom |
| *Random Access* | Tipe akses file |
| *Relational Database Management System* (RDBMS) | Kategori DBMS |
| *Structured Query Language* (SQL) | Bahasa query |

**KATEGORI E: Web & Internet (15 istilah)**
| Istilah | Konteks |
|---|---|
| *Web* | Aplikasi berbasis web |
| *Website* | Situs web |
| *Online* | Status koneksi |
| *Offline* | Status koneksi |
| *Login* | Autentikasi masuk |
| *Logout* | Autentikasi keluar |
| *Dashboard* | Dasbor |
| *Header* | Bagian atas halaman |
| *Footer* | Bagian bawah halaman |
| *Sidebar* | Bilah samping |
| *Download* | Unduh |
| *Upload* | Unggah |
| *Export* | Ekspor |
| *Import* | Impor |
| *Backup* / *Restore* | Pencadangan |

**KATEGORI F: Testing & Validasi (5 istilah)**
| Istilah | Konteks |
|---|---|
| *Black Box* | Metode pengujian |
| *White Box* | Metode pengujian |
| *Validasi* | Proses cek |
| *Verifikasi* | Proses cek |
| *Notifikasi* | Pemberitahuan |

**KATEGORI G: Bahasa Pemrograman & Standar (10 istilah)**
| Istilah | Konteks |
|---|---|
| *Hypertext Preprocessor* (PHP) | Bahasa PHP |
| *Hypertext Markup Language* (HTML) | Bahasa markup |
| *Cascading Style Sheets* (CSS) | Bahasa gaya |
| *JavaScript* | Bahasa skrip |
| *MySQL* | DBMS |
| *Object-Oriented Programming* (OOP) | Paradigma |
| *Application Programming Interface* (API) | Antarmuka |
| *Single Page Application* (SPA) | Aplikasi |
| *Role-Based Access Control* (RBAC) | Kontrol akses |
| *Search Engine Optimization* (SEO) | Optimasi |

**KATEGORI H: Proses Pengembangan (5 istilah)**
| Istilah | Konteks |
|---|---|
| *Waterfall* | Model pengembangan |
| *Software* | Perangkat lunak |
| *Hardware* | Perangkat keras |
| *CRUD* (*Create, Read, Update, Delete*) | Operasi basis data |
| *Server*-*side* | Sisi server |

**TOTAL: 80 istilah asing wajib italic**

### 3.2 Aturan Pengecualian (TIDAK Italic)

| Pengecualian | Alasan |
|---|---|
| Internet | KBBI (sudah diserap) |
| Komputer | KBBI |
| Aplikasi | KBBI |
| Sistem informasi | Frasa Indonesia |
| Basis data | Frasa Indonesia |
| Daftar | KBBI |
| Komponen | KBBI |
| Modul | KBBI |
| Antarmuka | KBBI |
| Peranti | KBBI |
| Surel | KBBI (untuk e-mail) |

**Aturan nama orang & tempat**:
- ❌ *Hasanah* dan *Untari* (2020) — salah
- ✅ Hasanah dan Untari (2020) — benar

**Aturan judul buku di dalam kalimat** (boleh italic):
- ✅ Hasanah dan Untari (2020) dalam buku *Buku Ajar Rekayasa Perangkat Lunak* menjelaskan...

**Aturan istilah dalam kalimat kapital awal** (kalau di awal kalimat):
- ✅ *Use Case* pada penelitian ini... (kapital di awal)

---

## BAGIAN 4: REVISI KUTIPAN APA (Catatan #4)

### 4.1 Format APA 7th yang Harus Konsisten

**Kutipan dalam kalimat (di awal)**:
```
Hasanah dan Untari (2020) menjelaskan bahwa...
Annisa dkk. (2023) menyatakan bahwa...
Shalahuddin dan Rosa (2016) berpendapat bahwa...
```

**Kutipan di akhir kalimat**:
```
...sesuai dengan teori MVC (Shalahuddin & Rosa, 2016).
...efektivitas pengelolaan barang (Murdani dkk., 2023).
...penelitian sebelumnya (Annisa dkk., 2023; Murdani dkk., 2023).
```

**Kutipan langsung (< 40 kata)**:
```
Menurut Hasanah dan Untari (2020), "Rekayasa perangkat lunak adalah..." (hlm. 15).
```

**Kutipan langsung (> 40 kata) — block quotation**:
```
Hasanah dan Untari (2020) menjelaskan:

Rekayasa perangkat lunak adalah pendekatan sistematis untuk pengembangan
sistem. Tahapan utama meliputi analisis kebutuhan, desain, implementasi,
pengujian, dan pemeliharaan. (hlm. 15)
```

**Kutipan tidak langsung (parafrase)**:
```
Sistem inventaris berbasis web dapat meningkatkan efektivitas pengelolaan
barang di sekolah (Annisa dkk., 2023).
```

### 4.2 8 Inkonsistensi yang Harus Diperbaiki

| # | Kesalahan | Lokasi | Perbaikan |
|---|---|---|---|
| 1 | `Murdani1` (6× kemunculan) | BAB II §2.x + BAB III + Dapus | `Murdani` (hapus angka) |
| 2 | `Shalahuddin, Muhammad;Rosa, A.` | Dapus | `Shalahuddin, M., & Rosa, A.` |
| 3 | Judul buku TIDAK italic | Dapus (24 entri) | Judul buku *italic* |
| 4 | `Algoritma Dan Pemrograman...` kapital salah | Dapus (Munir & Lidya) | `Algoritma dan pemrograman...` (sentence case) |
| 5 | `PHP7` tanpa spasi | Dapus (Sidik, B.) | `PHP 7` |
| 6 | `database MySQL` di tengah judul | Dapus (Sidik, B.) | `Database MySQL` (kapital) |
| 7 | DOI tanpa `https://` | Dapus | `https://doi.org/...` |
| 8 | `Diakses dari` sebelum URL | Dapus | Hapus, langsung URL |

### 4.3 Daftar Pustaka Revisi (24 Entri)

**PERHATIAN**: Daftar di bawah ini adalah versi REVISI yang siap tempel. **Hapus semua entri lama di Word**, ganti dengan yang ini:

**1. Anis dkk. (2024)**
> Anis, Y., Wahyudi, E. N., & Kurniawan, H. C. (2024). Metode *Waterfall* dalam Pengembangan Sistem Inventaris Guna Meningkatkan Efisiensi Manajemen Stok Barang. *Jurnal Teknologi dan Sistem Informasi Bisnis*, *6*(2), 329–338. https://doi.org/10.47233/jteksis.v6i2.1351

**2. Annisa dkk. (2023)**
> Annisa, R., Rahayuningsih, P. A., & Anna, A. (2023). Perancangan Sistem Informasi Inventaris Sarana dan Prasarana Sekolah Berbasis Web. *Infotek: Jurnal Informatika dan Teknologi*, *6*(1), 60–70. https://doi.org/10.29408/jit.v6i1.7356

**3. Arif Rahman dkk. (2026)**
> Arif Rahman, M. K. P. A., & Satriandi. (2026). Rancang Bangun Sistem Inventaris Sekolah Berbasis *Website* Dengan Metode *Waterfall*. *Jurnal Publikasi Ilmu Komputer dan Multimedia*, *5*(1), 62–72. https://doi.org/10.55606/jupikom.v5i1.6146

**4. Christian & Voutama (2024)**
> Christian, C., & Voutama, A. (2024). Inventaris Berbasis *Website*. *Jurnal Informatika dan Teknik Elektro Terapan*, *12*(2), 1500–1509.

**5. Danovella dkk. (2024)**
> Danovella, M., Jarwo, Efendi, A., & Nazarudin, Z. (2024). Implementasi Sistem Informasi Manajemen Inventaris Berbasis *Web* Menggunakan *Framework* Laravel. *Jurnal Aplikasi Sistem dan Teknik Informatika Pomosda (JASTIP)*, *2*(01), 1–8.

**6. Dwi Putri & Andryani (2022)**
> Dwi Putri, R., & Andryani, R. (2022). *Rancang Bangun Sistem Informasi Inventaris Barang pada SMP Negeri 01 Runjung Agung Berbasis Website*. https://doi.org/10.29100/jipi.v7i4.3201

**7. Hasanah & Untari (2020)**
> Hasanah, F. N., & Untari, R. S. (2020). *Buku Ajar Rekayasa Perangkat Lunak*. UMSIDA Press. https://doi.org/10.21070/2020/978-623-6833-89-6

**8. Hidayatullah & Kawistara (2017)**
> Hidayatullah, P., & Kawistara, J. K. (2017). *Pemrograman Web*. Informatika Bandung.

**9. Jamaliyah (2022)**
> Jamaliyah, I. (2022). Perbandingan Metode *Testing* Antara. *8*(2), 105–114.

**10. Kadim dkk. (2023)**
> Kadim, A. A., Hadjaratie, L., & Muthia, M. (2023). Implementasi *Framework* Laravel Dalam Pembuatan Sistem Pencatatan Notula Berbasis *Website*. *J. Sistem Info. Bisnis*, *13*(1), 45–51. https://doi.org/10.21456/vol13iss1pp45-51

**11. Lubis & Ginting (2024)**
> Lubis, A. S., & Ginting, M. P. A. (2024). Pengujian Aplikasi Berbasis *Web* Data SKA Menggunakan Metode *Black Box Testing*. *Cosmic Jurnal Teknik*, *1*(1), 41–48.

**12. Muarif & Tantri (2025)**
> Muarif, A., & Tantri, A. H. (2025). Perancangan Sistem Informasi Inventaris Berbasis *Web* Menggunakan Permodelan UML. *Journal of Information System and Application Development*, *3*(2), 112–118. https://doi.org/10.26905/jisad.v3i2.16127

**13. Munawar (2018)**
> Munawar. (2018). *Analisis Perancangan Sistem Berorientasi Objek dengan UML (Unified Modeling Language)*. Informatika Bandung.

**14. Munir & Lidya (2016)**
> Munir, R., & Lidya, L. (2016). *Algoritma dan Pemrograman dalam Bahasa Pascal, C, dan C++*. Informatika Bandung.

**15. Murdani dkk. (2023)** — HAPUS ANGKA "1"
> Murdani, D., Oktafiani, R. J., & Anggraini, F. (2023). *Sistem Informasi Inventaris Barang Berbasis Web pada SMA Budi Mulia Utama*. Universitas Saintek Muhammadiyah, *9*(2), 24–36. https://doi.org/10.56459/jv.v9i2.71

**16. Perdana dkk. (2025)**
> Perdana, A. L., El Fazza, F., & Agung, A. (2025). *Rancang Bangun Sistem Informasi Inventaris Barang Berbasis Web pada Bengkel Rama Motor Menggunakan Laravel*, 724–732.

**17. Putri dkk. (2023)**
> Putri, N. A., Larasati, P. D., Mulya, M. F., & Anwar, S. (2023). Sistem Informasi Inventaris Barang Berbasis *Web* menggunakan *Codeigniter* pada Pusat Pendidikan dan Pelatihan Pajak (PPPP). *Jurnal SISKOM-KB (Sistem Komputer dan Kecerdasan Buatan)*, *7*(1), 62–72. https://doi.org/10.47970/siskom-kb.v7i1.475

**18. Shalahuddin & Rosa (2016)** — FIX SEPARATOR
> Shalahuddin, M., & Rosa, A. (2016). *Rekayasa Perangkat Lunak: Terstruktur dan Berorientasi Objek*. Informatika Bandung.

**19. Sidik, B. (2020)** — FIX "PHP7" & "database"
> Sidik, B. (2020). *Pemrograman Database MySQL dengan PHP 7*. Informatika Bandung.

**20. Sidik dkk. (2024)**
> Sidik, M. P., Supriatman, A., Ramadhan, T. I., & Ramadhan, T. I. (2024). *Rancang Bangun Sistem Informasi Inventaris Barang Menggunakan Metode Agile di Sekolah Menengah Kejuruan Bina Putera Nusantara*. *Jurnal Informatika dan Teknik Elektro Terapan*, *12*(3). https://doi.org/10.23960/jitet.v12i3.4370

**21. Sinlae dkk. (2024)**
> Sinlae, F., Maulana, I., Setiyansyah, F., & Ihsan, M. (2024). Pengenalan Pemrograman *Web*: Pembuatan Aplikasi *Web* Sederhana Dengan PHP dan MYSQL. *Jurnal Siber Multi Disiplin*, *2*(2), 68–82. https://doi.org/10.38035/jsmd.v2i2.156

**22. Usnaini dkk. (2021)**
> Usnaini, M., Yasin, V., & Sianipar, A. Z. (2021). Perancangan sistem informasi inventarisasi aset berbasis *web* menggunakan metode *waterfall*. *Jurnal Manajemen Informatika Jayakarta*, *1*(1), 36. https://doi.org/10.52362/jmijayakarta.v1i1.415

**23. Wijaya & Beeh (2023)**
> Wijaya, P. H., & Beeh, Y. R. (2023). Perancangan Sistem Informasi Gudang Jamu Semar Berbasis *Web* Menggunakan *Framework* Laravel. *Jurnal Teknik Informatika dan Sistem Informasi*, *10*(1), 2407–4322. https://doi.org/10.35957/jatisi.v10i1.2671

**24. (Tambahan) — Fowler, Pressman, Sommerville** (jika ingin tambahkan referensi standar)
> Fowler, M. (2004). *UML Distilled: A Brief Guide to the Standard Object Modeling Language* (3rd ed.). Addison-Wesley.

> Pressman, R. S., & Maxim, B. R. (2020). *Software Engineering: A Practitioner's Approach* (9th ed.). McGraw-Hill Education.

> Sommerville, I. (2015). *Software Engineering* (10th ed.). Pearson.

---

## BAGIAN 5: REVISI BAB I (Latar Belakang, Rumusan Masalah, Tujuan)

### 5.1 Latar Belakang (BAB I §1.1)

**Sebelum** (sudah ada di skripsi):
> SMAN 117 Jakarta adalah salah satu SMA negeri yang berlokasi di Kecamatan Menteng, Kota Administrasi Jakarta Pusat, Provinsi DKI Jakarta...

**Setelah (revisi minor - tambah istilah italic)**:
> SMAN 117 Jakarta adalah salah satu SMA negeri yang berlokasi di Kecamatan Menteng, Kota Administrasi Jakarta Pusat, Provinsi DKI Jakarta. Sebagai sekolah negeri, SMAN 117 Jakarta memiliki berbagai aset dan inventaris barang seperti meja, kursi, komputer, perangkat laboratorium, peralatan olahraga, serta fasilitas pendukung lainnya yang tersebar di berbagai ruangan dan unit sekolah. Pengelolaan inventaris yang baik dibutuhkan untuk memastikan setiap aset tercatat dengan jelas, terpelihara, dan dapat dipantau kondisinya secara berkala.

> Seiring dengan perkembangan teknologi informasi, penggunaan sistem informasi berbasis *web* menjadi salah satu solusi untuk meningkatkan efektivitas pengelolaan inventaris barang di lingkungan pendidikan. *Framework* Laravel sebagai salah satu *framework* PHP berbasis arsitektur *Model-View-Controller* (MVC) dirancang untuk mempermudah pengembangan aplikasi *web* yang terstruktur, mudah dipelihara, dan aman. Laravel menyediakan berbagai fitur seperti *routing*, *Eloquent* ORM, *Blade* *templating*, dan *Artisan* CLI yang mendukung proses pengembangan sistem inventaris berbasis *web* secara lebih cepat dan terorganisasi.

### 5.2 Identifikasi Masalah (BAB I §1.2)

**Sudah cukup baik**. Tambah istilah italic di beberapa tempat:

**Sebelum**:
> 1. Proses pendataan inventaris barang di sekolah masih berpotensi dilakukan secara manual sehingga rawan terjadi kesalahan pencatatan, duplikasi data, dan kehilangan informasi.

**Setelah**:
> 1. Proses pendataan inventaris barang di sekolah masih berpotensi dilakukan secara manual sehingga rawan terjadi kesalahan pencatatan, duplikasi *database*, dan kehilangan informasi.

### 5.3 Rumusan Masalah (BAB I §1.3)

**Sudah cukup baik**. Tidak perlu revisi besar. Hanya cek istilah asing.

### 5.4 Maksud dan Tujuan (BAB I §1.4)

**Sudah cukup baik**. Cek istilah italic.

### 5.5 Metode Penelitian (BAB I §1.5)

**Tambah**: sub-bab A.5.3 tentang Pengujian Sistem (jika belum ada).

**Sub-bab yang harus ada**:
1. **1.5.1 Teknik Pengumpulan Data** (Observasi, Wawancara, Studi Pustaka) ✅
2. **1.5.2 Model Pengembangan Software** (Waterfall: Analisis, Desain, Code Generation, Testing, Support) ✅
3. **1.5.3 Metode Pengujian** (Black Box Testing) ← **TAMBAHKAN jika belum ada**

**Narasi untuk 1.5.3 (jika belum ada)**:

> **1.5.3 Metode Pengujian**
>
> Metode pengujian yang digunakan dalam penelitian ini adalah *Black Box Testing*. Menurut Lubis dan Ginting (2024), *Black Box Testing* adalah metode pengujian yang berfokus pada fungsionalitas sistem tanpa memperhatikan struktur kode internal. Pengujian dilakukan dengan cara memberikan input tertentu dan memverifikasi apakah output yang dihasilkan sesuai dengan预期 (*expected output*).
>
> Pengujian *Black Box* dilakukan terhadap 15 skenario uji yang mencakup seluruh fitur utama sistem, meliputi: (1) autentikasi pengguna, (2) operasi CRUD pada modul barang, kategori, dan pengguna, (3) alur peminjaman (ajuan, validasi, penolakan), (4) alur pengembalian (kondisi baik, kondisi rusak), (5) ekspor laporan, dan (6) kontrol akses berbasis peran (role). Hasil pengujian secara lengkap disajikan pada BAB IV §4.3.

### 5.6 Ruang Lingkup (BAB I §1.6) — ✅ SUDAH

Lihat file `REVISI_BAB1_RUANG_LINGKUP.md` untuk narasi lengkap. Sudah push.

---

## BAGIAN 6: REVISI BAB II (Landasan Teori)

### 6.1 Sub-bab yang Wajib Ada di BAB II

**Minimal harus ada sub-bab tentang**:

1. **2.1 Tinjauan Pustaka** (Penelitian Terdahulu) ✅
2. **2.2 Sistem Informasi** (Definisi, komponen)
3. **2.3 Inventaris** (Definisi, tujuan)
4. **2.4 Laravel** (Framework, fitur)
5. **2.5 MySQL** (DBMS, fitur)
6. **2.6 Bootstrap** (CSS framework)
7. **2.7 UML** (Use Case, Activity, Sequence, Class, Component, Deployment)
8. **2.8 ERD (Entity Relationship Diagram)** (Notasi Chen, Crow's Foot)
9. **2.9 Black Box Testing** (Metode pengujian)
10. **2.10 Waterfall** (Model pengembangan)

### 6.2 Sub-bab yang Bisa Ditambahkan (Untuk Memperkuat)

11. **2.11 PHP** (Bahasa pemrograman)
12. **2.12 Blade** (Template engine)
13. **2.13 Eloquent ORM** (Object-Relational Mapping)
14. **2.14 Bcrypt** (Hashing algorithm)
15. **2.15 Maatwebsite/Excel** (Export library)
16. **2.16 DomPDF** (PDF library)

### 6.3 Format Penulisan Landasan Teori

**Setiap sub-bab** harus:
1. **Definisi** (paragraf pembuka, sitasi)
2. **Komponen/Fitur** (poin-poin, sitasi)
3. **Kaitannya dengan sistem** (paragraf penutup, 1-2 kalimat)

**Contoh format sub-bab Laravel**:

> **2.4 Laravel**
>
> *Framework* Laravel adalah *framework* PHP sumber terbuka (*open source*) yang dikembangkan oleh Taylor Otwell sejak tahun 2011 dan menggunakan pola arsitektur *Model-View-Controller* (MVC) (Wijaya & Beeh, 2023). Laravel menyediakan berbagai fitur yang mempercepat pengembangan aplikasi *web*, antara lain *routing*, *Eloquent* ORM, *Blade* *template engine*, *middleware*, *migration*, *seeding*, dan *Artisan* CLI.
>
> Pada penelitian ini, Laravel versi 12 digunakan sebagai *framework* utama untuk membangun Sistem Informasi Inventaris Barang karena menyediakan struktur kode yang modular, dokumentasi yang lengkap, serta komunitas yang aktif.

### 6.4 Sub-bab yang Belum Ada di BAB II (Berdasarkan Audit)

Cek apakah sub-bab berikut sudah ada di skripsi lo:
- [ ] 2.2 Definisi Sistem Informasi
- [ ] 2.3 Definisi Inventaris
- [ ] 2.5 MySQL (sub-bab khusus)
- [ ] 2.6 Bootstrap (sub-bab khusus)
- [ ] 2.7 UML (sub-bab khusus — bukan hanya definisi use case)
- [ ] 2.8 ERD (sub-bab khusus)
- [ ] 2.9 Black Box Testing (sub-bab khusus)
- [ ] 2.10 Waterfall (sub-bab khusus)

**Jika ada yang belum**, tambahkan dengan format seperti contoh di 6.3.

---

## BAGIAN 7: REVISI BAB III (Analisis & Perancangan)

### 7.1 Struktur BAB III yang Direkomendasikan

**BAB III ANALISIS DAN PERANCANGAN SISTEM**

1. **3.1 Analisis Sistem Berjalan**
   1.1 Analisis Masalah
   1.2 Analisis Kebutuhan Fungsional
   1.3 Analisis Kebutuhan Non-Fungsional
   1.4 Analisis Pengguna (Aktor)

2. **3.2 Use Case Diagram** ✅
   - Use Case Fish Level (lihat file diagram)
   - Skenario use case (9 tabel)

3. **3.3 Activity Diagram** ✅
   - 6 activity diagram (lihat file diagram)
   - Swimlane admin/staff/sistem

4. **3.4 Sequence Diagram** ✅
   - 4 sequence diagram (lihat file diagram)

5. **3.5 Class Diagram**
   - User, Kategori, Barang, Peminjaman
   - Relasi + method

6. **3.6 Component Diagram** ✅ BARU
   - 4 layer MVC + external (lihat file diagram)

7. **3.7 Deployment Diagram**
   - Server, Client, Browser

8. **3.8 Perancangan Database**
   - 4.1.A ERD ✅
   - 4.1.B LRS ✅
   - 4.1.C Spesifikasi Tabel

9. **3.9 Perancangan Antarmuka**
   - Mockup UI (bisa berupa gambar)

### 7.2 Revisi Sub-bab yang Sudah Ada

**Gambar III.1 sampai III.21** — lihat file `docs/diagrams/renders/`:
- III.1 Use Case Fish Level
- III.2-7 Activity Diagram (6)
- III.8-11 Sequence Diagram (4)
- III.12 Class Diagram (existing)
- III.13 Component Diagram (BARU)
- III.14 Deployment Diagram (existing)
- III.15 ERD (di BAB IV, tapi dirujuk dari sini)
- III.16 LRS (di BAB IV)

**Note**: Jika di skripsi lama penomoran gambar berbeda, **perbarui** dengan penomoran baru.

---

## BAGIAN 8: REVISI BAB IV (Implementasi & Pengujian)

### 8.1 Sub-bab yang Wajib Ada

1. **4.1 Implementasi Database** ✅
   - 4.1.A ERD
   - 4.1.B LRS
   - 4.1.C Spesifikasi Tabel (4 tabel)

2. **4.2 Implementasi Antarmuka**
   - Halaman Login
   - Halaman Dashboard Admin/Staff
   - Halaman CRUD (Kategori, Barang, User)
   - Halaman Peminjaman
   - Halaman Laporan

3. **4.3 Pengujian Sistem** ✅
   - 4.3.1 Metode Pengujian (Black Box)
   - 4.3.2 Skenario Pengujian (15 skenario)
   - 4.3.3 Hasil Pengujian (tabel)

4. **4.4 Support** ✅
   - 4.4.1 Publikasi Software
   - 4.4.2 Spesifikasi Hardware
   - 4.4.3 Spesifikasi Software

### 8.2 Revisi Spesifikasi Tabel (PK Eksplisit)

**Tabel IV.1 Users — REVISI**:

| No | Elemen Data | Field | Tipe | Size | Keterangan |
|---|---|---|---|---|---|
| 1 | Id User | `id_users` | Bigint | - | Primary Key, Auto Increment |
| 2 | Username | `name` | Varchar | 255 | Username unik untuk login |
| 3 | Nama Lengkap | `nama_lengkap` | Varchar | 255 | Nama lengkap pengguna |
| 4 | Email | `email` | Varchar | 255 | Email unik untuk login |
| 5 | Telepon | `telepon` | Varchar | 20 | Nomor telepon |
| 6 | Role | `role` | Enum | - | Level akses: admin/staff |
| 7 | Status Aktif | `is_active` | Boolean | - | Status aktif akun |
| 8 | Password | `password` | Varchar | 255 | Password terenkripsi (bcrypt) |
| 9 | Waktu Pembuatan | `created_at` | Timestamp | - | Otomatis terisi |
| 10 | Waktu Pembaruan | `updated_at` | Timestamp | - | Otomatis terisi |

**Penerapan pola sama untuk tabel `kategoris` (`id_kategoris`), `barangs` (`id_barangs`), `peminjamans` (`id_peminjamans`)**.

### 8.3 Tabel Black Box Testing (15 Skenario) ✅

**Sudah ada di skripsi**. Cek:
- Tabel IV.6 (15 skenario) ✅
- Tabel IV.7 (Spesifikasi Hardware) ✅
- Tabel IV.8 (Spesifikasi Software) ✅

**Cek istilah italic di tabel**:
- "Username" → tetap roman (bukan istilah asing yang wajib italic)
- "Password" → tetap roman
- "Black Box" → *Black Box* (italic)

---

## BAGIAN 9: REVISI BAB V (Penutup)

### 9.1 Kesimpulan (BAB V §5.1)

**Sudah ada, 6 poin**. Tambah 1 poin tentang redundansi:

> 7. Redundansi data pada tabel `users` dan `kategoris` dihindari dengan tidak menyimpan duplikasi field yang sudah terwakili di tabel lain, misalnya `kode_kategori` hanya disimpan di tabel `kategoris` sebagai *unique key* bisnis (bukan *primary key*), dan `password` disimpan di satu tempat dengan hashing Bcrypt.

**Tambah penjelasan teknis singkat**:
- Sistem menggunakan *Eloquent* ORM untuk abstraksi *database*
- Validasi input menggunakan *FormRequest* Laravel (aman dari SQL injection)
- Password di-hash dengan Bcrypt (cost factor 10)
- Foreign key constraint menjaga integritas referensial

### 9.2 Saran (BAB V §5.2)

**Sudah ada, 5 saran**. Cek istilah italic:

**Sebelum**:
> 1. Notifikasi Email/WhatsApp

**Setelah**:
> 1. *Notifikasi* Email/WhatsApp

---

## BAGIAN 10: CHECKLIST FINAL REVISI

| No | Item | Lokasi | Status | Tindakan |
|---|---|---|---|---|
| 1 | Abstract English bold italic | Abstrak | ☐ | Block + Ctrl+B+I |
| 2 | Daftar isi + section baru | Daftar Isi | ☐ | References → Update Table |
| 3 | 80 istilah asing italic | Seluruh bab | ☐ | Find & Replace |
| 4 | 8 inkonsistensi APA | BAB II, III, IV, V, Dapus | ☐ | Lihat Bagian 4 |
| 5 | Daftar Pustaka revisi (24 entri) | BAB Akhir | ☐ | Replace dengan list baru |
| 6 | BAB I §1.6 Backend+Frontend | BAB I | ✅ | Sudah push |
| 7 | BAB III Activity revisi (6 diagram) | BAB III | ✅ | Sudah push |
| 8 | BAB III Sequence revisi (4 diagram) | BAB III | ✅ | Sudah push |
| 9 | BAB III Component Diagram BARU | BAB III | ✅ | Sudah push |
| 10 | BAB IV ERD PK eksplisit | BAB IV | ✅ | Sudah push |
| 11 | BAB IV LRS format standar | BAB IV | ✅ | Sudah push |
| 12 | BAB II tambah sub-bab (jika belum ada) | BAB II | ☐ | Cek 6.4 |
| 13 | BAB I §1.5.3 Metode Pengujian | BAB I | ☐ | Tambah jika belum |
| 14 | BAB V §5.1 tambah 1 poin | BAB V | ☐ | Tambah poin 7 |

---

## CARA PAKAI DI WORD (Step by Step)

### Step 1: Fix Daftar Pustaka (30 menit)
1. Buka halaman daftar pustaka
2. **Hapus semua** entri yang ada
3. **Copy-paste 24 entri revisi** dari Bagian 4.3 di atas
4. Block semua → Paragraph → Hanging indent 1,27 cm
5. Line spacing 1.5

### Step 2: Fix Italic (30 menit)
1. Ctrl+H (Find & Replace)
2. **Find what**: Use Case (atau Activity Diagram, Sequence Diagram, dll)
3. **Replace with**: *Use Case* (Ctrl+I lalu ketik)
4. Replace All
5. Ulangi untuk 80 istilah

### Step 3: Fix Kutipan APA (15 menit)
1. Find: `Murdani1` → Replace: `Murdani`
2. Find: `Shalahuddin, Muhammad;Rosa` → Replace: `Shalahuddin & Rosa`
3. Cek semua `(dkk., YYYY)` — pastikan format konsisten

### Step 4: Fix Abstract (5 menit)
1. Buka abstract
2. Block semua + Ctrl+I + Ctrl+B
3. Bandingkan dengan abstrak Indonesia

### Step 5: Update Daftar Isi (5 menit)
1. References → Update Table
2. Selesai

### Step 6: Tambah Section Baru (30 menit)
- BAB I §1.5.3 (jika belum ada)
- BAB II sub-bab yang hilang
- BAB V §5.1 poin 7

**Total waktu**: ~2 jam

---

## FILE PENDUKUNG

- `REVISI_BAB1_RUANG_LINGKUP.md` — BAB 1.6 Backend + Frontend
- `REVISI_DOSEN_DIAGRAM.md` — Detail revisi diagram + audit
- `REVISI_PENULISAN_DETAIL.md` — Detail revisi penulisan
- `CHECKLIST_REVISI_PENULISAN.md` — Ringkasan checklist
- `docs/diagrams/` — 12 diagram Mermaid + render PNG/SVG

---

*Waktu pengerjaan total: 4-6 jam untuk seluruh revisi. Mulailah dari daftar pustaka karena paling cepat (30 menit), lalu italic, lalu abstract.*
