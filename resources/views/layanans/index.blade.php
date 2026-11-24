@extends('layouts.app')

@section('title', 'Data Layanan')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 p-5 sm:p-6 text-white shadow-md shadow-sky-600/15 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-white">Data Layanan Laundry</h1>
                <p class="mt-1 text-sm text-sky-100">Kelola daftar jenis layanan, tarif per kilogram, dan estimasi waktu selesai.</p>
            </div>
            <a href="{{ route('layanans.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-sky-700 shadow-sm hover:bg-sky-50 transition">
                <span>+ Tambah Layanan</span>
            </a>
        </div>

        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-sky-200/80 overflow-hidden">
            {{-- Tampilan Kartu Mobile (Layar HP) --}}
            <div class="md:hidden divide-y divide-sky-100">
                @forelse ($layanans as $layanan)
                    <div class="p-4 space-y-3 hover:bg-sky-50/40 transition">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-base font-extrabold text-slate-900">{{ $layanan->nama_layanan }}</p>
                                <p class="text-sm font-bold text-sky-700 mt-0.5">
                                    Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }} / kg
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-lg bg-cyan-50 px-2.5 py-1 text-xs font-bold text-cyan-800 ring-1 ring-cyan-200">
                                {{ $layanan->estimasi_waktu }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-sky-50">
                            <span class="inline-flex items-center rounded-md bg-sky-50 px-2 py-0.5 text-xs font-semibold text-sky-700">{{ $layanan->transaksis_count }} order tercatat</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('layanans.edit', $layanan) }}"
                                   class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-100 transition">
                                    Ubah
                                </a>
                                <form method="POST"
                                      action="{{ route('layanans.destroy', $layanan) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus layanan {{ $layanan->nama_layanan }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-sm text-slate-500">
                        Belum ada data layanan. Silakan tambahkan layanan baru.
                    </div>
                @endforelse
            </div>

            {{-- Tampilan Tabel Desktop / Tablet --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-sky-100 text-sm">
                    <thead class="bg-gradient-to-r from-sky-100/80 via-cyan-50/80 to-sky-50">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">#</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Nama Layanan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Harga / Kg</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Estimasi Waktu</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-sky-950">Transaksi</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wider text-sky-950">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($layanans as $layanan)
                            <tr class="hover:bg-sky-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-sky-700">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900">{{ $layanan->nama_layanan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-extrabold text-sky-700 ring-1 ring-sky-200">
                                        Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-lg bg-cyan-50 px-2.5 py-1 text-xs font-bold text-cyan-800 ring-1 ring-cyan-200">
                                        {{ $layanan->estimasi_waktu }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-600">
                                    {{ $layanan->transaksis_count }} order
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('layanans.edit', $layanan) }}"
                                           class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-1.5 text-xs font-bold text-sky-700 hover:bg-sky-100 transition">
                                            Ubah
                                        </a>
                                        <form method="POST"
                                              action="{{ route('layanans.destroy', $layanan) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus layanan {{ $layanan->nama_layanan }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500">
                                    Belum ada data layanan. Silakan tambahkan layanan baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
