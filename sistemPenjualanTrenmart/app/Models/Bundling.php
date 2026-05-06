<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundling extends Model
{
    // Nama tabel di database
    protected $table = 'bundlings';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'name',
        'total_normal_price',
        'bundling_price',
    ];

    /**
     * Relasi ke BundlingItem (Isi produk di dalam paket ini)
     * Satu bundling memiliki banyak item produk.
     */
    public function items()
    {
        return $this->hasMany(BundlingItem::class, 'bundling_id', 'id');
    }

    public function hasPriceDivergence()
    {
        foreach ($this->items as $item) {
            // Cek jika harga snapshot berbeda dengan harga master produk saat ini
            if ($item->price_at_snapshot != $item->produk->harga_jual_umum) {
                return true;
            }
        }
        return false;
    }
}