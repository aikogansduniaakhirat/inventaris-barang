@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Laporan Riwayat Peminjaman')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Riwayat Peminjaman</h1>
        <p class="page-header-sub">Rekap seluruh transaksi peminjaman barang</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.export.excel.peminjaman', request()->query()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Excel
        </a>
        <a href="{{ route('laporan.export.pdf.peminjaman', request()->query()) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i> PDF
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="dipinjam" {{ request('status')=='dipinjam'?'selected':'' }}>Dipinjam</option>
                    <option value="terlambat" {{ request('status')=='terlambat'?'selected':'' }}>Terlambat</option>
                    <option value="dikembalikan" {{ request('status')=='dikembalikan'?'selected':'' }}>Dikembalikan</option>
                    <option value="menunggu" {{ request('status')=='menunggu'?'selected':'' }}>Menunggu ACC</option>
                    <option value="ditolak" {{ request('status')=='ditolak'?'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('laporan.riwayat-peminjaman') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
            @if(request('sort'))<input type="hidden" name="sort"      value="{{ request('sort') }}">@endif
            @if(request('direction'))<input type="hidden" name="direction" value="{{ request('direction') }}">@endif
        </form>
    </div>
</div>

<!-- Summary cards -->
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon emerald"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Dikembalikan</div>
                <div class="stat-card-value">{{ $peminjamans->where('status', 'dikembalikan')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon sky"><i class="bi bi-arrow-left-right"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Dipinjam</div>
                <div class="stat-card-value">{{ $peminjamans->where('status', 'dipinjam')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Terlambat</div>
                <div class="stat-card-value">{{ $peminjamans->where('status', 'terlambat')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Menunggu ACC</div>
                <div class="stat-card-value">{{ $peminjamans->where('status', 'menunggu')->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Total: <strong>{{ $peminjamans->count() }}</strong> transaksi</span>
        <small class="text-muted">Urutan: <strong>{{ $sort }}</strong> ({{ $direction === 'asc' ? 'A→Z / kecil→besar' : 'Z→A / besar→kecil' }})</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="40"><x-sort field="id_peminjamans" :sort="$sort" :direction="$direction">ID</x-sort></th>
                    <th><x-sort field="kode_peminjaman" :sort="$sort" :direction="$direction">Kode</x-sort></th>
                    <th><x-sort field="nama_barang"     :sort="$sort" :direction="$direction">Barang</x-sort></th>
                    <th><x-sort field="nama_peminjam"   :sort="$sort" :direction="$direction">Peminjam</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah_pinjam" :sort="$sort" :direction="$direction">Jml</x-sort></th>
                    <th><x-sort field="tanggal_pinjam"          :sort="$sort" :direction="$direction">Tgl Pinjam</x-sort></th>
                    <th><x-sort field="tanggal_kembali_rencana" :sort="$sort" :direction="$direction">Rencana Kembali</x-sort></th>
                    <th><x-sort field="tanggal_kembali_aktual"  :sort="$sort" :direction="$direction">Tgl Kembali</x-sort></th>
                    <th><x-sort field="status" :sort="$sort" :direction="$direction">Status</x-sort></th>
                    <th>Catatan</th>
                    <th>Oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr>
                    <td class="text-muted text-center font-monospace">{{ $p->id_peminjamans }}</td>
                    <td><code><a href="{{ route('peminjaman.show', $p) }}" class="text-decoration-none">{{ $p->kode_peminjaman }}</a></code></td>
                    <td>{{ Str::limit($p->barang->nama_barang, 25) }}</td>
                    <td>
                        <div>{{ $p->nama_peminjam }}</div>
                        @if($p->instansi_peminjam)<small class="text-muted">{{ $p->instansi_peminjam }}</small>@endif
                    </td>
                    <td class="text-center">{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>
                        <div class="text-muted-sm">{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        @if($p->tanggal_kembali_aktual)
                            @php
                                $warna = $p->catatan_terlambat ? 'text-danger fw-bold' : 'text-success fw-semibold';
                                $ikon  = $p->catatan_terlambat ? '↩' : '✓';
                            @endphp
                            <span class="{{ $warna }} font-monospace">
                                {{ $ikon }} {{ $p->tanggal_kembali_aktual->format('d/m/Y') }}
                            </span>
                            @if($p->kondisi_kembali)
                            <br><small class="badge bg-{{ $p->kondisi_kembali_badge }}">{{ $p->kondisi_kembali_label }}</small>
                            @endif
                        @else
                            <span class="text-muted-sm">—</span>
                        @endif
                    </td>
                    <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                    <td>
                        @if($p->catatan_terlambat)
                            <span class="badge bg-danger" title="{{ $p->catatan_terlambat }}">
                                <i class="bi bi-clock-history me-1"></i>{{ $p->catatan_terlambat }}
                            </span>
                        @elseif($p->keterangan_kembali)
                            <small class="text-muted">{{ Str::limit($p->keterangan_kembali, 30) }}</small>
                        @else
                            <span class="text-muted-sm">—</span>
                        @endif
                    </td>
                    <td class="text-muted-sm">{{ $p->user->display_name }}</td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">Tidak ada data peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
