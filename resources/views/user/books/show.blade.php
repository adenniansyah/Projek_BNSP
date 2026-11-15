@extends('layouts.app')
@section('title', $book->title)
@section('content')
<div class="row g-4">
    <!-- Kolom Gambar -->
    <div class="col-md-5 text-center">
        <div class="card border-0 shadow-sm p-2 d-inline-block bg-white">
            <!-- PERBAIKAN DI SINI: Menambahkan onerror untuk fallback otomatis -->
            <img src="{{ $book->getImageUrl() }}" 
                 class="img-fluid rounded" 
                 alt="{{ $book->title }}"
                 style="max-height: 500px; width: auto;"
                 onerror="this.onerror=null; this.src='{{ $book->getFallbackUrl() }}';">
        </div>
    </div>

    <!-- Kolom Detail Buku -->
    <div class="col-md-7">
        <span class="badge bg-primary mb-3 fs-6">{{ $book->category->name }}</span>
        
        <h1 class="fw-bold mb-2">{{ $book->title }}</h1>
        <p class="text-muted fs-5"><i class="fas fa-user-edit me-2"></i>{{ $book->author }}</p>
        
        <hr class="my-4">
        
        <h2 class="text-primary fw-bold mb-3">Rp {{ number_format($book->price, 0, ',', '.') }}</h2>
        
        <div class="d-flex align-items-center mb-4">
            @if($book->stock > 0)
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i> Stok Tersedia: {{ $book->stock }}
                </span>
            @else
                <span class="badge bg-danger fs-6 px-3 py-2">
                    <i class="fas fa-times-circle me-1"></i> Stok Habis
                </span>
            @endif
        </div>

        <div class="bg-light p-3 rounded mb-4">
            <h5 class="mb-2">Deskripsi:</h5>
            <p class="mb-0 text-secondary">{{ $book->description ?? 'Tidak ada deskripsi.' }}</p>
        </div>
        
        <!-- Form Tambah ke Keranjang / Login -->
        @auth
            @if(auth()->user()->role === 'user')
                @if($book->stock > 0)
                    <form action="{{ route('cart.add', $book) }}" method="POST">
                        @csrf
                        <div class="input-group" style="max-width: 350px;">
                            <span class="input-group-text bg-white">Jumlah</span>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $book->stock }}">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-cart-plus me-1"></i> Tambah ke Keranjang
                            </button>
                        </div>
                    </form>
                @else
                    <button class="btn btn-secondary disabled" style="max-width: 350px;">
                        <i class="fas fa-ban me-1"></i> Stok Habis
                    </button>
                @endif
            @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle me-2"></i> Akun Admin tidak dapat melakukan pembelian.
                </div>
            @endif
        @else
            <a href="{{ route('login') }}" class="btn btn-warning btn-lg mt-2">
                <i class="fas fa-sign-in-alt me-2"></i> Login untuk Membeli
            </a>
        @endauth
    </div>
</div>
@endsection