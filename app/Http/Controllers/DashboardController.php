<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan ringkasan operasional & laporan keuangan laundry.
     */
    public function __invoke(): View
    {
        $jumlahProses = Transaksi::query()
            ->where('status_pengerjaan', Transaksi::STATUS_PENGERJAAN_PROSES)
            ->count();

        $jumlahSelesai = Transaksi::query()
            ->where('status_pengerjaan', Transaksi::STATUS_PENGERJAAN_SELESAI)
            ->count();

        $jumlahLunas = Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_LUNAS)
            ->count();

        $jumlahBelumLunas = Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_BELUM_LUNAS)
            ->count();

        $totalPendapatanLunas = (int) Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_LUNAS)
            ->sum('total_harga');

        $totalPiutang = (int) Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_BELUM_LUNAS)
            ->sum('total_harga');

        $totalPendapatanKeseluruhan = $totalPendapatanLunas + $totalPiutang;

        $transaksiTerbaru = Transaksi::query()
            ->with('layanan')
            ->latest()
            ->limit(6)
            ->get();

        $jumlahLayanan = Layanan::query()->count();

        $statistikLayanan = Layanan::query()
            ->withCount('transaksis')
            ->withSum('transaksis as total_pendapatan', 'total_harga')
            ->withSum('transaksis as total_berat', 'berat_kg')
            ->orderBy('harga_per_kg')
            ->get();

        $startDate = now()->subDays(6)->startOfDay();
        $recentTransaksis = Transaksi::query()
            ->where('created_at', '>=', $startDate)
            ->get(['id', 'total_harga', 'created_at']);

        $trenHarian = collect(range(6, 0))->map(function (int $daysAgo) use ($recentTransaksis): array {
            $date = now()->subDays($daysAgo);
            $dateKey = $date->format('Y-m-d');
            $items = $recentTransaksis->filter(
                fn (Transaksi $t): bool => $t->created_at?->format('Y-m-d') === $dateKey
            );

            return [
                'tanggal' => $dateKey,
                'label' => $date->format('d M'),
                'jumlah_order' => $items->count(),
                'pemasukan' => (int) $items->sum('total_harga'),
            ];
        })->all();

        return view('dashboard', [
            'jumlahProses' => $jumlahProses,
            'jumlahSelesai' => $jumlahSelesai,
            'jumlahLunas' => $jumlahLunas,
            'jumlahBelumLunas' => $jumlahBelumLunas,
            'totalTransaksi' => $jumlahProses + $jumlahSelesai,
            'totalPendapatanLunas' => $totalPendapatanLunas,
            'totalPiutang' => $totalPiutang,
            'totalPendapatanKeseluruhan' => $totalPendapatanKeseluruhan,
            'jumlahLayanan' => $jumlahLayanan,
            'transaksiTerbaru' => $transaksiTerbaru,
            'statistikLayanan' => $statistikLayanan,
            'trenHarian' => $trenHarian,
        ]);
    }
}
