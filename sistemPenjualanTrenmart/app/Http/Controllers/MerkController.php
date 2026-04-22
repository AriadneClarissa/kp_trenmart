<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Merk; 
use Illuminate\Support\Str; 

class MerkController extends Controller
{
    public function store(Request $request)
    {
    $request->validate([
        'nama_merk' => 'required|string|max:255',
    ]);

    // Format menjadi Kapital Awal Kata
    $nama_format = ucwords(strtolower($request->nama_merk));

    \App\Models\Merk::create([
        'kd_merk' => Str::slug($nama_format), 
        'nama_merk' => $nama_format
    ]);

    return redirect()->back()->with('success', 'Merk berhasil ditambahkan!');
    }  

    public function toggleVisible($id)
    {
    $merk = Merk::findOrFail($id);
    // Mengubah status: jika 0 jadi 1, jika 1 jadi 0
    $merk->is_hidden = !$merk->is_hidden; 
    $merk->save();

    return response()->json(['success' => true]);
    }
}