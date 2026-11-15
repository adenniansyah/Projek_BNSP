@extends('layouts.app')
@section('title', 'Checkout Pesanan')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Checkout Pesanan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                        @csrf
                        
                        <!-- Ringkasan Belanja -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="fw-bold">Ringkasan Belanja ({{ $cartItems->count() }} item)</h6>
                            <hr>
                            {{-- PERBAIKAN: Loop menggunakan $cartItems --}}
                            @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item->book->title }} x{{ $item->quantity }}</span>
                                <span>Rp {{ number_format($item->book->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                            @endforeach
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold text-primary">
                                <span>Total Pembayaran:</span>
                                <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Data Pengiriman -->
                        <h6 class="fw-bold mb-3"><i class="fas fa-shipping-fast me-2"></i>Data Pengiriman</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold small">Alamat Lengkap Penerima</label>
                                <textarea name="shipping_address" class="form-control" rows="3" required placeholder="Masukkan alamat lengkap...">{{ old('shipping_address', auth()->user()->address) }}</textarea>
                            </div>
                        </div>

                        <!-- Metode Pembayaran -->
                        <h6 class="fw-bold mb-3"><i class="fas fa-wallet me-2"></i>Metode Pembayaran</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="pay-cod" value="cod" checked>
                                <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3" for="pay-cod">
                                    <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                    <span class="fw-bold">COD</span>
                                    <small class="text-muted">Bayar di Tempat</small>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" class="btn-check" name="payment_method" id="pay-midtrans" value="midtrans">
                                <label class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3" for="pay-midtrans">
                                    <i class="fas fa-qrcode fa-2x mb-2"></i>
                                    <span class="fw-bold">Midtrans</span>
                                    <small class="text-muted">QRIS, VA, E-Wallet</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                <i class="fas fa-check-circle me-1"></i> Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection