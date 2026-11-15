@extends('layouts.app')
@section('title', 'Riwayat Pesan Saya')
@section('content')

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Pesan Saya</h2>
        <!-- Tombol ini hanya mengarah ke form KIRIM PESAN BARU, bukan membalas -->
        <a href="{{ route('contact.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i> Ajukan Pertanyaan Baru
        </a>
    </div>

    @forelse($contacts as $contact)
    <div class="card shadow-sm border-0 mb-4 overflow-hidden">
        <!-- Header Kartu: Subjek & Status -->
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <div>
                <h5 class="mb-0 fw-bold">{{ $contact->subject }}</h5>
                <small class="text-muted">
                    <i class="far fa-clock me-1"></i> {{ $contact->created_at->format('d M Y, H:i WIB') }}
                </small>
            </div>
            
            <!-- Badge Status Dinamis -->
            @if($contact->status == 'unread')
                <span class="badge bg-danger rounded-pill px-3 py-2">Menunggu Respon Admin</span>
            @elseif($contact->status == 'read')
                <span class="badge bg-info rounded-pill px-3 py-2">Sedang Diproses</span>
            @elseif($contact->status == 'replied')
                <span class="badge bg-success rounded-pill px-3 py-2">Sudah Dibalas</span>
            @else
                <span class="badge bg-secondary rounded-pill px-3 py-2">Tiket Ditutup</span>
            @endif
        </div>

        <div class="card-body p-4">
            <!-- Bagian 1: Pesan Asli User -->
            <div class="mb-4 ps-3 border-start border-4 border-primary bg-light p-3 rounded-end">
                <small class="text-primary fw-bold text-uppercase mb-2 d-block">Pesan Anda:</small>
                <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $contact->message }}</p>
            </div>

            <!-- Bagian 2: Balasan Admin (Muncul jika sudah ada) -->
            @if($contact->admin_reply)
            <div class="ps-3 border-start border-4 border-success bg-success bg-opacity-10 p-3 rounded-end">
                <small class="text-success fw-bold text-uppercase mb-2 d-block">
                    <i class="fas fa-user-shield me-1"></i> Balasan Admin:
                </small>
                <p class="mb-0 text-dark" style="white-space: pre-wrap;">{{ $contact->admin_reply }}</p>
                
                <div class="mt-2 text-end">
                    <small class="text-muted fst-italic">
                        Dibalas pada: {{ $contact->updated_at->format('d M Y, H:i WIB') }}
                    </small>
                </div>
            </div>
            @else
            <!-- Jika belum dibalas -->
            <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                <i class="fas fa-hourglass-half me-2"></i>
                <div>
                    Admin belum memberikan balasan untuk pesan ini. Mohon bersabar ya!
                </div>
            </div>
            @endif
        </div>
    </div>
    @empty
    <!-- Tampilan Jika Belum Ada Pesan -->
    <div class="text-center py-5 bg-white rounded shadow-sm border">
        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Belum ada riwayat pesan</h4>
        <p class="text-muted mb-4">Anda belum pernah menghubungi admin. Silakan kirim pertanyaan baru.</p>
        <a href="{{ route('contact.create') }}" class="btn btn-outline-primary px-4">
            Kirim Pesan Pertama
        </a>
    </div>
    @endforelse

    <!-- Pagination -->
    @if($contacts->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $contacts->links() }}
    </div>
    @endif
</div>

@endsection