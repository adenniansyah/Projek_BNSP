@extends('layouts.app')
@section('title', 'Detail Pesanan #' . $order->id)
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            <!-- Header & Navigasi -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Detail Pesanan #{{ $order->id }}</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                </a>
            </div>

            <!-- Kartu Status Utama -->
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="text-muted small mb-1 text-uppercase fw-bold">Status Pesanan</p>
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-white',
                                    'shipped' => 'bg-primary text-white',
                                    'delivered' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $statusClass }} rounded-pill px-3 py-2 fs-6">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                            <p class="mt-2 mb-0 text-muted small">
                                <i class="far fa-clock me-1"></i> Dipesan pada: {{ $order->created_at->format('d M Y, H:i WIB') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            @if($order->payment_status !== 'paid' && $order->payment_method === 'midtrans')
                                <a href="{{ route('payment.page', $order) }}" class="btn btn-success w-100 fw-bold shadow-sm">
                                    <i class="fas fa-credit-card me-1"></i> Bayar Sekarang
                                </a>
                            @elseif($order->payment_status === 'paid')
                                <span class="badge bg-success rounded-pill px-3 py-2 fs-6">
                                    <i class="fas fa-check-circle me-1"></i> Lunas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Kolom Kiri: Daftar Item & Pengiriman -->
                <div class="col-lg-8">
                    
                    <!-- Daftar Item Dibeli -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-box-open me-2 text-primary"></i>Item Dibeli</h6>
                        </div>
                        <div class="card-body p-0">
                            @foreach($order->items as $item)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <!-- Placeholder Gambar Buku (Bisa diganti img book cover jika ada) -->
                                <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-book text-muted fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $item->book->title ?? 'Buku Tidak Diketahui' }}</h6>
                                    <p class="text-muted small mb-0">Rp {{ number_format($item->price, 0, ',', '.') }} x {{ $item->quantity }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-shipping-fast me-2 text-primary"></i>Informasi Pengiriman</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1 text-uppercase fw-bold">Alamat Penerima</p>
                                    <p class="mb-0">{{ $order->shipping_address ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-muted small mb-1 text-uppercase fw-bold">Metode Pembayaran</p>
                                    <p class="mb-0 text-uppercase fw-bold">{{ str_replace('_', ' ', $order->payment_method) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan: Ringkasan Pembayaran -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-bold">Ringkasan Belanja</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Harga ({{ $order->items->sum('quantity') }} item)</span>
                                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Ongkos Kirim</span>
                                <span class="text-success fw-bold">GRATIS</span>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold fs-5">Total Tagihan</span>
                                <span class="fw-bold fs-4 text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-3 text-center">
                            <small class="text-muted">ID Transaksi: ORD-{{ $order->id }}</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection