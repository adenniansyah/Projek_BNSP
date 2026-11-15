@extends('layouts.app')
@section('title', 'Koleksi Buku')
@section('content')

<!-- Header Halaman -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Koleksi Buku</h2>
</div>

<!-- Form Pencarian & Filter (Layout Lebih Rapi) -->
<div class="card shadow-sm border-0 mb-4 p-3">
    <form method="GET" action="{{ route('books.index') }}" class="row g-2 align-items-center">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control form-control-lg" 
                   placeholder="Cari judul atau penulis..." value="{{ request('search') }}">
        </div>
        <div class="col-md-5">
            <select name="category" class="form-select form-select-lg">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-search me-1"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Grid Daftar Buku -->
<div class="row g-4">
    @forelse($books as $book)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm border-0 hover-shadow transition-all">
                <!-- Gambar dengan Fallback Otomatis -->
                <img src="{{ $book->getImageUrl() }}" 
                     class="card-img-top" 
                     alt="{{ $book->title }}" 
                     style="height: 280px; object-fit: cover;"
                     onerror="this.onerror=null; this.src='{{ $book->getFallbackUrl() }}';">
                
                <div class="card-body d-flex flex-column p-3">
                    <span class="badge bg-secondary mb-2 align-self-start">{{ $book->category->name }}</span>
                    <h6 class="card-title fw-bold mb-1" style="min-height: 40px;">
                        {{ Str::limit($book->title, 35) }}
                    </h6>
                    <p class="text-muted small mb-2">{{ $book->author }}</p>
                    <p class="fw-bold text-primary mt-auto fs-6 mb-2">
                        Rp {{ number_format($book->price, 0, ',', '.') }}
                    </p>
                    <a href="{{ route('books.show', $book) }}" class="btn btn-outline-primary btn-sm w-100 mt-1">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="alert alert-light border shadow-sm d-inline-block px-5 py-4">
                <i class="fas fa-book-open display-4 text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Tidak ada buku yang ditemukan.</h5>
                <p class="small text-muted">Coba gunakan kata kunci lain atau reset filter.</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination Rapi (Hanya Wrapper, Logic ada di Controller) -->
<div class="mt-5 mb-4 d-flex justify-content-center">
    {{ $books->links() }}
</div>

@endsection