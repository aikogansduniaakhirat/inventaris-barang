<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barang_id'               => ['required', 'exists:barangs,id_barangs'],
            'nama_peminjam'           => ['required', 'string', 'max:200'],
            'instansi_peminjam'       => ['nullable', 'string', 'max:200'],
            'jumlah_pinjam'           => ['required', 'integer', 'min:1'],
            'tanggal_pinjam'          => ['required', 'date'],
            'tanggal_kembali_rencana' => ['required', 'date', 'after_or_equal:tanggal_pinjam'],
            'keterangan'              => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'barang_id'               => 'Barang',
            'nama_peminjam'           => 'Nama Peminjam',
            'instansi_peminjam'       => 'Kelas/Jabatan',
            'jumlah_pinjam'           => 'Jumlah Pinjam',
            'tanggal_pinjam'          => 'Tanggal Pinjam',
            'tanggal_kembali_rencana' => 'Tanggal Kembali',
            'keterangan'              => 'Keterangan',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal pinjam.',
        ];
    }
}
