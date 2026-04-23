<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\BerandaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    /**
     * Menampilkan halaman beranda utama
     */
    public function index()
    {
        $settings = BerandaSetting::all()->pluck('value', 'key');

        $produk_terbaru = Produk::latest()->take(8)->get();
        foreach ($produk_terbaru as $item) { $this->setHargaTampil($item); }

        // Menggunakan nama $produk_terpopuler agar sesuai dengan Blade
        $produk_terpopuler = Produk::where('is_highlight', true)->get();
        foreach ($produk_terpopuler as $item) { $this->setHargaTampil($item); }

        $kategori = Kategori::all();
        $merk = Merk::all();

        return view('beranda', compact('settings', 'produk_terbaru', 'produk_terpopuler', 'kategori', 'merk'));
    }

    /**
     * Menampilkan Halaman Katalog/Filter (diarahkan ke view beranda)
     */
    public function katalog(Request $request)
    {
        // Pastikan variabel settings tetap dikirim agar header/footer di beranda tidak error
        $settings = BerandaSetting::all()->pluck('value', 'key');
        $kategori = Kategori::all();
        $merk = Merk::where('is_hidden', 0)->get(); 

        $query = Produk::with(['kategori', 'merk']);

        if ($request->has('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        if ($request->has('merk')) {
            $query->where('kd_merk', $request->merk);
        }

        // Kita masukkan hasil filter ke $produk_terbaru agar section produk di beranda terupdate
        $produk_terbaru = $query->get();
        foreach ($produk_terbaru as $item) {
            $this->setHargaTampil($item);
        }

        // Tetap kirimkan produk terpopuler agar section tersebut tidak error/kosong
        $produk_terpopuler = Produk::where('is_highlight', true)->get();
        foreach ($produk_terpopuler as $item) { $this->setHargaTampil($item); }
        
        // PENTING: Ganti 'katalog' menjadi 'beranda' karena file katalog.blade.php tidak ada
        return view('beranda', compact('settings', 'produk_terbaru', 'produk_terpopuler', 'kategori', 'merk'));
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
        $imageName = time() . '.' . $request->gambar->extension();  
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
    // Mengambil data yang dibutuhkan oleh file edit_katalog
    $produk = Produk::with('merk')->get(); 
    $kategori = Kategori::all();
    $merk = Merk::all();

    // Pastikan string di sini SAMA dengan nama file di folder (admin/edit_katalog.blade.php)
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
            $request->validate([
                'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);
            $imageName = time() . '.' . $request->gambar->extension();  
            $request->gambar->move(public_path('storage'), $imageName);
            $updateData['gambar'] = $imageName;
        }

        $produk->update($updateData);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($kd_produk)
    {
        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
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