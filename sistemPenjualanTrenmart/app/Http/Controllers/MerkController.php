<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function index() {
        $merk = Merk::all();
        return view('admin.kelola_merk', compact('merk'));
    }

    public function store(Request $request) {
        $request->validate([
            'kd_merk' => 'required|unique:merk,kd_merk',
            'nama_merk' => 'required'
        ]);

        Merk::create([
            'kd_merk' => $request->kd_merk,
            'nama_merk' => $request->nama_merk
        ]);

        return back()->with('success', 'Merk baru berhasil ditambahkan!');
    }

    public function destroy($id) {
        // Menggunakan where karena primary key adalah string
        Merk::where('kd_merk', $id)->delete();
        return back()->with('success', 'Merk berhasil dihapus!');
    }
}