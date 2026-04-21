<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori; // Tambahkan ini
use App\Models\Merk;     // Tambahkan ini
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
        
        // Memproses harga tampil secara dinamis
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
     * Jalur 1: Tambah Produk via Beranda
     */
    public function createBeranda()
    {
        // Mengambil data kategori & merk dari DB agar Admin tidak utak-atik kodingan
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
        // 1. Validasi Input
        $request->validate([
            'nama_produk'     => 'required|string|max:255',
            'gambar'          => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'harga_jual_umum' => 'required|numeric',
            'stok_tersedia'   => 'required|numeric',
            'kd_kategori'     => 'required',
            'kd_merk'         => 'required',
        ]);

        // 2. Proses Upload Gambar ke public/storage
        $imageName = time() . '.' . $request->gambar->extension();  
        $request->gambar->move(public_path('storage'), $imageName);

        // 3. Simpan ke tabel produk
        // Note: kd_produk biasanya otomatis jika di DB diset auto-increment
        Produk::create([
            'nama_produk'     => $request->nama_produk,
            'deskripsi'       => $request->deskripsi,
            'kd_kategori'     => $request->kd_kategori,
            'kd_merk'         => $request->kd_merk,
            'harga_jual_umum' => $request->harga_jual_umum,
            'stok_tersedia'   => $request->stok_tersedia,
            'gambar'          => $imageName,
        ]);

        // 4. Redirect otomatis berdasarkan asal tombol (origin)
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
        // Mencari berdasarkan primary key kd_produk
        $produk = Produk::where('kd_produk', $id)->firstOrFail();
        return view('produk.detail', compact('produk'));
    }
}