<?php

namespace App\Http\Controllers;

use App\Models\Produk; // Pastikan Model Produk sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan daftar produk terbaru.
     */
    public function index()
    {
    // 1. Ambil data untuk Produk Terbaru (urut berdasarkan yang terakhir diinput)
    $produk_terbaru = Produk::latest()->take(10)->get();

    // 2. Ambil data untuk Produk Terpopuler 
    // (Untuk sementara kita ambil random atau bisa berdasarkan stok tersedikit/terbanyak)
    $produk_terpopuler = Produk::inRandomOrder()->take(10)->get();

    // 3. Gabungkan semua produk untuk diproses harganya
    $semua_produk = $produk_terbaru->merge($produk_terpopuler);

    foreach ($semua_produk as $item) {
        if (Auth::check() && Auth::user()->customer_type === 'langganan') {
            $item->harga_tampil = $item->harga_jual_langganan;
        } else {
            $item->harga_tampil = $item->harga_jual_umum;
        }
    }

    // 4. Kirim KEDUA variabel ke View
    return view('beranda', compact('produk_terbaru', 'produk_terpopuler'));
    }

    /**
     * Fitur Pencarian Produk
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Mencari produk berdasarkan nama atau deskripsi
        $produk = Produk::where('nama_produk', 'LIKE', "%{$query}%")
                         ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                         ->get();

        return view('beranda', compact('produk', 'query'));
    }

    /**
     * Menampilkan detail produk jika gambar atau nama ditekan
     */
    public function show($id)
    {
        $produk = Produk::where('kd_produk', $id)->firstOrFail();
        return view('produk.detail', compact('produk'));
    }
}