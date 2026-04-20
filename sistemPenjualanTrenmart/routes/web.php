<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Tren Abadi Stationeri
|--------------------------------------------------------------------------
*/

// --- HALAMAN PUBLIK / KATALOG ---

// Halaman Utama (Menampilkan Produk Terbaru)
Route::get('/', [ProdukController::class, 'index'])->name('beranda');

// Fitur Pencarian Produk
Route::get('/search', [ProdukController::class, 'search'])->name('produk.search');

// Detail Produk
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');

// Navigasi Statis
Route::get('/katalog', [ProdukController::class, 'index'])->name('katalog');

Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang');


// --- SISTEM AUTENTIKASI (LOGIN & REGISTER) ---

// Guest Middleware: Hanya bisa diakses jika BELUM login
Route::middleware(['guest'])->group(function () {
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

// Auth Middleware: Harus Login untuk akses ini
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Keranjang (Minggu ke-9)
    Route::post('/keranjang/tambah', [ProdukController::class, 'addToCart'])->name('keranjang.tambah');
    
    // Route Pesanan (Minggu ke-10)
    Route::get('/pesanan', function () {
        return view('pesanan');
    })->name('pesanan');

    // --- KHUSUS ADMIN ---
    // Route ini hanya boleh diakses jika user punya role 'admin'
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
    Route::get('/admin/tambah', [ProdukController::class, 'create'])->name('admin.produk.create');
    Route::post('/admin/tambah', [ProdukController::class, 'store'])->name('admin.produk.store');
});