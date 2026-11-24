<?php

namespace Database\Factories;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $berat = fake()->randomFloat(1, 1, 10);
        $hargaPerKg = 7000;

        return [
            'kode_transaksi' => 'TRX-'.now()->format('Ymd').'-'.fake()->unique()->numerify('####'),
            'nama_pelanggan' => fake()->name(),
            'no_hp' => '08'.fake()->numerify('##########'),
            'layanan_id' => Layanan::factory(),
            'berat_kg' => $berat,
            'total_harga' => Transaksi::hitungTotalHarga($berat, $hargaPerKg),
            'status_bayar' => fake()->randomElement([Transaksi::STATUS_BAYAR_LUNAS, Transaksi::STATUS_BAYAR_BELUM_LUNAS]),
            'status_pengerjaan' => fake()->randomElement([Transaksi::STATUS_PENGERJAAN_PROSES, Transaksi::STATUS_PENGERJAAN_SELESAI]),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Transaksi $transaksi): void {
            if ($transaksi->layanan_id && $transaksi->layanan) {
                $transaksi->total_harga = Transaksi::hitungTotalHarga(
                    (float) $transaksi->berat_kg,
                    (int) $transaksi->layanan->harga_per_kg,
                );
            }
        });
    }
}
