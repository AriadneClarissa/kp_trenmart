<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController; // Tambahkan ini
use App\Http\Controllers\MerkController;     // Tambahkan ini
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Tren Abadi Stationeri
|--------------------------------------------------------------------------
*/

// --- HALAMAN PUBLIK / KATALOG ---
Route::get('/', [ProdukController::class, 'index'])->name('beranda');
Route::get('/search', [ProdukController::class, 'search'])->name('produk.search');
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');

Route::get('/katalog', function () { return view('katalog'); })->name('katalog');
Route::get('/tentang-kami', function () { return view('tentang-kami'); })->name('tentang');

// --- SISTEM AUTENTIKASI (GUEST - Belum Login) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// --- SISTEM AUTENTIKASI (AUTH - Sudah Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/keranjang/tambah', [ProdukController::class, 'addToCart'])->name('keranjang.tambah');
    Route::get('/pesanan', function () { return view('pesanan'); })->name('pesanan.index');

    // --- GRUP KHUSUS ADMIN ---
    // (Middleware 'can:admin' dikomentari sementara sesuai permintaanmu untuk menghindari 403)
    // Route::middleware(['can:admin'])->group(function () { 
        
        Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');

        Route::prefix('admin')->group(function () {
            
            // 1. MANAJEMEN PRODUK
            // Jalur akses via tombol di Beranda
            Route::get('/tambah-beranda', [ProdukController::class, 'createBeranda'])->name('admin.tambah.beranda');
            // Jalur akses via Layar Produk
            Route::get('/produk', [ProdukController::class, 'produkIndex'])->name('produk.index');
            Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.create');
            // Proses Simpan Produk
            Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');

            // 2. MANAJEMEN KATEGORI (Data Master)
            Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
            Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
            Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

            // 3. MANAJEMEN MERK (Data Master)
            Route::get('/merk', [MerkController::class, 'index'])->name('merk.index');
            Route::post('/merk/simpan', [MerkController::class, 'store'])->name('merk.store');
            Route::delete('/merk/hapus/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');
            
        });
    // }); 
});