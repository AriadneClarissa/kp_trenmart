<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $table = 'satuan';
    protected $primaryKey = 'kd_satuan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; 

    protected $fillable = ['kd_satuan', 'nama_satuan', 'stok_minimal', 'is_hidden'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kd_satuan', 'kd_satuan');
    }
}
