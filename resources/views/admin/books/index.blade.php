@extends('layouts.app')
@section('title', 'Kelola Buku')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Buku</h2>
    <a href="{{ route('admin.books.create') }}" class="btn btn-primary">+ Tambah Buku</a>
</div>

<table class="table table-hover bg-white shadow-sm rounded">
    <thead class="table-dark"><tr><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
    <tbody>
        @forelse($books as $book)
        <tr>
            <td>{{ Str::limit($book->title, 30) }}</td>
            <td>{{ $book->author }}</td>
            <td><span class="badge bg-secondary">{{ $book->category->name }}</span></td>
            <td>Rp {{ number_format($book->price, 0, ',', '.') }}</td>
            <td>{{ $book->stock }}</td>
            <td>
                <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">Belum ada buku.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection