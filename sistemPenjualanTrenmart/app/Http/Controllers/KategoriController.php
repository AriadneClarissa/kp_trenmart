<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori; 
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
    ]);

    $nama_format = ucwords(strtolower($request->nama_kategori));
    
    $kategori = \App\Models\Kategori::create([
        'kd_kategori' => \Illuminate\Support\Str::slug($nama_format), 
        'nama_kategori' => $nama_format
    ]);

    // Respon untuk AJAX
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambah!',
            'data' => $kategori
        ]);
    }

    return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
}
}