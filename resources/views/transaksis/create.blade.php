@extends('layouts.app')

@section('title', 'Catat Transaksi Baru')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 p-5 sm:p-6 text-white shadow-md shadow-sky-600/15">
            <a href="{{ route('transaksis.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-sky-100 hover:text-white">
                ← Kembali ke Rekap Transaksi
            </a>
            <h1 class="mt-2 text-xl sm:text-2xl font-extrabold tracking-tight text-white">Catat Transaksi Laundry Baru</h1>
            <p class="mt-1 text-sm text-sky-100">Isi data pelanggan dan berat cucian. Total tarif akan dihitung otomatis.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Form Pencatatan --}}
            <form method="POST"
                  action="{{ route('transaksis.store') }}"
                  class="lg:col-span-7 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-sky-200/80 border-t-4 border-t-sky-500 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Pelanggan</label>
                        <input id="nama_pelanggan"
                               type="text"
                               name="nama_pelanggan"
                               value="{{ old('nama_pelanggan') }}"
                               required
                               placeholder="Contoh: Budi Santoso"
                               class="w-full rounded-xl border {{ $errors->has('nama_pelanggan') ? 'border-rose-400' : 'border-sky-200' }} bg-sky-50/30 px-3.5 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        @error('nama_pelanggan')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor HP / WhatsApp</label>
                        <input id="no_hp"
                               type="text"
                               name="no_hp"
                               value="{{ old('no_hp') }}"
                               required
                               placeholder="081234567890"
                               class="w-full rounded-xl border {{ $errors->has('no_hp') ? 'border-rose-400' : 'border-sky-200' }} bg-sky-50/30 px-3.5 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        @error('no_hp')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="layanan_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Layanan</label>
                        <select id="layanan_id"
                                name="layanan_id"
                                required
                                class="w-full rounded-xl border {{ $errors->has('layanan_id') ? 'border-rose-400' : 'border-sky-200' }} bg-sky-50/30 px-3.5 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                            <option value="" data-harga="0" data-estimasi="-" data-nama="-">-- Pilih Layanan --</option>
                            @foreach ($layanans as $layanan)
                                <option value="{{ $layanan->id }}"
                                        data-harga="{{ $layanan->harga_per_kg }}"
                                        data-estimasi="{{ $layanan->estimasi_waktu }}"
                                        data-nama="{{ $layanan->nama_layanan }}"
                                        {{ (string) old('layanan_id') === (string) $layanan->id ? 'selected' : '' }}>
                                    {{ $layanan->nama_layanan }} — Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}/kg ({{ $layanan->estimasi_waktu }})
                                </option>
                            @endforeach
                        </select>
                        @error('layanan_id')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="berat_kg" class="block text-sm font-semibold text-slate-700 mb-1.5">Berat Cucian (kg)</label>
                        <input id="berat_kg"
                               type="number"
                               name="berat_kg"
                               value="{{ old('berat_kg') }}"
                               step="0.1"
                               min="0.1"
                               max="1000"
                               required
                               placeholder="Contoh: 3.5"
                               class="w-full rounded-xl border {{ $errors->has('berat_kg') ? 'border-rose-400' : 'border-sky-200' }} bg-sky-50/30 px-3.5 py-2.5 text-sm text-slate-900 focus:border-sky-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sky-500/20">
                        @error('berat_kg')
                            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <span class="block text-sm font-semibold text-slate-700 mb-2">Status Pembayaran</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 rounded-xl border border-sky-200 bg-sky-50/20 px-4 py-3 cursor-pointer hover:bg-sky-50 has-checked:border-emerald-500 has-checked:bg-emerald-50/70 has-checked:ring-1 has-checked:ring-emerald-500 transition">
                            <input type="radio"
                                   name="status_bayar"
                                   value="Lunas"
                                   {{ old('status_bayar', 'Belum Lunas') === 'Lunas' ? 'checked' : '' }}
                                   class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="block text-sm font-bold text-slate-900">Lunas</span>
                                <span class="block text-xs text-slate-500">Dibayar penuh di awal</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-sky-200 bg-sky-50/20 px-4 py-3 cursor-pointer hover:bg-sky-50 has-checked:border-amber-500 has-checked:bg-amber-50/70 has-checked:ring-1 has-checked:ring-amber-500 transition">
                            <input type="radio"
                                   name="status_bayar"
                                   value="Belum Lunas"
                                   {{ old('status_bayar', 'Belum Lunas') === 'Belum Lunas' ? 'checked' : '' }}
                                   class="text-amber-600 focus:ring-amber-500">
                            <div>
                                <span class="block text-sm font-bold text-slate-900">Belum Lunas</span>
                                <span class="block text-xs text-slate-500">Bayar saat pengambilan</span>
                            </div>
                        </label>
                    </div>
                    @error('status_bayar')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-sky-100">
                    <a href="{{ route('transaksis.index') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:from-sky-700 hover:to-cyan-700 transition cursor-pointer">
                        Simpan Transaksi
                    </button>
                </div>
            </form>

            {{-- Ringkasan Kalkulasi Real-Time --}}
            <div class="lg:col-span-5 rounded-2xl bg-gradient-to-br from-sky-700 via-cyan-700 to-indigo-800 text-white p-6 shadow-md shadow-sky-700/20 space-y-5">
                <div>
                    <span class="inline-flex items-center rounded-lg bg-white/15 px-2.5 py-1 text-xs font-bold text-cyan-100 ring-1 ring-white/25">
                        Kalkulator Tarif Real-Time
                    </span>
                    <h2 class="mt-3 text-lg font-extrabold">Ringkasan Estimasi Biaya</h2>
                    <p class="text-xs text-sky-100">Otomatis dihitung dari berat (kg) dikalikan harga layanan.</p>
                </div>

                <dl class="space-y-3 border-y border-white/15 py-4 text-sm">
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-sky-100">Layanan Dipilih</dt>
                        <dd id="preview-nama" class="font-bold text-white">-</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-sky-100">Tarif per Kg</dt>
                        <dd id="preview-harga" class="font-bold text-white">Rp 0</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-sky-100">Estimasi Selesai</dt>
                        <dd id="preview-estimasi" class="font-bold text-cyan-200">-</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-sky-100">Berat Cucian</dt>
                        <dd id="preview-berat" class="font-bold text-white">0 kg</dd>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <dt class="text-sky-100">Status Awal</dt>
                        <dd class="inline-flex items-center rounded-full bg-amber-400/25 px-2.5 py-0.5 text-xs font-bold text-amber-200 ring-1 ring-amber-300/40">
                            Proses
                        </dd>
                    </div>
                </dl>

                <div class="rounded-xl bg-white/10 p-4 ring-1 ring-white/20">
                    <p class="text-xs font-bold uppercase tracking-wider text-cyan-100">Total Harga</p>
                    <p id="preview-total" class="mt-1 text-3xl font-extrabold tracking-tight text-white">Rp 0</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const layananSelect = document.getElementById('layanan_id');
            const beratInput = document.getElementById('berat_kg');
            const previewNama = document.getElementById('preview-nama');
            const previewHarga = document.getElementById('preview-harga');
            const previewEstimasi = document.getElementById('preview-estimasi');
            const previewBerat = document.getElementById('preview-berat');
            const previewTotal = document.getElementById('preview-total');

            function formatRupiah(angka) {
                return 'Rp ' + Math.round(angka).toLocaleString('id-ID');
            }

            function updateKalkulasi() {
                const selectedOption = layananSelect.options[layananSelect.selectedIndex];
                const hargaPerKg = parseInt(selectedOption?.dataset?.harga || '0', 10);
                const estimasi = selectedOption?.dataset?.estimasi || '-';
                const nama = selectedOption?.dataset?.nama || '-';
                const berat = parseFloat(beratInput.value || '0');
                const validBerat = Number.isFinite(berat) && berat > 0 ? berat : 0;
                const total = Math.round(validBerat * hargaPerKg);

                previewNama.textContent = nama;
                previewHarga.textContent = formatRupiah(hargaPerKg);
                previewEstimasi.textContent = estimasi;
                previewBerat.textContent = validBerat.toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' kg';
                previewTotal.textContent = formatRupiah(total);
            }

            layananSelect.addEventListener('change', updateKalkulasi);
            beratInput.addEventListener('input', updateKalkulasi);
            updateKalkulasi();
        })();
    </script>
@endpush
