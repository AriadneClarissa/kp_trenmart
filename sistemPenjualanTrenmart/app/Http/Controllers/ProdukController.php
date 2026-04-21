<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori; 
use App\Models\Merk;    
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda untuk pembeli
     */
    public function index()
    {
        $produk_terbaru = Produk::latest()->take(10)->get();
        $produk_terpopuler = Produk::inRandomOrder()->take(10)->get();
        
        // Memproses harga tampil secara dinamis pada beranda
        $semua_produk = $produk_terbaru->merge($produk_terpopuler);
        foreach ($semua_produk as $item) {
            if (Auth::check() && Auth::user()->customer_type === 'langganan') {
                $item->harga_tampil = $item->harga_jual_langganan;
            } else {
                $item->harga_tampil = $item->harga_jual_umum;
            }
        }

        return view('beranda', compact('produk_terbaru', 'produk_terpopuler'));
    }

    /**
     * PERUBAHAN DISINI: Menampilkan Halaman Katalog Produk
     */
    public function katalog(Request $request)
    {
        // 1. Ambil data kategori & merk untuk sidebar/filter
        $kategori = Kategori::all();
        $merk = Merk::all();

        // 2. Query dasar produk dengan relasi
        $query = Produk::with(['kategori', 'merk']);

        // 3. Logika Filter Pencarian
        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // 4. Logika Filter Kategori
        if ($request->has('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        $produk = $query->get();

        // 5. Memproses Harga Dinamis untuk setiap produk di Katalog
        foreach ($produk as $item) {
            if (Auth::check() && Auth::user()->customer_type === 'langganan') {
                $item->harga_tampil = $item->harga_jual_langganan;
            } else {
                $item->harga_tampil = $item->harga_jual_umum;
            }
        }

        // 6. Kirim ke view katalog.blade.php
        return view('katalog', compact('produk', 'kategori', 'merk'));
    }

    /**
     * Jalur 1: Tambah Produk via Beranda
     */
    public function createBeranda()
    {
        $kategoris = Kategori::all();
        $merks = Merk::all();

        return view('admin.tambah_produk', [
            'source' => 'beranda',
            'kategoris' => $kategoris,
            'merks' => $merks
        ]);
    }

    /**
     * Jalur 2: Tambah Produk via Layar Produk (Admin)
     */
    public function create()
    {
        $kategoris = Kategori::all();
        $merks = Merk::all();

        return view('admin.tambah_produk', [
            'source' => 'layar_produk',
            'kategoris' => $kategoris,
            'merks' => $merks
        ]);
    }

    /**
     * Menyimpan produk baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk'     => 'required|string|max:255',
            'gambar'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'harga_jual_umum' => 'required|numeric',
            'stok_tersedia'   => 'required|numeric',
            'kd_kategori'     => 'required',
            'kd_merk'         => 'required',
        ]);

        $imageName = time() . '.' . $request->gambar->extension();  
        $request->gambar->move(public_path('storage'), $imageName);

        Produk::create([
            'nama_produk'     => $request->nama_produk,
            'deskripsi'       => $request->deskripsi,
            'kd_kategori'     => $request->kd_kategori,
            'kd_merk'         => $request->kd_merk,
            'harga_jual_umum' => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum, // Tambahan field jika ada
            'stok_tersedia'   => $request->stok_tersedia,
            'gambar'          => $imageName,
        ]);

        if ($request->origin == 'beranda') {
            return redirect()->route('beranda')->with('success', 'Produk berhasil ditambahkan ke Beranda!');
        }
        
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan ke Daftar Produk!');
    }

    /**
     * Daftar semua produk untuk sisi Admin
     */
    public function produkIndex()
    {
        $produk = Produk::with(['kategori', 'merk'])->get(); 
        return view('admin.produk_index', compact('produk')); 
    }

    /**
     * Menampilkan detail produk
     */
    public function show($id)
    {
        $produk = Produk::where('kd_produk', $id)->firstOrFail();
        return view('produk.detail', compact('produk'));
    }
}