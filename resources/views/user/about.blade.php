@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')

@php
    // Ubah data ini sesuai toko kamu
    $alamat = 'Jl. Pendidikan No. 123, Jakarta Selatan';
    $email  = 'info@bookstore.com';
    $wa     = '0812-3456-7890';
    $waLink = '62' . ltrim(preg_replace('/\D/', '', $wa), '0');
@endphp

<div class="row justify-content-center">
    <div class="col-lg-9">

        {{-- Header --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-5 text-center">
                <i class="fas fa-book-reader display-1 text-primary mb-3"></i>
                <h2 class="fw-bold">Tentang BookStore Arjuanda</h2>
                <p class="lead text-muted mt-3 mb-0 mx-auto" style="max-width: 700px;">
                    Kami adalah toko buku online terpercaya yang menyediakan berbagai
                    koleksi buku berkualitas dengan harga terjangkau.
                </p>
            </div>
        </div>

        {{-- Alamat & Kontak --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>Alamat
                        </h5>
                        <p class="text-muted mb-0">{{ $alamat }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="fas fa-phone text-primary me-2"></i>Kontak
                        </h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <a href="mailto:{{ $email }}" class="text-decoration-none text-muted">{{ $email }}</a>
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fab fa-whatsapp text-success me-2"></i>
                            <a href="https://wa.me/{{ $waLink }}" target="_blank" rel="noopener" class="text-decoration-none text-muted">{{ $wa }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Google Maps --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-location-dot text-primary me-2"></i>Lokasi Kami
                    </h5>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($alamat) }}"
                       target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                        Buka di Google Maps
                    </a>
                </div>
                <div class="ratio ratio-16x9 rounded overflow-hidden border">
                    <iframe
                        src="https://www.google.com/maps?q={{ urlencode($alamat) }}&output=embed"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi BookStore Arjuanda"></iframe>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection