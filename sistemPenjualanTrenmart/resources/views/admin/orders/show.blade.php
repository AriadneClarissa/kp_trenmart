@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="fw-bold">Detail Pesanan #{{ $order->order_number }}</h4>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Order Status Card -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-bold">Status Pesanan</h6>
                        </div>
                        <div class="col-auto">
                            @if($order->payment_status === 'waiting_confirmation')
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            @elseif($order->payment_status === 'confirmed')
                                <span class="badge bg-success">Pembayaran Dikonfirmasi</span>
                            @elseif($order->payment_status === 'rejected')
                                <span class="badge bg-danger">Pembayaran Ditolak</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Tanggal Pesanan</p>
                            <p class="fw-semibold mb-3">{{ $order->created_at->format('d M Y H:i') }}</p>
                            
                            <p class="small text-muted mb-1">Pelanggan</p>
                            <p class="fw-semibold mb-3">{{ $order->user ? $order->user->name : 'Unknown' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Metode Pembayaran</p>
                            <p class="fw-semibold mb-3">{{ $order->paymentMethod->name ?? '-' }}</p>
                            
                            <p class="small text-muted mb-1">Total Pembayaran</p>
                            <p class="fw-bold fs-5 mb-0" style="color:#660000;">Rp {{ number_format($order->total,0,',','.') }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="small text-muted mb-1">Status Pesanan</p>
                            <p class="fw-semibold mb-0">
                                @if($order->order_status === 'processing')
                                    Diproses
                                @elseif($order->order_status === 'ready_to_ship')
                                    Siap Dikirim
                                @elseif($order->order_status === 'completed')
                                    Selesai
                                @elseif($order->order_status === 'payment_rejected')
                                    Pembayaran Ditolak
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status ?? 'new')) }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="small text-muted mb-1">Langkah berikutnya</p>
                            <p class="fw-semibold mb-0">
                                @if($order->payment_status !== 'confirmed')
                                    Konfirmasi pembayaran dulu.
                                @elseif($order->order_status === 'processing')
                                    Bisa ubah ke siap dikirim.
                                @elseif($order->order_status === 'ready_to_ship')
                                    Bisa tandai selesai.
                                @else
                                    Tidak ada aksi lanjutan.
                                @endif
                            </p>
                        </div>
                        <div class="col-12 mt-3">
                            <p class="small text-muted mb-1">Alamat Pengiriman</p>
                            <p class="fw-semibold mb-0">{{ $order->shipping_address ?? '-' }}</p>
                            <p class="small text-muted mt-2 mb-0">Tarif ongkir flat: Rp {{ number_format($order->shipping_cost ?? 0,0,',','.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Card -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Produk ({{ $order->items->count() }})</h6>
                </div>
                <div class="card-body">
                    @foreach($order->items as $it)
                        <div class="row mb-3 pb-3 border-bottom" style="@if($loop->last)border-bottom:none!important;@endif">
                            <div class="col-auto">
                                        <img src="{{ \App\Helpers\StorageProxy::url($it->produk->gambar ?? 'images/no-image.png') }}" 
                                     style="width:60px;height:60px;object-fit:cover;border-radius:8px;" alt="">
                            </div>
                            <div class="col">
                                <p class="fw-semibold mb-1">{{ $it->produk->nama_produk ?? '-' }}</p>
                                <p class="small text-muted mb-2">Kode: {{ $it->kd_produk }}</p>
                            </div>
                            <div class="col-auto text-end">
                                <p class="small text-muted mb-1">{{ $it->quantity }} × Rp {{ number_format($it->price,0,',','.') }}</p>
                                <p class="fw-bold">Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Proof Card -->
            @php
                $proofPath = $order->payment_proof ?? $order->bukti_pembayaran ?? null;
                $proofExists = $proofPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($proofPath) : false;
                $proofMime = $proofExists ? (\Illuminate\Support\Facades\Storage::disk('public')->mimeType($proofPath) ?: 'image/jpeg') : null;
                $proofBase64 = $proofExists ? base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($proofPath)) : null;
            @endphp
            @if($proofBase64)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold">Bukti Transfer</h6>
                    </div>
                    <div class="card-body">
                        <img src="data:{{ $proofMime }};base64,{{ $proofBase64 }}" 
                             style="max-width:100%;max-height:400px;border-radius:10px;" alt="Bukti Transfer">
                    </div>
                </div>
            @elseif($proofPath)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 fw-bold">Bukti Transfer</h6>
                    </div>
                    <div class="card-body text-warning">
                        Bukti transfer tercatat di data pesanan, tetapi file gambar tidak ditemukan di storage.
                        <div class="small text-muted mt-1">Path: {{ $proofPath }}</div>
                    </div>
                </div>
            @endif

            <!-- Chat Card -->
            @include('partials.order_chat', ['order' => $order])
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Card -->
            @if($order->payment_status === 'waiting_confirmation')
                <div class="card border-warning mb-3">
                    <div class="card-header bg-warning bg-opacity-10 border-warning">
                        <h6 class="mb-0 fw-bold text-warning">Konfirmasi Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted mb-3">Periksa bukti transfer dengan cermat sebelum mengkonfirmasi pembayaran.</p>
                        <div class="d-grid gap-2">
                            <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle me-1"></i> Terima & Proses Pesanan
                                </button>
                            </form>

                            <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-x-circle me-1"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($order->payment_status === 'confirmed')
                <div class="card border-success mb-3">
                    <div class="card-header bg-success bg-opacity-10 border-success">
                        <h6 class="mb-0 fw-bold text-success">
                            <i class="bi bi-check-circle me-1"></i> Pembayaran Dikonfirmasi
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Pesanan dalam status <strong>Diproses</strong>. Siap untuk dikemas dan dikirim.</p>
                        <div class="d-grid gap-2 mt-3">
                            @if($order->order_status === 'processing')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_status" value="ready_to_ship">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-truck me-1"></i> Tandai Siap Dikirim
                                    </button>
                                </form>
                            @elseif($order->order_status === 'ready_to_ship')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_status" value="completed">
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="bi bi-check2-circle me-1"></i> Tandai Selesai
                                    </button>
                                </form>
                            @elseif($order->order_status === 'completed')
                                <span class="badge bg-success-subtle text-success-emphasis py-2">Pesanan sudah selesai diproses.</span>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($order->payment_status === 'rejected')
                <div class="card border-danger mb-3">
                    <div class="card-header bg-danger bg-opacity-10 border-danger">
                        <h6 class="mb-0 fw-bold text-danger">
                            <i class="bi bi-x-circle me-1"></i> Pembayaran Ditolak
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">Pembayaran telah ditolak. Pelanggan dapat mengirimkan ulang bukti transfer.</p>
                    </div>
                </div>
            @endif

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Ringkasan Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="small text-muted mb-1">No. Pesanan</p>
                        <p class="fw-semibold">#{{ $order->order_number }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="small text-muted mb-1">Tanggal</p>
                        <p class="fw-semibold">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="small text-muted mb-1">Jumlah Item</p>
                        <p class="fw-semibold">{{ $order->items->count() }} produk</p>
                    </div>
                    <hr>
                    <div>
                        <p class="small text-muted mb-1">Total</p>
                        <p class="fw-bold fs-5" style="color:#660000;">Rp {{ number_format($order->total,0,',','.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
