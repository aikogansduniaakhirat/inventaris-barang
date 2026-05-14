<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom alasan_tolak
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->text('alasan_tolak')->nullable()->after('keterangan_kembali');
        });

        // 2. Ubah enum status agar support 'menunggu' dan 'ditolak'
        // SQLite tidak support ALTER COLUMN ENUM, tapi kita pakai pendekatan string
        // Untuk MySQL:
        try {
            DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('menunggu','dipinjam','dikembalikan','terlambat','rusak','ditolak') NOT NULL DEFAULT 'dipinjam'");
        } catch (\Exception $e) {
            // SQLite: tidak perlu ALTER ENUM karena SQLite tidak enforce enum
        }
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn('alasan_tolak');
        });

        try {
            DB::statement("ALTER TABLE peminjamans MODIFY COLUMN status ENUM('dipinjam','dikembalikan','terlambat','rusak') NOT NULL DEFAULT 'dipinjam'");
        } catch (\Exception $e) {
            // SQLite: skip
        }
    }
};
