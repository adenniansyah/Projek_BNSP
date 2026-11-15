@extends('layouts.app')
@section('title', 'Keranjang Belanja')
@section('content')

<div class="container py-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-shopping-cart me-2 text-primary"></i>Keranjang Belanja</h2>

    @if($cartItems->isEmpty())
        <!-- Tampilan Jika Keranjang Kosong -->
        <div class="alert alert-info text-center py-5 shadow-sm border-0 rounded">
            <i class="fas fa-shopping-basket fa-3x mb-3 text-muted"></i>
            <h4 class="fw-bold">Keranjang Anda masih kosong</h4>
            <p class="text-muted mb-4">Yuk, temukan buku favoritmu sekarang!</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary px-4 fw-bold">
                <i class="fas fa-book-open me-2"></i>Mulai Belanja
            </a>
        </div>
    @else
        <div class="row g-4">
            <!-- Kolom Kiri: Daftar Item -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center p-4 border-bottom">
                                <!-- ✅ PERBAIKAN: Menggunakan $item->book->image -->
                                <div class="me-4">
                                    @if($item->book->image)
                                        <img src="{{ asset('storage/' . $item->book->image) }}" 
                                             alt="{{ $item->book->title }}" 
                                             class="rounded shadow-sm" 
                                             style="width: 100px; height: 140px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm" 
                                             style="width: 100px; height: 140px;">
                                            <i class="fas fa-book fa-2x text-muted"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Info Buku -->
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1">{{ $item->book->title }}</h5>
                                    <p class="text-primary fw-bold fs-5 mb-2">Rp {{ number_format($item->book->price, 0, ',', '.') }}</p>
                                    
                                    @if($item->book->stock > 0)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                            <i class="fas fa-check-circle me-1"></i>Stok: {{ $item->book->stock }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger">
                                            <i class="fas fa-times-circle me-1"></i>Stok Habis
                                        </span>
                                    @endif
                                </div>

                                <!-- Aksi: Quantity & Hapus -->
                                <div class="text-end ms-3" style="min-width: 150px;">
                                    <!-- Form Update Quantity -->
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="mb-2">
                                        @csrf
                                        @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="quantity" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->book->stock }}" 
                                                   class="form-control text-center" 
                                                   {{ $item->book->stock < 1 ? 'disabled' : '' }}>
                                            <button type="submit" class="btn btn-outline-secondary" 
                                                    {{ $item->book->stock < 1 ? 'disabled' : '' }}>
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                    
                                    <!-- Form Hapus Item -->
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan & Tombol Checkout -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 10;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Total Harga ({{ $cartItems->sum('quantity') }} item)</span>
                            <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="my-3">

                        <!-- ✅ TOMBOL CHECKOUT -->
                        <a href="{{ route('checkout.show') }}" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                            <i class="fas fa-wallet me-2"></i> Lanjut ke Checkout
                        </a>
                        
                        <small class="text-muted d-block text-center mt-3">
                            <i class="fas fa-shield-alt me-1"></i> Pembayaran Aman via Midtrans
                        </small>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection