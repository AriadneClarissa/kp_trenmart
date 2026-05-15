@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pesanan Pelanggan</h4>
        <p class="text-muted small">Menunggu Konfirmasi: <span class="badge bg-warning">{{ $orders->where('payment_status', 'waiting_confirmation')->count() }}</span></p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->order_number }}</strong>
                            </td>
                            <td>
                                {{ $order->user ? $order->user->name : 'Unknown' }}
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <strong>Rp {{ number_format($order->total,0,',','.') }}</strong>
                            </td>
                            <td>
                                @if($order->payment_status === 'waiting_confirmation')
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                @elseif($order->payment_status === 'confirmed')
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                @elseif($order->payment_status === 'rejected')
                                    <span class="badge bg-danger">Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($order->order_status === 'processing')
                                    <span class="badge bg-primary">Diproses</span>
                                @elseif($order->order_status === 'ready_to_ship')
                                    <span class="badge bg-info text-dark">Siap Dikirim</span>
                                @elseif($order->order_status === 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($order->order_status === 'payment_rejected')
                                    <span class="badge bg-danger">Pembayaran Ditolak</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'new')) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada pesanan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
