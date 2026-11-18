@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Dashboard Admin</h2>
    <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
</div>

<div class="row g-4 mb-4">
    {{-- Total Buku --}}
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="card-title">Total Buku</h6>
                <h2 class="fw-bold">{{ $totalBooks ?? 0 }}</h2>
                <a href="{{ route('admin.books.index') }}" class="text-white-50 text-decoration-none stretched-link">
                    Klik untuk kelola buku →
                </a>
            </div>
        </div>
    </div>

    {{-- Total Kategori --}}
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="card-title">Total Kategori</h6>
                <h2 class="fw-bold">{{ $totalCategories ?? 0 }}</h2>
                <a href="{{ route('admin.categories.index') }}" class="text-white-50 text-decoration-none stretched-link">
                    Klik untuk kelola kategori →
                </a>
            </div>
        </div>
    </div>

    {{-- Total User --}}
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="card-title">Total User</h6>
                <h2 class="fw-bold">{{ $totalUsers ?? 0 }}</h2>
                @if(Route::has('admin.users.index'))
                    <a href="{{ route('admin.users.index') }}" class="text-white-50 text-decoration-none stretched-link">
                        Klik untuk lihat data user →
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Pesan Masuk --}}
    <div class="col-md-3">
        <div class="card bg-warning shadow-sm h-100 border-0">
            <div class="card-body">
                <h6 class="card-title">Pesan Masuk</h6>
                <h2 class="fw-bold">{{ $unreadMessages ?? 0 }}</h2>
                <a href="{{ route('admin.contacts.index') }}" class="text-dark text-decoration-none stretched-link">
                    Klik untuk baca pesan →
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-bolt text-warning me-2"></i>Menu Cepat
    </div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Tambah Buku Baru
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-success">
            <i class="fas fa-tag me-1"></i> Tambah Kategori
        </a>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-warning">
            <i class="fas fa-inbox me-1"></i> Cek Pesan User
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">
            <i class="fas fa-shopping-cart me-1"></i> Kelola Pesanan
        </a>
        @if(Route::has('admin.users.index'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-info text-white">
                <i class="fas fa-users me-1"></i> Data User
            </a>
        @endif
    </div>
</div>
@endsection