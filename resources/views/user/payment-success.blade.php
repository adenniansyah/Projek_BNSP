@extends('layouts.app')
@section('title', 'Pembayaran Berhasil')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="fw-bold text-success mb-3">Pembayaran Berhasil!</h2>
                <p class="text-muted mb-4">Terima kasih telah berbelanja di BookStore Arjuanda. Pesanan Anda sedang diproses.</p>
                
                <div class="alert alert-light border mb-4">
                    <p class="mb-1"><strong>Nomor Pesanan:</strong> #{{ $order->id }}</p>
                    <p class="mb-0"><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                </div>

                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary px-4">
                    <i class="fas fa-eye me-1"></i> Lihat Detail Pesanan
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary px-4 ms-2">
                    <i class="fas fa-shopping-bag me-1"></i> Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
</div>

@endsection