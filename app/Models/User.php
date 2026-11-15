<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'role',
        'avatar',
    ];

    // Kolom yang disembunyikan (password)
    protected $hidden = [
        'password',
    ];

    // Relasi: User punya banyak CartItem
    public function cartItems() {
        return $this->hasMany(CartItem::class);
    }

    // Relasi: User punya banyak Order
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Relasi: User punya banyak Contact
    public function contacts() {
        return $this->hasMany(Contact::class);
    }

    // Helper: Cek apakah Admin
    public function isAdmin() {
        return $this->role === 'admin';
    }
}