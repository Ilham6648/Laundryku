@extends('layouts.app')

@section('title', 'Rekap Transaksi Laundry')

@section('content')
    <div class="space-y-6">
        {{-- Banner Header Rekap --}}
        <div class="rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 p-5 sm:p-6 text-white shadow-md shadow-sky-600/15 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white">Rekap Transaksi &amp; Laporan Keuangan</h1>
                <p class="mt-1 text-sm text-sky-100">Daftar riwayat transaksi laundry, filter status, cetak laporan, serta ringkasan pemasukan.</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 print:hidden">
                <a href="{{ route('transaksis.cetak', request()->query()) }}"
                   target="_blank"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 backdrop-blur-xs ring-1 ring-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/25 transition">
                    <span>Cetak Rekap Order</span>
                </a>
                <a href="{{ route('transaksis.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-sky-700 shadow-sm hover:bg-sky-50 transition">
                    <span>+ Tambah Transaksi Baru</span>
                </a>
            </div>
        </div>

        {{-- Ringkasan Kartu Keuangan Berwarna --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-2xl bg-gradient-to-br from-indigo-50 via-white to-blue-50/70 p-4 shadow-xs ring-1 ring-indigo-200/80 border-t-4 border-t-indigo-500">
                <p class="text-xs font-bold uppercase tracking-wider text-indigo-700">Total Pemasukan Keseluruhan</p>
                <p class="mt-2 text-xl font-extrabold text-indigo-950">Rp {{ number_format($totalPemasukanKeseluruhan, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-teal-50/70 p-4 shadow-xs ring-1 ring-emerald-200/80 border-t-4 border-t-emerald-500">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Sudah Lunas</p>
                <p class="mt-2 text-xl font-extrabold text-emerald-800">Rp {{ number_format($totalPemasukanLunas, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-rose-50 via-white to-pink-50/70 p-4 shadow-xs ring-1 ring-rose-200/80 border-t-4 border-t-rose-500">
                <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Belum Lunas</p>
                <p class="mt-2 text-xl font-extrabold text-rose-700">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-amber-50 via-white to-orange-50/70 p-4 shadow-xs ring-1 ring-amber-200/80 border-t-4 border-t-amber-500">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Dalam Proses</p>
                <p class="mt-2 text-xl font-extrabold text-amber-800">{{ $totalTransaksiProses }} Order</p>
            </div>
        </div>

        {{-- Grafik Rekap Transaksi (Khusus Admin) --}}
        @if (auth()->user()->isAdmin())
            <x-admin-transaksi-charts
                :statistik-layanan="$statistikLayanan"
                :tren-harian="$trenHarian"
                :jumlah-proses="$totalTransaksiProses"
                :jumlah-selesai="$totalTransaksiSelesai"
                :jumlah-lunas="$jumlahLunas"
                :jumlah-belum-lunas="$jumlahBelumLunas"
            />
        @endif

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('transaksis.index') }}"
              class="rounded-2xl bg-gradient-to-r from-white via-sky-50/50 to-cyan-50/40 p-4 shadow-xs ring-1 ring-sky-200/80 print:hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-4">
                    <label for="search" class="block text-xs font-bold text-sky-900 mb-1">Pencarian</label>
                    <input id="search"
                           type="text"
                           name="search"
                           value="{{ $filters['search'] }}"
                           placeholder="Kode transaksi, pelanggan, atau no HP..."
                           class="w-full rounded-xl border border-sky-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                </div>

                <div class="lg:col-span-3">
                    <label for="status_pengerjaan" class="block text-xs font-bold text-sky-900 mb-1">Status Pengerjaan</label>
                    <select id="status_pengerjaan"
                            name="status_pengerjaan"
                            class="w-full rounded-xl border border-sky-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        <option value="">Semua Status</option>
                        <option value="Proses" {{ $filters['status_pengerjaan'] === 'Proses' ? 'selected' : '' }}>Proses</option>
                        <option value="Selesai" {{ $filters['status_pengerjaan'] === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <label for="status_bayar" class="block text-xs font-bold text-sky-900 mb-1">Status Bayar</label>
                    <select id="status_bayar"
                            name="status_bayar"
                            class="w-full rounded-xl border border-sky-200 bg-white px-3.5 py-2 text-sm text-slate-900 focus:border-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        <option value="">Semua Pembayaran</option>
                        <option value="Lunas" {{ $filters['status_bayar'] === 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Belum Lunas" {{ $filters['status_bayar'] === 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    </select>
                </div>

                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 px-4 py-2 text-sm font-bold text-white shadow-xs hover:from-sky-700 hover:to-cyan-700 transition cursor-pointer">
                        Terapkan
                    </button>
                    @if ($filters['search'] || $filters['status_pengerjaan'] || $filters['status_bayar'])
                        <a href="{{ route('transaksis.index') }}"
                           class="rounded-xl border border-sky-200 bg-white px-3 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-50 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-sky-200/80 overflow-hidden">
            {{-- Tampilan Kartu Mobile (Layar HP) --}}
            <div class="md:hidden divide-y divide-sky-100">
                @forelse ($transaksis as $transaksi)
                    <div class="p-4 space-y-3 hover:bg-sky-50/30 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 font-mono text-xs font-bold text-sky-700 ring-1 ring-sky-200">{{ $transaksi->kode_transaksi }}</span>
                                <span class="block text-[11px] text-slate-400 mt-0.5">{{ $transaksi->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex flex-wrap items-center justify-end gap-1.5">
                                <x-status-badge :value="$transaksi->status_bayar" />
                                <x-status-badge :value="$transaksi->status_pengerjaan" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 rounded-xl bg-gradient-to-r from-sky-50/80 to-cyan-50/60 p-3 text-xs ring-1 ring-sky-100">
                            <div>
                                <span class="block text-sky-600 font-medium">Pelanggan</span>
                                <span class="font-bold text-slate-900">{{ $transaksi->nama_pelanggan }}</span>
                                <span class="block text-slate-500">{{ $transaksi->no_hp }}</span>
                            </div>
                            <div>
                                <span class="block text-sky-600 font-medium">Layanan &amp; Berat</span>
                                <span class="font-bold text-cyan-900">{{ $transaksi->layanan->nama_layanan }} ({{ number_format($transaksi->berat_kg, 1, ',', '.') }} kg)</span>
                                <span class="block text-slate-500">Est. {{ $transaksi->layanan->estimasi_waktu }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-1">
                            <div>
                                <span class="block text-[11px] text-slate-400">Total Tarif</span>
                                <span class="text-base font-extrabold text-sky-700">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ route('transaksis.struk', $transaksi) }}"
                                   target="_blank"
                                   class="rounded-xl border border-sky-200 bg-white px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-50 transition">
                                    Struk
                                </a>

                                @if (! $transaksi->isLunas())
                                    <form method="POST" action="{{ route('transaksis.lunas', $transaksi) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-xl border border-sky-300 bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-100 transition cursor-pointer">
                                            Lunasi
                                        </button>
                                    </form>
                                @endif

                                @if ($transaksi->isProses())
                                    <form method="POST" action="{{ route('transaksis.selesai', $transaksi) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:from-emerald-600 hover:to-teal-700 transition cursor-pointer">
                                            Tandai Selesai
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-sm text-slate-500">
                        Tidak ada data transaksi yang ditemukan.
                    </div>
                @endforelse
            </div>

            {{-- Tampilan Tabel Desktop / Tablet --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100 text-sm">
                    <thead class="bg-gradient-to-r from-sky-100/80 via-cyan-50/80 to-sky-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Kode &amp; Tanggal</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Pelanggan</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Layanan &amp; Estimasi</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Berat</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Total Tarif</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Status Bayar</th>
                            <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Pengerjaan</th>
                            <th class="px-5 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-sky-950 print:hidden">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($transaksis as $transaksi)
                            <tr class="hover:bg-sky-50/50 transition">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 font-mono text-xs font-bold text-sky-700 ring-1 ring-sky-200">{{ $transaksi->kode_transaksi }}</span>
                                    <span class="block text-xs text-slate-400 mt-0.5">{{ $transaksi->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="font-bold text-slate-900">{{ $transaksi->nama_pelanggan }}</span>
                                    <span class="block text-xs text-slate-500">{{ $transaksi->no_hp }}</span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-lg bg-cyan-50 px-2.5 py-0.5 text-xs font-bold text-cyan-800 ring-1 ring-cyan-200">{{ $transaksi->layanan->nama_layanan }}</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">
                                        Rp {{ number_format($transaksi->layanan->harga_per_kg, 0, ',', '.') }}/kg • {{ $transaksi->layanan->estimasi_waktu }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-semibold text-slate-700">
                                    {{ number_format($transaksi->berat_kg, 1, ',', '.') }} kg
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap font-extrabold text-sky-700">
                                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <x-status-badge :value="$transaksi->status_bayar" />
                                        @if (! $transaksi->isLunas())
                                            <form method="POST" action="{{ route('transaksis.lunas', $transaksi) }}" class="print:hidden">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="text-[11px] font-bold text-sky-600 hover:text-sky-700 underline underline-offset-2 cursor-pointer">
                                                    Lunasi Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <x-status-badge :value="$transaksi->status_pengerjaan" />
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right print:hidden">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('transaksis.struk', $transaksi) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1 rounded-xl border border-sky-200 bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-100 transition">
                                            <span>Cetak Struk</span>
                                        </a>

                                        @if ($transaksi->isProses())
                                            <form method="POST" action="{{ route('transaksis.selesai', $transaksi) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:from-emerald-600 hover:to-teal-700 transition cursor-pointer">
                                                    <span>Tandai Selesai</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Selesai</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-sm text-slate-500">
                                    Tidak ada data transaksi yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($transaksis->hasPages())
                <div class="border-t border-sky-100 px-6 py-4 bg-sky-50/40 print:hidden">
                    {{ $transaksis->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
