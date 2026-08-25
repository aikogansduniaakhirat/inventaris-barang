<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Revert: hapus tabel pengembalians (entitas pisah).
        // Karena kolom tanggal_kembali_aktual dll sudah ada di peminjamans
        // (migration 2026_08_26_000002), data tetap aman.
        Schema::dropIfExists('pengembalians');
    }

    public function down(): void
    {
        // Recreate tabel pengembalians (rollback). Tabel ini pernah ada
        // untuk fitur partial return + history multi-return, tapi sudah
        // disederhanakan jadi field inline di peminjamans.
        \Illuminate\Support\Facades\DB::statement("
            CREATE TABLE `pengembalians` (
                `id_pengembalians` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `kode_pengembalian` VARCHAR(30) NOT NULL,
                `peminjaman_id` BIGINT UNSIGNED NOT NULL,
                `user_id` BIGINT UNSIGNED NOT NULL,
                `jumlah_kembali` INT NOT NULL,
                `tanggal_kembali` DATE NOT NULL,
                `kondisi_kembali` ENUM('baik','rusak_ringan','rusak_berat') NOT NULL DEFAULT 'baik',
                `keterangan` TEXT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL,
                PRIMARY KEY (`id_pengembalians`),
                UNIQUE KEY `pengembalians_kode_pengembalian_unique` (`kode_pengembalian`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }
};
