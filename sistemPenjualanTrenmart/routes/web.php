<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\KatalogController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - PT Tren Abadi Stationeri (StockPen)
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN PUBLIK / KATALOG ---
Route::get('/', [ProdukController::class, 'index'])->name('beranda');
Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');
Route::get('/tentang-kami', function () { return view('tentang-kami'); })->name('tentang');


// --- 2. SISTEM AUTENTIKASI (GUEST) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


// --- 3. SISTEM AUTENTIKASI (AUTH - Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pesanan', function () { return view('pesanan'); })->name('pesanan.index');

    // --- FITUR KERANJANG ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'store'])->name('cart.add');
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'destroy'])->name('cart.remove');
    Route::get('/cart/sidebar-content', [ProdukController::class, 'getSidebarContent'])->name('cart.sidebar.content');

    // --- GRUP KHUSUS ADMIN ---
    Route::middleware(['admin'])->group(function () {
        
        // Dashboard & User Approval
        Route::get('/admin/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::post('/admin/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
        Route::post('/admin/promote/{id}', [AuthController::class, 'promoteToAdmin'])->name('admin.promote');

        // --- FITUR KATALOG & TAMPILAN (KatalogController) ---
        // 1. Kelola Produk (List Stok, Hapus, Edit Detail)
        Route::get('/admin/edit-katalog', [KatalogController::class, 'edit'])->name('admin.katalog.edit');
        
        // 2. Kelola Judul & Promo (Halaman Baru: edit_judul.blade.php)
        Route::get('/admin/edit-judul', [KatalogController::class, 'editJudul'])->name('admin.judul.edit');
        
        // 3. Proses Update (Hanya satu route update yang mencakup judul & highlight)
        Route::put('/admin/update-judul', [KatalogController::class, 'update'])->name('admin.judul.update');
        

        // Manajemen Data Master (Prefix Admin)
        Route::prefix('admin')->group(function () {
            
            // A. MANAJEMEN PRODUK (Tambah/Hapus/Detail)
            Route::get('/produk', [ProdukController::class, 'produkIndex'])->name('produk.index');
            Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.create');
            Route::get('/produk/edit/{kd_produk}', [ProdukController::class, 'edit'])->name('produk.edit');
            Route::put('/produk/update/{kd_produk}', [ProdukController::class, 'update'])->name('produk.update');
            Route::get('/tambah-beranda', [ProdukController::class, 'createBeranda'])->name('admin.tambah.beranda');
            Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');
            Route::delete('/produk/hapus/{kd_produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
            // B. MANAJEMEN KATEGORI
            Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
            Route::post('/kategori/toggle/{id}', [KategoriController::class, 'toggleVisible']);
            Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

            // C. MANAJEMEN MERK
            Route::post('/merk/simpan', [MerkController::class, 'store'])->name('merk.store');
            Route::post('/merk/toggle/{id}', [MerkController::class, 'toggleVisible'])->name('merk.toggle');
            Route::delete('/merk/hapus/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');
        });
    });
});