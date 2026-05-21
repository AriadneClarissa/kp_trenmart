@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="fw-bold">Pesanan Saya</h3>
    <p class="text-muted">Lihat riwayat dan status pesanan Anda di sini.</p>

    <div class="mt-4">
        @forelse($orders as $order)
            <div class="card mb-3" style="border-radius:12px;">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div>
                            <div class="fw-bold">{{ $order->order_number }}</div>
                            <div class="small text-muted">{{ $order->created_at->format('d M Y \p\u\k\u\l H:i') }}</div>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="badge rounded-pill" style="background:#fff6e6;color:#b45309;">{{ ucfirst(str_replace('_',' ', $order->payment_status)) }}</div>
                            <div class="fw-bold mt-2">Rp {{ number_format($order->total,0,',','.') }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        @foreach($order->items as $it)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ \App\Helpers\StorageProxy::url($it->produk->gambar ?? 'images/no-image.png') }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;" alt="">
                                <div class="ms-3">
                                    <div class="fw-semibold">{{ $it->produk->nama_produk ?? '-' }}</div>
                                    <div class="small text-muted">{{ $it->quantity }} × Rp {{ number_format($it->price,0,',','.') }}</div>
                                </div>
                                <div class="ms-auto small text-muted">{{ $order->paymentMethod->name ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('pesanan.show', $order->id) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="card p-4 text-center">
                <div class="text-muted">Belum ada pesanan.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
