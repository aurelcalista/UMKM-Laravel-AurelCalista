<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = 
    [
    'user_id',
    'nama',
    'kota',
    'menu',
    'ulasan',
    'rating'
    ];
}
