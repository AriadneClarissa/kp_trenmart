<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bundling extends Model
{
    // Nama tabel di database
    protected $table = 'bundlings';

    protected $casts = [
        'promo_start_at' => 'datetime',
        'promo_end_at' => 'datetime',
    ];

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'name',
        'total_normal_price',
        'bundling_price',
        'description',
        'promo_start_at',
        'promo_end_at',
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

    public function scopeActivePromo($query)
    {
        $now = now();

        return $query
            ->where(function ($q) use ($now) {
                $q->whereNull('promo_start_at')
                  ->orWhere('promo_start_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('promo_end_at')
                  ->orWhere('promo_end_at', '>=', $now);
            });
    }

    public function isPromoActive(): bool
    {
        $now = now();

        if ($this->promo_start_at && $this->promo_start_at->gt($now)) {
            return false;
        }

        if ($this->promo_end_at && $this->promo_end_at->lt($now)) {
            return false;
        }

        return true;
    }
}