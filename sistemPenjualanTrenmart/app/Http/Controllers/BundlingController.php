<?php

namespace App\Http\Controllers;

use App\Models\Bundling; 
use App\Models\BundlingItem; 
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar lebih rapi

class BundlingController extends Controller
{
    public function create()
    {
        $produk = Produk::all();
        // Pastikan view mengarah ke path yang benar di folder admin
        return view('admin.manage_bundling', compact('produk'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'bundling_price' => 'required|numeric',
            'product_id' => 'required|array|min:2', // Minimal 2 barang
            'product_id.*' => 'required|exists:produk,kd_produk',
        ]);

        DB::beginTransaction();
        try {
            // 2. Simpan ke tabel bundlings
            $bundling = Bundling::create([
                'name' => $request->name,
                'total_normal_price' => $request->total_normal_price,
                'bundling_price' => $request->bundling_price,
                'description' => $request->description,
            ]);

            // 3. Simpan item ke tabel bundling_items
            foreach ($request->product_id as $pid) {
                $produk = Produk::where('kd_produk', $pid)->first();
                
                if ($produk) {
                    BundlingItem::create([
                        'bundling_id' => $bundling->id,
                        'product_id' => $pid,
                        'quantity' => 1,
                        'price_at_snapshot' => $produk->harga_jual_umum,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Paket Bundling Berhasil Dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // API untuk ambil harga produk (AJAX)
    public function getProductPrice($id)
    {
        $produk = Produk::where('kd_produk', $id)->first();
        return response()->json(['price' => $produk ? $produk->harga_jual_umum : 0]);
    }
}