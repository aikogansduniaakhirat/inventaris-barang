@extends('layouts.app')
@section('title', 'Pilih Peminjaman untuk Dikembalikan')
@section('page-title', 'Pilih Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Pilih Peminjaman</h1>
        <p class="page-header-sub">Pilih peminjaman yang akan diproses pengembaliannya</p>
    </div>
    <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Barang</th>
                    <th>Peminjam</th>
                    <th>Jumlah</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr>
                    <td><code>{{ $p->kode_peminjaman }}</code></td>
                    <td>{{ $p->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->jumlah_pinjam }}</td>
                    <td><strong class="text-primary">{{ $p->sisa_pinjam }}</strong></td>
                    <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                    <td>
                        <a href="{{ route('pengembalian.create', ['peminjaman_id' => $p->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-return-left me-1"></i> Kembalikan
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada peminjaman aktif.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
