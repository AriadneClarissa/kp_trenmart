<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    // Memberitahu Laravel bahwa nama tabelnya adalah 'keranjang'
    protected $table = 'keranjang';
    
    protected $fillable = ['user_id', 'kd_produk', 'jumlah'];

    // Relasi agar bisa mengambil data produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'kd_produk', 'kd_produk');
    }
}