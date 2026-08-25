@extends('layouts.app')
@section('title', 'Daftar Pengembalian')
@section('page-title', 'Pengembalian Barang')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">Pengembalian Barang</h1>
        <p class="page-header-sub">Riwayat pengembalian barang inventaris</p>
    </div>
    <a href="{{ route('pengembalian.create') }}" class="btn btn-primary">
        <i class="bi bi-arrow-return-left me-1"></i> Catat Pengembalian
    </a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="form-control"></div>
    <div class="col-md-2">
        <select name="kondisi" class="form-select">
            <option value="">-- Kondisi --</option>
            <option value="baik"         @selected(request('kondisi')=='baik')>Baik</option>
            <option value="rusak_ringan" @selected(request('kondisi')=='rusak_ringan')>Rusak Ringan</option>
            <option value="rusak_berat"  @selected(request('kondisi')=='rusak_berat')>Rusak Berat</option>
        </select>
    </div>
    <div class="col-md-2"><input type="date" name="tanggal_dari"   value="{{ request('tanggal_dari') }}"   class="form-control"></div>
    <div class="col-md-2"><input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control"></div>
    <div class="col-md-3"><button class="btn btn-outline-primary w-100">Filter</button></div>
    {{-- Preserve sort saat filter berubah --}}
    @if(request('sort'))<input type="hidden" name="sort"      value="{{ request('sort') }}">@endif
    @if(request('direction'))<input type="hidden" name="direction" value="{{ request('direction') }}">@endif
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th><x-sort field="kode_pengembalian" :sort="$sort" :direction="$direction">Kode Kembali</x-sort></th>
                    <th><x-sort field="kode_peminjaman"    :sort="$sort" :direction="$direction">Peminjaman</x-sort></th>
                    <th>Barang</th>
                    <th><x-sort field="jumlah_kembali"     :sort="$sort" :direction="$direction">Jumlah</x-sort></th>
                    <th><x-sort field="tanggal_kembali"    :sort="$sort" :direction="$direction">Tanggal</x-sort></th>
                    <th><x-sort field="kondisi_kembali"    :sort="$sort" :direction="$direction">Kondisi</x-sort></th>
                    <th>Petugas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalians as $p)
                <tr>
                    <td><code>{{ $p->kode_pengembalian }}</code></td>
                    <td><a href="{{ route('peminjaman.show', $p->peminjaman_id) }}">{{ $p->peminjaman->kode_peminjaman }}</a></td>
                    <td>{{ $p->peminjaman->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $p->jumlah_kembali }}</td>
                    <td>{{ $p->tanggal_kembali->format('d/m/Y') }}</td>
                    <td><span class="badge bg-{{ $p->kondisi_badge }}">{{ $p->kondisi_label }}</span></td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('pengembalian.show', $p) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data pengembalian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">
        Urutan: <strong>{{ $sort }}</strong> ({{ $direction === 'asc' ? 'A→Z / kecil→besar' : 'Z→A / besar→kecil' }})
        &middot; {{ $pengembalians->total() }} data
    </small>
    {{ $pengembalians->links() }}
</div>
@endsection
