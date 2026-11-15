@extends('layouts.app')
@section('title', 'Kelola Pesanan')
@section('content')
<h2 class="mb-4">Kelola Pesanan Masuk</h2>

{{-- Filter pembayaran --}}
<div class="mb-3 d-flex gap-2">
    <a href="{{ route('admin.orders.index') }}"
       class="btn btn-sm {{ !request('payment') ? 'btn-dark' : 'btn-outline-dark' }}">Semua</a>
    <a href="{{ route('admin.orders.index', ['payment' => 'paid']) }}"
       class="btn btn-sm {{ request('payment') === 'paid' ? 'btn-success' : 'btn-outline-success' }}">Sudah Lunas</a>
    <a href="{{ route('admin.orders.index', ['payment' => 'pending']) }}"
       class="btn btn-sm {{ request('payment') === 'pending' ? 'btn-warning' : 'btn-outline-warning' }}">Belum Bayar</a>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle bg-white shadow-sm rounded">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Pembeli</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status</th>
            <th>Ubah Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($orders as $order)
        <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->user->name ?? '-' }}</td>
            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>

            {{-- Status pembayaran --}}
            <td>
                @if($order->payment_status === 'paid')
                    <span class="badge bg-success">Lunas</span>
                @elseif($order->payment_status === 'failed')
                    <span class="badge bg-danger">Gagal</span>
                @else
                    <span class="badge bg-warning text-dark">Belum Bayar</span>
                @endif
                <div class="small text-muted">{{ strtoupper($order->payment_method) }}</div>
            </td>

            {{-- Status pesanan --}}
            <td>
                @php
                    $badge = match($order->status) {
                        'pending'    => 'bg-warning text-dark',
                        'processing' => 'bg-info',
                        'shipped'    => 'bg-primary',
                        'delivered',
                        'completed'  => 'bg-success',
                        'cancelled'  => 'bg-secondary',
                        default      => 'bg-secondary',
                    };
                    $label = match($order->status) {
                        'pending'    => 'Pending',
                        'processing' => 'Diproses',
                        'shipped'    => 'Dikirim',
                        'delivered',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                        default      => ucfirst($order->status),
                    };
                @endphp
                <span class="badge {{ $badge }}">{{ $label }}</span>
            </td>

            {{-- Form ubah status --}}
            <td>
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm" style="width: 150px;">
                        <option value="pending"    {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="shipped"    {{ $order->status == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed"  {{ in_array($order->status, ['completed', 'delivered']) ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled"  {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">Belum ada pesanan.</td></tr>
        @endforelse
    </tbody>
</table>
</div>

<div class="mt-3">
    {{ $orders->links() }}
</div>
@endsection