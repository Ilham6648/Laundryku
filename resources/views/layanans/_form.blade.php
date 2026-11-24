<div class="space-y-5">
    <div>
        <label for="nama_layanan" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Layanan</label>
        <input id="nama_layanan"
               type="text"
               name="nama_layanan"
               value="{{ old('nama_layanan', $layanan?->nama_layanan) }}"
               required
               placeholder="Contoh: Reguler, Ekspres, Cuci Bed Cover"
               class="w-full rounded-xl border {{ $errors->has('nama_layanan') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-4 transition">
        @error('nama_layanan')
            <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="harga_per_kg" class="block text-sm font-semibold text-slate-700 mb-1.5">Harga per Kg (Rp)</label>
            <input id="harga_per_kg"
                   type="number"
                   name="harga_per_kg"
                   value="{{ old('harga_per_kg', $layanan?->harga_per_kg) }}"
                   min="500"
                   step="100"
                   required
                   placeholder="7000"
                   class="w-full rounded-xl border {{ $errors->has('harga_per_kg') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-4 transition">
            @error('harga_per_kg')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="estimasi_waktu" class="block text-sm font-semibold text-slate-700 mb-1.5">Estimasi Waktu</label>
            <input id="estimasi_waktu"
                   type="text"
                   name="estimasi_waktu"
                   value="{{ old('estimasi_waktu', $layanan?->estimasi_waktu) }}"
                   required
                   placeholder="Contoh: 3-4 hari / 5 jam selesai"
                   class="w-full rounded-xl border {{ $errors->has('estimasi_waktu') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-4 transition">
            @error('estimasi_waktu')
                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 pt-3 border-t border-sky-100">
        <a href="{{ route('layanans.index') }}"
           class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            Batal
        </a>
        <button type="submit"
                class="rounded-xl bg-gradient-to-r from-sky-600 to-cyan-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:from-sky-700 hover:to-cyan-700 transition cursor-pointer">
            {{ $submitLabel }}
        </button>
    </div>
</div>
