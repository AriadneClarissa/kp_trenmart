<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan Halaman Beranda
     */
    public function index()
    {
        // 1. Ambil 5 produk terbaru untuk section "Produk Terbaru"
        $produk_terbaru = Produk::latest()->take(5)->get();

        // 2. Ambil 5 produk secara acak untuk section "Produk Terpopuler"
        // (Nantinya bisa diganti berdasarkan jumlah penjualan terbanyak)
        $produk_populer = Produk::inRandomOrder()->take(5)->get();

        // 3. Kirim kedua variabel ke view 'beranda'
        return view('beranda', compact('produk_terbaru', 'produk_populer'));
    }

    /**
     * Menangani Pencarian Produk
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Cari produk yang namanya mirip dengan kata kunci
        $produk_terbaru = Produk::where('nama_produk', 'LIKE', "%{$query}%")
                                ->get();

        // Biarkan produk populer kosong saat pencarian agar fokus pada hasil cari
        $produk_populer = collect(); 

        return view('beranda', compact('produk_terbaru', 'produk_populer', 'query'));
    }

    /**
     * Menampilkan Detail Produk (Opsional jika dibutuhkan)
     */
    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.detail', compact('produk'));
    }

    public function create() {
    return view('admin.tambah_produk');
}

public function store(Request $request) {
    $request->validate([
        'kd_produk' => 'required|unique:produk',
        'nama_produk' => 'required',
        'harga_jual_umum' => 'required|numeric',
        'harga_jual_langganan' => 'required|numeric',
        'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    // Handle Upload Gambar
    $path = $request->file('gambar')->store('produk', 'public');

    // Simpan ke Database
    Produk::create([
        'kd_produk' => $request->kd_produk,
        'nama_produk' => $request->nama_produk,
        'harga_jual_umum' => $request->harga_jual_umum,
        'harga_jual_langganan' => $request->harga_jual_langganan,
        'gambar' => $path, // Ini akan menyimpan path: produk/namafile.png
        'status' => 'aktif',
    ]);

    return redirect()->route('beranda')->with('success', 'Produk berhasil ditambahkan!');
}
}