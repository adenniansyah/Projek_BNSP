@extends('layouts.app')
@section('title', 'Detail Pesan')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <!-- Header Kartu -->
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-envelope-open-text me-2 text-primary"></i>Detail Pesan</h5>
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="card-body p-4">
                <!-- Informasi Pengirim -->
                <div class="mb-4">
                    <label class="fw-bold text-muted small text-uppercase">Dari:</label>
                    <p class="fs-5 mb-0">{{ $contact->name ?? 'Unknown' }} <span class="text-muted fs-6">({{ $contact->email ?? '-' }})</span></p>
                </div>
                
                <!-- Subjek Pesan -->
                <div class="mb-4">
                    <label class="fw-bold text-muted small text-uppercase">Subjek:</label>
                    <h4 class="mb-0 fw-bold text-dark">{{ $contact->subject }}</h4>
                </div>

                <hr class="my-4">

                <!-- Isi Pesan User -->
                <div class="mb-4">
                    <label class="fw-bold text-muted small text-uppercase">Pesan Masuk:</label>
                    <div class="p-3 bg-light rounded mt-2 border text-break" style="white-space: pre-wrap;">
                        {{ $contact->message }}
                    </div>
                </div>

                <!-- AREA BARU: Form Balasan Admin -->
                <div class="mb-4 p-3 border rounded bg-white shadow-sm">
                    <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <label class="fw-bold text-primary small mb-2 d-block">
                            <i class="fas fa-reply me-1"></i> TULIS BALASAN ADMIN:
                        </label>
                        
                        <!-- Pastikan name="admin_reply" sesuai dengan controller -->
                        <textarea name="admin_reply" class="form-control mb-3" rows="4" 
                                  placeholder="Ketik rekomendasi buku atau jawaban untuk user di sini...">{{ old('admin_reply', $contact->admin_reply) }}</textarea>
                        
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i> Dikirim pada: {{ $contact->created_at->format('d F Y, H:i WIB') }}
                            </small>
                            
                            <div class="d-flex gap-2">
                                <select name="status" class="form-select form-select-sm" style="width: 150px;">
                                    <option value="read" {{ $contact->status == 'read' ? 'selected' : '' }}>Tandai Dibaca</option>
                                    <option value="replied" {{ $contact->status == 'replied' ? 'selected' : '' }}>Sudah Dibalas</option>
                                    <option value="closed" {{ $contact->status == 'closed' ? 'selected' : '' }}>Tutup Tiket</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm px-3">
                                    <i class="fas fa-paper-plane me-1"></i> Kirim & Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Menampilkan Riwayat Balasan Admin (Jika Sudah Ada) -->
                @if(!empty($contact->admin_reply))
                <div class="alert alert-success border-0 shadow-sm">
                    <h6 class="alert-heading fw-bold"><i class="fas fa-check-circle me-2"></i>Balasan Terakhir Admin:</h6>
                    <p class="mb-0 text-break" style="white-space: pre-wrap;">{{ $contact->admin_reply }}</p>
                    <hr>
                    <small class="text-muted">Status saat ini: 
                        @if($contact->status == 'replied') <span class="badge bg-success">Sudah Dibalas</span>
                        @elseif($contact->status == 'closed') <span class="badge bg-secondary">Tiket Ditutup</span>
                        @else <span class="badge bg-info">Sedang Diproses</span>
                        @endif
                    </small>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection