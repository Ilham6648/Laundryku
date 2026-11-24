<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransaksiRequest;
use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Tampilkan rekap transaksi laundry & laporan keuangan dengan filter status.
     */
    public function index(Request $request): View
    {
        $filters = [
            'status_pengerjaan' => $request->string('status_pengerjaan')->toString() ?: null,
            'status_bayar' => $request->string('status_bayar')->toString() ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ];

        $transaksis = Transaksi::query()
            ->with('layanan')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalPemasukanKeseluruhan = (int) Transaksi::query()->sum('total_harga');
        $totalPemasukanLunas = (int) Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_LUNAS)
            ->sum('total_harga');
        $totalBelumLunas = (int) Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_BELUM_LUNAS)
            ->sum('total_harga');
        $totalTransaksiProses = Transaksi::query()
            ->where('status_pengerjaan', Transaksi::STATUS_PENGERJAAN_PROSES)
            ->count();
        $totalTransaksiSelesai = Transaksi::query()
            ->where('status_pengerjaan', Transaksi::STATUS_PENGERJAAN_SELESAI)
            ->count();
        $jumlahLunas = Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_LUNAS)
            ->count();
        $jumlahBelumLunas = Transaksi::query()
            ->where('status_bayar', Transaksi::STATUS_BAYAR_BELUM_LUNAS)
            ->count();

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

        return view('transaksis.index', [
            'transaksis' => $transaksis,
            'filters' => $filters,
            'totalPemasukanKeseluruhan' => $totalPemasukanKeseluruhan,
            'totalPemasukanLunas' => $totalPemasukanLunas,
            'totalBelumLunas' => $totalBelumLunas,
            'totalTransaksiProses' => $totalTransaksiProses,
            'totalTransaksiSelesai' => $totalTransaksiSelesai,
            'jumlahLunas' => $jumlahLunas,
            'jumlahBelumLunas' => $jumlahBelumLunas,
            'statistikLayanan' => $statistikLayanan,
            'trenHarian' => $trenHarian,
        ]);
    }

    /**
     * Tampilkan halaman cetak rekap order laundry sesuai filter aktif.
     */
    public function cetak(Request $request): View
    {
        $filters = [
            'status_pengerjaan' => $request->string('status_pengerjaan')->toString() ?: null,
            'status_bayar' => $request->string('status_bayar')->toString() ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ];

        $transaksis = Transaksi::query()
            ->with('layanan')
            ->filter($filters)
            ->latest()
            ->get();

        $totalPemasukanKeseluruhan = (int) $transaksis->sum('total_harga');
        $totalPemasukanLunas = (int) $transaksis
            ->where('status_bayar', Transaksi::STATUS_BAYAR_LUNAS)
            ->sum('total_harga');
        $totalBelumLunas = (int) $transaksis
            ->where('status_bayar', Transaksi::STATUS_BAYAR_BELUM_LUNAS)
            ->sum('total_harga');
        $totalBeratKg = (float) $transaksis->sum('berat_kg');

        return view('transaksis.cetak', [
            'transaksis' => $transaksis,
            'filters' => $filters,
            'totalPemasukanKeseluruhan' => $totalPemasukanKeseluruhan,
            'totalPemasukanLunas' => $totalPemasukanLunas,
            'totalBelumLunas' => $totalBelumLunas,
            'totalBeratKg' => $totalBeratKg,
        ]);
    }

    /**
     * Tampilkan halaman cetak struk kasir untuk satu transaksi laundry.
     */
    public function struk(Transaksi $transaksi): View
    {
        $transaksi->load('layanan');

        return view('transaksis.struk', [
            'transaksi' => $transaksi,
        ]);
    }

    /**
     * Tampilkan formulir pencatatan transaksi laundry baru.
     */
    public function create(): View
    {
        $layanans = Layanan::query()
            ->orderBy('harga_per_kg')
            ->get();

        return view('transaksis.create', [
            'layanans' => $layanans,
        ]);
    }

    /**
     * Simpan transaksi laundry baru dengan perhitungan tarif tervalidasi di backend.
     */
    public function store(StoreTransaksiRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $layanan = Layanan::query()->findOrFail($validated['layanan_id']);

        $beratKg = (float) $validated['berat_kg'];
        $totalHarga = Transaksi::hitungTotalHarga($beratKg, $layanan->harga_per_kg);

        $transaksi = Transaksi::query()->create([
            'kode_transaksi' => Transaksi::generateKodeTransaksi(),
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'no_hp' => $validated['no_hp'],
            'layanan_id' => $layanan->id,
            'berat_kg' => $beratKg,
            'total_harga' => $totalHarga,
            'status_bayar' => $validated['status_bayar'],
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_PROSES,
        ]);

        return redirect()
            ->route('transaksis.index')
            ->with('success', "Transaksi {$transaksi->kode_transaksi} untuk {$transaksi->nama_pelanggan} berhasil dicatat.");
    }

    /**
     * Ubah status pengerjaan transaksi menjadi Selesai dengan 1-klik.
     */
    public function tandaiSelesai(Transaksi $transaksi): RedirectResponse
    {
        if ($transaksi->isSelesai()) {
            return back()->with('success', "Transaksi {$transaksi->kode_transaksi} sudah berstatus Selesai.");
        }

        $transaksi->update([
            'status_pengerjaan' => Transaksi::STATUS_PENGERJAAN_SELESAI,
        ]);

        return back()->with('success', "Status pengerjaan {$transaksi->kode_transaksi} berhasil diubah menjadi Selesai.");
    }

    /**
     * Ubah status pembayaran transaksi menjadi Lunas dengan 1-klik.
     */
    public function tandaiLunas(Transaksi $transaksi): RedirectResponse
    {
        if ($transaksi->isLunas()) {
            return back()->with('success', "Transaksi {$transaksi->kode_transaksi} sudah berstatus Lunas.");
        }

        $transaksi->update([
            'status_bayar' => Transaksi::STATUS_BAYAR_LUNAS,
        ]);

        return back()->with('success', "Pembayaran transaksi {$transaksi->kode_transaksi} telah ditandai Lunas.");
    }
}
