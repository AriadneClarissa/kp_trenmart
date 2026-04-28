<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\KatalogController; 
use App\Http\Controllers\TentangController;
use Illuminate\Support\Facades\Route;

// --- 1. HALAMAN PUBLIK ---
Route::get('/', [ProdukController::class, 'index'])->name('beranda');
Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');
Route::get('/tentang-kami', [TentangController::class, 'index'])->name('tentang');

// --- 2. SISTEM AUTENTIKASI GUEST (LOGIN CUSTOMER) ---
Route::middleware(['guest'])->group(function () {
    // Jalur Customer
    Route::get('/register', function () { return view('auth.register'); })->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', function () { return view('auth.login'); })->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // JALUR KHUSUS ADMIN (Link Login Terpisah)
    Route::get('/internal-trenmart-admin', function () { return view('auth.login_admin'); })->name('admin.login');
    Route::post('/internal-trenmart-admin', [AuthController::class, 'login']); 

    // Google Login
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// --- 3. SISTEM AUTH (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // --- ALUR PROFIL SETELAH LOGIN GOOGLE ---
    Route::get('/pilih-jenis', [AuthController::class, 'showPilihJenis'])->name('pilih.jenis');
    Route::post('/pilih-jenis', [AuthController::class, 'handlePilihJenis'])->name('pilih.jenis.post');
    Route::get('/lengkapi-profil/umum', [AuthController::class, 'formUmum'])->name('form.umum');
    Route::get('/lengkapi-profil/langganan', [AuthController::class, 'formLangganan'])->name('form.langganan');
    Route::post('/update-profil-awal', [AuthController::class, 'updateProfileAfterGoogle'])->name('profile.initial.update');
    Route::get('/status-tinjau', [AuthController::class, 'statusTinjau'])->name('status.tinjau');

    // --- FITUR PELANGGAN ---
    Route::get('/dashboard', function () { return redirect()->route('beranda'); })->name('dashboard');
    Route::get('/pesanan', function () { return view('pesanan'); })->name('pesanan.index');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profile.edit');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');
    

    // --- FITUR KERANJANG ---
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{id}', [KeranjangController::class, 'store'])->name('cart.add');
    Route::put('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [KeranjangController::class, 'destroy'])->name('cart.remove');
    Route::get('/cart/sidebar-content', [ProdukController::class, 'getSidebarContent'])->name('cart.sidebar.content');

    // --- 4. GRUP KHUSUS ADMIN (Hanya Bisa Diakses Role Admin) ---
    Route::middleware(['admin'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [AuthController::class, 'adminDashboard'])->name('admin.dashboard');
        
        // Pengaturan Tampilan & Search
        Route::get('/edit-judul', [KatalogController::class, 'editJudul'])->name('admin.judul.edit');
        Route::put('/update-judul', [KatalogController::class, 'update'])->name('admin.judul.update');
        Route::get('/search-produk-ajax', [ProdukController::class, 'searchAjax'])->name('admin.produk.search_ajax');

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

        Route::post('/tentang/update', [TentangController::class, 'update'])->name('admin.tentang.update');

        // Admin Approval (Tolak dan Terima Pendaftaran Pelanggan Langganan)
        Route::post('/approve/{id}', [AuthController::class, 'approveUser'])->name('admin.approve');
        Route::delete('/reject/{id}', [AuthController::class, 'reject'])->name('admin.reject');
    });
});