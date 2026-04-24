<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BerandaSetting;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Merk;
use Illuminate\Support\Facades\Auth;

class KatalogController extends Controller
{
    // UNTUK HALAMAN DAFTAR PRODUK (Stok, Tambah, Hapus)
    public function edit()
    {
    // Ambil data untuk tabel produk
    $produk = Produk::with(['kategori', 'merk'])->get(); 
    
    // AMBIL DATA INI supaya dropdown filter tidak error
    $kategori = Kategori::all(); 
    $merk = Merk::all(); 

    // Kirimkan semua variabel ke view
    return view('admin.edit_katalog', compact('produk', 'kategori', 'merk'));
    }

    // UNTUK HALAMAN EDIT JUDUL & PROMO (File baru: edit_judul.blade.php)
    public function editJudul()
    {
    $settings = BerandaSetting::all()->pluck('value', 'key');
    $produk = Produk::with('merk')->get();

    foreach ($produk as $item) {
        $item->harga_tampil = $item->harga_jual_umum ?? 0;

        if (Auth::check() && Auth::user()->customer_type === 'langganan') {
            $item->harga_tampil = $item->harga_jual_langganan ?? $item->harga_jual_umum;
        }
    }
    
    // Pastikan diarahkan ke file blade yang baru saja kamu kirim
    return view('admin.edit_judul', compact('settings', 'produk')); 
    }

    // PROSES UPDATE (Dipakai oleh form di edit_judul.blade.php)
    public function update(Request $request)
    {
        // 1. Update Judul Section
        BerandaSetting::updateOrCreate(
            ['key' => 'judul_terbaru'], 
            ['value' => $request->judul_terbaru]
        );
        
        BerandaSetting::updateOrCreate(
            ['key' => 'judul_terpopuler'], 
            ['value' => $request->judul_terpopuler]
        );

        // 2. Update Produk Highlight/Promo
        Produk::where('is_highlight', true)->update(['is_highlight' => false]);

        if($request->produk_pilihan) {
            Produk::whereIn('kd_produk', $request->produk_pilihan)->update(['is_highlight' => true]);
        }

        return redirect()->route('admin.judul.edit')->with('success', 'Tampilan Beranda berhasil diperbarui!');
    }
    
}