@extends('layouts.app')

@push('styles')
<style>
    :root { --maroon-trenmart: #800000; }
    body { background-color: #f8f9fa; }

    .card-keranjang { border-radius: 20px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .product-img { width: 80px; height: 80px; object-fit: contain; background: #f8f9fa; border-radius: 12px; padding: 5px; }
    
    .qty-input { width: 60px; text-align: center; border: 1px solid #ddd; border-radius: 8px; font-weight: bold; }
    .btn-qty { background: white; border: 1px solid #ddd; width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
    .btn-qty:hover { background: var(--maroon-trenmart); color: white; border-color: var(--maroon-trenmart); }

    .summary-card { background: white; border-radius: 20px; padding: 25px; position: sticky; top: 100px; }
    .btn-checkout { background: var(--maroon-trenmart); color: white; border-radius: 15px; padding: 12px; width: 100%; font-weight: 700; border: none; transition: 0.3s; }
    .btn-checkout:hover { background: #600000; transform: translateY(-3px); box-shadow: 0 8px 20px rgba(128,0,0,0.2); color: white; }

    .text-maroon { color: var(--maroon-trenmart); }
</style>
@endpush

@section('content')
<div class="container mt-5 mb-5">
    <div class="row g-4">
        {{-- BAGIAN KIRI: DAFTAR BARANG --}}
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold m-0"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h4>
                <span class="text-muted">{{ count($items) }} Barang</span>
            </div>

            @forelse($items as $item)
            <div class="card card-keranjang p-3 mb-3">
                <div class="row align-items-center">
                    {{-- Foto --}}
                    <div class="col-auto">
                        <img src="{{ asset('storage/' . $item->produk->gambar) }}" class="product-img">
                    </div>
                    {{-- Detail Produk --}}
                    <div class="col">
                        <h6 class="fw-bold mb-1">{{ $item->produk->nama_produk }}</h6>
                        <p class="text-muted small mb-0">{{ $item->produk->merk->nama_merk ?? 'Tanpa Merk' }}</p>
                        <p class="text-maroon fw-bold mb-0">Rp {{ number_format($item->harga_at_time, 0, ',', '.') }}</p>
                    </div>
                    {{-- Kontrol Jumlah --}}
                    <div class="col-auto">
                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" class="btn-qty"><i class="bi bi-dash"></i></button>
                            </form>
                            <input type="text" class="qty-input" value="{{ $item->jumlah }}" readonly>
                            <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" class="btn-qty"><i class="bi bi-plus"></i></button>
                            </form>
                        </div>
                    </div>
                    {{-- Subtotal & Hapus --}}
                    <div class="col-md-2 text-end">
                        <p class="fw-bold mb-2">Rp {{ number_format($item->harga_at_time * $item->jumlah, 0, ',', '.') }}</p>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm text-danger border-0"><i class="bi bi-trash3"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="card card-keranjang p-5 text-center">
                <i class="bi bi-cart-x display-1 text-muted opacity-25"></i>
                <h5 class="mt-3 text-muted">Keranjangmu masih kosong nih.</h5>
                <div class="mt-4">
                    <a href="{{ route('katalog') }}" class="btn btn-outline-dark rounded-pill px-4">Mulai Belanja</a>
                </div>
            </div>
            @endforelse
        </div>

        {{-- BAGIAN KANAN: RINGKASAN HARGA --}}
        <div class="col-lg-4">
            <div class="summary-card shadow-sm">
                <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Total Harga ({{ $items->sum('jumlah') }} Barang)</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Diskon</span>
                    <span class="text-success">- Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold fs-5">Total Bayar</span>
                    <span class="fw-bold fs-5 text-maroon">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <button class="btn-checkout">
                    Checkout Sekarang <i class="bi bi-arrow-right ms-2"></i>
                </button>

                <div class="mt-3 text-center">
                    <p class="small text-muted"><i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terverifikasi</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection