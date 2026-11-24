<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLayananRequest;
use App\Http\Requests\UpdateLayananRequest;
use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Tampilkan daftar layanan laundry (khusus Admin).
     */
    public function index(): View
    {
        $layanans = Layanan::query()
            ->withCount('transaksis')
            ->orderBy('harga_per_kg')
            ->get();

        return view('layanans.index', [
            'layanans' => $layanans,
        ]);
    }

    /**
     * Tampilkan formulir tambah layanan baru.
     */
    public function create(): View
    {
        return view('layanans.create');
    }

    /**
     * Simpan data layanan baru ke database.
     */
    public function store(StoreLayananRequest $request): RedirectResponse
    {
        Layanan::query()->create($request->validated());

        return redirect()
            ->route('layanans.index')
            ->with('success', 'Layanan laundry baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan formulir ubah layanan.
     */
    public function edit(Layanan $layanan): View
    {
        return view('layanans.edit', [
            'layanan' => $layanan,
        ]);
    }

    /**
     * Perbarui data layanan di database.
     */
    public function update(UpdateLayananRequest $request, Layanan $layanan): RedirectResponse
    {
        $layanan->update($request->validated());

        return redirect()
            ->route('layanans.index')
            ->with('success', 'Data layanan berhasil diperbarui.');
    }

    /**
     * Hapus layanan jika belum pernah digunakan dalam transaksi.
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        if ($layanan->transaksis()->exists()) {
            return redirect()
                ->route('layanans.index')
                ->with('error', 'Layanan tidak dapat dihapus karena sudah digunakan pada riwayat transaksi.');
        }

        $layanan->delete();

        return redirect()
            ->route('layanans.index')
            ->with('success', 'Data layanan berhasil dihapus.');
    }
}
