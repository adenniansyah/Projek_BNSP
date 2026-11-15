<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator; // Penting untuk pagination rapi

class BookController extends Controller
{
    // --- METHOD UNTUK USER (SUDAH ADA) ---
    public function userIndex(Request $request) {
        Paginator::useBootstrapFive(); // Agar pagination rapi
        $query = Book::with('category');
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(12);
        $categories = Category::all();
        return view('user.books.index', compact('books', 'categories'));
    }

    public function show(Book $book) {
        return view('user.books.show', compact('book'));
    }

    // --- METHOD BARU UNTUK ADMIN (TAMBAHKAN INI) ---
    
    // Menampilkan daftar buku di admin
    public function index() {
        $books = Book::with('category')->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    // Menampilkan form tambah buku
    public function create() {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    // Memproses simpan buku baru
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = $request->except('image');
        
        // Handle upload gambar jika ada
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('books', 'public');
            $data['image'] = $path;
        }

        Book::create($data);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    // Menampilkan form edit buku
    public function edit(Book $book) {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    // Memproses update buku
    public function update(Request $request, Book $book) {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($book->image && \Storage::disk('public')->exists($book->image)) {
                \Storage::disk('public')->delete($book->image);
            }
            $path = $request->file('image')->store('books', 'public');
            $data['image'] = $path;
        }

        $book->update($data);
        return redirect()->route('admin.books.index')->with('success', 'Buku berhasil diupdate!');
    }

    // Menghapus buku
    public function destroy(Book $book) {
        if ($book->image && \Storage::disk('public')->exists($book->image)) {
            \Storage::disk('public')->delete($book->image);
        }
        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus!');
    }
}