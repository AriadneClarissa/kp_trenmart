<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'kd_kategori';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true; 

    protected $fillable = ['kd_kategori', 'nama_kategori', 'is_hidden'];

    public function produk()
    {
        return $this->hasMany(Produk::class, 'kd_kategori', 'kd_kategori');
    }
}