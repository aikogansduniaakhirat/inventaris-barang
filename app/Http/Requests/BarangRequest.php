<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $barangId = $this->route('barang')?->id_barangs;

        return [
            'nama_barang'  => ['required', 'string', 'max:200'],
            'kategori_id'  => ['required', 'exists:kategoris,id_kategoris'],
            'merk'         => ['nullable', 'string', 'max:100'],
            'jumlah'       => ['required', 'integer', 'min:0'],
            'jumlah_tersedia' => ['required', 'integer', 'min:0', 'lte:jumlah'],
            'satuan'       => ['required', 'string', 'max:30'],
            'lokasi'       => ['nullable', 'string', 'max:200'],
            'kondisi'      => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'nilai'        => ['nullable', 'numeric', 'min:0'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'keterangan'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_barang'     => 'Nama Barang',
            'kategori_id'     => 'Kategori',
            'merk'            => 'Merk',
            'jumlah'          => 'Jumlah',
            'jumlah_tersedia' => 'Jumlah Tersedia',
            'satuan'          => 'Satuan',
            'lokasi'          => 'Lokasi',
            'kondisi'         => 'Kondisi',
            'nilai'           => 'Nilai/Harga',
            'foto'            => 'Foto',
            'keterangan'      => 'Keterangan',
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_tersedia.lte' => 'Jumlah tersedia tidak boleh melebihi jumlah total.',
            'foto.max'            => 'Ukuran foto tidak boleh lebih dari 2MB.',
            'foto.mimes'          => 'Format foto harus JPG, JPEG, PNG, atau WebP.',
        ];
    }
}
