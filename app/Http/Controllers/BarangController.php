<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BarangController extends Controller
{
    /**
     * Whitelist kolom yang boleh di-sort oleh user (anti SQL injection di orderBy).
     * 'nama_kategori' = sort by relasi (handled by leftJoin).
     */
    private const SORTABLE = [
        'id_barangs', 'nama_barang', 'kode_barang', 'merk', 'jumlah',
        'jumlah_tersedia', 'kondisi', 'lokasi', 'nilai', 'created_at',
        'nama_kategori',
    ];

    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        $sort      = $request->get('sort', 'nama_barang');
        $direction = $request->get('direction', 'asc');
        $direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';

        if (in_array($sort, self::SORTABLE)) {
            if ($sort === 'nama_kategori') {
                // Sort by relasi: leftJoin kategoris
                $query->leftJoin('kategoris', 'barangs.kategori_id', '=', 'kategoris.id_kategoris')
                      ->select('barangs.*')
                      ->orderBy('kategoris.nama_kategori', $direction);
            } else {
                $query->orderBy($sort, $direction);
            }
        } else {
            // Fallback ke default kalau sort param di-whitelist
            $query->orderBy('nama_barang', 'asc');
        }

        $barangs   = $query->paginate(15)->withQueryString();
        $kategoris = Kategori::where('is_active', true)->orderBy('nama_kategori')->get();
        $lokasis   = Barang::distinct()->pluck('lokasi')->filter()->sort()->values();

        return view('barang.index', compact('barangs', 'kategoris', 'lokasis', 'sort', 'direction'));
    }

    public function create()
    {
        $kategoris = Kategori::where('is_active', true)->orderBy('nama_kategori')->get();
        return view('barang.create', compact('kategoris'));
    }

    public function store(BarangRequest $request)
    {
        $data = $request->validated();

        // Generate kode barang otomatis
        $data['kode_barang'] = $this->generateKodeBarang($request->kategori_id);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        }

        $barang = Barang::create($data);

        return redirect()->route('barang.show', $barang)
                         ->with('success', 'Barang <strong>' . $barang->nama_barang . '</strong> berhasil ditambahkan dengan kode <code>' . $barang->kode_barang . '</code>.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'peminjamans' => fn($q) => $q->with('user')->latest()->take(10)]);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        $kategoris = Kategori::where('is_active', true)->orderBy('nama_kategori')->get();
        return view('barang.edit', compact('barang', 'kategoris'));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $data = $request->validated();

        // Handle upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($barang->foto) {
                Storage::disk('public')->delete($barang->foto);
            }
            $data['foto'] = $request->file('foto')->store('barang', 'public');
        } else {
            unset($data['foto']); // Jangan overwrite foto lama
        }

        $barang->update($data);

        return redirect()->route('barang.show', $barang)
                         ->with('success', 'Data barang <strong>' . $barang->nama_barang . '</strong> berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        // Cek apakah masih ada peminjaman aktif
        if ($barang->peminjamansAktif()->count() > 0) {
            return redirect()->route('barang.index')
                             ->with('error', 'Barang tidak dapat dihapus karena masih dalam status dipinjam.');
        }

        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }

        $barang->delete(); // SoftDelete
        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus dari inventaris.');
    }

    public function hapusFoto(Barang $barang)
    {
        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
            $barang->update(['foto' => null]);
        }
        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function formLaporKerusakan(Barang $barang)
    {
        return view('barang.lapor-kerusakan', compact('barang'));
    }

    public function prosesLaporKerusakan(Request $request, Barang $barang)
    {
        $request->validate([
            'jenis_kerusakan' => 'required|in:rusak_ringan,rusak_berat,perbaikan',
            'jumlah_laporan'  => 'required|integer|min:1',
            'keterangan'      => 'nullable|string|max:500',
        ]);

        $jenis   = $request->jenis_kerusakan;
        $jumlah  = (int) $request->jumlah_laporan;

        // Validasi: jumlah yang dilaporkan tidak melebihi stok tersedia (untuk rusak/berat)
        if ($jenis !== 'perbaikan') {
            $maks = $barang->jumlah_tersedia;
            if ($jumlah > $maks) {
                return back()->withInput()
                    ->with('error', "Jumlah rusak ({$jumlah}) melebihi stok tersedia ({$maks} {$barang->satuan}).");
            }

            // Update kolom rusak & kurangi tersedia
            if ($jenis === 'rusak_ringan') {
                $barang->jumlah_rusak_ringan += $jumlah;
            } else {
                $barang->jumlah_rusak_berat += $jumlah;
            }
            $barang->jumlah_tersedia -= $jumlah;

        } else {
            // Perbaikan: kembalikan unit rusak_ringan ke tersedia
            $maks = $barang->jumlah_rusak_ringan;
            if ($jumlah > $maks) {
                return back()->withInput()
                    ->with('error', "Jumlah perbaikan ({$jumlah}) melebihi unit rusak ringan yang tercatat ({$maks} {$barang->satuan}).");
            }
            $barang->jumlah_rusak_ringan -= $jumlah;
            $barang->jumlah_tersedia     += $jumlah;
        }

        // Otomatis update kondisi berdasarkan komposisi kerusakan
        $totalRusak = $barang->jumlah_rusak_ringan + $barang->jumlah_rusak_berat;
        if ($totalRusak === 0) {
            $barang->kondisi = 'baik';
        } elseif ($barang->jumlah_rusak_berat > 0) {
            $barang->kondisi = 'rusak_berat';
        } else {
            $barang->kondisi = 'rusak_ringan';
        }

        // Append keterangan
        if ($request->filled('keterangan')) {
            $prefix = now()->format('d/m/Y') . ' — ' . ucfirst(str_replace('_', ' ', $jenis)) . " ({$jumlah} unit): ";
            $barang->keterangan = ltrim(($barang->keterangan ? $barang->keterangan . "\n" : '') . $prefix . $request->keterangan);
        }

        $barang->save();

        $pesan = $jenis === 'perbaikan'
            ? "{$jumlah} unit berhasil dicatat sebagai selesai diperbaiki."
            : "{$jumlah} unit berhasil dilaporkan sebagai " . str_replace('_', ' ', $jenis) . ".";

        return redirect()->route('barang.show', $barang)->with('success', $pesan);
    }


    private function generateKodeBarang(int $kategoriId): string
    {
        $kategori    = Kategori::find($kategoriId);
        $kode        = $kategori ? strtoupper($kategori->kode_kategori ?? Str::upper(Str::substr($kategori->nama_kategori, 0, 3))) : 'BRG';
        $tahun       = now()->year;
        $lastBarang  = Barang::withTrashed()
                              ->where('kode_barang', 'like', "{$kode}-{$tahun}-%")
                              ->orderBy('kode_barang', 'desc')
                              ->first();

        $urutan = $lastBarang
            ? (int) substr($lastBarang->kode_barang, -4) + 1
            : 1;

        return "{$kode}-{$tahun}-" . str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }
}
