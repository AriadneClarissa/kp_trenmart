<?php

namespace App\Http\Controllers;

use App\Models\Produk; // Pastikan Model Produk sudah dibuat
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan daftar produk terbaru.
     */
    public function index()
    {
        // Mengambil semua produk dari database
        // Kita gunakan paginate(10) agar jika produk banyak, halaman tidak terlalu panjang
        $produk = Produk::all();

        // Logika untuk menentukan harga yang tampil berdasarkan status login
        foreach ($produk as $produk) {
            // Cek apakah user sudah login dan memiliki role 'langganan'
            // Catatan: Asumsi kamu punya kolom 'role' atau 'tipe' di tabel users/pelanggan
            if (Auth::check() && Auth::user()->role === 'langganan') {
                $produk->harga_tampil = $produk->harga_jual_langganan;
                $produk->label_status = 'Harga Langganan';
            } else {
                $produk->harga_tampil = $produk->harga_jual_umum;
                $produk->label_status = 'Harga Umum';
            }
        }

        // Mengirim data ke view 'beranda'
        return view('beranda', compact('produk'));
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