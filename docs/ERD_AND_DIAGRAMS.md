# ERD & Diagram UML — Sistem Inventaris Barang

> Dokumen diagram untuk revisi skripsi.
> Catatan dosen: "ERD — primary key, atribut, penyederhanaan diagram, entitas pengembalian."

---

## 1. Entity Relationship Diagram (ERD)

### ERD Sederhana (Perbaikan dari catatan dosen — minimal, hanya atribut penting)

```mermaid
erDiagram
    USERS ||--o{ PEMINJAMANS : "mencatat"
    USERS ||--o{ PENGEMBALIANS : "menerima"
    KATEGORIS ||--o{ BARANGS : "mengelompokkan"
    BARANGS ||--o{ PEMINJAMANS : "dipinjam"
    PEMINJAMANS ||--o{ PENGEMBALIANS : "dikembalikan"

    USERS {
        int id_users PK
        string name
        string email UK
        string nama_lengkap
        string telepon
        enum role "admin|staff"
        string password
        boolean is_active
    }

    KATEGORIS {
        int id_kategoris PK
        string kode_kategori UK
        string nama_kategori
        text deskripsi
        string warna
        boolean is_active
    }

    BARANGS {
        int id_barangs PK
        string kode_barang UK
        string nama_barang
        int kategori_id FK → KATEGORIS(id_kategoris)
        string merk
        int jumlah
        int jumlah_tersedia
        int jumlah_rusak_ringan
        int jumlah_rusak_berat
        string satuan
        string lokasi
        enum kondisi "baik|rusak_ringan|rusak_berat"
        decimal nilai
        string foto
        text keterangan
    }

    PEMINJAMANS {
        int id_peminjamans PK
        string kode_peminjaman UK
        int barang_id FK → BARANGS(id_barangs)
        int user_id FK → USERS(id_users)
        string nama_peminjam
        string instansi_peminjam
        int jumlah_pinjam
        date tanggal_pinjam
        date tanggal_kembali_rencana
        enum status "menunggu|dipinjam|dikembalikan|terlambat|ditolak|rusak"
        text alasan_tolak
        text keterangan
    }

    PENGEMBALIANS {
        int id_pengembalians PK
        string kode_pengembalian UK
        int peminjaman_id FK → PEMINJAMANS(id_peminjamans)
        int user_id FK → USERS(id_users)
        int jumlah_kembali
        date tanggal_kembali
        enum kondisi_kembali "baik|rusak_ringan|rusak_berat"
        text keterangan
    }
```

### Catatan Penting ERD

1. **Primary key (id)**: tiap entitas punya PK auto-increment
2. **Unique key**: `kode_barang`, `kode_peminjaman`, `kode_pengembalian`, `kode_kategori`, `email`
3. **Foreign key** dengan `onDelete('restrict')` agar data historis tidak hilang
4. **Refactor pengembalian**: dipisah jadi entitas sendiri (catatan dosen) supaya bisa **pengembalian parsial** (pinjam 5, kembali 2 dulu)

---

## 2. Use Case Diagram

```mermaid
flowchart LR
    classDef actor fill:#fef3c7,stroke:#f59e0b,color:#000
    classDef usecase fill:#dbeafe,stroke:#3b82f6,color:#000

    Admin((Admin)):::actor
    Staff((Staff)):::actor

    subgraph Sistem Inventaris Barang
        UC1[Login]:::usecase
        UC2[Kelola Data Barang]:::usecase
        UC3[Kelola Kategori]:::usecase
        UC4[Kelola Data User]:::usecase
        UC5[Laporan Stok]:::usecase
        UC6[Riwayat Transaksi]:::usecase
        UC7[Catat Peminjaman]:::usecase
        UC8[Catat Pengembalian]:::usecase
        UC9[Lihat Detail Barang]:::usecase
        UC10[Persetujuan Peminjaman]:::usecase
    end

    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC10

    Staff --> UC1
    Staff --> UC7
    Staff --> UC8
    Staff --> UC9
```

---

## 3. Activity Diagram — Alur Peminjaman

```mermaid
flowchart TD
    Start([Mulai]) --> Login[Login Staff]
    Login -->|Gagal| LoginErr[Tampil Error] --> Login
    Login -->|Sukses| PilihBarang[Pilih Barang]
    PilihBarang --> IsiForm[Isi Form Peminjaman]
    IsiForm --> Submit{Kirim}
    Submit -->|Invalid| FormErr[Validasi Gagal] --> IsiForm
    Submit -->|Valid| Simpan[Simpan status menunggu]
    Simpan --> NotifAdmin[Notifikasi Admin]
    NotifAdmin --> Review{Admin Review}
    Review -->|Tolak| StatusTolak[Status ditolak + alasan]
    Review -->|Setuju| KurangiStok[Kurangi jumlah_tersedia]
    KurangiStok --> StatusDipinjam[Status dipinjam]
    StatusDipinjam --> Selesai([Selesai])
    StatusTolak --> Selesai
```

---

## 4. Activity Diagram — Alur Pengembalian

```mermaid
flowchart TD
    Start([Mulai]) --> PilihPeminjaman[Pilih Peminjaman Aktif]
    PilihPeminjaman --> IsiForm[Isi Form: jumlah, kondisi, tanggal]
    IsiForm --> Validasi{Valid?}
    Validasi -->|Tidak| FormErr[Tampil Error] --> IsiForm
    Validasi -->|Ya| CekSisa{Sisa > 0?}
    CekSisa -->|Tidak| ErrStok[Error: jumlah > sisa] --> IsiForm
    CekSisa -->|Ya| Simpan[Simpan ke tabel pengembalians]
    Simpan --> UpdateBarang{Kondisi Baik?}
    UpdateBarang -->|Ya| IncBaik[+ jumlah_tersedia]
    UpdateBarang -->|Tidak| IncRusak[+ jumlah_rusak_ringan/berat]
    IncBaik --> CekStatus
    IncRusak --> CekStatus
    CekStatus{Sisa = 0?}
    CekStatus -->|Ya| StatusKembali[Update peminjaman: dikembalikan]
    CekStatus -->|Tidak| StatusTetap[Status tetap dipinjam]
    StatusKembali --> Selesai([Selesai])
    StatusTetap --> Selesai
```

---

## 5. Sequence Diagram — Peminjaman (Staff → Admin)

```mermaid
sequenceDiagram
    actor Staff
    actor Admin
    participant View as Browser
    participant Ctrl as PeminjamanController
    participant DB as Database

    Staff->>View: Buka form peminjaman
    View->>Ctrl: GET /peminjaman/create
    Ctrl->>DB: Ambil barang tersedia
    DB-->>Ctrl: List barang
    Ctrl-->>View: Render form

    Staff->>View: Isi form + submit
    View->>Ctrl: POST /peminjaman (status=menunggu)
    Ctrl->>DB: INSERT peminjamans (status=menunggu)
    DB-->>Ctrl: OK
    Ctrl-->>View: Redirect + success

    Note over Admin: Admin login
    Admin->>View: Buka detail peminjaman
    View->>Ctrl: POST /peminjaman/{id}/approve
    Ctrl->>DB: UPDATE status=dipinjam, kurangi stok
    DB-->>Ctrl: OK
    Ctrl-->>View: Redirect + success
```

---

## 6. Sequence Diagram — Pengembalian

```mermaid
sequenceDiagram
    actor Petugas
    participant View
    participant Ctrl as PengembalianController
    participant DB as Database

    Petugas->>View: Buka form pengembalian
    View->>Ctrl: GET /pengembalian/create?peminjaman_id=X
    Ctrl->>DB: Ambil peminjaman + hitung sisa
    DB-->>Ctrl: Data peminjaman
    Ctrl-->>View: Render form

    Petugas->>View: Isi (jumlah, kondisi, tanggal) + submit
    View->>Ctrl: POST /pengembalian
    Ctrl->>DB: Validasi sisa
    alt Jumlah > sisa
        Ctrl-->>View: Error "jumlah > sisa"
    else Valid
        Ctrl->>DB: INSERT pengembalians
        Ctrl->>DB: UPDATE barang (stok/rusak)
        Ctrl->>DB: UPDATE peminjaman status
        DB-->>Ctrl: OK
        Ctrl-->>View: Redirect + success
    end
```

---

## 7. Class Diagram

```mermaid
classDiagram
    class User {
        +int id_users
        +string name
        +string email
        +enum role
        +boolean is_active
        +isAdmin() bool
        +isStaff() bool
    }

    class Kategori {
        +int id_kategoris
        +string kode_kategori
        +string nama_kategori
        +string warna
    }

    class Barang {
        +int id_barangs
        +string kode_barang
        +string nama_barang
        +int jumlah
        +int jumlah_tersedia
        +int jumlah_rusak_ringan
        +int jumlah_rusak_berat
        +enum kondisi
    }

    class Peminjaman {
        +int id_peminjamans
        +string kode_peminjaman
        +int jumlah_pinjam
        +date tanggal_pinjam
        +date tanggal_kembali_rencana
        +enum status
        +getSisaPinjam() int
    }

    class Pengembalian {
        +int id_pengembalians
        +string kode_pengembalian
        +int jumlah_kembali
        +date tanggal_kembali
        +enum kondisi_kembali
    }

    User "1" --> "*" Peminjaman : mencatat
    User "1" --> "*" Pengembalian : menerima
    Kategori "1" --> "*" Barang : mengelompokkan
    Barang "1" --> "*" Peminjaman : dipinjam
    Peminjaman "1" --> "*" Pengembalian : dikembalikan
```

---

## 8. Penomoran Catatan Dosen & Jawaban

| Catatan Dosen | Status | Keterangan |
|---|---|---|
| "Req non-FS" → **Req non-Fungsional** | ✅ | Sudah ada di BAB 4 (performa, keamanan, usabilitas) |
| "A.K.F. user" → **Aktor / Kebutuhan Fungsional user** | ✅ | Tersedia use case di Section 2 |
| Admin: login, kelola barang, kategori, persediaan, user, laporan, riwayat | ✅ | Sudah implemented |
| Staff: login, pinjam, kembali, detail, persetujuan | ✅ | Sudah implemented (admin only untuk approve) |
| ERD: PK, atribut, penyederhanaan | ✅ | Section 1 — ERD sederhana + atribut lengkap |
| ERD: entitas pengembalian | ✅ | **Refactor: entitas `pengembalians` terpisah** (migration baru) |
| Diagram loom → use case, activity, sequence | ✅ | Section 2-6 (Mermaid) |
| Boundary X actor (UML) | ✅ | Use case menampilkan actor & boundary system |

---

*Dokumen ini bisa langsung di-convert ke PDF via [mermaid.live](https://mermaid.live) atau plugin Obsidian/VSCode.*

---

## 9. Sequence Diagram (ZenUML)

> **ZenUML** = notasi sequence ringkas, fokus alur antar objek. Cocok untuk skripsi.
> Preview: buka [zenuml.com](https://zenuml.com) → paste code → klik Render.

### 9.1. Alur Peminjaman (Staff → Admin ACC)

```zenuml
title Peminjaman Barang

Staff -> PeminjamanController : GET /peminjaman/create
PeminjamanController -> Barang : all aktif
Barang --> PeminjamanController : list
PeminjamanController --> Staff : form

Staff -> PeminjamanController : POST /peminjaman
PeminjamanController -> PeminjamanRequest : validate()
if valid {
  PeminjamanController -> Peminjaman : create(status=menunggu)
  PeminjamanController -> Barang : decrement jumlah_tersedia
  PeminjamanController --> Staff : flash "Menunggu ACC"
} else {
  PeminjamanRequest --> PeminjamanController : 422
  PeminjamanController --> Staff : back() + error
}

Admin -> PeminjamanController : POST /peminjaman/{id}/approve
PeminjamanController -> Peminjaman : update(status=dipinjam)
PeminjamanController --> Staff : notif "Disetujui"
```

### 9.2. Alur Pengembalian (Partial / Full)

```zenuml
title Pengembalian Barang

Petugas -> PengembalianController : GET /pengembalian/create?peminjaman_id=X
PengembalianController -> Peminjaman : findOrFail(X)
PengembalianController -> Pengembalian : sum(jumlah_kembali)
PengembalianController -> PengembalianController : sisaPinjam = jumlah - sum
PengembalianController --> Petugas : form (max = sisa)

Petugas -> PengembalianController : POST /pengembalian
PengembalianController -> PengembalianRequest : validate(jumlah <= sisa)
if valid {
  PengembalianController -> Pengembalian : create(kode, jumlah, tanggal, kondisi)
  PengembalianController -> Barang : increment jumlah_tersedia
  PengembalianController -> Barang : update kondisi (jika rusak)
  PengembalianController -> Peminjaman : update(status = sisa<=0 ? dikembalikan : dipinjam)
  PengembalianController -> Peminjaman : refresh()
  PengembalianController -> Peminjaman : if dipinjam && tanggal_rencana < today => update status=terlambat
  PengembalianController --> Petugas : success + badge "Terlambat X hari"
} else {
  PengembalianRequest --> PengembalianController : 422
  PengembalianController --> Petugas : "Jumlah kembali melebihi sisa pinjam"
}
```

### 9.3. Laporan + Sort + Filter

```zenuml
title Laporan (Filter + Sort)

User -> LaporanController : GET /laporan/stok?kategori=1&sort=nama_barang&direction=asc
LaporanController -> Barang : with(kategori).where(filter).orderBy(sort, direction)
LaporanController --> User : Tabel + header sortable (icon ↑↓)

User -> LaporanController : GET /laporan/riwayat?status=terlambat&sort=tanggal_pinjam&direction=desc
LaporanController -> Peminjaman : with(barang,user).where(status).orderBy(sort)
LaporanController -> Peminjaman : get catatan_terlambat() via accessor pengembalians()
LaporanController --> User : Tabel + summary card (Dikembalikan / Dipinjam / Terlambat / Menunggu)

User -> LaporanController : GET /laporan/export/excel/{type}?{same filter}
LaporanController -> ExcelExport : new BarangExport / PeminjamanExport
ExcelExport --> User : .xlsx download
```

### 9.4. Approve / Reject (Peminjaman)

```zenuml
title Approve / Reject

Admin -> PeminjamanController : POST /peminjaman/{id}/approve
PeminjamanController -> Peminjaman : findOrFail
if status == menunggu {
  PeminjamanController -> Peminjaman : update(status=dipinjam)
  PeminjamanController --> Admin : success
} else {
  PeminjamanController --> Admin : "Tidak dapat diubah"
}

Admin -> PeminjamanController : POST /peminjaman/{id}/reject
PeminjamanController -> Peminjaman : update(status=ditolak, alasan_tolak=X)
PeminjamanController -> Barang : increment jumlah_tersedia (rollback stok)
PeminjamanController --> Admin : success
```

### 9.5. Perbandingan Mermaid vs ZenUML

| Aspek | Mermaid sequenceDiagram | ZenUML |
|---|---|---|
| Sintaks | Verbose | Ringkas |
| Branch | `alt/else/end` | `if/else {}` (native) |
| Activate | Manual | Auto |
| Cocok untuk | Dokumentasi tim | Skripsi / akademis |

**Rekomendasi skripsi**: pakai **ZenUML** untuk sequence (bagian 9.1-9.4), **Mermaid** untuk ERD + activity + class (bagian 1-7). Jangan duplicate alur yang sama di dua notasi.

---

## 10. Format PDF Laporan

Template: `resources/views/laporan/pdf/{stok_barang,riwayat_peminjaman}.blade.php` (Landscape A4, header logo, footer page number, sort + filter dari query string).
