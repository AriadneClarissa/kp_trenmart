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
    // 3. HALAMAN EDIT JUDUL & SECTION CUSTOM
    public function editJudul()
    {
        $settings = BerandaSetting::all()->pluck('value', 'key');
        $selectedSection = request('target_section', 'section_3');
        
        $sectionProdukMap = [
            'section_3' => Produk::where('is_custom_section', true)->get(),
            'terpopuler' => Produk::where('is_highlight', true)->get(),
            'terbaru' => Produk::where('is_custom_section', true)->get(),
        ];
        
        // Ambil semua produk hanya untuk section "Terpopuler" (jika datanya masih sedikit)
        // Jika produk terpopuler juga mencapai ribuan, gunakan sistem search yang sama seperti section 3
        $produk = Produk::with('merk')->get();

        foreach ($produk as $item) {
            $item->harga_tampil = $item->harga_jual_umum ?? 0;
            if (Auth::check() && Auth::user()->customer_type === 'langganan') {
                $item->harga_tampil = $item->harga_jual_langganan ?? $item->harga_jual_umum;
            }
        }
        
        return view('admin.edit_judul', compact('settings', 'produk', 'sectionProdukMap', 'selectedSection')); 
    }

    // 4. PROSES UPDATE (Menyimpan perubahan nama section dan pilihan produk)
    public function update(Request $request)
    {
        $request->validate([
            'judul_terbaru' => 'required|string|max:255',
            'judul_terpopuler' => 'required|string|max:255',
            'target_section' => 'required|in:section_3,terpopuler,terbaru',
            'produk_pilihan' => 'nullable|array',
            'produk_pilihan.*' => 'string',
        ]);

        // 1. Simpan judul yang benar-benar dipakai di beranda
        BerandaSetting::updateOrCreate(
            ['key' => 'judul_terbaru'],
            ['value' => trim($request->judul_terbaru)]
        );

        BerandaSetting::updateOrCreate(
            ['key' => 'judul_terpopuler'],
            ['value' => trim($request->judul_terpopuler)]
        );

        // 2. Tentukan kolom database mana yang akan diupdate berdasarkan dropdown
        $column = ($request->target_section == 'terpopuler') ? 'is_highlight' : 'is_custom_section';

        // Reset status lama untuk section tersebut
        \App\Models\Produk::where($column, true)->update([$column => false]);

        // Simpan produk yang baru dipilih via Search Bar
        if ($request->filled('produk_pilihan')) {
            \App\Models\Produk::whereIn('kd_produk', $request->produk_pilihan)
                ->update([$column => true]);
        }

        return redirect()->back()->with('success', 'Konfigurasi Beranda berhasil disimpan!');
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