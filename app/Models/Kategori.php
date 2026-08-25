<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategoris';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'warna',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    // Accessors
    public function getTotalBarangAttribute(): int
    {
        return $this->barangs()->count();
    }

    public function getTotalStokAttribute(): int
    {
        return $this->barangs()->sum('jumlah');
    }

    // Backward-compat: banyak view pakai ->id sebelum refactor PK
    public function getIdAttribute(): mixed
    {
        return $this->id_kategoris;
    }
}
