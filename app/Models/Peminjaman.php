<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';
    protected $primaryKey = 'id_peminjamans';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_peminjaman',
        'barang_id',
        'user_id',
        'nama_peminjam',
        'instansi_peminjam',
        'jumlah_pinjam',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'kondisi_kembali',
        'keterangan_kembali',
        'status',
        'keterangan',
        'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual'  => 'date',
        'jumlah_pinjam'           => 'integer',
    ];

    // Relationships
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'warning',
            'dipinjam'     => 'primary',
            'dikembalikan' => 'success',
            'terlambat'    => 'danger',
            'ditolak'      => 'danger',
            'rusak'        => 'dark',
            default        => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'Menunggu ACC',
            'dipinjam'     => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat'   => 'Terlambat',
            'ditolak'      => 'Ditolak',
            'rusak'        => 'Rusak',
            default        => '-',
        };
    }

    public function getSisaHariAttribute(): ?int
    {
        if ($this->status === 'dikembalikan') return null;
        return now()->diffInDays($this->tanggal_kembali_rencana, false);
    }

    public function getIsTerlambatAttribute(): bool
    {
        return in_array($this->status, ['dipinjam', 'terlambat'])
            && now()->greaterThan($this->tanggal_kembali_rencana);
    }

    public function getIsMenungguAttribute(): bool
    {
        return $this->status === 'menunggu';
    }

    /**
     * Catatan keterlambatan.
     * Null kalau tidak terlambat atau belum dikembalikan.
     * Format: "Terlambat X hari dari rencana (dd/mm/yyyy)"
     */
    public function getCatatanTerlambatAttribute(): ?string
    {
        if (!$this->tanggal_kembali_aktual || !$this->tanggal_kembali_rencana) {
            return null;
        }
        if ($this->tanggal_kembali_aktual->lessThanOrEqualTo($this->tanggal_kembali_rencana)) {
            return null;
        }
        $hari = $this->tanggal_kembali_rencana->diffInDays($this->tanggal_kembali_aktual);
        return "Terlambat {$hari} hari dari rencana ({$this->tanggal_kembali_rencana->format('d/m/Y')})";
    }

    /**
     * Badge warna untuk kondisi kembali (di tabel).
     * null = belum dikembalikan.
     */
    public function getKondisiKembaliBadgeAttribute(): ?string
    {
        return match ($this->kondisi_kembali) {
            'baik'         => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat'  => 'danger',
            default         => null,
        };
    }

    public function getKondisiKembaliLabelAttribute(): ?string
    {
        return match ($this->kondisi_kembali) {
            'baik'         => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default         => null,
        };
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['dipinjam', 'terlambat']);
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', 'terlambat')
                     ->orWhere(function ($q) {
                         $q->where('status', 'dipinjam')
                           ->where('tanggal_kembali_rencana', '<', now()->toDateString());
                     });
    }

    // Backward-compat: banyak view lama pakai ->id
    public function getIdAttribute(): mixed
    {
        return $this->id_peminjamans;
    }
}
