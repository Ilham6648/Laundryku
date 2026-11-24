<?php

namespace Tests\Feature;

use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_and_admin_can_create_transaksi_with_server_calculated_total(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $response = $this->actingAs($kasir)->post(route('transaksis.store'), [
            'nama_pelanggan' => 'Rina Kartika',
            'no_hp' => '081298765432',
            'layanan_id' => $layanan->id,
            'berat_kg' => 3.5,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
            'total_harga' => 100, // Manipulated client value must be ignored
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI, // Must default to Proses
        ]);

        $response->assertRedirect(route('transaksis.index'));

        $this->assertDatabaseHas('transaksis', [
            'nama_pelanggan' => 'Rina Kartika',
            'no_hp' => '081298765432',
            'layanan_id' => $layanan->id,
            'total_harga' => 42000,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]);
    }

    public function test_kasir_can_mark_transaksi_selesai_and_lunas_with_one_click(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create(['harga_per_kg' => 7000]);
        $transaksi = Transaksi::factory()->create([
            'layanan_id' => $layanan->id,
            'berat_kg' => 2.0,
            'total_harga' => 14000,
            'status_bayar' => Transaksi::STATUS_BAYAR_BELUM_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]);

        $this->actingAs($kasir)
            ->from(route('transaksis.index'))
            ->patch(route('transaksis.selesai', $transaksi))
            ->assertRedirect(route('transaksis.index'));

        $this->actingAs($kasir)
            ->from(route('transaksis.index'))
            ->patch(route('transaksis.lunas', $transaksi))
            ->assertRedirect(route('transaksis.index'));

        $transaksi->refresh();
        $this->assertSame(Transaksi::STATUS_PENGERJAAN_SELESAI, $transaksi->status_pengerjaan);
        $this->assertSame(Transaksi::STATUS_BAYAR_LUNAS, $transaksi->status_bayar);
    }

    public function test_rekap_transaksi_filters_by_status_and_search(): void
    {
        $admin = User::factory()->admin()->create();
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Reguler', 'harga_per_kg' => 7000]);

        Transaksi::factory()->create([
            'nama_pelanggan' => 'Pelanggan Proses',
            'layanan_id' => $layanan->id,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
            'status_bayar' => Transaksi::STATUS_BAYAR_BELUM_LUNAS,
        ]);

        Transaksi::factory()->create([
            'nama_pelanggan' => 'Pelanggan Selesai',
            'layanan_id' => $layanan->id,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
        ]);

        $prosesResponse = $this->actingAs($admin)->get(route('transaksis.index', [
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]));
        $prosesResponse->assertOk();
        $prosesResponse->assertSee('Pelanggan Proses');
        $prosesResponse->assertDontSee('Pelanggan Selesai');

        $searchResponse = $this->actingAs($admin)->get(route('transaksis.index', [
            'search' => 'Pelanggan Selesai',
        ]));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Pelanggan Selesai');
        $searchResponse->assertDontSee('Pelanggan Proses');
    }

    public function test_dashboard_displays_accurate_summary_metrics(): void
    {
        $admin = User::factory()->admin()->create();
        $layanan = Layanan::factory()->create(['harga_per_kg' => 10000]);

        Transaksi::factory()->create([
            'layanan_id' => $layanan->id,
            'berat_kg' => 3.0,
            'total_harga' => 30000,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]);

        Transaksi::factory()->create([
            'layanan_id' => $layanan->id,
            'berat_kg' => 5.0,
            'total_harga' => 50000,
            'status_bayar' => Transaksi::STATUS_BAYAR_BELUM_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertOk();
        $response->assertViewHas('jumlahProses', 1);
        $response->assertViewHas('jumlahSelesai', 1);
        $response->assertViewHas('totalPendapatanLunas', 30000);
        $response->assertViewHas('totalPiutang', 50000);
        $response->assertViewHas('totalPendapatanKeseluruhan', 80000);
    }

    public function test_cetak_rekap_transaksi_displays_printable_report_and_totals(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Superkilat',
            'harga_per_kg' => 25000,
            'estimasi_waktu' => '5 jam selesai',
        ]);

        Transaksi::factory()->create([
            'kode_transaksi' => 'TRX-20261124-0099',
            'nama_pelanggan' => 'Pelanggan Cetak',
            'layanan_id' => $layanan->id,
            'berat_kg' => 2.0,
            'total_harga' => 50000,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
        ]);

        $response = $this->actingAs($kasir)->get(route('transaksis.cetak'));

        $response->assertOk();
        $response->assertSee('Laporan Rekapitulasi Order');
        $response->assertSee('TRX-20261124-0099');
        $response->assertSee('Pelanggan Cetak');
        $response->assertViewHas('totalPemasukanKeseluruhan', 50000);
    }

    public function test_kasir_can_view_and_print_struk_transaksi(): void
    {
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create([
            'nama_layanan' => 'Ekspres',
            'harga_per_kg' => 12000,
            'estimasi_waktu' => '1-2 hari',
        ]);

        $transaksi = Transaksi::factory()->create([
            'kode_transaksi' => 'TRX-20261124-0100',
            'nama_pelanggan' => 'Pelanggan Struk',
            'no_hp' => '081122334455',
            'layanan_id' => $layanan->id,
            'berat_kg' => 2.5,
            'total_harga' => 30000,
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]);

        $response = $this->actingAs($kasir)->get(route('transaksis.struk', $transaksi));

        $response->assertOk();
        $response->assertSee('LAUNDRYKU');
        $response->assertSee('TRX-20261124-0100');
        $response->assertSee('Pelanggan Struk');
        $response->assertSee('081122334455');
        $response->assertSee('Paket Ekspres');
        $response->assertSee('Cetak Struk');
    }

    public function test_admin_sees_grafik_rekap_transaksi_while_kasir_does_not(): void
    {
        $admin = User::factory()->admin()->create();
        $kasir = User::factory()->kasir()->create();
        $layanan = Layanan::factory()->create(['nama_layanan' => 'Reguler', 'harga_per_kg' => 7000]);

        Transaksi::factory()->create([
            'layanan_id' => $layanan->id,
            'berat_kg' => 3.0,
            'total_harga' => 21000,
        ]);

        $this->actingAs($admin)
            ->get(route('transaksis.index'))
            ->assertOk()
            ->assertSee('Grafik Rekapitulasi Transaksi')
            ->assertSee('Tren Transaksi 7 Hari Terakhir')
            ->assertSee('Pendapatan per Jenis Layanan');

        $this->actingAs($kasir)
            ->get(route('transaksis.index'))
            ->assertOk()
            ->assertDontSee('Grafik Rekapitulasi Transaksi');
    }
}
