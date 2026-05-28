<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksis';

    protected $fillable = [
        'user_id',
        'total_harga',
        'tanggal',
        'status',
        'approval_status',
        'metode_bayar',
        'metode_kirim',
        'alamat',
        'catatan',
        'bukti_bayar',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'approved_at' => 'datetime',  
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    // Helper methods
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function isCompleted()
    {
        return $this->approval_status === 'completed';
    }
}