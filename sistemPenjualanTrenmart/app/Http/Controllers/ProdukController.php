<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        foreach ($produk as $item) {
            $item->harga_tampil = (Auth::check() && Auth::user()->role === 'langganan') 
                ? $item->harga_jual_langganan : $item->harga_jual_umum;
        }
        return view('beranda', compact('produk'));
    }

    public function katalog(Request $request)
    {
        // 1. Ambil data master untuk SIDEBAR (Agar tidak error Undefined Variable)
        $kategori = Kategori::all();
        $merk = Merk::all();

        // 2. Query Produk dengan relasi
        $queryProduk = Produk::with(['kategori', 'merk']);

        // 3. Logika Filter
        if ($request->filled('kategori')) {
            $queryProduk->where('kd_kategori', $request->kategori);
        }
        if ($request->filled('merk')) {
            $queryProduk->where('kd_merk', $request->merk);
        }

        $produk = $queryProduk->get();

        // 4. Logika Harga (Sesuai role langganan)
        foreach ($produk as $item) {
            if (Auth::check() && Auth::user()->role === 'langganan') {
                $item->harga_tampil = $item->harga_jual_langganan;
                $item->label_status = 'Harga Langganan';
            } else {
                $item->harga_tampil = $item->harga_jual_umum;
                $item->label_status = 'Harga Umum';
            }
        }

        // 5. Kirim SEMUA variabel ke view 'katalog'
        // Pastikan 'kategori' dan 'merk' masuk ke dalam compact
        return view('katalog', compact('produk', 'kategori', 'merk'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $produk = Produk::where('nama_produk', 'LIKE', "%{$query}%")
                         ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                         ->get();

        foreach ($produk as $item) {
            $item->harga_tampil = (Auth::check() && Auth::user()->role === 'langganan') 
                ? $item->harga_jual_langganan : $item->harga_jual_umum;
        }

        return view('beranda', compact('produk', 'query'));
    }
}