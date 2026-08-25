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
