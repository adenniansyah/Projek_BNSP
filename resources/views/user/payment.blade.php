@extends('layouts.app')
@section('title', 'Pembayaran Pesanan #' . $order->id)
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-success text-white py-3 text-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-wallet me-2"></i>Pembayaran Pesanan #{{ $order->id }}</h5>
                </div>
                
                <div class="card-body p-5 text-center">
                    
                    <!-- Total Harga -->
                    <div class="mb-4">
                        <h2 class="fw-bold text-dark mb-1 display-5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</h2>
                        <p class="text-muted small mt-2">
                            Selesaikan pembayaran sebelum <br>
                            <strong>{{ \Carbon\Carbon::parse($order->created_at)->addHours(24)->format('d M Y, H:i') }} WIB</strong>
                        </p>
                    </div>

                    <!-- Tombol Bayar Midtrans -->
                    {{-- Pastikan snap_token ada sebelum menampilkan tombol --}}
                    @if($order->snap_token)
                        <button id="pay-button" class="btn btn-success btn-lg w-100 fw-bold py-3 mb-3 shadow-sm hover-scale">
                            <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
                        </button>
                    @else
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-exclamation-triangle me-1"></i> Token pembayaran belum tersedia. Silakan refresh halaman atau buat pesanan ulang.
                        </div>
                    @endif

                    <!-- Info Metode Pembayaran -->
                    <div class="alert alert-info small text-start mb-4 border-0 bg-light">
                        <i class="fas fa-info-circle me-1 text-primary"></i> 
                        <strong>Metode Pembayaran Tersedia:</strong>
                        <ul class="mb-0 mt-1 ps-3 text-muted">
                            <li>QRIS (GoPay, OVO, Dana, ShopeePay, dll)</li>
                            <li>Virtual Account (BCA, Mandiri, BRI, BNI, Permata)</li>
                            <li>Minimarket (Indomaret, Alfamart)</li>
                            <li>Kartu Kredit / Debit Online</li>
                        </ul>
                    </div>

                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ SCRIPT MIDTRANS SNAP (WAJIB ADA DI BAGIAN BAWAH) --}}
<script type="text/javascript"
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('services.midtrans.client_key') }}">
</script>

<script type="text/javascript">
  // Ambil elemen tombol
  const payButton = document.getElementById('pay-button');
  
  if (payButton) {
      payButton.addEventListener('click', function (event) {
        // Mencegah form submit jika ada (opsional)
        event.preventDefault();

        // Panggil popup Midtrans Snap
        snap.pay('{{ $order->snap_token }}', {
          
          // 1. Jika pembayaran SUKSES (Settlement/Capture)
          onSuccess: function(result){
            console.log('Success:', result);
            // Redirect ke halaman sukses Laravel
            window.location.href = "{{ route('payment.success', $order) }}";
          },
          
          // 2. Jika pembayaran PENDING (Misal: VA dibuat tapi belum dibayar)
          onPending: function(result){
            console.log('Pending:', result);
            alert('Pembayaran sedang menunggu! Silakan selesaikan pembayaran sesuai instruksi.');
            // Opsional: Reload halaman untuk cek status terbaru
            window.location.reload();
          },
          
          // 3. Jika terjadi ERROR
          onError: function(result){
            console.log('Error:', result);
            alert('Terjadi kesalahan saat pembayaran. Silakan coba lagi.');
          },
          
          // 4. Jika user MENUTUP popup tanpa membayar
          onClose: function(){
            console.log('Closed');
            // Tidak perlu alert, biarkan user klik tombol lagi
          }
          
        });
      });
  } else {
      console.error('Tombol bayar tidak ditemukan!');
  }
</script>

<style>
    /* Sedikit animasi agar tombol lebih menarik */
    .hover-scale { transition: transform 0.2s; }
    .hover-scale:hover { transform: scale(1.02); }
</style>

@endsection