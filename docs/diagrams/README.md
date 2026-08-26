# Diagram UML — Revisi Sidang (Final)

Diagram siap tempel ke skripsi. Semua sesuai kaidah UML + catatan dosen penguji (21-08-2026).

## Isi

| No | File | Jenis | Catatan Dosen yang Diakomodasi |
|---|---|---|---|
| 01 | usecase_fish_level | Use Case | ✅ Fish Level Level 0 (boundary, aktor di luar), tanpa include/extend |
| 02 | activity_login | Activity | Swimlane + decision untuk validasi (ada pengujian) |
| 03 | activity_kategori_crud | Activity | ✅ Decision diganti fork node di CRUD (Tambah/Edit), decision hanya di Hapus (ada pengujian relasi) |
| 04 | activity_validasi_peminjaman | Activity | ✅ Hanya 1 initial node (fix "2 start"), fork/join paralel |
| 05 | activity_pengembalian | Activity | ✅ Semua simbol dalam swimlane, 1 decision (cek tanggal) |
| 06 | activity_cetak_laporan | Activity | ✅ Decision disederhanakan jadi 1 (data ditemukan?), fork/join ekspor |
| 07 | sequence_login | Sequence | Sesuai teori: actor → view → controller → model → DB |
| 08 | sequence_ajukan_peminjaman | Sequence | Sama, dengan alt frame stok cukup/tidak |
| 09 | sequence_validasi_peminjaman | Sequence | Sama, alt frame setujui/tolak |
| 10 | sequence_pengembalian | Sequence | Sama, alt frame terlambat/tepat waktu |
| 11 | component_diagram | Component | ✅ BARU — 4 layer MVC + external libraries |
| 12 | erd | ERD | ✅ PK eksplisit (id_users/dst.), bukan "id" |

## Format File

- `*.mmd` — Source Mermaid (editable)
- `renders/*.png` — Gambar raster siap insert ke Word
- `renders/*.svg` — Gambar vektor (tajam di print, recommended untuk skripsi)

## Cara Pakai di Word

1. Insert → Pictures → pilih file PNG/SVG dari `renders/`
2. **Rekomendasi: pakai SVG** (Word 2016+ support) — tajam saat print & zoom
3. Tambahkan caption: "Gambar III.X <Judul Diagram>", sumber "Hasil Rancangan, 2026"

## Cara Edit & Re-render

```bash
# Edit file .mmd sesuai kebutuhan, lalu:
/home/ubuntu/.hermes/hermes-agent/venv/bin/python3 scripts/render_mermaid.py
```

## Pemetaan ke Struktur Skripsi

- BAB III §3.2 → `01_usecase_fish_level`
- BAB III §3.3 → `02` s/d `06` (activity)
- BAB III §3.4 → `07` s/d `10` (sequence)
- BAB III §3.6 (BARU) → `11_component_diagram`
- BAB IV §4.1 → `12_erd`
