<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Rekap Order — LaundryKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 print:bg-white text-slate-900 antialiased p-4 sm:p-8 print:p-0">
    <div class="max-w-5xl mx-auto space-y-4">
        {{-- Toolbar Aksi (Disembunyikan saat cetak) --}}
        <div class="print:hidden flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 rounded-2xl bg-white p-4 shadow-xs ring-1 ring-slate-200">
            <a href="{{ route('transaksis.index', array_filter($filters)) }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                <span>← Kembali ke Rekap Transaksi</span>
            </a>
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700 transition cursor-pointer">
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>

        {{-- Lembar Laporan --}}
        <div class="rounded-2xl print:rounded-none bg-white p-6 sm:p-10 print:p-2 shadow-sm print:shadow-none ring-1 ring-slate-200 print:ring-0 space-y-6">
            {{-- Kop Laporan --}}
            <div class="border-b-2 border-slate-900 pb-5 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-900 uppercase">LaundryKu</h1>
                    <p class="text-sm font-semibold text-slate-700">Laporan Rekapitulasi Order &amp; Keuangan Laundry</p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Filter:
                        Status Pengerjaan: <strong>{{ $filters['status_pengerjaan'] ?: 'Semua' }}</strong> •
                        Status Bayar: <strong>{{ $filters['status_bayar'] ?: 'Semua' }}</strong>
                        @if ($filters['search'])
                            • Kata Kunci: <strong>"{{ $filters['search'] }}"</strong>
                        @endif
                    </p>
                </div>
                <div class="text-xs text-slate-600 sm:text-right space-y-0.5">
                    <p>Tanggal Cetak: <strong>{{ now()->format('d M Y, H:i') }}</strong></p>
                    <p>Dicetak Oleh: <strong>{{ auth()->user()->name }} ({{ strtoupper(auth()->user()->role) }})</strong></p>
                    <p>Total Data: <strong>{{ $transaksis->count() }} Order</strong></p>
                </div>
            </div>

            {{-- Ringkasan Angka --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="rounded-xl border border-slate-200 p-3">
                    <span class="block text-slate-500">Total Berat Cucian</span>
                    <span class="mt-1 block text-base font-bold text-slate-900">{{ number_format($totalBeratKg, 1, ',', '.') }} kg</span>
                </div>
                <div class="rounded-xl border border-slate-200 p-3">
                    <span class="block text-slate-500">Pemasukan Lunas</span>
                    <span class="mt-1 block text-base font-bold text-emerald-700">Rp {{ number_format($totalPemasukanLunas, 0, ',', '.') }}</span>
                </div>
                <div class="rounded-xl border border-slate-200 p-3">
                    <span class="block text-slate-500">Tagihan Belum Lunas</span>
                    <span class="mt-1 block text-base font-bold text-rose-700">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</span>
                </div>
                <div class="rounded-xl border border-slate-200 p-3">
                    <span class="block text-slate-500">Total Keseluruhan</span>
                    <span class="mt-1 block text-base font-bold text-slate-900">Rp {{ number_format($totalPemasukanKeseluruhan, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tabel Rekap Order --}}
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-slate-300 text-xs">
                    <thead>
                        <tr class="bg-slate-100 text-slate-800">
                            <th class="border border-slate-300 px-3 py-2 text-left font-bold">No</th>
                            <th class="border border-slate-300 px-3 py-2 text-left font-bold">Kode Transaksi</th>
                            <th class="border border-slate-300 px-3 py-2 text-left font-bold">Tanggal</th>
                            <th class="border border-slate-300 px-3 py-2 text-left font-bold">Pelanggan &amp; No HP</th>
                            <th class="border border-slate-300 px-3 py-2 text-left font-bold">Layanan &amp; Estimasi</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-bold">Berat</th>
                            <th class="border border-slate-300 px-3 py-2 text-center font-bold">Pembayaran</th>
                            <th class="border border-slate-300 px-3 py-2 text-center font-bold">Pengerjaan</th>
                            <th class="border border-slate-300 px-3 py-2 text-right font-bold">Total Tarif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksis as $transaksi)
                            <tr>
                                <td class="border border-slate-300 px-3 py-2">{{ $loop->iteration }}</td>
                                <td class="border border-slate-300 px-3 py-2 font-mono font-semibold">{{ $transaksi->kode_transaksi }}</td>
                                <td class="border border-slate-300 px-3 py-2 whitespace-nowrap">{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                                <td class="border border-slate-300 px-3 py-2">
                                    <span class="font-semibold block">{{ $transaksi->nama_pelanggan }}</span>
                                    <span class="text-slate-500">{{ $transaksi->no_hp }}</span>
                                </td>
                                <td class="border border-slate-300 px-3 py-2">
                                    <span class="font-semibold block">{{ $transaksi->layanan->nama_layanan }}</span>
                                    <span class="text-slate-500">Rp {{ number_format($transaksi->layanan->harga_per_kg, 0, ',', '.') }}/kg ({{ $transaksi->layanan->estimasi_waktu }})</span>
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-right whitespace-nowrap">
                                    {{ number_format($transaksi->berat_kg, 1, ',', '.') }} kg
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-center font-semibold">
                                    {{ $transaksi->status_bayar }}
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-center font-semibold">
                                    {{ $transaksi->status_pengerjaan }}
                                </td>
                                <td class="border border-slate-300 px-3 py-2 text-right font-bold whitespace-nowrap">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="border border-slate-300 px-4 py-8 text-center text-slate-500">
                                    Tidak ada data transaksi untuk dicetak.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($transaksis->isNotEmpty())
                        <tfoot>
                            <tr class="bg-slate-50 font-bold text-slate-900">
                                <td colspan="5" class="border border-slate-300 px-3 py-2.5 text-right uppercase">Total Keseluruhan</td>
                                <td class="border border-slate-300 px-3 py-2.5 text-right whitespace-nowrap">{{ number_format($totalBeratKg, 1, ',', '.') }} kg</td>
                                <td colspan="2" class="border border-slate-300 px-3 py-2.5"></td>
                                <td class="border border-slate-300 px-3 py-2.5 text-right whitespace-nowrap">Rp {{ number_format($totalPemasukanKeseluruhan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            {{-- Tanda Tangan --}}
            <div class="pt-6 flex justify-end text-xs">
                <div class="w-56 text-center space-y-14">
                    <div>
                        <p>{{ now()->format('d F Y') }}</p>
                        <p class="font-semibold">Petugas Laundry</p>
                    </div>
                    <div>
                        <p class="font-bold underline">{{ auth()->user()->name }}</p>
                        <p class="text-slate-500 uppercase">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
