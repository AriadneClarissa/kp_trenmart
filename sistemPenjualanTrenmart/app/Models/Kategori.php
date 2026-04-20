<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kd_kategori'; // Sesuai atribut kamu
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kd_kategori', 'nama_kategori'];

    // Relasi: Satu kategori punya banyak produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kd_kategori', 'kd_kategori');
    }
}