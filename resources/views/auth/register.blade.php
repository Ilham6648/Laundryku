<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun — Sistem Manajemen Laundry</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-sky-600 via-cyan-500 to-indigo-700 flex items-center justify-center p-4 text-slate-800 antialiased">
    <div class="w-full max-w-md space-y-6">
        <div class="text-center text-white">
            <div class="mx-auto flex justify-center">
                <x-washing-machine size="md" />
            </div>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white drop-shadow-xs">LaundryKu</h1>
            <p class="mt-1 text-sm font-medium text-sky-100">Sistem Informasi Manajemen Laundry</p>
        </div>

        <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-xl ring-1 ring-white/60 border-t-4 border-t-cyan-400">
            <h2 class="text-lg font-bold text-slate-900">Registrasi Akun Baru</h2>
            <p class="mt-1 text-xs text-slate-500">Isi data diri dan pilih role pengguna untuk mendaftar.</p>

            <form method="POST" action="{{ route('register.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="Masukkan nama lengkap"
                           class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="email"
                           placeholder="Masukkan email"
                           class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Role</label>
                    <select id="role"
                            name="role"
                            required
                            class="w-full rounded-xl border {{ $errors->has('role') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-4 transition">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir</option>
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="Minimal 6 karakter"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="Ulangi kata sandi"
                           class="w-full rounded-xl border border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20 px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-sky-600 via-cyan-500 to-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-sky-500/25 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-sky-500/30 transition cursor-pointer">
                    Daftar
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-sky-100 text-center">
                <p class="text-xs text-slate-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-sky-600 hover:text-sky-700 underline underline-offset-2">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
