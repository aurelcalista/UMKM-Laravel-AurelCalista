<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'title',
        'message',
        'type',
        'link',
        'is_read',
    ];
}