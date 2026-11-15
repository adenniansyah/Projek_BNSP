@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Kelola Kategori</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Tambah Kategori</a>
</div>

<table class="table table-hover bg-white shadow-sm rounded">
    <thead class="table-dark"><tr><th>Nama</th><th>Deskripsi</th><th>Jumlah Buku</th><th>Aksi</th></tr></thead>
    <tbody>
        @forelse($categories as $cat)
        <tr>
            <td>{{ $cat->name }}</td>
            <td>{{ Str::limit($cat->description, 50) }}</td>
            <td><span class="badge bg-info">{{ $cat->books_count }}</span></td>
            <td>
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center">Belum ada kategori.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection