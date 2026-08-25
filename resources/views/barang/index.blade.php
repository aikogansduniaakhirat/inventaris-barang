@extends('layouts.app')
@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Data Barang</h1>
        <p class="page-header-sub">Kelola semua barang inventaris sekolah</p>
    </div>
    <a href="{{ route('barang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Barang
    </a>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('barang.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <label class="form-label">Cari Barang</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Nama, kode, merk..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <option value="">Semua</option>
                    <option value="baik" {{ request('kondisi')=='baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi')=='rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi')=='rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label">Lokasi</label>
                <select name="lokasi" class="form-select">
                    <option value="">Semua</option>
                    @foreach($lokasis as $l)
                        <option value="{{ $l }}" {{ request('lokasi') == $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Barang -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Barang <span class="badge bg-primary ms-1">{{ $barangs->total() }}</span></span>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.export.excel.stok', request()->query()) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-earmark-excel me-1"></i>Excel
            </a>
            <a href="{{ route('laporan.export.pdf.stok', request()->query()) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th width="44">#</th>
                    <th width="44">Foto</th>
                    <th><x-sort field="nama_barang"  :sort="$sort" :direction="$direction">Kode / Nama Barang</x-sort></th>
                    <th><x-sort field="nama_kategori" :sort="$sort" :direction="$direction">Kategori</x-sort></th>
                    <th><x-sort field="lokasi"        :sort="$sort" :direction="$direction">Lokasi</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah" :sort="$sort" :direction="$direction">Jml</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah_tersedia" :sort="$sort" :direction="$direction">Tersedia</x-sort></th>
                    <th><x-sort field="kondisi" :sort="$sort" :direction="$direction">Kondisi</x-sort></th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                @php $rowNum = ($barangs->currentPage() - 1) * $barangs->perPage() + $loop->iteration; @endphp
                <tr>
                    <td class="text-muted text-center font-monospace">{{ $rowNum }}</td>
                    <td>
                        @if($barang->foto)
                            <img src="{{ asset('storage/'.$barang->foto) }}" alt="{{ $barang->nama_barang }}" class="item-photo">
                        @else
                            <div class="item-photo-placeholder"><i class="bi bi-box-seam"></i></div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('barang.show', $barang) }}" class="fw-semibold text-decoration-none d-block">{{ $barang->nama_barang }}</a>
                        <span class="text-muted-sm font-monospace">{{ $barang->kode_barang }}</span>
                        @if($barang->merk)<span class="text-muted-sm"> · {{ $barang->merk }}</span>@endif
                    </td>
                    <td>
                        <span class="badge" style="background-color: {{ $barang->kategori->warna ?? '#6B7280' }}20; color: {{ $barang->kategori->warna ?? '#6B7280' }}; border: 1px solid {{ $barang->kategori->warna ?? '#6B7280' }}40;">
                            {{ $barang->kategori->nama_kategori }}
                        </span>
                    </td>
                    <td class="text-muted-sm">{{ $barang->lokasi ?? '-' }}</td>
                    <td class="text-center fw-semibold">{{ $barang->jumlah }}</td>
                    <td class="text-center">
                        <span class="badge bg-{{ $barang->status_stok_badge }}">{{ $barang->jumlah_tersedia }}</span>
                    </td>
                    <td><span class="badge bg-{{ $barang->kondisi_badge }}">{{ $barang->kondisi_label }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('barang.show', $barang) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-outline-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('barang.destroy', $barang) }}" method="POST" onsubmit="return confirm('Hapus barang {{ addslashes($barang->nama_barang) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <i class="bi bi-box-seam d-block"></i>
                            <h6>Tidak ada barang ditemukan</h6>
                            <p class="text-muted-sm">Coba ubah filter atau <a href="{{ route('barang.create') }}">tambah barang baru</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($barangs->hasPages())
    <div class="card-body border-top py-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Urutan: <strong>{{ $sort }}</strong> ({{ $direction === 'asc' ? 'A→Z / kecil→besar' : 'Z→A / besar→kecil' }})
        </small>
        {{ $barangs->links() }}
    </div>
    @endif
</div>
@endsection
