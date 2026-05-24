<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SatuanController extends Controller
{
    /**
     * Menangani penyimpanan Satuan baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'nama_satuan' => 'required|string|max:255|unique:satuan,nama_satuan',
            'stok_minimal' => 'required|integer|min:0',
        ]);

        // 2. Format menjadi Kapital Awal Kata
        $nama_format = ucwords(strtolower($request->nama_satuan));

        // 3. Simpan ke Database
        $satuan = Satuan::create([
            'kd_satuan' => Str::slug($nama_format), 
            'nama_satuan' => $nama_format,
            'stok_minimal' => (int) $request->stok_minimal,
            'is_hidden' => false // Default tampil
        ]);

        // 4. Cek Jika Permintaan via AJAX (Untuk fitur instan di modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Satuan ' . $nama_format . ' berhasil ditambahkan!',
                'data' => $satuan
            ]);
        }

        // Redirect biasa jika bukan AJAX
        return redirect()->back()->with('success', 'Satuan berhasil ditambahkan!');
    }

    /**
     * Fitur Pencarian Satuan
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $satuan = Satuan::where('nama_satuan', 'LIKE', "%$search%")
            ->orderBy('nama_satuan')
            ->get();

        return response()->json($satuan);
    }

    /**
     * Mengubah status visibilitas satuan (Sembunyikan/Tampilkan)
     */
    public function toggleVisible($id)
    {
        // Cari satuan berdasarkan Primary Key
        $satuan = Satuan::findOrFail($id);

        // Mengubah status: jika true jadi false, jika false jadi true
        $satuan->is_hidden = !$satuan->is_hidden;
        $satuan->save();

        return response()->json([
            'success' => true,
            'is_hidden' => $satuan->is_hidden
        ]);
    }

    /**
     * Menghapus Satuan
     */
    public function destroy($id)
    {
        $satuan = Satuan::findOrFail($id);
        $satuan->delete();

        return redirect()->back()->with('success', 'Satuan berhasil dihapus!');
    }
    public function toggleHidden(Request $request)
    {
        // Contoh untuk Kategori, ulangi untuk Merk & Satuan
        $data = Satuan::where('kd_satuan', $request->id)->firstOrFail();
        $data->is_hidden = !$data->is_hidden; // Membalik nilai (true jadi false, dst)
        $data->save();

        return response()->json(['success' => true, 'is_hidden' => $data->is_hidden]);
    }
}
