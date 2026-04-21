<?php
namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    // Menampilkan isi keranjang
    public function index()
    {
        $cartItems = Keranjang::with('produk')->where('user_id', Auth::id())->get();
        
        $total = 0;
        foreach ($cartItems as $item) {
            $harga = (Auth::user()->customer_type === 'langganan') 
                      ? $item->produk->harga_jual_langganan 
                      : $item->produk->harga_jual_umum;
            $total += $harga * $item->jumlah;
        }

        return view('keranjang', compact('cartItems', 'total'));
    }

    // Tambah/Update keranjang
    public function store($id)
    {
        $itemExist = Keranjang::where('user_id', Auth::id())
                              ->where('kd_produk', $id)
                              ->first();

        if ($itemExist) {
            // Jika sudah ada, tambah jumlahnya
            $itemExist->increment('jumlah');
        } else {
            // Jika belum ada, buat baru
            Keranjang::create([
                'user_id' => Auth::id(),
                'kd_produk' => $id,
                'jumlah' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Hapus item
    public function destroy($id)
    {
        Keranjang::where('id', $id)->where('user_id', Auth::id())->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }
}