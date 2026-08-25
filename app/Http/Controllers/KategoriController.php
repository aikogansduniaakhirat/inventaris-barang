<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('barangs')->orderBy('nama_kategori')->get();
        return view('kategori.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', 'unique:kategoris,nama_kategori'],
            'kode_kategori' => ['nullable', 'string', 'max:10', 'unique:kategoris,kode_kategori'],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
            'warna'         => ['required', 'string', 'max:20'],
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah ada.',
            'kode_kategori.unique' => 'Kode kategori sudah digunakan.',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', Rule::unique('kategoris', 'nama_kategori')->ignore($kategori->id_kategoris)],
            'kode_kategori' => ['nullable', 'string', 'max:10', Rule::unique('kategoris', 'kode_kategori')->ignore($kategori->id_kategoris)],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
            'warna'         => ['required', 'string', 'max:20'],
            'is_active'     => ['boolean'],
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barangs()->count() > 0) {
            return redirect()->route('kategori.index')
                             ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki ' . $kategori->barangs()->count() . ' barang.');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
