<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 
        'title', 
        'author', 
        'price', 
        'stock', 
        'image', 
        'description'
    ];

    // Relasi ke Category
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Method untuk mendapatkan URL gambar utama
    public function getImageUrl() {
        if (!$this->image) {
            return $this->getFallbackUrl();
        }

        // Jika link eksternal (http...)
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        // Jika path lokal
        return asset('storage/' . $this->image);
    }

    // Method untuk mendapatkan URL fallback (placeholder)
    // INI METHOD YANG HILANG/TIDAK TERBACA SEBELUMNYA
    public function getFallbackUrl() {
        $colors = ['2E86AB', 'C1121F', 'FFB703', '06D6A0', '9D4EDD', 'EF233C'];
        $colorIndex = crc32($this->title) % count($colors);
        $color = $colors[$colorIndex];
        
        // Batasi panjang judul agar tidak terlalu panjang di gambar
        $text = urlencode(substr($this->title, 0, 15));
        
        return "https://placehold.co/300x450/{$color}/ffffff?text={$text}";
    }
}