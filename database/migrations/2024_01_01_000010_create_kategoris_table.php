<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id('id_kategoris');
            $table->string('nama_kategori', 100);
            $table->string('kode_kategori', 10)->unique()->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('warna', 20)->default('#4F46E5')->comment('Hex color untuk badge UI');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
