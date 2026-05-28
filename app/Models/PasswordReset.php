<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'email',
        'token',
        'approved',
        'temp_password',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'approved' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public function isExpired()
    {
        return $this->expires_at && now()->gt($this->expires_at);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }
}