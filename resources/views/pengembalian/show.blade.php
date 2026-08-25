@extends('layouts.app')
@section('title', 'Detail Pengembalian')
@section('page-title', 'Detail Pengembalian')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-header-title">{{ $pengembalian->kode_pengembalian }}</h1>
        <p class="page-header-sub">Peminjaman: <a href="{{ route('peminjaman.show', $pengembalian->peminjaman_id) }}">{{ $pengembalian->peminjaman->kode_peminjaman }}</a></p>
    </div>
    <a href="{{ route('pengembalian.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr><th width="200">Barang</th><td>{{ $pengembalian->peminjaman->barang->nama_barang ?? '-' }} ({{ $pengembalian->peminjaman->barang->kode_barang ?? '-' }})</td></tr>
            <tr><th>Peminjam</th><td>{{ $pengembalian->peminjaman->nama_peminjam }}</td></tr>
            <tr><th>Jumlah Kembali</th><td>{{ $pengembalian->jumlah_kembali }} {{ $pengembalian->peminjaman->barang->satuan ?? '' }}</td></tr>
            <tr><th>Tanggal Kembali</th><td>{{ $pengembalian->tanggal_kembali->format('d/m/Y') }}</td></tr>
            <tr><th>Kondisi</th><td><span class="badge bg-{{ $pengembalian->kondisi_badge }}">{{ $pengembalian->kondisi_label }}</span></td></tr>
            <tr><th>Petugas Penerima</th><td>{{ $pengembalian->user->name ?? '-' }}</td></tr>
            <tr><th>Keterangan</th><td>{{ $pengembalian->keterangan ?: '-' }}</td></tr>
            <tr><th>Dicatat</th><td>{{ $pengembalian->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
    </div>
</div>
@endsection
