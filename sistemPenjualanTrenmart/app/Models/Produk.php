<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'kd_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_produk', 
        'kd_kategori', 
        'kd_merk', 
        'nama_produk', 
        'deskripsi',           
        'harga_jual_umum',     
        'harga_jual_langganan', 
        'stok_tersedia',     
        'satuan',
        'status',
        'gambar',
        'is_highlight',
        'is_custom_section'
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kd_kategori', 'kd_kategori');
    }

    // Relasi ke Merk
    public function merk()
    {
        return $this->belongsTo(Merk::class, 'kd_merk', 'kd_merk');
    }
}