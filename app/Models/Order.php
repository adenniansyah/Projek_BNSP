<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'total_amount', 
        'status', 
        'payment_method', 
        'shipping_address',
        'snap_token',      // ✅ KOLOM BARU: Untuk menyimpan token pembayaran Midtrans
        'payment_status',  // ✅ KOLOM BARU: Status pembayaran (pending/paid/failed)
    ];

    // Relasi: Order milik User
    public function user() { 
        return $this->belongsTo(User::class); 
    }
    
    // Relasi: Order punya banyak Item
    public function items() { 
        return $this->hasMany(OrderItem::class); 
    }
}