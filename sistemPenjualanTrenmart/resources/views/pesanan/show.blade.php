@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4 class="fw-bold">Pesanan {{ $order->order_number }}</h4>

    <div class="card mt-3 p-4" style="border-radius:12px;">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <div class="fw-bold">{{ $order->order_number }}</div>
                <div class="small text-muted">{{ $order->created_at->format('d M Y \p\u\k\u\l H:i') }}</div>
            </div>
            <div class="text-end">
                <div class="badge rounded-pill mb-2" style="background:#fff6e6;color:#b45309;">{{ ucfirst(str_replace('_',' ', $order->payment_status)) }}</div>
                <div class="badge rounded-pill d-block" style="background:#e8f3ff;color:#1d4ed8;">
                    @if($order->order_status === 'processing')
                        Diproses
                    @elseif($order->order_status === 'ready_to_ship')
                        Siap Dikirim
                    @elseif($order->order_status === 'completed')
                        Selesai
                    @elseif($order->order_status === 'payment_rejected')
                        Pembayaran Ditolak
                    @else
                        {{ ucfirst(str_replace('_',' ', $order->order_status ?? 'new')) }}
                    @endif
                </div>
                <div class="fw-bold mt-2">Rp {{ number_format($order->total,0,',','.') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-semibold">Produk</h6>
                <div class="mt-2">
                    @foreach($order->items as $it)
                        <div class="card mb-2 p-2" style="border-radius:10px;">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . ($it->produk->gambar ?? 'images/no-image.png')) }}" style="width:64px;height:64px;object-fit:cover;border-radius:8px;" alt="">
                                <div class="ms-3">
                                    <div class="fw-semibold">{{ $it->produk->nama_produk ?? '-' }}</div>
                                    <div class="small text-muted">{{ $it->quantity }} × Rp {{ number_format($it->price,0,',','.') }}</div>
                                </div>
                                <div class="ms-auto fw-bold">Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <h6 class="mt-4 fw-semibold">Bukti Transfer</h6>
                @if($order->payment_proof)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" style="max-width:220px;border-radius:10px;" alt="">
                    </div>
                @else
                    <div class="text-muted">Belum ada bukti transfer.</div>
                @endif
            </div>

            <div class="col-md-4">
                <h6 class="fw-semibold">Detail Pesanan</h6>
                <div class="mt-2 small text-muted">Metode: {{ $order->paymentMethod->name ?? '-' }}</div>
                <div class="mt-2 small text-muted">Status pembayaran: {{ $order->payment_status }}</div>
                <div class="mt-2 small text-muted">Status pesanan: {{ $order->order_status === 'processing' ? 'Diproses' : ($order->order_status === 'ready_to_ship' ? 'Siap Dikirim' : ($order->order_status === 'completed' ? 'Selesai' : ucfirst(str_replace('_',' ', $order->order_status ?? 'new')))) }}</div>
                @if($order->pickup_method === 'delivery')
                    <div class="mt-2 small text-muted">Alamat kirim: {{ $order->shipping_address ?? '-' }}</div>
                    <div class="mt-2 small text-muted">Jarak: {{ $order->shipping_distance_km !== null ? number_format($order->shipping_distance_km, 2, ',', '.') . ' km' : '-' }}</div>
                    <div class="mt-2 small text-muted">Ongkir: Rp {{ number_format($order->shipping_cost ?? 0,0,',','.') }}</div>
                @endif
                <div class="mt-3 fw-bold">Total: Rp {{ number_format($order->total,0,',','.') }}</div>
            </div>
        </div>
    </div>
    @include('partials.order_chat', ['order' => $order])
</div>
@endsection
