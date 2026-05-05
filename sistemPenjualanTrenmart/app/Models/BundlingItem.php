<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BundlingItem extends Model
{
    protected $table = 'bundling_items';

    protected $fillable = [
        'bundling_id',
        'product_id',
        'quantity',
        'price_at_snapshot' 
    ];

    // Relasi balik ke Bundling (Opsional tapi berguna)
    public function bundling()
    {
        return $this->belongsTo(Bundling::class, 'bundling_id');
    }

    // Relasi ke Produk (Penting untuk menampilkan nama barang di keranjang nanti)
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'product_id', 'kd_produk');
    }
}