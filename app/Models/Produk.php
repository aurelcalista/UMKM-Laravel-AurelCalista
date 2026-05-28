<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'kategori',
        'deskripsi',
        'poto',

        'waktu_masak',
        'level_pedas',
        'bahan_utama',
        'porsi',

        'modal',
        'diskon',

        'promo_id',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    
    public function getHargaFinalAttribute()
    {
        if (
            $this->promo_id &&
            $this->promo &&
            $this->promo->status &&
            !$this->promo->isExpired()
        ) {

            return $this->harga -
                ($this->harga * $this->promo->diskon / 100);
        }

        return $this->harga;
    }

    public function getProfitAttribute()
    {
        return $this->harga_final - ($this->modal ?? 0);
    }
}