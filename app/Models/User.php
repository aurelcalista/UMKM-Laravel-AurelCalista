<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Kolom yang boleh diisi mass assignment
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'hp',
        'alamat',
        'foto',      
        'role',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast tipe data
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Helper: cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: URL foto profil (dengan fallback)
     */
    public function photoUrl(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        // Fallback: UI Avatars otomatis pakai inisial nama
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=C0392B&color=fff&size=128';
    }

     public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'user_id');
    }

}