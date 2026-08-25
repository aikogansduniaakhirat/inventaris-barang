<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $elk = Kategori::where('kode_kategori', 'ELK')->first()->id_kategoris;
        $frn = Kategori::where('kode_kategori', 'FRN')->first()->id_kategoris;
        $lab = Kategori::where('kode_kategori', 'LAB')->first()->id_kategoris;
        $olr = Kategori::where('kode_kategori', 'OLR')->first()->id_kategoris;
        $atk = Kategori::where('kode_kategori', 'ATK')->first()->id_kategoris;
        $lnl = Kategori::where('kode_kategori', 'LNL')->first()->id_kategoris;

        $barangs = [
            // Elektronik
            ['kode_barang'=>'ELK-2024-0001','nama_barang'=>'Laptop ASUS VivoBook','kategori_id'=>$elk,'merk'=>'ASUS','jumlah'=>15,'jumlah_tersedia'=>12,'lokasi'=>'Lab Komputer','kondisi'=>'baik','nilai'=>8500000,'keterangan'=>'Intel Core i5, RAM 8GB, SSD 512GB'],
            ['kode_barang'=>'ELK-2024-0002','nama_barang'=>'Proyektor Epson EB-X41','kategori_id'=>$elk,'merk'=>'Epson','jumlah'=>8,'jumlah_tersedia'=>6,'lokasi'=>'Gudang AV','kondisi'=>'baik','nilai'=>4200000,'keterangan'=>'3600 Lumens, WXGA'],
            ['kode_barang'=>'ELK-2024-0003','nama_barang'=>'Komputer PC All-in-One','kategori_id'=>$elk,'merk'=>'HP','jumlah'=>20,'jumlah_tersedia'=>20,'lokasi'=>'Lab Komputer','kondisi'=>'baik','nilai'=>6500000,'keterangan'=>'Intel Core i3, RAM 4GB'],
            ['kode_barang'=>'ELK-2024-0004','nama_barang'=>'Printer Canon G3010','kategori_id'=>$elk,'merk'=>'Canon','jumlah'=>5,'jumlah_tersedia'=>4,'lokasi'=>'Ruang TU','kondisi'=>'baik','nilai'=>1800000,'keterangan'=>'Inkjet, WiFi printing'],
            ['kode_barang'=>'ELK-2024-0005','nama_barang'=>'Speaker Aktif','kategori_id'=>$elk,'merk'=>'JBL','jumlah'=>6,'jumlah_tersedia'=>5,'lokasi'=>'Gudang AV','kondisi'=>'rusak_ringan','nilai'=>750000,'keterangan'=>'Salah satu unit volume drop'],
            // Furnitur
            ['kode_barang'=>'FRN-2024-0001','nama_barang'=>'Meja Belajar Siswa','kategori_id'=>$frn,'merk'=>'Olympic','jumlah'=>200,'jumlah_tersedia'=>180,'lokasi'=>'Gudang Furnitur','kondisi'=>'baik','nilai'=>350000,'keterangan'=>'Ukuran standar 60x40cm'],
            ['kode_barang'=>'FRN-2024-0002','nama_barang'=>'Kursi Siswa','kategori_id'=>$frn,'merk'=>'Olympic','jumlah'=>220,'jumlah_tersedia'=>200,'lokasi'=>'Gudang Furnitur','kondisi'=>'baik','nilai'=>180000,'keterangan'=>'Besi powder coating'],
            ['kode_barang'=>'FRN-2024-0003','nama_barang'=>'Meja Guru','kategori_id'=>$frn,'merk'=>'Chitose','jumlah'=>30,'jumlah_tersedia'=>28,'lokasi'=>'Gudang Furnitur','kondisi'=>'baik','nilai'=>650000,'keterangan'=>'Dengan laci kunci'],
            ['kode_barang'=>'FRN-2024-0004','nama_barang'=>'Lemari Arsip','kategori_id'=>$frn,'merk'=>'Brother','jumlah'=>10,'jumlah_tersedia'=>8,'lokasi'=>'Ruang TU','kondisi'=>'baik','nilai'=>1200000,'keterangan'=>'4 pintu, besi'],
            ['kode_barang'=>'FRN-2024-0005','nama_barang'=>'Whiteboard 120x240','kategori_id'=>$frn,'merk'=>'Sakana','jumlah'=>25,'jumlah_tersedia'=>23,'lokasi'=>'Gudang Furnitur','kondisi'=>'baik','nilai'=>420000,'keterangan'=>'Magnetic whiteboard'],
            // Lab
            ['kode_barang'=>'LAB-2024-0001','nama_barang'=>'Mikroskop Binokuler','kategori_id'=>$lab,'merk'=>'Olympus','jumlah'=>20,'jumlah_tersedia'=>18,'lokasi'=>'Lab Biologi','kondisi'=>'baik','nilai'=>2500000,'keterangan'=>'Perbesaran 40x-1000x'],
            ['kode_barang'=>'LAB-2024-0002','nama_barang'=>'Gelas Beker 500ml','kategori_id'=>$lab,'merk'=>'Pyrex','jumlah'=>50,'jumlah_tersedia'=>45,'lokasi'=>'Lab Kimia','kondisi'=>'baik','nilai'=>35000,'keterangan'=>'Borosilicate glass'],
            ['kode_barang'=>'LAB-2024-0003','nama_barang'=>'Timbangan Digital Presisi','kategori_id'=>$lab,'merk'=>'AND','jumlah'=>5,'jumlah_tersedia'=>4,'lokasi'=>'Lab Kimia','kondisi'=>'baik','nilai'=>850000,'keterangan'=>'Kapasitas 500g, akurasi 0.01g'],
            // Olahraga
            ['kode_barang'=>'OLR-2024-0001','nama_barang'=>'Bola Sepak','kategori_id'=>$olr,'merk'=>'Adidas','jumlah'=>10,'jumlah_tersedia'=>8,'lokasi'=>'Gudang Olahraga','kondisi'=>'baik','nilai'=>250000,'keterangan'=>'Size 5'],
            ['kode_barang'=>'OLR-2024-0002','nama_barang'=>'Net Badminton','kategori_id'=>$olr,'merk'=>'Li-Ning','jumlah'=>4,'jumlah_tersedia'=>4,'lokasi'=>'Gudang Olahraga','kondisi'=>'baik','nilai'=>180000,'keterangan'=>'Standar BWF'],
            ['kode_barang'=>'OLR-2024-0003','nama_barang'=>'Matras Senam','kategori_id'=>$olr,'merk'=>'Decathlon','jumlah'=>8,'jumlah_tersedia'=>8,'lokasi'=>'Gudang Olahraga','kondisi'=>'baik','nilai'=>320000,'keterangan'=>'Tebal 5cm, ukuran 1.2x2m'],
            // ATK
            ['kode_barang'=>'ATK-2024-0001','nama_barang'=>'Kertas HVS A4 80gr','kategori_id'=>$atk,'merk'=>'SiDU','jumlah'=>100,'jumlah_tersedia'=>100,'lokasi'=>'Ruang TU','kondisi'=>'baik','nilai'=>55000,'keterangan'=>'Per rim 500 lembar'],
            ['kode_barang'=>'ATK-2024-0002','nama_barang'=>'Spidol Whiteboard','kategori_id'=>$atk,'merk'=>'Snowman','jumlah'=>200,'jumlah_tersedia'=>180,'lokasi'=>'Ruang TU','kondisi'=>'baik','nilai'=>8000,'keterangan'=>'Set 4 warna'],
            // Lain-lain
            ['kode_barang'=>'LNL-2024-0001','nama_barang'=>'APAR 3kg','kategori_id'=>$lnl,'merk'=>'Fireguard','jumlah'=>12,'jumlah_tersedia'=>12,'lokasi'=>'Seluruh Gedung','kondisi'=>'baik','nilai'=>350000,'keterangan'=>'Cek berkala tiap 6 bulan'],
            ['kode_barang'=>'LNL-2024-0002','nama_barang'=>'Kamera CCTV','kategori_id'=>$lnl,'merk'=>'Hikvision','jumlah'=>24,'jumlah_tersedia'=>24,'lokasi'=>'Seluruh Gedung','kondisi'=>'baik','nilai'=>450000,'keterangan'=>'2MP, IR night vision'],
        ];

        foreach ($barangs as $b) {
            Barang::create($b);
        }
    }
}
