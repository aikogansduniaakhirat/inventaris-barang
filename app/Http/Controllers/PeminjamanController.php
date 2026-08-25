<?php

namespace App\Http\Controllers;

use App\Http\Requests\PeminjamanRequest;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $query = Peminjaman::with(['barang.kategori', 'user']);

        // Staff hanya lihat peminjaman milik sendiri
        if ($user->isStaff()) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_peminjam', 'like', "%{$keyword}%")
                  ->orWhere('kode_peminjaman', 'like', "%{$keyword}%")
                  ->orWhereHas('barang', fn($b) => $b->where('nama_barang', 'like', "%{$keyword}%"));
            });
        }
        if ($request->filled('tanggal_dari')) {
            $query->where('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->where('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjamans    = $query->latest()->paginate(15)->withQueryString();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')
                                    ->orWhere(fn($q) => $q->where('status','dipinjam')->where('tanggal_kembali_rencana','<',now()))
                                    ->count();
        $totalMenunggu  = Peminjaman::where('status', 'menunggu')->count();

        return view('peminjaman.index', compact('peminjamans', 'totalTerlambat', 'totalMenunggu'));
    }

    public function create(Request $request)
    {
        $barangs = Barang::where('jumlah_tersedia', '>', 0)
                         ->with('kategori')
                         ->orderBy('nama_barang')
                         ->get();
        $selectedBarang = $request->filled('barang_id') ? Barang::find($request->barang_id) : null;
        return view('peminjaman.create', compact('barangs', 'selectedBarang'));
    }

    public function store(PeminjamanRequest $request)
    {
        $validated = $request->validated();
        $barang    = Barang::findOrFail($validated['barang_id']);
        $user      = auth()->user();

        // Validasi stok mencukupi
        if ($barang->jumlah_tersedia < $validated['jumlah_pinjam']) {
            return back()->withInput()
                         ->with('error', "Stok tersedia hanya {$barang->jumlah_tersedia} {$barang->satuan}.");
        }

        DB::transaction(function () use ($validated, $barang, $user) {
            $validated['kode_peminjaman'] = $this->generateKodePeminjaman();
            $validated['user_id']         = $user->id;

            if ($user->isAdmin()) {
                // Admin → langsung dipinjam, kurangi stok
                $validated['status'] = 'dipinjam';
                Peminjaman::create($validated);
                $barang->decrement('jumlah_tersedia', $validated['jumlah_pinjam']);
            } else {
                // Staff → menunggu ACC admin, stok belum berkurang
                $validated['status'] = 'menunggu';
                Peminjaman::create($validated);
            }
        });

        $msg = $user->isAdmin()
            ? 'Peminjaman berhasil dicatat.'
            : 'Permintaan peminjaman berhasil diajukan. Menunggu persetujuan admin.';

        return redirect()->route('peminjaman.index')->with('success', $msg);
    }

    public function show(Peminjaman $peminjaman)
    {
        // Staff hanya bisa lihat peminjaman miliknya sendiri
        if (auth()->user()->isStaff() && $peminjaman->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }
        $peminjaman->load(['barang.kategori', 'user']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Admin: Setujui permintaan peminjaman dari staff.
     */
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->route('peminjaman.show', $peminjaman)
                             ->with('info', 'Peminjaman ini tidak dalam status menunggu.');
        }

        $barang = $peminjaman->barang;

        if ($barang->jumlah_tersedia < $peminjaman->jumlah_pinjam) {
            return redirect()->route('peminjaman.show', $peminjaman)
                             ->with('error', "Stok tidak mencukupi. Tersedia: {$barang->jumlah_tersedia} {$barang->satuan}.");
        }

        DB::transaction(function () use ($peminjaman, $barang) {
            $peminjaman->update(['status' => 'dipinjam']);
            $barang->decrement('jumlah_tersedia', $peminjaman->jumlah_pinjam);
        });

        return redirect()->route('peminjaman.show', $peminjaman)
                         ->with('success', 'Peminjaman berhasil disetujui. Stok telah dikurangi.');
    }

    /**
     * Admin: Tolak permintaan peminjaman dari staff.
     */
    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'menunggu') {
            return redirect()->route('peminjaman.show', $peminjaman)
                             ->with('info', 'Peminjaman ini tidak dalam status menunggu.');
        }

        $validated = $request->validate([
            'alasan_tolak' => ['nullable', 'string', 'max:500'],
        ]);

        $peminjaman->update([
            'status'       => 'ditolak',
            'alasan_tolak' => $validated['alasan_tolak'] ?? null,
        ]);

        return redirect()->route('peminjaman.show', $peminjaman)
                         ->with('success', 'Permintaan peminjaman telah ditolak.');
    }

    public function formPengembalian(Peminjaman $peminjaman)
    {
        // DEPRECATED: pengembalian sekarang entitas terpisah
        // Redirect ke form pengembalian baru dengan peminjaman_id
        return redirect()->route('pengembalian.create', ['peminjaman_id' => $peminjaman->id]);
    }

    public function prosesPengembalian(Request $request, Peminjaman $peminjaman)
    {
        // DEPRECATED: handled by PengembalianController@store
        return redirect()->route('pengembalian.create', ['peminjaman_id' => $peminjaman->id]);
    }

    private function generateKodePeminjaman(): string
    {
        $tahun = now()->year;
        $last  = Peminjaman::where('kode_peminjaman', 'like', "PMJ-{$tahun}-%")
                            ->orderBy('kode_peminjaman', 'desc')
                            ->first();
        $urutan = $last ? (int) substr($last->kode_peminjaman, -4) + 1 : 1;
        return "PMJ-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
