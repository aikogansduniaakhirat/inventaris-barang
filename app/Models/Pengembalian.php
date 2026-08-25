<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    use HasFactory;

    protected $table = 'pengembalians';

    protected $fillable = [
        'kode_pengembalian',
        'peminjaman_id',
        'user_id',
        'jumlah_kembali',
        'tanggal_kembali',
        'kondisi_kembali',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kembali' => 'date',
        'jumlah_kembali'  => 'integer',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi_kembali) {
            'baik'          => 'success',
            'rusak_ringan'  => 'warning',
            'rusak_berat'   => 'danger',
            default         => 'secondary',
        };
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi_kembali) {
            'baik'          => 'Baik',
            'rusak_ringan'  => 'Rusak Ringan',
            'rusak_berat'   => 'Rusak Berat',
            default         => '-',
        };
    }
}
