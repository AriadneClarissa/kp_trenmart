<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MerkController extends Controller
{
    /**
     * Menangani penyimpanan Merk baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            // Tambahkan unique agar tidak ada merk ganda
            'nama_merk' => 'required|string|max:255|unique:merk,nama_merk',
        ]);

        // 2. Format menjadi Kapital Awal Kata
        $nama_format = ucwords(strtolower($request->nama_merk));

        // 3. Simpan ke Database
        $merk = Merk::create([
            'kd_merk' => Str::slug($nama_format), 
            'nama_merk' => $nama_format,
            'is_hidden' => false // Default tampil
        ]);

        // 4. Cek Jika Permintaan via AJAX (Untuk fitur instan di modal)
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Merk ' . $nama_format . ' berhasil ditambahkan!',
                'data' => $merk
            ]);
        }

        // Redirect biasa jika bukan AJAX
        return redirect()->back()->with('success', 'Merk berhasil ditambahkan!');
    }

    /**
     * Fitur Pencarian Merk (Opsional - Jika ingin pencarian via Server-Side)
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        $merks = Merk::where('nama_merk', 'LIKE', "%$search%")->get();
        
        return response()->json($merks);
    }

    /**
     * Mengubah status visibilitas merk (Sembunyikan/Tampilkan)
     */
    public function toggleVisible($id)
    {
        // Cari merk berdasarkan Primary Key
        $merk = Merk::findOrFail($id);
        
        // Mengubah status: jika true jadi false, jika false jadi true
        $merk->is_hidden = !$merk->is_hidden; 
        $merk->save();

        return response()->json([
            'success' => true, 
            'is_hidden' => $merk->is_hidden
        ]);
    }

    /**
     * Menghapus Merk
     */
    public function destroy($id)
    {
        $merk = Merk::findOrFail($id);
        $merk->delete();

        return redirect()->back()->with('success', 'Merk berhasil dihapus!');
    }
}