<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /**
     * Whitelist kolom sortable.
     * - 'kode_peminjaman' = sort by relasi peminjaman
     */
    private const SORTABLE = [
        'kode_pengembalian', 'jumlah_kembali', 'tanggal_kembali',
        'kondisi_kembali', 'created_at', 'kode_peminjaman',
    ];

    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.barang', 'user']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($x) use ($q) {
                $x->where('kode_pengembalian', 'like', "%{$q}%")
                  ->orWhereHas('peminjaman', fn($p) => $p->where('kode_peminjaman', 'like', "%{$q}%")
                                                          ->orWhere('nama_peminjam', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi_kembali', $request->kondisi);
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_kembali', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_kembali', '<=', $request->tanggal_sampai);
        }

        $sort      = $request->get('sort', 'tanggal_kembali');
        $direction = $request->get('direction', 'desc');
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'desc';

        if (in_array($sort, self::SORTABLE)) {
            if ($sort === 'kode_peminjaman') {
                $query->leftJoin('peminjamans', 'pengembalians.peminjaman_id', '=', 'peminjamans.id_peminjamans')
                      ->select('pengembalians.*')
                      ->orderBy('peminjamans.kode_peminjaman', $direction);
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->orderBy('tanggal_kembali', 'desc');
        }

        $pengembalians = $query->paginate(15)->withQueryString();
        return view('pengembalian.index', compact('pengembalians', 'sort', 'direction'));
    }

    public function create(Request $request)
    {
        $peminjaman = null;
        if ($request->filled('peminjaman_id')) {
            $peminjaman = Peminjaman::with('barang')
                ->findOrFail($request->peminjaman_id);

            if (in_array($peminjaman->status, ['dikembalikan', 'menunggu', 'ditolak'])) {
                return redirect()->route('peminjaman.show', $peminjaman)
                                 ->with('info', 'Peminjaman ini tidak dapat diproses pengembalian.');
            }
        } else {
            $peminjamans = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])
                ->with('barang')
                ->latest()
                ->get();
            return view('pengembalian.select', compact('peminjamans'));
        }

        $peminjaman->load(['barang', 'user']);
        return view('pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'peminjaman_id'    => ['required', 'exists:peminjamans,id_peminjamans'],
            'jumlah_kembali'   => ['required', 'integer', 'min:1'],
            'tanggal_kembali'  => ['required', 'date'],
            'kondisi_kembali'  => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'keterangan'       => ['nullable', 'string', 'max:500'],
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($validated['peminjaman_id']);

        if (!in_array($peminjaman->status, ['dipinjam', 'terlambat'])) {
            return back()->with('error', 'Peminjaman tidak dalam status yang dapat dikembalikan.');
        }

        // Hitung sisa yang belum dikembalikan
        $sudahKembali = Pengembalian::where('peminjaman_id', $peminjaman->id_peminjamans)->sum('jumlah_kembali');
        $sisaPinjam   = $peminjaman->jumlah_pinjam - $sudahKembali;

        if ($validated['jumlah_kembali'] > $sisaPinjam) {
            return back()->withInput()
                         ->with('error', "Jumlah kembali ({$validated['jumlah_kembali']}) melebihi sisa pinjam ({$sisaPinjam}).");
        }

        DB::transaction(function () use ($validated, $peminjaman) {
            $validated['kode_pengembalian'] = $this->generateKode();
            $validated['user_id']           = auth()->user()->id_users;
            Pengembalian::create($validated);

            $barang  = $peminjaman->barang;
            $jumlah  = $validated['jumlah_kembali'];
            $kondisi = $validated['kondisi_kembali'];

            if ($kondisi === 'baik') {
                $barang->increment('jumlah_tersedia', $jumlah);
            } else {
                if ($kondisi === 'rusak_ringan') {
                    $barang->increment('jumlah_rusak_ringan', $jumlah);
                } else {
                    $barang->increment('jumlah_rusak_berat', $jumlah);
                }
                $barang->refresh();
                $totalRusak = $barang->jumlah_rusak_ringan + $barang->jumlah_rusak_berat;
                $newKondisi = match (true) {
                    $totalRusak === 0           => 'baik',
                    $barang->jumlah_rusak_berat > 0 => 'rusak_berat',
                    default                     => 'rusak_ringan',
                };
                $barang->update(['kondisi' => $newKondisi]);
            }

            // Update status peminjaman
            $sisaSetelah = $sisaPinjam - $jumlah;
            $peminjaman->update([
                'status' => $sisaSetelah <= 0 ? 'dikembalikan' : 'dipinjam',
            ]);

            // Auto-tandai terlambat (setelah status update di-refresh)
            $peminjaman->refresh();
            if ($peminjaman->status === 'dipinjam' && $peminjaman->tanggal_kembali_rencana < now()->toDateString()) {
                $peminjaman->update(['status' => 'terlambat']);
            }
        });

        return redirect()->route('pengembalian.index')->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function show(Pengembalian $pengembalian)
    {
        $pengembalian->load(['peminjaman.barang.kategori', 'user']);
        return view('pengembalian.show', compact('pengembalian'));
    }

    private function generateKode(): string
    {
        $tahun = now()->year;
        $last  = Pengembalian::where('kode_pengembalian', 'like', "KMB-{$tahun}-%")
                             ->orderBy('kode_pengembalian', 'desc')
                             ->first();
        $urutan = $last ? (int) substr($last->kode_pengembalian, -4) + 1 : 1;
        return "KMB-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
