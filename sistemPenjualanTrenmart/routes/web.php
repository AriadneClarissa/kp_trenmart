<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\KeranjangController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Tren Abadi Stationeri (StockPen)
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN PUBLIK / KATALOG (Bisa diakses tanpa login) ---
    Route::get('/', [ProdukController::class, 'index'])->name('beranda');
    Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
    Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');
    Route::get('/search', [ProdukController::class, 'search'])->name('produk.search');
    Route::get('/tentang-kami', function () { return view('tentang-kami'); })->name('tentang');


// --- 2. SISTEM AUTENTIKASI (GUEST - Hanya untuk yang belum login) ---
    Route::middleware(['guest'])->group(function () {
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// --- 3. SISTEM AUTENTIKASI (AUTH - Harus Login) ---
    Route::middleware(['auth'])->group(function () {
    
    // Fitur Dasar User Terautentikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pesanan', function () { return view('pesanan'); })->name('pesanan.index');

    // --- FITUR KERANJANG (TABEL TUNGGAL: keranjang) ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'store'])->name('cart.add');
    Route::post('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('cart.update'); // Untuk +/- jumlah
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'destroy'])->name('cart.remove');

    // --- GRUP KHUSUS ADMIN & PEMILIK TOKO ---
    Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
    Route::post('/admin/promote/{id}', [AuthController::class, 'promoteToAdmin'])->name('admin.promote');

    // Manajemen Data Master via Prefix Admin
    Route::prefix('admin')->group(function () {
        
        // A. MANAJEMEN PRODUK
        Route::get('/tambah-beranda', [ProdukController::class, 'createBeranda'])->name('admin.tambah.beranda');
        Route::get('/produk', [ProdukController::class, 'produkIndex'])->name('produk.index');
        Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/edit/{id}', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/update/{id}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/hapus/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');
        

        // B. MANAJEMEN KATEGORI
        Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
        Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
        Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        // C. MANAJEMEN MERK
        Route::get('/merk', [MerkController::class, 'index'])->name('merk.index');
        Route::post('/merk/simpan', [MerkController::class, 'store'])->name('merk.store');
        Route::delete('/merk/hapus/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');
        
        // D. MANAJEMEN KATALOG PRODUK
        Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
        Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');


        // E. MANAJEMEN MERK PRODUK
        Route::post('/merk/store', [MerkController::class, 'store'])->name('merk.store');

        // F. TOGGLE VISIBILITAS MERK (AJAX)
        Route::post('/merk/toggle/{id}', [MerkController::class, 'toggleVisible'])->name('merk.toggle');
        Route::post('/kategori/toggle/{id}', [KategoriController::class, 'toggleVisible']);    
        
    });
});