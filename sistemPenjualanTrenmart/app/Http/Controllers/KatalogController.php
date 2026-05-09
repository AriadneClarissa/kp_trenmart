<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerandaSetting;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Merk;
use App\Models\Bundling;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    // 1. UNTUK HALAMAN DAFTAR PRODUK (Stok, Tambah, Hapus)
    public function edit()
    {
        $produk = Produk::with(['kategori', 'merk'])->get(); 
        $kategori = Kategori::all(); 
        $merk = Merk::all(); 

        return view('admin.edit_katalog', compact('produk', 'kategori', 'merk'));
    }

    // 2. API UNTUK PENCARIAN PRODUK (Menangani ribuan produk agar tidak lemot)
    public function searchProduk(Request $request)
    {
        $search = $request->term; // Konsisten dengan JS 'term'
        $merkSearch = $request->merk; // Konsisten dengan JS 'merk'

        $query = Produk::with('merk');

        // Filter Nama/Kode Produk
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%$search%")
                ->orWhere('kd_produk', 'LIKE', "%$search%");
            });
        }

        // Filter Merk: Memastikan hanya merk yang dicari yang tampil
        if ($merkSearch) {
            $query->whereHas('merk', function($q) use ($merkSearch) {
                $q->where('nama_merk', 'LIKE', "%$merkSearch%");
            });
        }

        $produk = $query->take(10)->get();

        // Mapping Data: Konsisten mengembalikan 'id', 'text', 'price', dan 'merk'
        $results = $produk->map(function($p) {
            return [
                'id'    => $p->kd_produk,
                'text'  => $p->nama_produk,
                'price' => (int)($p->harga_jual_umum ?? 0), // Menghindari NaN di JS
                'merk'  => $p->merk->nama_merk ?? 'Tanpa Merk' // Menghindari undefined di JS
            ];
        });

        return response()->json($results);
    }
    
    public function index()
    {
        $settings = BerandaSetting::all()->pluck('value', 'key');
        $produk_terbaru = Produk::with('merk')->latest()->take(8)->get();

        // Pastikan mengambil data bundling
        $bundlings = Bundling::with('items.produk.merk')->latest()->get(); 

        // Kirim variabel ke view menggunakan compact
        return view('beranda', compact('settings', 'produk_terbaru', 'bundlings'));
    }
}