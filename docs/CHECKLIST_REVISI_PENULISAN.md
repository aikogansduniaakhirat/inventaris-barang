# CHECKLIST CEPAT — Revisi Penulisan Skripsi

> **Tujuan**: Panduan kilat untuk revise penulisan skripsi berdasarkan catatan dosen.
> **Waktu**: ~2-4 jam untuk seluruh item
> **Target**: Skripsi siap sidang ulang

---

## ✅ RANGKUMAN KATA YANG PERLU DIREVISI

### 1. **80 ISTILAH ASING → ITALIC**

**Kategori: Framework & Tools**:
- *Framework*, *Laravel*, *Blade*, *Eloquent*, *Artisan*, *Migration*, *Seeder*, *Middleware*, *Routing*, *Library*, *DomPDF*, *Maatwebsite/Excel*, *Bcrypt*, *Hashing*, *Token*, *Session*, *Cookie*

**Kategori: Arsitektur & Pattern**:
- MVC (*Model-View-Controller*), *Class*, *Object*, *Abstract*, *Method*, *Function*, *View*, *Controller*, *Model*, *Template*, *Engine*, *Interface*

**Kategori: UML & Diagram**:
- *Use Case*, *Activity Diagram*, *Sequence Diagram*, *Class Diagram*, *Component Diagram*, *Deployment Diagram*, ERD (*Entity Relationship Diagram*), LRS (*Logical Record Structure*), *Fish Level*, *Fork Node*, *Join Node*, *Decision Node*, *Swimlane*

**Kategori: Database**:
- *Database*, *Query*, *Primary Key* (PK), *Foreign Key* (FK), *Unique Key* (UK), *Alternate Key* (AK), *Auto Increment*, *Random Access*

**Kategori: Web & Internet**:
- *Web*, *Website*, *Online*, *Offline*, *Login*, *Logout*, *Dashboard*, *Header*, *Footer*, *Sidebar*, *Download*, *Upload*, *Export*, *Import*, *Backup*, *Restore*

**Kategori: Testing & Validasi**:
- *Black Box*, *White Box*, *Validasi*, *Verifikasi*, *Notifikasi*

**Kategori: Bahasa & Standar**:
- *Hypertext Preprocessor* (PHP), *Hypertext Markup Language* (HTML), *Cascading Style Sheets* (CSS), *JavaScript*, *Structured Query Language* (SQL), *MySQL*, *Unified Modeling Language* (UML), *Object-Oriented Programming* (OOP)

**Kategori: Proses Pengembangan**:
- *Waterfall*, *Software*, *Hardware*, *CRUD* (*Create, Read, Update, Delete*), *Role-Based Access Control* (RBAC), *Application Programming Interface* (API), *Single Page Application* (SPA)

---

### 2. **8 KESALAHAN FORMAT KUTIPAN APA**

| # | Kesalahan | Lokasi | Perbaikan |
|---|---|---|---|
| 1 | "Murdani1" (ada angka) | BAB II, III, Daftar Pustaka | → "Murdani" (hapus angka) |
| 2 | "Shalahuddin, Muhammad;Rosa, A." (pakai titik-koma) | Daftar Pustaka | → "Shalahuddin, M., & Rosa, A." (pakai koma + inisial) |
| 3 | Judul buku TIDAK italic | Semua entri daftar pustaka | → Judul buku *italic* |
| 4 | "Algoritma **D**an **P**emrograman **D**alam..." | Daftar Pustaka | → "Algoritma **d**an **p**emrograman **d**alam..." (sentence case, kecuali nama orang) |
| 5 | "PHP7" tanpa spasi | Daftar Pustaka | → "PHP 7" (spasi) |
| 6 | "database MySQL" di tengah judul | Daftar Pustaka | → "Database MySQL" (kapital) |
| 7 | DOI tanpa "https://" | Daftar Pustaka | → "https://doi.org/..." (prefix) |
| 8 | "Diakses dari" sebelum URL | Daftar Pustaka | → Hapus "Diakses dari", langsung URL |

---

### 3. **4 ITEM ABSTRACT & DAFTAR ISI**

| # | Item | Tindakan |
|---|---|---|
| 1 | **Abstract Bahasa Inggris** | Bold + Italic (beda dengan Bahasa Indonesia yang roman) |
| 2 | **Daftar isi** | Tambah section baru: 1.6 (Backend+Frontend), 3.6 (Component Diagram) |
| 3 | **Penomoran halaman** | Romawi kecil (i, ii) untuk halaman awal, Latin (1, 2) untuk BAB I–V |
| 4 | **Caption tabel/gambar** | Konsisten tanpa titik di akhir, tanpa bold |

---

### 4. **2 FORMAT DAFTAR PUSTAKA**

| # | Format | Detail |
|---|---|---|
| 1 | **Hanging indent** | 1,27 cm (0.5 inch) untuk baris kedua+ |
| 2 | **Spasi** | 1.5 atau 2 (sesuai pedoman UBSI) |

---

## 🚀 LANGKAH CEPAT (90 menit)

### Step 1: Fix Daftar Pustaka (30 menit)

```bash
# Download file ini
# (Sudah ada di repo: docs/REVISI_PENULISAN_DETAIL.md)
```

1. Buka file daftar pustaka di Word
2. Sort by alphabet (kalau belum)
3. Untuk SETIAP entri:
   - ✅ Cek format nama: `Nama, A. A.`
   - ✅ Cek separator: `, & ` (koma, spasi, &, spasi) untuk 2 penulis
   - ✅ Cek judul buku/jurnal: harus *italic*
   - ✅ Cek DOI: harus `https://doi.org/...`
   - ✅ Cek "Murdani1" → "Murdani"
4. Set hanging indent 1,27 cm + line spacing 1.5

### Step 2: Fix Italic di Body (30 menit)

1. Buka dokumen skripsi
2. **Find & Replace** untuk istilah yang sering muncul:
   - "Use Case" → "*Use Case*"
   - "Activity Diagram" → "*Activity Diagram*"
   - "Sequence Diagram" → "*Sequence Diagram*"
   - "Framework" → "*Framework*"
   - "Database" → "*Database*"
   - "Waterfall" → "*Waterfall*"
   - "Black Box" → "*Black Box*"
   - "Eloquent" → "*Eloquent*"
   - "Blade" → "*Blade*"
   - "Middleware" → "*Middleware*"
3. Manual cek 80 istilah asing lainnya (lihat list di atas)

### Step 3: Fix Kutipan di Body (15 menit)

1. **Find**: "Murdani1" → **Replace**: "Murdani"
2. Cek format kutipan:
   - `(Murdani dkk., 2023)` → `Murdani dkk. (2023)` di awal kalimat
   - `(Murdani dkk., 2023)` → di akhir kalimat (sudah benar)
3. Cek konsistensi: semua kutipanakhiri dengan tahun + titik di luar kurung

### Step 4: Fix Abstract (10 menit)

1. Buka halaman abstract
2. Block teks "ABSTRACT" + isinya
3. Bold + Italic (Ctrl+B + Ctrl+I)
4. Bandingkan dengan abstrak Bahasa Indonesia (yang roman) — pastikan beda

### Step 5: Update Daftar Isi (5 menit)

1. References → Update Table → Update entire table
2. Cek section baru (1.6, 3.6) muncul
3. Cek nomor halaman benar

---

## 📋 TEMPLATE SITASI APA (Copy-Paste)

**Untuk kutipan dalam kalimat (di awal)**:
```
Hasanah dan Untari (2020) menjelaskan bahwa rekayasa perangkat lunak...
Annisa dkk. (2023) menyatakan bahwa...
```

**Untuk kutipan di akhir kalimat**:
```
...sesuai dengan teori MVC (Shalahuddin & Rosa, 2016).
...efektivitas pengelolaan barang (Murdani dkk., 2023).
```

**Untuk kutipan langsung (< 40 kata)**:
```
Menurut Hasanah dan Untari (2020), "Rekayasa perangkat lunak adalah..." (hlm. 15).
...menurut Fowler (2004), "use case adalah..." (hlm. 24).
```

**Untuk daftar pustaka (buku)**:
```
Nama, A. A. (Tahun). Judul buku (edisi). Penerbit.
→ Shalahuddin, M., & Rosa, A. (2016). Rekayasa Perangkat Lunak: Terstruktur dan Berorientasi Objek. Informatika Bandung.
```

**Untuk daftar pustaka (jurnal)**:
```
Nama, A. A. (Tahun). Judul artikel. Nama Jurnal, volume(nomor), halaman. https://doi.org/xxx
→ Annisa, R., Rahayuningsih, P. A., & Anna, A. (2023). Perancangan Sistem Informasi Inventaris Sarana dan Prasarana Sekolah Berbasis Web. Infotek: Jurnal Informatika dan Teknologi, 6(1), 60–70. https://doi.org/10.29408/jit.v6i1.7356
```

---

## ❌ CONTOH KALAU SALAH (Jangan Ditiru)

**Italic salah (di tengah kalimat)**:
> ❌ *Sistem Informasi Inventaris Barang* adalah sistem yang digunakan untuk...
> ✅ Sistem Informasi Inventaris Barang adalah sistem yang digunakan untuk... (nama sistem, italic hanya untuk istilah asing)

**Italic salah (nama orang)**:
> ❌ *Hasanah* dan *Untari* (2020) menjelaskan...
> ✅ Hasanah dan Untari (2020) menjelaskan... (nama orang TIDAK italic)

**Italic salah (judul buku di tengah kalimat)**:
> ❌ Dalam buku *Buku Ajar Rekayasa Perangkat Lunak*, Hasanah dan Untari (2020) menjelaskan...
> ✅ Dalam buku *Buku Ajar Rekayasa Perangkat Lunak*, Hasanah dan Untari (2020) menjelaskan... (judul buku di tengah kalimat BOLEH italic)

**Kutipan salah (format)**:
> ❌ (Murdani1 dkk., 2023).
> ✅ (Murdani dkk., 2023).

**Kutipan salah (separator)**:
> ❌ (Shalahuddin, Muhammad;Rosa, 2016)
> ✅ (Shalahuddin & Rosa, 2016)

---

## 📂 FILE PENDUKUNG

- `REVISI_PENULISAN_DETAIL.md` — Detail lengkap setiap item revisi
- `ERD_AND_DIAGRAMS.md` — Diagram UML
- `REVISI_BAB1_RUANG_LINGKUP.md` — BAB 1.6 revisi
- `REVISI_DOSEN_DIAGRAM.md` — Diagram revisi + audit redundancy

---

*Waktu pengerjaan: 2-4 jam | Kesulitan: mudah-sedang | Tools: Microsoft Word + Find & Replace*
