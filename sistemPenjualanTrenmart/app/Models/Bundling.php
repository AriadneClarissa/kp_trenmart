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
        'description'
    ];

    /**
     * Relasi ke BundlingItem (Isi produk di dalam paket ini)
     * Satu bundling memiliki banyak item produk.
     */
    public function items()
    {
        return $this->hasMany(BundlingItem::class, 'bundling_id', 'id');
    }
}