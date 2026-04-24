<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\BerandaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda utama
     */
    public function index()
    {
        $settings = BerandaSetting::all()->pluck('value', 'key');

        // Mengambil produk terbaru (8 item)
        $produk_terbaru = Produk::latest()->take(8)->get();
        foreach ($produk_terbaru as $item) { 
            $this->setHargaTampil($item); 
        }

        // Mengambil produk populer (is_highlight)
        $produk_terpopuler = Produk::where('is_highlight', true)->get();
        foreach ($produk_terpopuler as $item) { 
            $this->setHargaTampil($item); 
        }

        $kategori = Kategori::all();
        $merk = Merk::all();

        return view('beranda', compact('settings', 'produk_terbaru', 'produk_terpopuler', 'kategori', 'merk'));
    }

    /**
     * Menampilkan Halaman Katalog dengan Filter Pencarian
     */
    public function katalog(Request $request)
    {
        $kategori = Kategori::all();
        $merk = Merk::where('is_hidden', 0)->get(); 

        $query = Produk::with(['kategori', 'merk']);

        // Filter Pencarian Nama
        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        // Filter Merk
        if ($request->filled('merk')) {
            $query->where('kd_merk', $request->merk);
        }

        $produk = $query->latest()->get();
        
        // PENTING: Memproses harga agar tidak muncul Rp 0 di halaman katalog
        foreach ($produk as $item) {
            $this->setHargaTampil($item);
        }

        return view('katalog', compact('produk', 'kategori', 'merk'));
    }

    /**
     * Helper untuk menentukan harga berdasarkan tipe customer (Umum/Langganan)
     */
    private function setHargaTampil($item)
    {
        // Default harga menggunakan harga jual umum
        $item->harga_tampil = $item->harga_jual_umum ?? 0;

        // Jika user login dan tipenya 'langganan', gunakan harga langganan
        if (Auth::check() && Auth::user()->customer_type === 'langganan') {
            $item->harga_tampil = $item->harga_jual_langganan ?? $item->harga_jual_umum;
        }
    }

    // --- Bagian Manajemen Admin ---

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
            'satuan'          => 'required', 
        ]);

        $satuanFinal = ($request->satuan === 'Lainnya') ? $request->satuan_custom : $request->satuan;
        
        // Simpan gambar dengan nama unik
        $imageName = time() . '_' . uniqid() . '.' . $request->gambar->extension();  
        $request->gambar->move(public_path('storage'), $imageName);

        Produk::create([
            'kd_produk'            => 'PRD-' . strtoupper(uniqid()),
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
            'satuan'               => $satuanFinal,
            'status'               => 'aktif', 
            'gambar'               => $imageName,
        ]);

        return redirect()->route($request->origin == 'beranda' ? 'beranda' : 'produk.index')
                         ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkIndex()
    {
        // Untuk halaman tabel manajemen stok admin
        $produk = Produk::with(['merk', 'kategori'])->latest()->get(); 
        foreach ($produk as $item) {
            $this->setHargaTampil($item);
        }
        $kategori = Kategori::all();
        $merk = Merk::all();

        return view('admin.edit_katalog', compact('produk', 'kategori', 'merk'));
    }

    public function edit($kd_produk)
    {
        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        $kategoris = Kategori::all();
        $merks = Merk::all();
        return view('admin.edit_produk', compact('produk', 'kategoris', 'merks'));
    }

    public function update(Request $request, $kd_produk)
    {
        $request->validate([
            'nama_produk'     => 'required|string|max:255',
            'harga_jual_umum' => 'required|numeric',
            'stok_tersedia'   => 'required|numeric',
            'kd_kategori'     => 'required',
            'kd_merk'         => 'required',
            'satuan'          => 'required',
        ]);

        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        $satuanFinal = ($request->satuan === 'Lainnya') ? $request->satuan_custom : $request->satuan;
        
        $updateData = [
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
            'satuan'               => $satuanFinal,
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($produk->gambar && File::exists(public_path('storage/' . $produk->gambar))) {
                File::delete(public_path('storage/' . $produk->gambar));
            }

            $imageName = time() . '_' . uniqid() . '.' . $request->gambar->extension();  
            $request->gambar->move(public_path('storage'), $imageName);
            $updateData['gambar'] = $imageName;
        }

        $produk->update($updateData);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($kd_produk)
    {
        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        
        // Hapus file gambar
        if ($produk->gambar && File::exists(public_path('storage/' . $produk->gambar))) {
            File::delete(public_path('storage/' . $produk->gambar));
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        $produk = Produk::where('kd_produk', $id)->firstOrFail();
        $this->setHargaTampil($produk);
        return view('produk.detail', compact('produk'));
    }
}