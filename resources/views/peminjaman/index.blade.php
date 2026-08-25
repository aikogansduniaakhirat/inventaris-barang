@extends('layouts.app')
@section('title', 'Peminjaman Barang')
@section('page-title', 'Peminjaman Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Peminjaman Barang</h1>
        <p class="page-header-sub">Kelola transaksi peminjaman dan pengembalian barang</p>
    </div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>
        {{ auth()->user()->isAdmin() ? 'Catat Peminjaman' : 'Ajukan Peminjaman' }}
    </a>
</div>

{{-- Alert: Menunggu Persetujuan (Admin only) --}}
@if(auth()->user()->isAdmin() && $totalMenunggu > 0)
<div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-3 mb-3" role="alert">
    <i class="bi bi-hourglass-split fs-5"></i>
    <div>
        <strong>{{ $totalMenunggu }} permintaan peminjaman menunggu persetujuan!</strong>
        <a href="?status=menunggu" class="alert-link ms-2">Tinjau sekarang →</a>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Alert: Terlambat --}}
@if($totalTerlambat > 0)
<div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <div>
        <strong>{{ $totalTerlambat }} peminjaman terlambat!</strong>
        Segera hubungi peminjam untuk pengembalian barang.
        <a href="?status=terlambat" class="alert-link ms-2">Lihat sekarang →</a>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label">Cari</label>
                <input type="text" name="search" class="form-control" placeholder="Kode, nama peminjam, barang..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    @if(auth()->user()->isAdmin())
                    <option value="menunggu" {{ request('status')=='menunggu' ? 'selected':'' }}>⏳ Menunggu ACC</option>
                    @endif
                    <option value="dipinjam" {{ request('status')=='dipinjam' ? 'selected':'' }}>Dipinjam</option>
                    <option value="terlambat" {{ request('status')=='terlambat' ? 'selected':'' }}>Terlambat</option>
                    <option value="dikembalikan" {{ request('status')=='dikembalikan' ? 'selected':'' }}>Dikembalikan</option>
                    <option value="ditolak" {{ request('status')=='ditolak' ? 'selected':'' }}>Ditolak</option>
                    <option value="rusak" {{ request('status')=='rusak' ? 'selected':'' }}>Rusak</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
            </div>
            <div class="col-6 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Daftar Peminjaman <span class="badge bg-primary ms-1">{{ $peminjamans->total() }}</span>
        @if(auth()->user()->isStaff())
        <span class="badge bg-secondary ms-1">Hanya milik Anda</span>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th><x-sort field="kode_peminjaman" :sort="$sort" :direction="$direction">Kode</x-sort></th>
                    <th><x-sort field="nama_barang"      :sort="$sort" :direction="$direction">Barang</x-sort></th>
                    <th><x-sort field="nama_peminjam"    :sort="$sort" :direction="$direction">Peminjam</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah_pinjam" :sort="$sort" :direction="$direction">Jml</x-sort></th>
                    <th><x-sort field="tanggal_pinjam"          :sort="$sort" :direction="$direction">Tgl Pinjam</x-sort></th>
                    <th><x-sort field="tanggal_kembali_rencana" :sort="$sort" :direction="$direction">Tgl Kembali</x-sort></th>
                    <th><x-sort field="status" :sort="$sort" :direction="$direction">Status</x-sort></th>
                    <th>Kondisi Kembali</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $p)
                <tr class="{{ $p->is_terlambat ? 'table-danger' : ($p->status === 'menunggu' ? 'table-warning' : ($p->status === 'ditolak' ? 'table-light' : '')) }}">
                    <td><a href="{{ route('peminjaman.show', $p) }}" class="fw-semibold text-decoration-none font-monospace">{{ $p->kode_peminjaman }}</a></td>
                    <td>
                        <div class="fw-semibold">{{ Str::limit($p->barang->nama_barang, 30) }}</div>
                        <small class="text-muted">{{ $p->barang->kode_barang }}</small>
                    </td>
                    <td>
                        <div>{{ $p->nama_peminjam }}</div>
                        @if($p->instansi_peminjam)<small class="text-muted">{{ $p->instansi_peminjam }}</small>@endif
                    </td>
                    <td class="text-center fw-semibold">{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="{{ $p->is_terlambat ? 'text-danger fw-bold' : '' }}">
                        {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                        @if($p->is_terlambat)
                        <br><small class="badge bg-danger">{{ abs($p->sisa_hari) }} hari terlambat</small>
                        @elseif(in_array($p->status, ['dipinjam','terlambat']) && $p->sisa_hari <= 2 && $p->sisa_hari >= 0)
                        <br><small class="badge bg-warning text-dark">{{ $p->sisa_hari }} hari lagi</small>
                        @endif
                    </td>
                    <td>
                        @if($p->status === 'menunggu')
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Menunggu ACC</span>
                        @elseif($p->status === 'ditolak')
                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                        @else
                            <span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span>
                        @endif
                    </td>
                    <td>
                        @if($p->kondisi_kembali)
                            @php
                                $kb = $p->kondisi_kembali;
                                $kbColor = match($kb) {
                                    'baik'        => 'success',
                                    'rusak_ringan' => 'warning',
                                    'rusak_berat'  => 'danger',
                                    default        => 'secondary',
                                };
                                $kbLabel = match($kb) {
                                    'baik'        => 'Baik',
                                    'rusak_ringan' => 'Rusak Ringan',
                                    'rusak_berat'  => 'Rusak Berat',
                                    default        => $kb,
                                };
                            @endphp
                            <span class="badge bg-{{ $kbColor }}">{{ $kbLabel }}</span>
                        @else
                            <span class="text-muted-sm">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>

                            {{-- Tombol Setujui & Tolak (Admin only, status menunggu) --}}
                            @if(auth()->user()->isAdmin() && $p->status === 'menunggu')
                            <form action="{{ route('peminjaman.approve', $p) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Setujui"
                                    onclick="return confirm('Setujui permintaan peminjaman ini?')">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Tolak"
                                data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->id }}">
                                <i class="bi bi-x-lg"></i>
                            </button>
                            @endif

                            {{-- Tombol Pengembalian --}}
                            @if(in_array($p->status, ['dipinjam','terlambat']))
                            <a href="{{ route('pengembalian.create', ['peminjaman_id' => $p->id_peminjamans]) }}" class="btn btn-sm btn-outline-success" title="Proses Pengembalian">
                                <i class="bi bi-arrow-return-left"></i>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Modal Tolak --}}
                @if(auth()->user()->isAdmin() && $p->status === 'menunggu')
                <div class="modal fade" id="modalTolak{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('peminjaman.reject', $p) }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title"><i class="bi bi-x-circle text-danger me-2"></i>Tolak Permintaan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted mb-3">Permintaan peminjaman <strong>{{ $p->kode_peminjaman }}</strong> oleh <strong>{{ $p->nama_peminjam }}</strong> akan ditolak.</p>
                                    <label class="form-label">Alasan Penolakan (opsional)</label>
                                    <textarea name="alasan_tolak" class="form-control" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
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

                @empty
                <tr><td colspan="9">
                    <div class="empty-state">
                        <i class="bi bi-inbox d-block"></i>
                        <h6>Tidak ada data peminjaman</h6>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($peminjamans->hasPages())
    <div class="card-body border-top py-3">{{ $peminjamans->links() }}</div>
    @endif
</div>
@endsection
