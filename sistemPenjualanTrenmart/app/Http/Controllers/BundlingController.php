<?php

namespace App\Http\Controllers;

use App\Models\Bundling; 
use App\Models\BundlingItem; 
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar lebih rapi

class BundlingController extends Controller
{
    public function create(Request $request)
    {
        $produk = Produk::with('merk')->get();
        
        // Ambil parameter source dari URL (misal: ?source=beranda)
        // Jika tidak ada, default-nya ke 'index'
        $source = $request->query('source', 'index');

        return view('admin.manage_bundling', compact('produk', 'source'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'bundling_price' => 'required|numeric',
            'product_id' => 'required|array|min:2', // Minimal 2 barang
            'product_id.*' => 'required|exists:produk,kd_produk',
            'promo_start_at' => 'nullable|date',
            'promo_end_at' => 'nullable|date|after_or_equal:promo_start_at',
        ]);

        DB::beginTransaction();
        try {
            $promoStartAt = $request->promo_start_at ?: now();
            $promoEndAt = $request->promo_end_at ?: null;

            // 2. Simpan ke tabel bundlings
            $bundling = Bundling::create([
                'name' => $request->name,
                'total_normal_price' => $request->total_normal_price,
                'bundling_price' => $request->bundling_price,
                'description' => $request->description,
                'promo_start_at' => $promoStartAt,
                'promo_end_at' => $promoEndAt,
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
            // Redirect back to source (beranda) if form came from there
            $source = $request->input('source');
            if ($source == 'beranda') {
                return redirect()->route('beranda')->with('success', 'Paket Bundling Berhasil Dibuat!');
            }
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

    public function searchAjax(Request $request) 
    {
        $q = $request->q;
        $merk = $request->merk;

        // Pastikan query mengambil relasi merk
        $query = Produk::with('merk');

        // Filter Nama Produk (Jika ada)
        if ($q) {
            $query->where('nama_produk', 'LIKE', "%$q%");
        }

        // Filter Merk (Jika ada) - KUNCI AGAR HANYA MERK TERTENTU YANG TAMPIL
        if ($merk) {
            $query->whereHas('merk', function($m) use ($merk) {
                $m->where('nama_merk', 'LIKE', "%$merk%");
            });
        }

        $produk = $query->take(10)->get();

        // MAPPING DATA: Ini sangat penting agar JS tidak 'undefined'
        $results = $produk->map(function($p) {
            return [
                'id'    => $p->kd_produk,
                'text'  => $p->nama_produk,
                'price' => (int) ($p->harga_jual_umum ?? 0),
                'merk'  => $p->merk->nama_merk ?? 'Tanpa Merk'
            ];
        });

        return response()->json($results);
    }

    public function show($id)
    {
        // Mengambil data bundling beserta relasi produknya
        $bundling = Bundling::with(['items.produk.merk', 'items.produk.kategori', 'items.produk.satuanModel'])->findOrFail($id);

        abort_unless($bundling->isPromoActive(), 404);

        $images = collect();
        foreach ($bundling->items as $item) {
            if ($item->produk && $item->produk->gambar) {
                $images->push($item->produk->gambar);
            }
        }
        /** @var \App\Models\Bundling $bundling */
        $stok_tersedia = $bundling->items->min(function ($item) {
            return $item->produk->stok_tersedia ?? 0;
        });

        $is_bundling = true;

        return view('produk.detail', compact('images', 'stok_tersedia', 'is_bundling'))
            ->with('produk', $bundling);
    }
}