<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\KatalogController; 
use App\Http\Controllers\TentangController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

// --- 1. HALAMAN PUBLIK ---
Route::get('/', [ProdukController::class, 'index'])->name('beranda');
Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');
Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang');

// --- 2. SISTEM AUTENTIKASI GUEST (Hanya untuk yang BELUM Login) ---
Route::middleware(['guest'])->group(function () {
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Google Login
    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// --- 3. SISTEM AUTH (Harus Login: Pelanggan & Admin) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // PERBAIKAN: Mengatasi View [dashboard] not found
    // Dialihkan ke beranda agar user langsung melihat produk setelah login
    Route::get('/dashboard', function () {
        return redirect()->route('beranda');
    })->name('dashboard');

    Route::get('/pesanan', function () { return view('pesanan'); })->name('pesanan.index');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profile.edit');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');

    // --- FITUR KERANJANG ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'store'])->name('cart.add');
    Route::put('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'destroy'])->name('cart.remove');
    Route::get('/cart/sidebar-content', [ProdukController::class, 'getSidebarContent'])->name('cart.sidebar.content');

    // --- GRUP KHUSUS ADMIN (Hanya Role Admin) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // --- PENGATURAN TAMPILAN BERANDA ---
        Route::get('/edit-judul', [KatalogController::class, 'editJudul'])->name('admin.judul.edit');
        Route::put('/update-judul', [KatalogController::class, 'update'])->name('admin.judul.update');
        
        // Rute AJAX untuk pencarian ribuan produk secara cepat
        Route::get('/search-produk-ajax', [KatalogController::class, 'searchProduk'])->name('admin.produk.search_ajax');

        // Manajemen Produk
        Route::get('/produk', [ProdukController::class, 'produkIndex'])->name('produk.index');
        Route::get('/produk/tambah', [ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk/simpan', [ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/edit/{kd_produk}', [ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/update/{kd_produk}', [ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/hapus/{kd_produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        // Manajemen Kategori & Merk
        Route::post('/kategori/simpan', [KategoriController::class, 'store'])->name('kategori.store');
        Route::delete('/kategori/hapus/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
        Route::post('/merk/simpan', [MerkController::class, 'store'])->name('merk.store');
        Route::delete('/merk/hapus/{id}', [MerkController::class, 'destroy'])->name('merk.destroy');

        // PERBAIKAN: Mengatasi Route [admin.tentang.update] not defined
        Route::post('/tentang/update', [TentangController::class, 'update'])->name('admin.tentang.update');
        
        Route::post('/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
        Route::post('/promote/{id}', [AuthController::class, 'promoteToAdmin'])->name('admin.promote');
    });
});