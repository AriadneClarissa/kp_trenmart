<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'kd_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    // 1. LETAKKAN DI SINI (Daftar kolom di tabel produk kamu)
    protected $fillable = [
        'kd_produk', 
        'kd_kategori', 
        'kd_merk', 
        'nama_produk', 
        'harga', 
        'stok', 
        'gambar'
    ];

    // 2. DAN DI SINI (Relasi ke Kategori)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kd_kategori', 'kd_kategori');
    }

    // 3. DAN DI SINI (Relasi ke Merk)
    public function merk()
    {
        return $this->belongsTo(Merk::class, 'kd_merk', 'kd_merk');
    }
}