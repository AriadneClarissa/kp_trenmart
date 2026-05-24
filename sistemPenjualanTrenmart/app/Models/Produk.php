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
        'kd_satuan',
        'satuan',
        'nama_produk', 
        'deskripsi',           
        'harga_jual_umum',     
        'harga_jual_langganan', 
        'stok_tersedia',     
        'stok_minimal',
        'status',
        'gambar', // foto utama
        'foto_2', // foto tambahan 1
        'foto_3', // foto tambahan 2
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

    // Relasi ke Satuan
    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'kd_satuan', 'kd_satuan');
    }
}