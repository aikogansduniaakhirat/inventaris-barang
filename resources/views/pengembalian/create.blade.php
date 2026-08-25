@extends('layouts.app')
@section('title', 'Catat Pengembalian')
@section('page-title', 'Catat Pengembalian')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Catat Pengembalian</h1>
        <p class="page-header-sub">Peminjaman: <code>{{ $peminjaman->kode_peminjaman }}</code></p>
    </div>
    <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2 text-primary"></i>Info Peminjaman</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><th>Barang</th><td>{{ $peminjaman->barang->nama_barang }}</td></tr>
                    <tr><th>Peminjam</th><td>{{ $peminjaman->nama_peminjam }}</td></tr>
                    <tr><th>Jumlah Pinjam</th><td>{{ $peminjaman->jumlah_pinjam }}</td></tr>
                    <tr><th>Sisa</th><td><strong class="text-primary">{{ $peminjaman->sisa_pinjam }}</strong></td></tr>
                    <tr><th>Tgl Pinjam</th><td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td></tr>
                    <tr><th>Rencana Kembali</th><td>{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <form action="{{ route('pengembalian.store') }}" method="POST">
            @csrf
            <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

            <div class="card">
                <div class="card-header"><i class="bi bi-arrow-return-left me-2 text-primary"></i>Form Pengembalian</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Kembali <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_kembali" min="1" max="{{ $peminjaman->sisa_pinjam }}" value="{{ old('jumlah_kembali', $peminjaman->sisa_pinjam) }}" class="form-control" required>
                            <small class="text-muted">Maks: {{ $peminjaman->sisa_pinjam }}</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d')) }}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi_kembali" class="form-select" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-control" placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2 me-1"></i> Simpan Pengembalian
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
