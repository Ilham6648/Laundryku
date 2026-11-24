<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Struk {{ $transaksi->kode_transaksi }} — LaundryKu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-50 via-cyan-50/60 to-indigo-50/60 print:bg-white text-slate-900 antialiased p-4 sm:p-8 print:p-0">
    <div class="max-w-sm mx-auto space-y-4">
        {{-- Toolbar Aksi (Disembunyikan saat cetak) --}}
        <div class="print:hidden flex items-center justify-between gap-2 rounded-2xl bg-white p-3 shadow-xs ring-1 ring-sky-200">
            <a href="{{ route('transaksis.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                ← Kembali
            </a>
            <button type="button"
                    onclick="window.print()"
                    class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:from-sky-700 hover:to-cyan-700 transition cursor-pointer">
                <span>Cetak Struk</span>
            </button>
        </div>

        {{-- Kertas Struk Kasir (Thermal Receipt Style) --}}
        <div class="rounded-2xl print:rounded-none bg-white p-6 print:p-2 shadow-md print:shadow-none ring-1 ring-sky-200/80 print:ring-0 border-t-4 border-t-sky-500 print:border-t-0 font-mono text-xs text-slate-800 space-y-3">
            {{-- Header Toko --}}
            <div class="text-center space-y-0.5 pb-3 border-b-2 border-dashed border-slate-300">
                <p class="text-lg font-extrabold tracking-wider text-slate-900 uppercase">LAUNDRYKU</p>
                <p class="text-[11px] font-semibold text-slate-600">Fresh &amp; Clean Laundry Service</p>
                <p class="text-[11px] text-slate-500">Jl. Raya Bersih No. 88 • Telp: 0812-3456-7890</p>
            </div>

            {{-- Informasi Nota & Pelanggan --}}
            <div class="space-y-1 pb-3 border-b border-dashed border-slate-300 text-[11px]">
                <div class="flex justify-between gap-2">
                    <span class="text-slate-500">No. Struk</span>
                    <span class="font-bold text-slate-900">{{ $transaksi->kode_transaksi }}</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-slate-500">Tanggal</span>
                    <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-slate-500">Kasir</span>
                    <span>{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between gap-2 pt-1">
                    <span class="text-slate-500">Pelanggan</span>
                    <span class="font-bold text-slate-900 text-right">{{ $transaksi->nama_pelanggan }}</span>
                </div>
                <div class="flex justify-between gap-2">
                    <span class="text-slate-500">No. HP</span>
                    <span>{{ $transaksi->no_hp }}</span>
                </div>
            </div>

            {{-- Rincian Layanan Cucian --}}
            <div class="space-y-1.5 pb-3 border-b border-dashed border-slate-300">
                <p class="font-bold text-slate-900 uppercase text-[11px]">Rincian Cucian</p>
                <div class="flex justify-between items-start gap-2">
                    <div>
                        <p class="font-bold text-slate-900">Paket {{ $transaksi->layanan->nama_layanan }}</p>
                        <p class="text-[11px] text-slate-500">
                            {{ number_format($transaksi->berat_kg, 1, ',', '.') }} kg x Rp {{ number_format($transaksi->layanan->harga_per_kg, 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-slate-500">
                            Estimasi: {{ $transaksi->layanan->estimasi_waktu }}
                        </p>
                    </div>
                    <span class="font-bold text-slate-900 whitespace-nowrap">
                        Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Total & Status --}}
            <div class="space-y-1.5 pb-3 border-b-2 border-dashed border-slate-300">
                <div class="flex justify-between items-center text-sm font-extrabold text-slate-900">
                    <span>TOTAL TARIF</span>
                    <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px] pt-1">
                    <span class="text-slate-500">Status Pembayaran</span>
                    <span class="font-extrabold uppercase {{ $transaksi->isLunas() ? 'text-emerald-700' : 'text-rose-600' }}">
                        {{ $transaksi->status_bayar }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="text-slate-500">Status Cucian</span>
                    <span class="font-bold uppercase text-slate-900">
                        {{ $transaksi->status_pengerjaan }}
                    </span>
                </div>
            </div>

            {{-- Footer Struk --}}
            <div class="pt-1 text-center space-y-1 text-[11px] text-slate-500">
                <p class="font-bold text-slate-700">*** TERIMA KASIH ***</p>
                <p>Harap simpan dan bawa struk ini saat pengambilan pakaian Anda.</p>
                <p class="text-[10px] text-slate-400 pt-1">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
