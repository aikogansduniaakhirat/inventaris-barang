<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';
    protected $primaryKey = 'id_barangs';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'merk',
        'jumlah',
        'jumlah_tersedia',
        'jumlah_rusak_ringan',
        'jumlah_rusak_berat',
        'satuan',
        'lokasi',
        'kondisi',
        'nilai',
        'foto',
        'keterangan',
        'qr_code',
    ];

    protected $casts = [
        'jumlah'              => 'integer',
        'jumlah_tersedia'     => 'integer',
        'jumlah_rusak_ringan' => 'integer',
        'jumlah_rusak_berat'  => 'integer',
        'nilai'               => 'decimal:2',
    ];

    // Relationships
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'barang_id');
    }

    public function peminjamansAktif(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'barang_id')
                    ->whereIn('status', ['dipinjam', 'terlambat']);
    }

    // Accessors
    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'         => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat'  => 'danger',
            default        => 'secondary',
        };
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => '-',
        };
    }

    public function getNilaiFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nilai, 0, ',', '.');
    }

    public function getJumlahBaikAttribute(): int
    {
        return $this->jumlah - $this->jumlah_rusak_ringan - $this->jumlah_rusak_berat;
    }

    public function getJumlahRusakAttribute(): int
    {
        return $this->jumlah_rusak_ringan + $this->jumlah_rusak_berat;
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && file_exists(public_path('storage/' . $this->foto))) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/no-image.png');
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->jumlah_tersedia <= 0) return 'habis';
        if ($this->jumlah_tersedia <= 2) return 'kritis';
        return 'tersedia';
    }

    public function getStatusStokBadgeAttribute(): string
    {
        return match ($this->status_stok) {
            'habis'    => 'danger',
            'kritis'   => 'warning',
            'tersedia' => 'success',
            default    => 'secondary',
        };
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('jumlah', '>', 0);
    }

    public function scopeByKondisi($query, string $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_barang', 'like', "%{$keyword}%")
              ->orWhere('kode_barang', 'like', "%{$keyword}%")
              ->orWhere('merk', 'like', "%{$keyword}%")
              ->orWhere('lokasi', 'like', "%{$keyword}%");
        });
    }

    // Backward-compat: banyak view pakai ->id sebelum refactor PK
    public function getIdAttribute(): mixed
    {
        return $this->id_barangs;
    }
}
