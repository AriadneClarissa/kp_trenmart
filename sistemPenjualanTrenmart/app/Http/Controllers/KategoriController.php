<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori; // Pastikan Model Kategori dipanggil
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|string|max:255',
    ]);

    // Menggunakan ucwords untuk membuat huruf pertama kapital
    $nama_format = ucwords(strtolower($request->nama_kategori));

    \App\Models\Kategori::create([
        'kd_kategori' => Str::slug($nama_format), 
        'nama_kategori' => $nama_format
    ]);

    return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
    }
}