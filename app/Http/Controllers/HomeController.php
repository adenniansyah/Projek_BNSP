<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class HomeController extends Controller
{
    // Halaman Utama
    public function index() {
        // Ambil 8 buku terbaru yang stoknya ada
        $featuredBooks = Book::with('category')
                            ->where('stock', '>', 0)
                            ->latest()
                            ->take(8)
                            ->get();
        
        // Ambil semua kategori untuk ditampilkan di home
        $categories = Category::withCount('books')->get();

        return view('user.home', compact('featuredBooks', 'categories'));
    }

    // Halaman About Us
    public function about() {
        return view('user.about');
    }
}