<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    // Memberitahu Laravel bahwa nama tabelnya adalah 'keranjang'
    protected $table = 'keranjang';
    
    protected $fillable = ['user_id', 'kd_produk', 'jumlah', 'bundling_id', 'harga_at_time'];

    // Relasi agar bisa mengambil data produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }

    // Relasi untuk bundling (paket produk)
    public function bundling()
    {
        return $this->belongsTo(Bundling::class, 'bundling_id', 'id');
    }
    
}