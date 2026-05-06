<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Merk;
use App\Models\BerandaSetting;
use App\Models\User;
use App\Models\Bundling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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

        $kategori = Kategori::all();
        $merk = Merk::all();

        // Mengambil data admin untuk banner
        $admin = User::where('role', 'admin')->first();
        $bundling = Bundling::with(['items.produk.merk'])->latest()->get();
        return view('beranda', compact('settings', 'produk_terbaru', 'kategori', 'merk', 'admin', 'bundling'));
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
            'merks' => Merk::all(),
            'satuan' => \App\Models\Satuan::all()
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'kd_produk'       => 'required|unique:produk,kd_produk',
            'nama_produk'     => 'required|string|max:255',
            'harga_jual_umum' => 'required|numeric',
            'stok_tersedia'   => 'required|numeric',
            'files.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Siapkan data awal
        $data = [
            'kd_produk'            => $request->kd_produk,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'kd_satuan'            => $request->kd_satuan,
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
            'status'               => 'aktif', // default status
        ];

        // 3. Logika Simpan Banyak Foto (Maksimal 3)
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            
            // Pemetaan urutan file ke kolom database Trenmart
            $columns = [0 => 'gambar', 1 => 'foto_2', 2 => 'foto_3'];

            foreach ($files as $index => $file) {
                if (isset($columns[$index])) {
                    $columnName = $columns[$index];
                    
                    // Simpan file ke storage/app/public/produk
                    $path = $file->store('produk', 'public');
                    $data[$columnName] = $path;
                }
            }
        }

        // 4. Eksekusi Simpan ke Database
        Produk::create($data);

        // 5. Redirect sesuai origin (Beranda atau Index Produk)
        $route = ($request->origin == 'beranda') ? 'beranda' : 'produk.index';
        return redirect()->route($route)->with('success', 'Produk berhasil ditambahkan!');
    }

    public function produkIndex(Request $request)
    {
        $query = Produk::with(['merk', 'kategori', 'satuanModel']);

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kd_kategori', $request->kategori);
        }

        if ($request->filled('merk')) {
            $query->where('kd_merk', $request->merk);
        }

        // Untuk halaman tabel manajemen stok admin
        $produk = $query->latest()->get();
        foreach ($produk as $item) {
            $this->setHargaTampil($item);
        }
        $kategori = Kategori::all();
        $merk = Merk::all();
        $satuan = \App\Models\Satuan::all();

        return view('admin.edit_katalog', compact('produk', 'kategori', 'merk', 'satuan'));
    }

    public function edit($kd_produk)
    {
        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        $kategoris = Kategori::all();
        $merks = Merk::all();
        $satuan = \App\Models\Satuan::all();
        return view('admin.edit_produk', compact('produk', 'kategoris', 'merks', 'satuan'));
    }

    public function update(Request $request, $kd_produk)
    {
        $request->validate([
            'nama_produk'          => 'required|string|max:255',
            'harga_jual_umum'      => 'required|numeric',
            'stok_tersedia'        => 'required|numeric',
            'files.*'              => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $produk = Produk::where('kd_produk', $kd_produk)->firstOrFail();
        
        $updateData = [
            'nama_produk'          => $request->nama_produk,
            'deskripsi'            => $request->deskripsi,
            'kd_kategori'          => $request->kd_kategori,
            'kd_merk'              => $request->kd_merk,
            'kd_satuan'            => $request->kd_satuan,
            'harga_jual_umum'      => $request->harga_jual_umum,
            'harga_jual_langganan' => $request->harga_jual_langganan ?? $request->harga_jual_umum,
            'stok_tersedia'        => $request->stok_tersedia,
        ];

        // Logika Multiple Upload
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $columns = [0 => 'gambar', 1 => 'foto_2', 2 => 'foto_3'];

            foreach ($files as $index => $file) {
                if (isset($columns[$index])) {
                    $columnName = $columns[$index];

                    // Hapus gambar lama jika ada
                    if ($produk->$columnName && Storage::disk('public')->exists($produk->$columnName)) {
                        Storage::disk('public')->delete($produk->$columnName);
                    }

                    // Simpan file baru ke folder 'produk'
                    $path = $file->store('produk', 'public');
                    $updateData[$columnName] = $path;
                }
            }
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

    public function searchAjax(Request $request)
    {
    $cari = $request->q; // 'q' adalah parameter default dari Select2

    $produk = \App\Models\Produk::where('nama_produk', 'LIKE', "%$cari%")
                ->select('kd_produk as id', 'nama_produk as text') // SANGAT PENTING: id dan text
                ->limit(20)
                ->get();

    return response()->json($produk);
    }

    public function updateStatus(Request $request)
    {
        // 1. Validasi dengan menangkap pesan error agar tidak langsung 500
        $request->validate([
            // Sesuaikan 'produk' dengan nama tabel asli di SQL Server Anda
            'id' => 'required|exists:produk,kd_produk', 
            'status' => 'required|in:aktif,nonaktif'
        ]);

        try {
            // 2. Gunakan where karena primary key Anda adalah kd_produk, bukan id (integer)
            $produk = \App\Models\Produk::where('kd_produk', $request->id)->firstOrFail();
            
            $produk->status = $request->status;
            $produk->save();

            return response()->json([
            'success' => true,
            'message' => 'Status ' . $produk->nama_produk . ' berhasil diperbarui!' 
        ]);

        } catch (\Exception $e) {
            // 3. Jika terjadi error database, kirimkan pesan yang jelas ke AJAX
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui database: ' . $e->getMessage()
            ], 500);
        }
    }
}