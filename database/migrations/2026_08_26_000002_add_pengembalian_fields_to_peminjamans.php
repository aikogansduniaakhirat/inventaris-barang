<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Revert: tambah kembali kolom-kolom pengembalian ke peminjamans.
        // Idempotent — skip kalau kolom sudah ada.
        if (!Schema::hasColumn('peminjamans', 'tanggal_kembali_aktual')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->date('tanggal_kembali_aktual')->nullable()->after('tanggal_kembali_rencana');
                $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable()->after('tanggal_kembali_aktual');
                $table->text('keterangan_kembali')->nullable()->after('kondisi_kembali');
            });
        }
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kembali_aktual', 'kondisi_kembali', 'keterangan_kembali']);
        });
    }
};
