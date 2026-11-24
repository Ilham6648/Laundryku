<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Transaksi::query()->exists()) {
            return;
        }

        $reguler = Layanan::query()->where('nama_layanan', 'Reguler')->first();
        $ekspres = Layanan::query()->where('nama_layanan', 'Ekspres')->first();
        $superkilat = Layanan::query()->where('nama_layanan', 'Superkilat')->first();

        if (! $reguler || ! $ekspres || ! $superkilat) {
            return;
        }

        $samples = [
            [
                'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-0001',
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'layanan' => $reguler,
                'berat_kg' => 4.5,
                'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
                'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
            ],
            [
                'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-0002',
                'nama_pelanggan' => 'Siti Aminah',
                'no_hp' => '085678901234',
                'layanan' => $ekspres,
                'berat_kg' => 3.0,
                'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
                'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
            ],
            [
                'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-0003',
                'nama_pelanggan' => 'Andi Wijaya',
                'no_hp' => '087812345678',
                'layanan' => $superkilat,
                'berat_kg' => 2.0,
                'status_bayar' => Transaksi::STATUS_BAYAR_BELUM_LUNAS,
                'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
            ],
            [
                'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-0004',
                'nama_pelanggan' => 'Dewi Lestari',
                'no_hp' => '082198765432',
                'layanan' => $reguler,
                'berat_kg' => 6.0,
                'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
                'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
            ],
        ];

        foreach ($samples as $sample) {
            Transaksi::query()->create([
                'kode_transaksi' => $sample['kode_transaksi'],
                'nama_pelanggan' => $sample['nama_pelanggan'],
                'no_hp' => $sample['no_hp'],
                'layanan_id' => $sample['layanan']->id,
                'berat_kg' => $sample['berat_kg'],
                'total_harga' => Transaksi::hitungTotalHarga($sample['berat_kg'], $sample['layanan']->harga_per_kg),
                'status_bayar' => $sample['status_bayar'],
                'status_pengerjaan' => $sample['status_pengerjaan'],
            ]);
        }
    }
}
