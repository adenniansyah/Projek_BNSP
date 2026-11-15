@extends('layouts.app')
@section('title', 'Pesan Masuk')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pesan Masuk dari User</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Pengirim</th>
                        <th>Subjek</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr class="{{ $contact->status == 'unread' ? 'table-primary' : '' }}">
                        <td>
                            <strong>{{ $contact->name }}</strong><br>
                            <small class="text-muted">{{ $contact->email }}</small>
                        </td>
                        <td>{{ Str::limit($contact->subject, 40) }}</td>
                        <td>
                            @if($contact->status == 'unread')
                                <span class="badge bg-danger">Baru</span>
                            @elseif($contact->status == 'read')
                                <span class="badge bg-info">Dibaca</span>
                            @else
                                <span class="badge bg-success">Dibalas</span>
                            @endif
                        </td>
                        <td>{{ $contact->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-primary">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $contacts->links() }}</div>
@endsection