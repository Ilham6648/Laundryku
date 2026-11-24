@props([
    'statistikLayanan',
    'trenHarian',
    'jumlahProses',
    'jumlahSelesai',
    'jumlahLunas',
    'jumlahBelumLunas',
])

@php
    $totalTransaksi = max($jumlahProses + $jumlahSelesai, 1);
    $persenSelesai = round(($jumlahSelesai / $totalTransaksi) * 100);
    $persenProses = 100 - $persenSelesai;

    $totalBayarCount = max($jumlahLunas + $jumlahBelumLunas, 1);
    $persenLunas = round(($jumlahLunas / $totalBayarCount) * 100);
    $persenBelumLunas = 100 - $persenLunas;

    $maxPendapatanLayanan = max((int) $statistikLayanan->max('total_pendapatan'), 1);
    $maxTrenPemasukan = max((int) collect($trenHarian)->max('pemasukan'), 1);
@endphp

<div class="space-y-5 print:hidden" aria-label="Grafik Rekapitulasi Transaksi Admin">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700 ring-1 ring-indigo-200">
                Analitik Khusus Admin
            </span>
            <h2 class="mt-1.5 text-lg font-extrabold text-slate-900">Grafik Rekapitulasi Transaksi &amp; Pendapatan</h2>
        </div>
        <p class="text-xs text-slate-500">Visualisasi performa layanan, tren harian, dan rasio penyelesaian order.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        {{-- 1. Grafik Tren Pemasukan 7 Hari Terakhir (Vertical Bar Chart) --}}
        <div class="lg:col-span-5 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-sky-200/80 border-t-4 border-t-sky-500 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between gap-2">
                    <h3 class="text-sm font-extrabold text-slate-900">Tren Transaksi 7 Hari Terakhir</h3>
                    <span class="text-[11px] font-semibold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md ring-1 ring-sky-200">7 Hari</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Grafik batang harian berdasarkan total nilai transaksi masuk</p>
            </div>

            <div class="mt-5 pt-4 h-48 flex items-end justify-between gap-2 border-b border-sky-100 px-1">
                @foreach ($trenHarian as $hari)
                    @php
                        $tinggiPersen = $hari['pemasukan'] > 0
                            ? max(round(($hari['pemasukan'] / $maxTrenPemasukan) * 100), 14)
                            : 6;
                    @endphp
                    <div class="flex-1 flex flex-col items-center justify-end h-full group">
                        <span class="text-[10px] font-bold text-sky-700 mb-1">
                            {{ $hari['jumlah_order'] }}x
                        </span>
                        <div class="w-full max-w-9 rounded-t-xl transition-all duration-300 {{ $hari['pemasukan'] > 0 ? 'bg-gradient-to-t from-sky-600 via-cyan-500 to-teal-400 shadow-xs group-hover:from-sky-700 group-hover:to-teal-500' : 'bg-slate-200' }}"
                             style="height: {{ $tinggiPersen }}%;"
                             title="{{ $hari['label'] }}: {{ $hari['jumlah_order'] }} order (Rp {{ number_format($hari['pemasukan'], 0, ',', '.') }})">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-2 flex items-center justify-between gap-2 px-1">
                @foreach ($trenHarian as $hari)
                    <div class="flex-1 text-center">
                        <span class="block text-[10px] font-bold text-slate-600">{{ $hari['label'] }}</span>
                        <span class="block text-[9px] text-slate-400 truncate">
                            {{ $hari['pemasukan'] > 0 ? number_format($hari['pemasukan'] / 1000, 0, ',', '.').'k' : '0' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 2. Grafik Performa per Jenis Layanan (Horizontal Bar Chart) --}}
        <div class="lg:col-span-4 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-sky-200/80 border-t-4 border-t-cyan-500 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Pendapatan per Jenis Layanan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbandingan kontribusi omzet &amp; berat cucian tiap paket</p>

                <div class="mt-4 space-y-4">
                    @php
                        $barColors = [
                            'from-sky-500 to-cyan-500',
                            'from-indigo-500 to-blue-500',
                            'from-emerald-500 to-teal-500',
                            'from-amber-500 to-orange-500',
                        ];
                    @endphp
                    @forelse ($statistikLayanan as $index => $item)
                        @php
                            $pendapatan = (int) ($item->total_pendapatan ?? 0);
                            $berat = (float) ($item->total_berat ?? 0);
                            $lebar = $pendapatan > 0 ? max(round(($pendapatan / $maxPendapatanLayanan) * 100), 8) : 3;
                            $grad = $barColors[$index % count($barColors)];
                        @endphp
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-800">
                                    {{ $item->nama_layanan }}
                                    <span class="text-[11px] font-medium text-slate-400">({{ $item->transaksis_count }} order • {{ number_format($berat, 1, ',', '.') }} kg)</span>
                                </span>
                                <span class="font-extrabold text-sky-700">Rp {{ number_format($pendapatan, 0, ',', '.') }}</span>
                            </div>
                            <div class="h-3 w-full rounded-full bg-sky-50 ring-1 ring-sky-100 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r {{ $grad }} transition-all duration-500"
                                     style="width: {{ $lebar }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-6 text-center">Belum ada data layanan.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-sky-100 flex items-center justify-between text-xs text-slate-500">
                <span>Total Paket Aktif:</span>
                <span class="font-bold text-slate-800">{{ $statistikLayanan->count() }} Layanan</span>
            </div>
        </div>

        {{-- 3. Grafik Donut Status Pengerjaan & Pembayaran --}}
        <div class="lg:col-span-3 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-sky-200/80 border-t-4 border-t-indigo-500 flex flex-col justify-between space-y-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Rasio Status Order</h3>
                <p class="text-xs text-slate-500 mt-0.5">Persentase pengerjaan &amp; pelunasan</p>
            </div>

            {{-- Donut SVG Status Pengerjaan --}}
            <div class="flex items-center gap-4">
                <div class="relative h-20 w-20 shrink-0">
                    <svg class="h-20 w-20 -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="15.915" fill="none" class="stroke-amber-200" stroke-width="4.2"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="none" class="stroke-emerald-500" stroke-width="4.2"
                                stroke-dasharray="{{ $persenSelesai }} {{ 100 - $persenSelesai }}" stroke-linecap="round"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xs font-extrabold text-slate-900">{{ $persenSelesai }}%</span>
                        <span class="text-[9px] font-semibold text-emerald-700">Selesai</span>
                    </div>
                </div>
                <div class="space-y-1 text-xs flex-1">
                    <p class="font-bold text-slate-800">Status Pengerjaan</p>
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>Selesai
                        </span>
                        <span class="font-bold text-slate-900">{{ $jumlahSelesai }} ({{ $persenSelesai }}%)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>Proses
                        </span>
                        <span class="font-bold text-slate-900">{{ $jumlahProses }} ({{ $persenProses }}%)</span>
                    </div>
                </div>
            </div>

            {{-- Progress Bar Rasio Pembayaran --}}
            <div class="pt-3 border-t border-sky-100 space-y-2">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-800">Rasio Pembayaran</span>
                    <span class="font-extrabold text-sky-700">{{ $persenLunas }}% Lunas</span>
                </div>
                <div class="h-3 w-full rounded-full bg-rose-100 overflow-hidden flex">
                    <div class="h-full bg-gradient-to-r from-emerald-500 to-teal-500" style="width: {{ $persenLunas }}%;"></div>
                </div>
                <div class="flex items-center justify-between text-[11px] text-slate-600">
                    <span>Lunas: <strong class="text-emerald-700">{{ $jumlahLunas }}</strong></span>
                    <span>Belum Lunas: <strong class="text-rose-600">{{ $jumlahBelumLunas }}</strong></span>
                </div>
            </div>
        </div>
    </div>
</div>
