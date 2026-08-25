<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id('id_peminjamans');
            $table->string('kode_peminjaman', 30)->unique();
            $table->foreignId('barang_id')->constrained('barangs', 'id_barangs')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users', 'id_users')->onDelete('restrict')->comment('Petugas yang mencatat');
            $table->string('nama_peminjam', 200)->comment('Nama orang yang meminjam');
            $table->string('instansi_peminjam', 200)->nullable()->comment('Kelas / jabatan / instansi');
            $table->integer('jumlah_pinjam')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat', 'rusak'])->default('dipinjam');
            $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->text('keterangan_kembali')->nullable();
            $table->timestamps();

            $table->index(['status', 'tanggal_kembali_rencana']);
            $table->index('barang_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamans');
    }
};
