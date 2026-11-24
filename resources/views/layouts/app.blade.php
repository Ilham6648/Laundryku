<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Sistem Manajemen Laundry') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-sky-50 via-cyan-50/50 to-indigo-50/60 text-slate-800 antialiased min-h-screen">
    <div class="min-h-screen flex flex-col lg:flex-row">
        {{-- Mobile Sidebar Backdrop --}}
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-30 bg-sky-950/50 backdrop-blur-xs hidden lg:hidden print:hidden"
             onclick="toggleSidebar(false)"></div>

        {{-- Sidebar --}}
        <aside id="app-sidebar"
               class="fixed inset-y-0 left-0 z-40 w-72 max-w-[85vw] bg-white border-r border-sky-100 shadow-sm flex flex-col justify-between transform -translate-x-full transition-transform duration-200 ease-out lg:translate-x-0 lg:static lg:inset-auto lg:shrink-0 print:hidden">
            <div>
                {{-- Brand Banner --}}
                <div class="h-18 px-6 bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 text-white flex items-center justify-between shadow-xs">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20 backdrop-blur-xs text-white ring-1 ring-white/30 shadow-xs">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                <circle cx="12" cy="13" r="4" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h.01M11 7h.01" />
                            </svg>
                        </span>
                        <div>
                            <p class="text-base font-extrabold tracking-tight text-white">LaundryKu</p>
                            <p class="text-[11px] text-sky-100 font-medium">Fresh &amp; Clean Laundry</p>
                        </div>
                    </a>
                    <button type="button"
                            class="lg:hidden p-1.5 rounded-lg text-white/80 hover:bg-white/15"
                            onclick="toggleSidebar(false)"
                            aria-label="Tutup menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Quick Action --}}
                <div class="px-4 pt-5 pb-2">
                    <a href="{{ route('transaksis.create') }}"
                       class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-sky-500 via-cyan-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-sky-500/25 hover:from-sky-600 hover:to-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Catat Transaksi Baru</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="px-4 py-3 space-y-1.5" aria-label="Navigasi Utama">
                    <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-sky-600/70">Menu Utama</p>

                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-sky-500/15 to-cyan-500/10 text-sky-700 ring-1 ring-sky-300/80 shadow-2xs' : 'text-slate-600 hover:bg-sky-50/80 hover:text-sky-800' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('dashboard') ? 'bg-sky-600 text-white shadow-xs' : 'bg-sky-50 text-sky-600' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('layanans.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('layanans.*') ? 'bg-gradient-to-r from-sky-500/15 to-cyan-500/10 text-sky-700 ring-1 ring-sky-300/80 shadow-2xs' : 'text-slate-600 hover:bg-sky-50/80 hover:text-sky-800' }}">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('layanans.*') ? 'bg-cyan-600 text-white shadow-xs' : 'bg-cyan-50 text-cyan-600' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </span>
                            <span>Data Layanan</span>
                        </a>
                    @endif

                    <a href="{{ route('transaksis.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('transaksis.*') ? 'bg-gradient-to-r from-sky-500/15 to-cyan-500/10 text-sky-700 ring-1 ring-sky-300/80 shadow-2xs' : 'text-slate-600 hover:bg-sky-50/80 hover:text-sky-800' }}">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ request()->routeIs('transaksis.*') ? 'bg-indigo-600 text-white shadow-xs' : 'bg-indigo-50 text-indigo-600' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                            </svg>
                        </span>
                        <span>Rekap Transaksi</span>
                    </a>
                </nav>
            </div>

            {{-- User Info & Logout --}}
            <div class="p-4 border-t border-sky-100 bg-gradient-to-b from-sky-50/70 to-cyan-50/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-sky-500 to-cyan-600 text-white font-bold text-sm flex items-center justify-center shrink-0 shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide {{ auth()->user()->isAdmin() ? 'bg-indigo-100 text-indigo-700 ring-1 ring-indigo-200' : 'bg-teal-100 text-teal-700 ring-1 ring-teal-200' }}">
                                {{ auth()->user()->role }}
                            </span>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 rounded-xl border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer shadow-2xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Mobile Header --}}
            <header class="lg:hidden sticky top-0 z-20 h-16 bg-gradient-to-r from-sky-600 via-cyan-600 to-blue-600 text-white px-4 flex items-center justify-between shadow-sm print:hidden">
                <button type="button"
                        class="p-2 -ml-2 rounded-xl text-white hover:bg-white/15"
                        onclick="toggleSidebar(true)"
                        aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="text-base font-extrabold tracking-tight text-white">LaundryKu</a>
                <span class="inline-flex items-center rounded-lg bg-white/20 px-2.5 py-1 text-xs font-bold uppercase text-white ring-1 ring-white/30">
                    {{ auth()->user()->role }}
                </span>
            </header>

            {{-- Page Content --}}
            <main class="flex-1 p-4 pb-24 sm:p-6 sm:pb-24 lg:p-8 lg:pb-8 max-w-7xl w-full mx-auto">
                @if (session('success'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-300 bg-gradient-to-r from-emerald-50 to-teal-50 px-4 py-3 text-sm text-emerald-900 shadow-xs" role="alert">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="flex-1 font-semibold">{{ session('success') }}</div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-start gap-3 rounded-xl border border-rose-300 bg-gradient-to-r from-rose-50 to-pink-50 px-4 py-3 text-sm text-rose-900 shadow-xs" role="alert">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div class="flex-1 font-semibold">{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Mobile Bottom Navigation Bar --}}
            <nav class="lg:hidden fixed bottom-0 inset-x-0 z-20 bg-white/95 backdrop-blur-xs border-t border-sky-200 px-2 py-1.5 shadow-lg print:hidden" aria-label="Navigasi Bawah Mobile">
                <div class="grid {{ auth()->user()->isAdmin() ? 'grid-cols-4' : 'grid-cols-3' }} gap-1 max-w-md mx-auto">
                    <a href="{{ route('dashboard') }}"
                       class="flex flex-col items-center justify-center py-1.5 rounded-xl text-[11px] font-bold transition {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-sky-600 to-cyan-600 shadow-xs' : 'text-slate-600 hover:text-sky-700' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('layanans.index') }}"
                           class="flex flex-col items-center justify-center py-1.5 rounded-xl text-[11px] font-bold transition {{ request()->routeIs('layanans.*') ? 'text-white bg-gradient-to-r from-sky-600 to-cyan-600 shadow-xs' : 'text-slate-600 hover:text-sky-700' }}">
                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            </svg>
                            <span>Layanan</span>
                        </a>
                    @endif

                    <a href="{{ route('transaksis.index') }}"
                       class="flex flex-col items-center justify-center py-1.5 rounded-xl text-[11px] font-bold transition {{ request()->routeIs('transaksis.index') ? 'text-white bg-gradient-to-r from-sky-600 to-cyan-600 shadow-xs' : 'text-slate-600 hover:text-sky-700' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" />
                        </svg>
                        <span>Rekap</span>
                    </a>

                    <a href="{{ route('transaksis.create') }}"
                       class="flex flex-col items-center justify-center py-1.5 rounded-xl text-[11px] font-bold transition {{ request()->routeIs('transaksis.create') ? 'text-white bg-gradient-to-r from-sky-600 to-cyan-600 shadow-xs' : 'text-slate-600 hover:text-sky-700' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Order Baru</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <script>
        function toggleSidebar(open) {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar || !backdrop) return;

            if (open) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
