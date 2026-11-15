<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'book_id', 
        'quantity', 
        'price'
    ];

    // Relasi: Item milik Order
    public function order() { 
        return $this->belongsTo(Order::class); 
    }

    // Relasi: Item merujuk ke Buku
    public function book() { 
        return $this->belongsTo(Book::class); 
    }
}