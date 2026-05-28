<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
 use SoftDeletes;


protected $fillable = [
    'nama_promo',
    'diskon',
    'banner',
    'tanggal_mulai',
    'tanggal_selesai',
    'status',
    'deskripsi',
];

protected $appends = ['is_aktif'];

// Accessor: promo aktif = tanggal mulai <= hari ini <= tanggal selesai
public function getIsAktifAttribute(): bool
{
    $now = now();
    return $now->greaterThanOrEqualTo($this->tanggal_mulai)
        && $now->lessThanOrEqualTo($this->tanggal_selesai);
}

public function isExpired(): bool
{
    return now()->gt($this->tanggal_selesai);
}

public function isEndingSoon(): bool
{
    return now()->diffInDays($this->tanggal_selesai, false) <= 1;
}

public function produks()
{
    return $this->hasMany(Produk::class);
}
    
}