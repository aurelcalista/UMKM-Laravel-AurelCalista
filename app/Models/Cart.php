<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'produk_id',
        'qty'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}