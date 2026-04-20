<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'kd_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kd_kategori', 'kd_kategori');
    }

    public function merk()
    {
        return $this->belongsTo(Merk::class, 'kd_merk', 'kd_merk');
    }
}