<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table = 'merk';
    protected $primaryKey = 'kd_merk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; 

    protected $fillable = ['kd_merk', 'nama_merk', 'is_hidden'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kd_merk', 'kd_merk');
    }
}