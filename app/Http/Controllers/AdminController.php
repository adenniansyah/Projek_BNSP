<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Fungsi untuk menampilkan Dashboard Admin
    public function dashboard() {
        $data = [
            'totalBooks' => Book::count(),
            'totalCategories' => Category::count(),
            'totalUsers' => User::where('role', 'user')->count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
        ];
        
        // Mengirim data ke view admin.dashboard
        return view('admin.dashboard', $data);
    }

    // Fungsi untuk menampilkan daftar User (Opsional, jika tombol "Lihat Data User" diklik)
    public function users() {
        $users = User::where('role', 'user')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
}