<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja
     */
    public function index()
    {
        $previousUrl = url()->previous();
        if ($previousUrl && !str_contains($previousUrl, '/keranjang')) {
            session(['cart_back_url' => $previousUrl]);
        }

        $backUrl = session('cart_back_url', route('katalog'));

        // Gunakan eager loading 'produk.merk' agar tidak error saat memanggil nama merk
        $items = Keranjang::with(['produk.merk'])
                            ->where('user_id', Auth::id())
                            ->get();
        
        $total = 0;
        foreach ($items as $item) {
            // Tentukan harga berdasarkan tipe customer saat ini
            $harga = (Auth::user()->customer_type === 'langganan') 
                      ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                      : $item->produk->harga_jual_umum;
            
            // Simpan harga ke atribut sementara untuk ditampilkan di Blade
            $item->harga_at_time = $harga;
            $total += $harga * $item->jumlah;
        }

        // Variabel dikirim sebagai 'items' agar sesuai dengan @forelse($items as $item) di Blade
        return view('keranjang', compact('items', 'total', 'backUrl'));
    }

    /**
     * Menambahkan produk ke keranjang atau update jumlah jika sudah ada
     */
    public function store($id)
    {
        // 1. Cek apakah produk tersebut valid
        $produk = Produk::where('kd_produk', $id)->firstOrFail();

        // 2. Cek apakah barang sudah ada di keranjang user
        $itemExist = Keranjang::where('user_id', Auth::id())
                              ->where('kd_produk', $id)
                              ->first();

        if ($itemExist) {
            // Cek stok sebelum menambah jumlah
            if ($itemExist->jumlah < $produk->stok_tersedia) {
                $itemExist->increment('jumlah');
            } else {
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }
        } else {
            // Jika belum ada, buat record baru
            Keranjang::create([
                'user_id' => Auth::id(),
                'kd_produk' => $id,
                'jumlah' => 1
            ]);
        }

        // Redirect ke keranjang agar user bisa langsung melihat hasilnya
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Menghapus item dari keranjang
     */
    public function destroy($id)
    {
        // Hapus berdasarkan ID primary key keranjang dan pastikan milik user yang login
        Keranjang::where('id', $id)
                  ->where('user_id', Auth::id())
                  ->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    /**
     * Update jumlah (Opsional: Jika kamu ingin tambah tombol +/- di halaman keranjang)
     */
    public function update(Request $request, $id)
    {
        $item = Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($request->action == 'increase') {
            $item->increment('jumlah');
        } elseif ($request->action == 'decrease' && $item->jumlah > 1) {
            $item->decrement('jumlah');
        }

        return back();
    }
}