<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang
     */
    public function index() {
        // Ambil item keranjang milik user yang sedang login beserta detail bukunya
        $cartItems = CartItem::where('user_id', Auth::id())
                             ->with('book')
                             ->get();
        
        // Hitung total harga
        $total = $cartItems->sum(function($item) {
            return $item->book->price * $item->quantity;
        });

        // Kirim ke view. Note: View kita nanti akan loop $cartItems
        return view('user.cart.index', compact('cartItems', 'total'));
    }

    /**
     * Menambah buku ke keranjang
     */
    public function add(Request $request, Book $book) {
        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($book->stock < 1) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // Cek apakah buku sudah ada di keranjang user ini
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('book_id', $book->id)
                            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambahkan quantity
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $book->stock) {
                return back()->with('error', 'Stok tidak mencukupi untuk jumlah tersebut.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            // Jika belum ada, buat baru
            if ($request->quantity > $book->stock) {
                return back()->with('error', 'Stok tidak mencukupi.');
            }
            CartItem::create([
                'user_id' => Auth::id(),
                'book_id' => $book->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity item di keranjang
     */
    public function update(Request $request, $id) {
        // Cari item berdasarkan ID cart_item (bukan book_id)
        $cartItem = CartItem::findOrFail($id);
        
        // Keamanan: Pastikan item milik user yang login
        if ($cartItem->user_id !== Auth::id()) abort(403);

        $request->validate(['quantity' => 'required|integer|min:1']);

        if ($request->quantity > $cartItem->book->stock) {
            return back()->with('error', 'Stok tidak cukup! Maksimal: ' . $cartItem->book->stock);
        }
        
        $cartItem->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah item diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function remove($id) {
        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->user_id !== Auth::id()) abort(403);
        
        $cartItem->delete();
        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Kosongkan seluruh keranjang
     */
    public function clear() {
        CartItem::where('user_id', Auth::id())->delete();
        return back()->with('success', 'Keranjang belanja dikosongkan.');
    }
}