<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::where('role', 'staff')->first();
        $admin = User::where('role', 'admin')->first();

        if (!$staff || !$admin) {
            $this->command->warn('PeminjamanSeeder dilewati: User admin/staff belum ada.');
            return;
        }

        $barangs = Barang::take(5)->get();
        if ($barangs->count() < 5) {
            $this->command->warn('PeminjamanSeeder butuh minimal 5 barang, seed BarangSeeder dulu.');
            return;
        }

        // ─────────────────────────────────────────────────────────────────
        // PMJ-2024-0001: sedang dipinjam (belum dikembalikan)
        // ─────────────────────────────────────────────────────────────────
        $p1 = Peminjaman::create([
            'kode_peminjaman'         => 'PMJ-2024-0001',
            'barang_id'               => $barangs[0]->id_barangs,
            'user_id'                 => $staff->id_users,
            'nama_peminjam'           => 'Ahmad Fauzi',
            'instansi_peminjam'       => 'Kelas XI IPA 1',
            'jumlah_pinjam'           => 1,
            'tanggal_pinjam'          => Carbon::now()->subDays(5)->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(2)->toDateString(),
            'status'                  => 'dipinjam',
            'keterangan'              => 'Untuk keperluan presentasi tugas',
        ]);

        // ─────────────────────────────────────────────────────────────────
        // PMJ-2024-0002: terlambat (belum dikembalikan, lewat tanggal rencana)
        // ─────────────────────────────────────────────────────────────────
        $p2 = Peminjaman::create([
            'kode_peminjaman'         => 'PMJ-2024-0002',
            'barang_id'               => $barangs[1]->id_barangs,
            'user_id'                 => $admin->id_users,
            'nama_peminjam'           => 'Bapak Hendra',
            'instansi_peminjam'       => 'Guru Matematika',
            'jumlah_pinjam'           => 1,
            'tanggal_pinjam'          => Carbon::now()->subDays(10)->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->subDays(3)->toDateString(),
            'status'                  => 'terlambat',
            'keterangan'              => 'Untuk pembelajaran di kelas',
        ]);

        // ─────────────────────────────────────────────────────────────────
        // PMJ-2024-0003: SUDAH dikembalikan (2 unit, full return)
        //  → insert ke pengembalians (entitas terpisah)
        // ─────────────────────────────────────────────────────────────────
        $p3 = Peminjaman::create([
            'kode_peminjaman'         => 'PMJ-2024-0003',
            'barang_id'               => $barangs[2]->id_barangs,
            'user_id'                 => $staff->id_users,
            'nama_peminjam'           => 'Siti Nurhaliza',
            'instansi_peminjam'       => 'Kelas XII IPS 2',
            'jumlah_pinjam'           => 2,
            'tanggal_pinjam'          => Carbon::now()->subDays(15)->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->subDays(8)->toDateString(),
            'status'                  => 'dikembalikan',
            'keterangan'              => 'Untuk praktikum biologi',
        ]);

        Pengembalian::create([
            'kode_pengembalian'   => 'KMB-2024-0001',
            'peminjaman_id'       => $p3->id_peminjamans,
            'user_id'             => $admin->id_users,
            'jumlah_kembali'      => 2,
            'tanggal_kembali'     => Carbon::now()->subDays(8)->toDateString(),
            'kondisi_kembali'     => 'baik',
            'keterangan'          => 'Dikembalikan tepat waktu dalam kondisi baik',
        ]);

        // ─────────────────────────────────────────────────────────────────
        // PMJ-2024-0004: dipinjam oleh staff (lagi presentasi)
        // ─────────────────────────────────────────────────────────────────
        Peminjaman::create([
            'kode_peminjaman'         => 'PMJ-2024-0004',
            'barang_id'               => $barangs[3]->id_barangs,
            'user_id'                 => $staff->id_users,
            'nama_peminjam'           => 'Dewi Lestari',
            'instansi_peminjam'       => 'Kelas X MIPA 3',
            'jumlah_pinjam'           => 1,
            'tanggal_pinjam'          => Carbon::now()->subDays(2)->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(5)->toDateString(),
            'status'                  => 'dipinjam',
            'keterangan'              => 'Untuk praktikum multimedia',
        ]);

        // ─────────────────────────────────────────────────────────────────
        // PMJ-2024-0005: menunggu persetujuan (status = menunggu)
        // ─────────────────────────────────────────────────────────────────
        Peminjaman::create([
            'kode_peminjaman'         => 'PMJ-2024-0005',
            'barang_id'               => $barangs[4]->id_barangs,
            'user_id'                 => $staff->id_users,
            'nama_peminjam'           => 'Rudi Hermawan',
            'instansi_peminjam'       => 'Kelas XI IPS 1',
            'jumlah_pinjam'           => 1,
            'tanggal_pinjam'          => Carbon::now()->toDateString(),
            'tanggal_kembali_rencana' => Carbon::now()->addDays(7)->toDateString(),
            'status'                  => 'menunggu',
            'keterangan'              => 'Untuk kegiatan OSIS',
        ]);
    }
}
