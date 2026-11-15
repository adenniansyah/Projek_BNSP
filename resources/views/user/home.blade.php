@extends('layouts.app')
@section('title', 'Home')
@section('content')
<div class="p-5 mb-4 bg-light rounded-3 text-center shadow-sm">
    <h1 class="display-4 fw-bold">Selamat Datang di BookStore Arjuanda</h1>
    <p class="lead">Temukan buku favoritmu dengan harga terbaik.</p>
    <a href="{{ route('books.index') }}" class="btn btn-primary btn-lg px-4">Lihat Koleksi Buku</a>
</div>

<h3 class="mb-4 border-bottom pb-2">Buku Unggulan</h3>
<div class="row">
    @forelse($featuredBooks as $book)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- PERBAIKAN DI SINI: Menambahkan onerror untuk fallback otomatis -->
                <img src="{{ $book->getImageUrl() }}" 
                     class="card-img-top" 
                     alt="{{ $book->title }}" 
                     style="height: 250px; object-fit: cover;"
                     onerror="this.onerror=null; this.src='{{ $book->getFallbackUrl() }}';">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ Str::limit($book->title, 30) }}</h5>
                    <p class="text-muted small mb-1">{{ $book->author }}</p>
                    <p class="fw-bold text-primary mt-auto fs-5">Rp {{ number_format($book->price, 0, ',', '.') }}</p>
                    <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="alert alert-info">Belum ada buku unggulan tersedia.</div>
        </div>
    @endforelse
</div>
@endsection