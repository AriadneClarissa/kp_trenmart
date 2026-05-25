<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class KeranjangController extends Controller
{
    /**
     * Menampilkan isi keranjang belanja
     */
    public function index()
    {
        $previousUrl = URL::previous();
        if ($previousUrl && !str_contains($previousUrl, '/keranjang')) {
            session(['cart_back_url' => $previousUrl]);
        }

        $backUrl = session('cart_back_url', route('katalog'));

        // PERBAIKAN 1: Tambahkan 'bundling' di eager loading agar data paket ikut terpanggil
        $items = Keranjang::with(['produk.merk', 'bundling'])
                            ->where('user_id', Auth::id())
                            ->get();
        
        $total = 0;
        foreach ($items as $item) {
            
            // PERBAIKAN 2: Pisahkan pengecekan harga untuk Bundling dan Produk Reguler
            if ($item->bundling_id != null && $item->bundling) {
                // JIKA INI PAKET BUNDLING (Ambil harga dari tabel bundling)
                $harga = $item->bundling->bundling_price; 
            } else {
                // JIKA INI PRODUK REGULER (Cek apakah user langganan atau umum)
                $harga = (Auth::user()->customer_type === 'langganan') 
                          ? ($item->produk->harga_jual_langganan ?? $item->produk->harga_jual_umum) 
                          : $item->produk->harga_jual_umum;
            }
            
            // Simpan harga ke atribut sementara untuk ditampilkan di Blade
            $item->harga_at_time = $harga;
            $total += $harga * $item->jumlah;
        }

        return view('keranjang', compact('items', 'total', 'backUrl'));
    }

    /**
     * Menambahkan produk ke keranjang atau update jumlah jika sudah ada
     */
    public function store(Request $request, $id)
    {
        // Ambil tipe dari request (kita kirim via hidden input atau parameter)
        $type = $request->input('type', 'reguler'); 

        if ($type === 'bundling') {
            // 1a. Validasi Bundling
            $bundling = \App\Models\Bundling::findOrFail($id);
            $stokTersedia = 999; // Atau logic stok bundling kamu
            $identifierColumn = 'bundling_id'; 
            $kdProdukValue = null;
            $bundlingIdValue = $id;
        } else {
            // 1b. Validasi Produk Reguler
            $produk = Produk::where('kd_produk', $id)->firstOrFail();
            $stokTersedia = $produk->stok_tersedia;
            $identifierColumn = 'kd_produk';
            $kdProdukValue = $id;
            $bundlingIdValue = null;
        }

        // 2. Cek apakah barang sudah ada di keranjang user
        $itemExist = Keranjang::where('user_id', Auth::id())
                        ->when($type === 'bundling', function($q) use ($id) {
                            return $q->where('bundling_id', $id);
                        })
                        ->when($type === 'reguler', function($q) use ($id) {
                            return $q->where('kd_produk', $id);
                        })
                        ->first();

        if ($itemExist) {
            if ($itemExist->jumlah < $stokTersedia) {
                $itemExist->increment('jumlah');
            } else {
                return $this->errorResponse($request, 'Stok tidak mencukupi.');
            }
        } else {
            // 3. Buat record baru
            Keranjang::create([
                'user_id'   => Auth::id(),
                'kd_produk' => $kdProdukValue,
                'bundling_id' => $bundlingIdValue, 
                'jumlah'    => 1
            ]);
        }

        $cartCount = Keranjang::where('user_id', Auth::id())->sum('jumlah');

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'cartCount' => $cartCount]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    // Helper untuk handle error response
    private function errorResponse($request, $message) {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }
        return back()->with('error', $message);
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
     * Menghapus semua item di keranjang milik user login
     */
    public function clearAll()
    {
        Keranjang::where('user_id', Auth::id())->delete();

        return back()->with('success', 'Semua item berhasil dihapus dari keranjang.');
    }

    /**
     * Update jumlah (Opsional: Jika kamu ingin tambah tombol +/- di halaman keranjang)
     */
    public function update(Request $request, $id)
    {
        $item = Keranjang::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $action = $request->input('action');

        if ($action == 'increase') {
            $item->increment('jumlah');
        } elseif ($action == 'decrease' && $item->jumlah > 1) {
            $item->decrement('jumlah');
        }

        return back();
    }
}