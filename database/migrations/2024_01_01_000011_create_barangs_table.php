<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barangs');
            $table->string('kode_barang', 30)->unique();
            $table->string('nama_barang', 200);
            $table->foreignId('kategori_id')->constrained('kategoris', 'id_kategoris')->onDelete('restrict');
            $table->string('merk', 100)->nullable();
            $table->integer('jumlah')->default(0)->comment('Total unit keseluruhan');
            $table->integer('jumlah_tersedia')->default(0)->comment('Unit yang tersedia untuk dipinjam');
            $table->string('satuan', 30)->default('unit');
            $table->string('lokasi', 200)->nullable()->comment('Ruangan / gedung tempat penyimpanan');
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->decimal('nilai', 15, 2)->nullable()->comment('Nilai/harga barang dalam rupiah');
            $table->string('foto')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('qr_code')->nullable()->comment('Path file QR Code');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['kategori_id', 'kondisi']);
            $table->index('nama_barang');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
