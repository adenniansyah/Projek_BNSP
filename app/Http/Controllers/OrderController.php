<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\CartItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Daftar riwayat pesanan User
     */
    public function index() {
        $orders = Order::where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Detail pesanan User
     */
    public function show(Order $order) {
        if ($order->user_id !== Auth::id()) abort(403, 'Akses ditolak.');
        $order->load('items.book');
        return view('user.orders.show', compact('order'));
    }

    /**
     * Halaman Checkout
     */
    public function showCheckout() {
        $cartItems = CartItem::where('user_id', Auth::id())->with('book')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }
        $totalAmount = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);
        return view('user.checkout', compact('cartItems', 'totalAmount'));
    }

    /**
     * Proses Checkout (COD & Midtrans)
     */
    public function processCheckout(Request $request) {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method'   => 'required|in:cod,midtrans',
        ]);

        $user = Auth::user();
        $cartItems = CartItem::where('user_id', $user->id)->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong!');
        }

        $totalAmount = $cartItems->sum(fn($item) => $item->book->price * $item->quantity);

        // Buat Order
        $order = Order::create([
            'user_id'          => $user->id,
            'total_amount'     => $totalAmount,
            'status'           => 'pending',
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'payment_status'   => 'pending',
        ]);

        // Simpan Items & Kurangi Stok
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id'  => $item->book_id,
                'quantity' => $item->quantity,
                'price'    => $item->book->price,
            ]);
            $item->book->decrement('stock', $item->quantity);
        }

        // Kosongkan Cart
        CartItem::where('user_id', $user->id)->delete();

        // COD
        if ($request->payment_method === 'cod') {
            $order->update(['status' => 'processing', 'payment_status' => 'paid']);
            return redirect()->route('orders.show', $order)
                             ->with('success', 'Pesanan COD berhasil dibuat!');
        }

        // Midtrans
        try {
            $customerDetails = [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone ?? '08123456789',
            ];

            $snapToken = MidtransService::createTransaction(
                'ORDER-' . $order->id,
                $totalAmount,
                $customerDetails
            );

            $order->update(['snap_token' => $snapToken]);
            return redirect()->route('payment.page', $order);

        } catch (\Exception $e) {
            return redirect()->route('orders.show', $order)
                             ->with('error', 'Gagal generate pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Pembayaran Midtrans (Auto-generate token jika null)
     */
    public function showPaymentPage(Order $order) {
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order);
        }

        // Auto-generate jika token kosong
        if (empty($order->snap_token)) {
            try {
                $customerDetails = [
                    'first_name' => Auth::user()->name,
                    'email'      => Auth::user()->email,
                    'phone'      => Auth::user()->phone ?? '08123456789',
                ];
                $snapToken = MidtransService::createTransaction(
                    'ORDER-' . $order->id,
                    $order->total_amount,
                    $customerDetails
                );
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return view('user.payment', compact('order'))
                       ->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
            }
        }

        return view('user.payment', compact('order'));
    }

    /**
     * Webhook Midtrans (otomatis set Lunas)
     */
    public function handleMidtransNotification(Request $request) {
        try {
            MidtransService::configure();

            $notif = new \Midtrans\Notification();
            $transactionStatus = $notif->transaction_status;
            $orderId = str_replace('ORDER-', '', $notif->order_id);
            $order = Order::find($orderId);

            if (!$order) return response()->json(['status' => 'error'], 404);

            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                // Jangan timpa status kalau sudah lunas (admin mungkin sudah ubah ke shipped, dll)
                if ($order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                }
            } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
                if ($order->payment_status !== 'paid') {
                    $order->update(['payment_status' => 'failed', 'status' => 'cancelled']);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Halaman Sukses Pembayaran
     * Cadangan kalau webhook tidak sampai: cek status langsung ke Midtrans
     */
    public function paymentSuccess(Order $order) {
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->payment_status !== 'paid') {
            try {
                MidtransService::configure();
                $status = \Midtrans\Transaction::status('ORDER-' . $order->id);
                $ts = $status->transaction_status ?? null;

                if (in_array($ts, ['settlement', 'capture'])) {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                }
            } catch (\Exception $e) {
                Log::warning('Cek status Midtrans gagal: ' . $e->getMessage());
            }
        }

        return view('user.payment-success', compact('order'));
    }

    /**
     * Daftar Pesanan Admin (dengan filter pembayaran)
     */
    public function adminIndex(Request $request) {
        $orders = Order::with('user')
            ->when($request->payment, fn($q, $p) => $q->where('payment_status', $p))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update Status Pengiriman (Admin)
     */
    public function updateStatus(Request $request, Order $order) {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Verifikasi Pembayaran Manual (Admin)
     */
    public function verifyPayment(Order $order) {
        return view('admin.orders.verify', compact('order'));
    }

    /**
     * Update Status Pembayaran Manual (Admin)
     */
    public function updatePaymentStatus(Request $request, Order $order) {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed'
        ]);
        $order->update(['payment_status' => $request->payment_status]);
        if ($request->payment_status === 'paid') {
            $order->update(['status' => 'processing']);
        }
        return back()->with('success', 'Status pembayaran berhasil diperbarui!');
    }
}