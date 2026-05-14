@extends('layouts.app')
@section('title', 'Detail Peminjaman: '.$peminjaman->kode_peminjaman)
@section('page-title', 'Detail Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">{{ $peminjaman->kode_peminjaman }}</h1>
        <p class="page-header-sub">Detail transaksi peminjaman barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        @if(in_array($peminjaman->status, ['dipinjam','terlambat']))
        <a href="{{ route('peminjaman.form-pengembalian', $peminjaman) }}" class="btn btn-success">
            <i class="bi bi-arrow-return-left me-1"></i> Proses Pengembalian
        </a>
        @endif

        {{-- Tombol Approve & Reject (Admin only, status menunggu) --}}
        @if(auth()->user()->isAdmin() && $peminjaman->status === 'menunggu')
        <form action="{{ route('peminjaman.approve', $peminjaman) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success"
                onclick="return confirm('Setujui permintaan peminjaman ini? Stok barang akan dikurangi.')">
                <i class="bi bi-check-circle me-1"></i> Setujui
            </button>
        </form>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
            <i class="bi bi-x-circle me-1"></i> Tolak
        </button>
        @endif
    </div>
</div>

{{-- Banner: Menunggu Persetujuan --}}
@if($peminjaman->status === 'menunggu')
<div class="alert alert-warning d-flex align-items-center gap-3 mb-3">
    <i class="bi bi-hourglass-split fs-4"></i>
    <div>
        <strong>Menunggu Persetujuan Admin</strong>
        <div class="text-muted" style="font-size:0.875rem;">Permintaan peminjaman ini belum disetujui. Stok barang belum dikurangi.</div>
    </div>
</div>
@endif

{{-- Banner: Ditolak --}}
@if($peminjaman->status === 'ditolak')
<div class="alert alert-danger d-flex align-items-center gap-3 mb-3">
    <i class="bi bi-x-circle fs-4"></i>
    <div>
        <strong>Permintaan Ditolak</strong>
        @if($peminjaman->alasan_tolak)
        <div class="text-muted" style="font-size:0.875rem;">Alasan: {{ $peminjaman->alasan_tolak }}</div>
        @endif
    </div>
</div>
@endif

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-text me-2 text-primary"></i>Informasi Peminjaman</span>
                @if($peminjaman->status === 'menunggu')
                    <span class="badge bg-warning text-dark fs-6"><i class="bi bi-hourglass-split me-1"></i>Menunggu ACC</span>
                @elseif($peminjaman->status === 'ditolak')
                    <span class="badge bg-danger fs-6"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                @else
                    <span class="badge bg-{{ $peminjaman->status_badge }} fs-6">{{ $peminjaman->status_label }}</span>
                @endif
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Barang Dipinjam</label>
                        <a href="{{ route('barang.show', $peminjaman->barang) }}" class="fw-semibold text-decoration-none">
                            {{ $peminjaman->barang->nama_barang }}
                        </a>
                        <div class="text-muted-sm">{{ $peminjaman->barang->kode_barang }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Jumlah Dipinjam</label>
                        <span class="fw-semibold fs-5">{{ $peminjaman->jumlah_pinjam }} {{ $peminjaman->barang->satuan }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Tanggal Pinjam</label>
                        <span class="fw-semibold">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Tanggal Kembali (Rencana)</label>
                        <span class="fw-semibold {{ $peminjaman->is_terlambat ? 'text-danger' : '' }}">
                            {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}
                            @if($peminjaman->is_terlambat)
                            <span class="badge bg-danger ms-1">Terlambat {{ abs($peminjaman->sisa_hari) }} hari</span>
                            @endif
                        </span>
                    </div>
                    @if($peminjaman->tanggal_kembali_aktual)
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Tanggal Kembali (Aktual)</label>
                        <span class="fw-semibold text-success">{{ $peminjaman->tanggal_kembali_aktual->format('d F Y') }}</span>
                    </div>
                    @endif
                    @if($peminjaman->kondisi_kembali)
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Kondisi Saat Kembali</label>
                        <span class="badge bg-{{ match($peminjaman->kondisi_kembali) { 'baik'=>'success', 'rusak_ringan'=>'warning', 'rusak_berat'=>'danger' } }}">
                            {{ ucwords(str_replace('_',' ',$peminjaman->kondisi_kembali)) }}
                        </span>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <label class="text-muted-sm d-block">Diajukan Oleh</label>
                        <span>{{ $peminjaman->user->display_name }}</span>
                    </div>
                    @if($peminjaman->keterangan)
                    <div class="col-12">
                        <label class="text-muted-sm d-block">Keterangan</label>
                        <p class="mb-0">{{ $peminjaman->keterangan }}</p>
                    </div>
                    @endif
                    @if($peminjaman->keterangan_kembali)
                    <div class="col-12">
                        <label class="text-muted-sm d-block">Keterangan Pengembalian</label>
                        <p class="mb-0">{{ $peminjaman->keterangan_kembali }}</p>
                    </div>
                    @endif
                    @if($peminjaman->alasan_tolak)
                    <div class="col-12">
                        <label class="text-muted-sm d-block text-danger">Alasan Penolakan</label>
                        <p class="mb-0 text-danger">{{ $peminjaman->alasan_tolak }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Data Peminjam</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted-sm d-block">Nama Peminjam</label>
                    <span class="fw-semibold fs-6">{{ $peminjaman->nama_peminjam }}</span>
                </div>
                @if($peminjaman->instansi_peminjam)
                <div>
                    <label class="text-muted-sm d-block">Kelas / Jabatan</label>
                    <span>{{ $peminjaman->instansi_peminjam }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
@if(auth()->user()->isAdmin() && $peminjaman->status === 'menunggu')
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('peminjaman.reject', $peminjaman) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-x-circle text-danger me-2"></i>Tolak Permintaan Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        Permintaan peminjaman <strong>{{ $peminjaman->kode_peminjaman }}</strong> oleh
                        <strong>{{ $peminjaman->nama_peminjam }}</strong> akan ditolak.
                    </p>
                    <label class="form-label">Alasan Penolakan <span class="text-muted">(opsional)</span></label>
                    <textarea name="alasan_tolak" class="form-control" rows="3"
                              placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
