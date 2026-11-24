<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TransaksiController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::prefix('transaksis')->name('transaksis.')->group(function (): void {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::get('/cetak', [TransaksiController::class, 'cetak'])->name('cetak');
        Route::get('/create', [TransaksiController::class, 'create'])->name('create');
        Route::post('/', [TransaksiController::class, 'store'])->name('store');
        Route::get('/{transaksi}/struk', [TransaksiController::class, 'struk'])->name('struk');
        Route::patch('/{transaksi}/selesai', [TransaksiController::class, 'tandaiSelesai'])->name('selesai');
        Route::patch('/{transaksi}/lunas', [TransaksiController::class, 'tandaiLunas'])->name('lunas');
    });

    Route::middleware('role:'.User::ROLE_ADMIN)->group(function (): void {
        Route::resource('layanans', LayananController::class)->except(['show']);
    });
});
