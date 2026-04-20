<?php

use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

/*
| Web Routes - Trenmart Project
|--------------------------------------------------------------------------
*/

// Halaman Utama (Menampilkan Produk Terbaru)
Route::get('/', [ProdukController::class, 'index'])->name('beranda');

// Fitur Pencarian Produk
Route::get('/search', [ProdukController::class, 'search'])->name('produk.search');

// Detail Produk (Saat gambar atau nama produk diklik)
// Kita gunakan {id} sebagai parameter kode produk
Route::get('/produk/detail/{id}', [ProdukController::class, 'show'])->name('produk.detail');

// Route Navigasi Statis (Sesuai KAKP)
Route::get('/katalog', [ProdukController::class, 'katalog'])->name('katalog');

Route::get('/pesanan', function () {
    return view('pesanan'); // Untuk fitur Minggu ke-10
})->name('pesanan');

Route::get('/tentang-kami', function () {
    return view('tentang-kami');
})->name('tentang');

// Route Keranjang (Minggu ke-9)
Route::post('/keranjang/tambah', [ProdukController::class, 'addToCart'])->name('keranjang.tambah');