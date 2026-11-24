@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Hero Banner Lobby dengan Animasi Mesin Cuci Berputar --}}
        <div class="rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-indigo-600 p-5 sm:p-7 text-white shadow-md shadow-sky-600/15 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5">
                <x-washing-machine size="md" class="shrink-0" />
                <div>
                    <span class="inline-flex items-center rounded-full bg-white/20 px-3 py-1 text-xs font-semibold text-sky-50 ring-1 ring-white/30">
                        Fresh &amp; Clean Laundry System
                    </span>
                    <h1 class="mt-2.5 text-xl sm:text-2xl font-extrabold tracking-tight text-white">Dashboard Operasional</h1>
                    <p class="mt-1 text-sm text-sky-100">
                        Selamat datang, <span class="font-bold text-white">{{ auth()->user()->name }}</span>. Mesin cuci siap beroperasi &amp; memantau transaksi hari ini.
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('layanans.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/15 backdrop-blur-xs ring-1 ring-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/25 transition">
                        <span>Kelola Layanan ({{ $jumlahLayanan }})</span>
                    </a>
                @endif
                <a href="{{ route('transaksis.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-sky-700 shadow-sm hover:bg-sky-50 transition">
                    <span>+ Transaksi Baru</span>
                </a>
            </div>
        </div>

        {{-- KPI Cards Berwarna --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
            {{-- Proses --}}
            <div class="rounded-2xl bg-gradient-to-br from-amber-50 via-white to-orange-50/70 p-5 shadow-xs ring-1 ring-amber-200/80 border-t-4 border-t-amber-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700">Sedang Proses</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white font-bold text-sm shadow-xs">P</span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-amber-950">{{ $jumlahProses }}</p>
                <p class="mt-1 text-xs font-medium text-amber-700/80">Transaksi dalam antrean pengerjaan</p>
            </div>

            {{-- Selesai --}}
            <div class="rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-teal-50/70 p-5 shadow-xs ring-1 ring-emerald-200/80 border-t-4 border-t-emerald-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700">Selesai Dicuci</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-bold text-sm shadow-xs">S</span>
                </div>
                <p class="mt-3 text-3xl font-extrabold text-emerald-950">{{ $jumlahSelesai }}</p>
                <p class="mt-1 text-xs font-medium text-emerald-700/80">Siap diambil / telah selesai</p>
            </div>

            {{-- Total Pendapatan Lunas --}}
            <div class="rounded-2xl bg-gradient-to-br from-sky-50 via-white to-cyan-50/70 p-5 shadow-xs ring-1 ring-sky-200/80 border-t-4 border-t-sky-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-700">Pendapatan Lunas</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600 text-white font-bold text-xs shadow-xs">Rp</span>
                </div>
                <p class="mt-3 text-2xl font-extrabold text-sky-950">Rp {{ number_format($totalPendapatanLunas, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs font-medium text-slate-600">
                    Belum lunas: <span class="font-bold text-rose-600">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</span>
                </p>
            </div>

            {{-- Total Keseluruhan --}}
            <div class="rounded-2xl bg-gradient-to-br from-indigo-50 via-white to-blue-50/70 p-5 shadow-xs ring-1 ring-indigo-200/80 border-t-4 border-t-indigo-500">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-700">Total Pemasukan</span>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 text-white font-bold text-xs shadow-xs">All</span>
                </div>
                <p class="mt-3 text-2xl font-extrabold text-indigo-950">Rp {{ number_format($totalPendapatanKeseluruhan, 0, ',', '.') }}</p>
                <p class="mt-1 text-xs font-medium text-indigo-700/80">Dari {{ $totalTransaksi }} total transaksi tercatat</p>
            </div>
        </div>

        {{-- Grafik Rekap Transaksi (Khusus Admin) --}}
        @if (auth()->user()->isAdmin())
            <x-admin-transaksi-charts
                :statistik-layanan="$statistikLayanan"
                :tren-harian="$trenHarian"
                :jumlah-proses="$jumlahProses"
                :jumlah-selesai="$jumlahSelesai"
                :jumlah-lunas="$jumlahLunas"
                :jumlah-belum-lunas="$jumlahBelumLunas"
            />
        @endif

        {{-- Transaksi Terbaru --}}
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-sky-200/80 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 bg-gradient-to-r from-sky-50 via-cyan-50/60 to-white border-b border-sky-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-base font-bold text-sky-950">Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-500">6 transaksi laundry paling baru yang masuk ke sistem</p>
                </div>
                <a href="{{ route('transaksis.index') }}"
                   class="text-xs font-bold text-sky-600 hover:text-sky-700 inline-flex items-center gap-1">
                    <span>Lihat Semua Rekap Transaksi →</span>
                </a>
            </div>

            {{-- Tampilan Kartu Mobile (Layar HP) --}}
            <div class="md:hidden divide-y divide-sky-100">
                @forelse ($transaksiTerbaru as $trx)
                    <div class="p-4 space-y-2.5 hover:bg-sky-50/40 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 font-mono text-xs font-bold text-sky-700 ring-1 ring-sky-200">{{ $trx->kode_transaksi }}</span>
                                <p class="text-sm font-bold text-slate-900 mt-1">{{ $trx->nama_pelanggan }}</p>
                                <p class="text-xs text-slate-500">{{ $trx->no_hp }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                <x-status-badge :value="$trx->status_pengerjaan" />
                                <x-status-badge :value="$trx->status_bayar" />
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-sky-50 text-xs">
                            <span class="text-slate-600">
                                <strong class="text-cyan-800">{{ $trx->layanan->nama_layanan }}</strong> • {{ number_format($trx->berat_kg, 1, ',', '.') }} kg
                            </span>
                            <span class="text-sm font-extrabold text-sky-700">
                                Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada transaksi tercatat.
                    </div>
                @endforelse
            </div>

            {{-- Tampilan Tabel Desktop / Tablet --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100 text-sm">
                    <thead class="bg-sky-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Kode</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Layanan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Berat &amp; Total</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider text-sky-900">Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($transaksiTerbaru as $trx)
                            <tr class="hover:bg-sky-50/50 transition">
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-block rounded-md bg-sky-50 px-2 py-0.5 font-mono text-xs font-bold text-sky-700 ring-1 ring-sky-200">{{ $trx->kode_transaksi }}</span>
                                    <span class="block text-[11px] text-slate-400 mt-0.5">{{ $trx->created_at->format('d M Y H:i') }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="font-semibold text-slate-900">{{ $trx->nama_pelanggan }}</span>
                                    <span class="block text-xs text-slate-500">{{ $trx->no_hp }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-lg bg-cyan-50 px-2.5 py-0.5 text-xs font-bold text-cyan-800 ring-1 ring-cyan-200">{{ $trx->layanan->nama_layanan }}</span>
                                    <span class="block text-xs text-slate-500 mt-0.5">Est. {{ $trx->layanan->estimasi_waktu }}</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <span class="font-bold text-sky-700">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</span>
                                    <span class="block text-xs text-slate-500">{{ number_format($trx->berat_kg, 1, ',', '.') }} kg</span>
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <x-status-badge :value="$trx->status_bayar" />
                                </td>
                                <td class="px-6 py-3.5 whitespace-nowrap">
                                    <x-status-badge :value="$trx->status_pengerjaan" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Belum ada transaksi tercatat. Klik <strong>Transaksi Baru</strong> untuk mencatat order pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
