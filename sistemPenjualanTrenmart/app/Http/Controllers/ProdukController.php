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
        
        $semua_produk = $produk_terbaru->merge($produk_terpopuler);
        foreach ($semua_produk as $item) {
            $this->setHargaTampil($item);
        }

        return view('beranda', compact('produk_terbaru', 'produk_terpopuler'));
    }

    /**
     * Menampilkan Halaman Katalog Produk dengan Filter Kategori & Merk
     */
    public function katalog(Request $request)
{
    // 1. Ambil semua kategori untuk bar navigasi atas
    $kategori = Kategori::all();
    
    // Merk yang tampil di sidebar hanya yang TIDAK disembunyikan
    $merk = Merk::where('is_hidden', 0)->get(); 

    $query = Produk::with(['kategori', 'merk']);

    // 4. Filter Pencarian Nama
    if ($request->has('search')) {
        $query->where('nama_produk', 'like', '%' . $request->search . '%');
    }

    // 5. Filter Kategori
    if ($request->has('kategori')) {
        $kd_kat = $request->kategori;
        $query->where('kd_kategori', $kd_kat);
        
        // OPSI: Jika ingin merk tetap terfilter berdasarkan kategori yang dipilih, 
        // aktifkan baris di bawah ini. Jika ingin semua merk tampil terus, biarkan saja.
        /*
        $merk = Merk::whereHas('produk', function($q) use ($kd_kat) {
            $q->where('kd_kategori', $kd_kat);
        })->get();
        */
    }

    // 6. Filter Merk
    if ($request->has('merk')) {
        $query->where('kd_merk', $request->merk);
    }

    $produk = $query->get();

    // 7. Memproses Harga Dinamis
    foreach ($produk as $item) {
        $this->setHargaTampil($item);
    }

    return view('katalog', compact('produk', 'kategori', 'merk'));
    }

    /**
     * Helper untuk menentukan harga berdasarkan tipe customer
     */
    private function setHargaTampil($item)
    {
        if (Auth::check() && Auth::user()->customer_type === 'langganan') {
            $item->harga_tampil = $item->harga_jual_langganan;
        } else {
            $item->harga_tampil = $item->harga_jual_umum;
        }
    }

    /**
     * Tambah Produk via Beranda & Admin
     */
    public function createBeranda() { return $this->createForm('beranda'); }
    public function create() { return $this->createForm('layar_produk'); }

    private function createForm($source)
    {
        return view('admin.tambah_produk', [
            'source' => $source,
            'kategoris' => Kategori::all(),
            'merks' => Merk::all()
        ]);
    }

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
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'   => $request->stok_tersedia,
            'gambar'          => $imageName,
        ]);

        return redirect()->route($request->origin == 'beranda' ? 'beranda' : 'produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkIndex()
    {
        $produk = Produk::with(['kategori', 'merk'])->get(); 
        return view('admin.produk_index', compact('produk')); 
    }

    public function show($id)
    {
        $produk = Produk::where('kd_produk', $id)->firstOrFail();
        $this->setHargaTampil($produk);
        return view('produk.detail', compact('produk'));
    }
}