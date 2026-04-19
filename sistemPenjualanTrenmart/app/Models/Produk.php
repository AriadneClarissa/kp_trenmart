<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';    
// Karena PK kamu bukan 'id' tapi 'kd_produk'
    protected $primaryKey = 'kd_produk';
    
    // Karena PK kamu string, bukan auto-increment integer
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_produk', 'nama_produk', 'deskripsi', 'satuan', 
        'harga_jual_umum', 'harga_jual_langganan', 
        'stok_tersedia', 'gambar', 'status', 'kd_kategori', 'kd_merk'
    ];
}