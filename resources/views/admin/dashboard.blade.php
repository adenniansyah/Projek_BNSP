@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">Dashboard Admin</h2>
    <span class="text-muted">Selamat datang, {{ auth()->user()->name }}!</span>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">Total Buku</h6>
                <h2 class="fw-bold">{{ $totalBooks ?? 0 }}</h2>
                <a href="{{ route('admin.books.index') }}" class="text-white-50 text-decoration-none">Klik untuk kelola buku →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">Total Kategori</h6>
                <h2 class="fw-bold">{{ $totalCategories ?? 0 }}</h2>
                <a href="{{ route('admin.categories.index') }}" class="text-white-50 text-decoration-none">Klik untuk kelola kategori →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">Total User</h6>
                <h2 class="fw-bold">{{ $totalUsers ?? 0 }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning shadow-sm h-100">
            <div class="card-body">
                <h6 class="card-title">Pesan Masuk</h6>
                <h2 class="fw-bold">{{ $unreadMessages ?? 0 }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold">Menu Cepat</div>
    <div class="card-body d-flex flex-wrap gap-2">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Tambah Buku Baru</a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-success">Tambah Kategori</a>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-warning">Cek Pesan User</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Kelola Pesanan</a>
    </div>
</div>
@endsection