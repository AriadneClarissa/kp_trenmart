<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Merk extends Model
{
    protected $table = 'merk';
    protected $primaryKey = 'kd_merk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kd_merk', 'nama_merk'];

    // Relasi: Satu merk punya banyak produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kd_merk', 'kd_merk');
    }
}