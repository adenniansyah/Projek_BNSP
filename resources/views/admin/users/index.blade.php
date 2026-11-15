@extends('layouts.app')
@section('title', 'Data Pengguna')
@section('content')
<h2 class="mb-4">Data Pengguna Terdaftar</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Alamat</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td>{{ Str::limit($user->address, 30) ?? '-' }}</td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Belum ada user terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection