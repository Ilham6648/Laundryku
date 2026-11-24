<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Sistem Manajemen Laundry</title>
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
            <h2 class="text-lg font-bold text-slate-900">Masuk ke Akun Anda</h2>
            <p class="mt-1 text-xs text-slate-500">Gunakan akun Admin atau Kasir untuk mengelola operasional laundry.</p>

            @if (session('success'))
                <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-xs font-semibold text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="email"
                           placeholder="Masukkan email"
                           class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full rounded-xl border {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-sky-200 bg-sky-50/30 focus:bg-white focus:border-sky-500 focus:ring-sky-500/20' }} px-3.5 py-2.5 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-4 transition">
                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               value="1"
                               {{ old('remember') ? 'checked' : '' }}
                               class="rounded border-sky-300 text-sky-600 focus:ring-sky-500">
                        <span>Ingat sesi saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-sky-600 via-cyan-500 to-blue-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-sky-500/25 hover:from-sky-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-sky-500/30 transition cursor-pointer">
                    Masuk
                </button>
            </form>

            <div class="mt-6 pt-5 border-t border-sky-100 text-center">
                <p class="text-xs text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-sky-600 hover:text-sky-700 underline underline-offset-2">
                        Daftar akun baru
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
