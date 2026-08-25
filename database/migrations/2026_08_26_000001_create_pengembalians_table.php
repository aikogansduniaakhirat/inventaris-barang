<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Refactor: Pengembalian jadi entitas terpisah.
 *
 * Catatan dosen sidang: "entitas pengembalian".
 * Sebelumnya data pengembalian (kondisi, tanggal aktual, keterangan)
 * berada di tabel peminjamans, sehingga tidak bisa multi-pengembalian
 * parsial. Sekarang setiap kali barang dikembalikan, tercatat sebagai
 * 1 baris di tabel pengembalians — mendukung pengembalian sebagian
 * (misal: pinjam 5, kembali 2 dulu, sisanya nanti).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Tabel pengembalians (entitas baru)
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pengembalian', 30)->unique();
            $table->foreignId('peminjaman_id')
                  ->constrained('peminjamans')
                  ->onDelete('restrict');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict')
                  ->comment('Petugas/admin yang menerima kembali');
            $table->integer('jumlah_kembali')->default(1);
            $table->date('tanggal_kembali');
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['peminjaman_id', 'tanggal_kembali']);
            $table->index('kondisi_kembali');
        });

        // Migrate data lama (jika ada) ke entitas baru — 1 baris per peminjaman
        if (Schema::hasColumn('peminjamans', 'tanggal_kembali_aktual')) {
            $peminjamans = DB::table('peminjamans')
                ->whereNotNull('tanggal_kembali_aktual')
                ->get();

            foreach ($peminjamans as $p) {
                DB::table('pengembalians')->insert([
                    'kode_pengembalian' => 'KMB-' . date('Y', strtotime($p->tanggal_kembali_aktual)) . '-' . str_pad($p->id, 4, '0', STR_PAD_LEFT),
                    'peminjaman_id'     => $p->id,
                    'user_id'           => $p->user_id,
                    'jumlah_kembali'    => $p->jumlah_pinjam,
                    'tanggal_kembali'   => $p->tanggal_kembali_aktual,
                    'kondisi_kembali'   => $p->kondisi_kembali ?? 'baik',
                    'keterangan'        => $p->keterangan_kembali,
                    'created_at'        => $p->updated_at ?? now(),
                    'updated_at'        => $p->updated_at ?? now(),
                ]);
            }

            // Hapus kolom lama yang sudah pindah ke pengembalians
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->dropColumn(['tanggal_kembali_aktual', 'kondisi_kembali', 'keterangan_kembali']);
            });
        }
    }

    public function down(): void
    {
        // Kembalikan kolom di peminjamans
        if (!Schema::hasColumn('peminjamans', 'tanggal_kembali_aktual')) {
            Schema::table('peminjamans', function (Blueprint $table) {
                $table->date('tanggal_kembali_aktual')->nullable();
                $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
                $table->text('keterangan_kembali')->nullable();
            });
        }

        Schema::dropIfExists('pengembalians');
    }
};
