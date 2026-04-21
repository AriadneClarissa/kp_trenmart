<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index() {
        $kategori = Kategori::all();
        return view('admin.kelola_kategori', compact('kategori'));
    }

    public function store(Request $request) {
        $request->validate([
            'kd_kategori' => 'required|unique:kategori,kd_kategori',
            'nama_kategori' => 'required'
        ]);

        Kategori::create([
            'kd_kategori' => $request->kd_kategori,
            'nama_kategori' => $request->nama_kategori
        ]);

        return back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy($id) {
        Kategori::where('kd_kategori', $id)->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}