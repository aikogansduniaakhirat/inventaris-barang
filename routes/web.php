<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root ke dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// ─── Authenticated Routes ────────────────────────────────────────────────────
Route::middleware(['auth', 'role'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Barang ──────────────────────────────────────────────────────────────
    Route::resource('barang', BarangController::class);
    Route::delete('/barang/{barang}/foto', [BarangController::class, 'hapusFoto'])->name('barang.hapus-foto');
    Route::get('/barang/{barang}/lapor-kerusakan',  [BarangController::class, 'formLaporKerusakan'])->name('barang.lapor-kerusakan');
    Route::post('/barang/{barang}/lapor-kerusakan', [BarangController::class, 'prosesLaporKerusakan'])->name('barang.proses-lapor-kerusakan');


    // ── Peminjaman & Pengembalian ────────────────────────────────────────────
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'show']);

    // Pengembalian = entitas terpisah (refactor: catatan dosen "entitas pengembalian")
    Route::resource('pengembalian', PengembalianController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('pengembalian-select', [PengembalianController::class, 'create'])->name('pengembalian.select');

    // Approve & Reject — hanya Admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject',  [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    });

    // ── Laporan ──────────────────────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/stok-barang',             [LaporanController::class, 'stokBarang'])->name('stok-barang');
        Route::get('/riwayat-peminjaman',      [LaporanController::class, 'riwayatPeminjaman'])->name('riwayat-peminjaman');
        Route::get('/export/pdf/stok',         [LaporanController::class, 'exportPdfStok'])->name('export.pdf.stok');
        Route::get('/export/pdf/peminjaman',   [LaporanController::class, 'exportPdfPeminjaman'])->name('export.pdf.peminjaman');
        Route::get('/export/excel/stok',       [LaporanController::class, 'exportExcelStok'])->name('export.excel.stok');
        Route::get('/export/excel/peminjaman', [LaporanController::class, 'exportExcelPeminjaman'])->name('export.excel.peminjaman');
    });

    // ── Admin Only ───────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        // Kategori
        Route::resource('kategori', KategoriController::class)->only(['index', 'store', 'update', 'destroy']);

        // Manajemen User
        Route::resource('user', UserController::class)->except(['show']);
        Route::post('/user/{user}/reset-password', [UserController::class, 'resetPassword'])->name('user.reset-password');
    });
});

// Auth routes (Breeze)
require __DIR__ . '/auth.php';
