@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body p-5 text-center">
                <i class="fas fa-book-reader display-1 text-primary mb-3"></i>
                <h2>Tentang BookStore Arjuanda</h2>
                <p class="lead mt-3">Kami adalah toko buku online terpercaya yang menyediakan berbagai koleksi buku berkualitas dengan harga terjangkau.</p>
                <hr>
                <div class="row mt-4 text-start">
                    <div class="col-md-6">
                        <h5><i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat</h5>
                        <p>Jl. Pendidikan No. 123, Jakarta Selatan</p>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-phone text-primary me-2"></i>Kontak</h5>
                        <p>Email: info@bookstore.com<br>WA: 0812-3456-7890</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection