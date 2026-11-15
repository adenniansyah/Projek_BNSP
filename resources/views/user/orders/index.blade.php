@extends('layouts.app')
@section('title', 'Riwayat Pesanan')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-history me-2 text-primary"></i>Riwayat Pesanan Saya</h2>
    <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-shopping-bag me-1"></i> Belanja Lagi
    </a>
</div>

<div class="table-responsive bg-white shadow-sm rounded p-3">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status Pengiriman</th>
                <th>Status Bayar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="fw-bold">#{{ $order->id }}</td>
                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                <td class="fw-bold text-dark">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                
                <!-- Badge Status Pengiriman -->
                <td>
                    @php
                        $shipBadge = match($order->status) {
                            'pending' => 'bg-warning text-dark',
                            'processing' => 'bg-info text-white',
                            'shipped' => 'bg-primary text-white',
                            'delivered' => 'bg-success text-white',
                            'cancelled' => 'bg-danger text-white',
                            default => 'bg-secondary text-white'
                        };
                    @endphp
                    <span class="badge {{ $shipBadge }} rounded-pill px-3 py-2">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </td>

                <!-- Badge Status Pembayaran (BARU) -->
                <td>
                    @php
                        $payBadge = match($order->payment_status ?? 'pending') {
                            'paid' => 'bg-success text-white',
                            'pending' => 'bg-warning text-dark',
                            'failed' => 'bg-danger text-white',
                            'expired' => 'bg-secondary text-white',
                            default => 'bg-light text-dark border'
                        };
                        $payLabel = match($order->payment_status ?? 'pending') {
                            'paid' => 'Lunas',
                            'pending' => 'Belum Bayar',
                            'failed' => 'Gagal',
                            'expired' => 'Kadaluarsa',
                            default => 'Unknown'
                        };
                    @endphp
                    <span class="badge {{ $payBadge }} rounded-pill px-3 py-2">
                        {{ $payLabel }}
                    </span>
                </td>

                <!-- Tombol Aksi -->
                <td>
                    @if($order->payment_status !== 'paid' && $order->payment_method === 'midtrans')
                        <a href="{{ route('payment.page', $order) }}" class="btn btn-sm btn-success me-1">
                            <i class="fas fa-credit-card me-1"></i> Bayar
                        </a>
                    @endif
                    
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Detail
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                    Belum ada riwayat pesanan. Yuk belanja sekarang!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection