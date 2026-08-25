<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',
        'keterangan',
        'alasan_tolak',
    ];

    protected $casts = [
        'tanggal_pinjam'          => 'date',
        'tanggal_kembali_rencana' => 'date',
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

    public function pengembalians(): HasMany
    {
        return $this->hasMany(Pengembalian::class, 'peminjaman_id');
    }

    public function getSisaPinjamAttribute(): int
    {
        $sudahKembali = $this->pengembalians()->sum('jumlah_kembali');
        return max(0, $this->jumlah_pinjam - (int) $sudahKembali);
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'    => 'warning',
            'dipinjam'    => 'primary',
            'dikembalikan' => 'success',
            'terlambat'   => 'danger',
            'ditolak'     => 'danger',
            'rusak'       => 'dark',
            default       => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'    => 'Menunggu ACC',
            'dipinjam'    => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'terlambat'   => 'Terlambat',
            'ditolak'     => 'Ditolak',
            'rusak'       => 'Rusak',
            default       => '-',
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

    public function getIsMenungguAttribute(): bool
    {
        return $this->status === 'menunggu';
    }

    // Backward-compat: banyak view pakai ->id sebelum refactor PK
    public function getIdAttribute(): mixed
    {
        return $this->id_peminjamans;
    }

    // Backward-compat: kolom ini dipindah ke tabel pengembalians
    // Accessor return pengembalian terakhir (full return) kalau ada
    public function getTanggalKembaliAktualAttribute(): mixed
    {
        $last = $this->pengembalians()->latest('tanggal_kembali')->first();
        return $last?->tanggal_kembali;
    }

    public function getKondisiKembaliAttribute(): mixed
    {
        $last = $this->pengembalians()->latest('tanggal_kembali')->first();
        return $last?->kondisi_kembali;
    }

    public function getKeteranganKembaliAttribute(): mixed
    {
        $last = $this->pengembalians()->latest('tanggal_kembali')->first();
        return $last?->keterangan;
    }

    /**
     * Accessor: catatan terlambat (untuk display di view/laporan).
     * Format: "Terlambat X hari dari rencana (dd/mm/yyyy)"
     */
    public function getCatatanTerlambatAttribute(): ?string
    {
        $aktual = $this->tanggal_kembali_aktual;
        $rencana = $this->tanggal_kembali_rencana;

        if (!$aktual || !$rencana) return null;
        if ($aktual->lessThanOrEqualTo($rencana)) return null;

        $hari = $rencana->diffInDays($aktual);
        return "Terlambat {$hari} hari dari rencana (" . $rencana->format('d/m/Y') . ")";
    }
}
