@extends('layouts.app')
@section('title', 'Laporan Stok Barang')
@section('page-title', 'Laporan Stok Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Laporan Stok Barang</h1>
        <p class="page-header-sub">Ringkasan stok dan kondisi semua barang inventaris</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.export.excel.stok', request()->query()) }}" class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('laporan.export.pdf.stok', request()->query()) }}" class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id_kategoris }}" {{ request('kategori')==$k->id_kategoris ? 'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi')=='baik'?'selected':'' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi')=='rusak_ringan'?'selected':'' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi')=='rusak_berat'?'selected':'' }}>Rusak Berat</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lokasi</label>
                <select name="lokasi" class="form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $l)
                    <option value="{{ $l }}" {{ request('lokasi')==$l?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-funnel me-1"></i>Filter</button>
                <a href="{{ route('laporan.stok-barang') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
            {{-- Preserve sort --}}
            @if(request('sort'))<input type="hidden" name="sort"      value="{{ request('sort') }}">@endif
            @if(request('direction'))<input type="hidden" name="direction" value="{{ request('direction') }}">@endif
        </form>
    </div>
</div>

<!-- Summary -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon indigo"><i class="bi bi-boxes"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Total Jenis</div>
                <div class="stat-card-value">{{ $barangs->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon emerald"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Total Unit</div>
                <div class="stat-card-value">{{ number_format($barangs->sum('jumlah')) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon sky"><i class="bi bi-arrow-left-right"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Tersedia</div>
                <div class="stat-card-value">{{ number_format($barangs->sum('jumlah_tersedia')) }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-card-icon purple"><i class="bi bi-currency-dollar"></i></div>
            <div class="stat-card-info">
                <div class="stat-card-label">Total Nilai</div>
                <div class="stat-card-value" style="font-size:1.1rem;">Rp {{ number_format($barangs->sum(fn($b)=>$b->jumlah*$b->nilai)/1000000,1) }}Jt</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th><x-sort field="kode_barang"   :sort="$sort" :direction="$direction">Kode</x-sort></th>
                    <th><x-sort field="nama_barang"   :sort="$sort" :direction="$direction">Nama Barang</x-sort></th>
                    <th><x-sort field="nama_kategori" :sort="$sort" :direction="$direction">Kategori</x-sort></th>
                    <th><x-sort field="lokasi"        :sort="$sort" :direction="$direction">Lokasi</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah" :sort="$sort" :direction="$direction">Jumlah</x-sort></th>
                    <th class="text-center"><x-sort field="jumlah_tersedia" :sort="$sort" :direction="$direction">Tersedia</x-sort></th>
                    <th><x-sort field="kondisi" :sort="$sort" :direction="$direction">Kondisi</x-sort></th>
                    <th class="text-end"><x-sort field="nilai" :sort="$sort" :direction="$direction">Nilai</x-sort></th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $i => $b)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td><code>{{ $b->kode_barang }}</code></td>
                    <td><a href="{{ route('barang.show', $b) }}" class="fw-semibold text-decoration-none">{{ $b->nama_barang }}</a></td>
                    <td>{{ $b->kategori->nama_kategori }}</td>
                    <td class="text-muted-sm">{{ $b->lokasi ?? '-' }}</td>
                    <td class="text-center">{{ $b->jumlah }} {{ $b->satuan }}</td>
                    <td class="text-center"><span class="badge bg-{{ $b->status_stok_badge }}">{{ $b->jumlah_tersedia }}</span></td>
                    <td><span class="badge bg-{{ $b->kondisi_badge }}">{{ $b->kondisi_label }}</span></td>
                    <td class="text-end text-muted-sm">{{ $b->nilai ? 'Rp '.number_format($b->nilai,0,',','.') : '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data barang</td></tr>
                @endforelse
            </tbody>
            @if($barangs->count() > 0)
            <tfoot>
                <tr class="table-light fw-semibold">
                    <td colspan="5" class="text-end">Total:</td>
                    <td class="text-center">{{ number_format($barangs->sum('jumlah')) }}</td>
                    <td class="text-center">{{ number_format($barangs->sum('jumlah_tersedia')) }}</td>
                    <td></td>
                    <td class="text-end">Rp {{ number_format($barangs->sum(fn($b)=>$b->jumlah*$b->nilai),0,',','.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <div class="card-footer text-muted small">
        Urutan: <strong>{{ $sort }}</strong> ({{ $direction === 'asc' ? 'A→Z / kecil→besar' : 'Z→A / besar→kecil' }})
    </div>
</div>
@endsection
