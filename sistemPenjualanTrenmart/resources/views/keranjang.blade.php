@extends('layouts.app')

@push('styles')
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
    }
    /* Background & Font */
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* Rapatkan jarak ke Navbar */
    .main-container { padding-top: 15px !important; }

    /* Layout Wrapper: Menjaga Kiri dan Kanan Sejajar Sempurna */
    .cart-wrapper { 
        display: flex; 
        align-items: flex-start !important; 
    }

    /* Cards */
    .card-custom { border-radius: 15px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); background: white; margin-bottom: 20px; }
    .product-img { width: 85px; height: 85px; object-fit: cover; border-radius: 12px; background: #f1f1f1; }
    
    /* Qty Control */
    .qty-container { border: 1px solid #eee; border-radius: 10px; padding: 2px; background: #fff; display: inline-flex; }
    .qty-input { width: 40px; text-align: center; border: none; font-weight: 700; background: transparent; outline: none; }
    .btn-qty { border: none; background: transparent; width: 30px; height: 30px; border-radius: 8px; font-weight: bold; transition: 0.2s; cursor: pointer; }
    .btn-qty:hover { background: #fceaea; color: var(--maroon-trenmart); }

    /* Sidebar Sticky: Menempel saat scroll tanpa getar */
    .summary-card { 
        background: white; 
        border-radius: 18px; 
        padding: 24px; 
        position: -webkit-sticky;
        position: sticky; 
        top: 20px; 
        border: none; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        will-change: transform; 
    }

    /* Button Bayar */
    .btn-checkout { 
        background: var(--accent-red); 
        color: white !important; 
        border-radius: 12px; 
        padding: 16px; 
        width: 100%; 
        font-weight: 700; 
        border: none; 
        transition: 0.3s; 
        display: flex; 
        justify-content: center; 
        align-items: center;
        text-decoration: none;
    }
    .btn-checkout:hover { background: #c5163e; transform: translateY(-2px); }

    /* Utility */
    .text-maroon { color: var(--maroon-trenmart); }
    .text-accent { color: var(--accent-red); }
    .btn-link-custom { text-decoration: none; font-weight: 600; font-size: 0.85rem; }
</style>
@endpush

@section('content')
<div class="container main-container pb-5">
    
    {{-- Header: Ikon Keranjang Belanja Warna Hitam Tanpa Bentuk Bulat --}}
    <div class="mb-3">
        <a href="{{ route('katalog') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Belanja
        </a>
        <div class="d-flex align-items-center mt-1">
            <i class="bi bi-cart3 text-black-custom fs-2 me-3"></i> <div>
                <h3 class="fw-bold mb-0">Keranjang Belanja</h3>
            </div>
        </div>
    </div>

    <div class="row cart-wrapper g-4">
        
        {{-- KOLOM KIRI: DAFTAR BARANG --}}
        <div class="col-lg-8">
            {{-- List Produk --}}
            <div class="card card-custom p-4">
                {{-- JUMLAH ITEM & HAPUS SEMUA --}}
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h6 class="fw-bold mb-0">Produk ({{ count($items) }} item)</h6>
                    @if(count($items) > 0)
                        <form action="{{ route('cart.clear') }}" method="POST" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger btn-link-custom p-0 d-flex align-items-center" onclick="return confirm('Hapus semua item di keranjang?');">
                                <i class="bi bi-trash-fill me-1"></i> Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>

                @forelse($items as $item)
                <div class="d-flex align-items-center py-3 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                    
                    {{-- LOGIKA PEMISAHAN TAMPILAN BUNDLING & REGULER --}}
                    @if($item->bundling_id != null && $item->bundling)
                        {{-- 1. Tampilan Paket Bundling --}}
                        @php
                            $gambarBundling = null;
                            if($item->bundling->items && $item->bundling->items->count() > 0) {
                                $produkPertama = $item->bundling->items->first()->produk;
                                $gambarBundling = $produkPertama ? $produkPertama->gambar : null;
                            }
                        @endphp
                        <img src="{{ \App\Helpers\StorageProxy::url($gambarBundling ?? 'images/no-image.png') }}" class="product-img me-3" style="object-fit: cover;">
                        
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-danger">{{ $item->bundling->name }}</h6>
                            <p class="text-muted small mb-1">Paket Bundling Hemat</p>
                            <h6 class="text-accent fw-bold mb-0">Rp {{ number_format($item->harga_at_time, 0, ',', '.') }}</h6>
                        </div>
                    @else
                        {{-- 2. Tampilan Produk Reguler (INI YANG TADI HILANG) --}}
                        <img src="{{ \App\Helpers\StorageProxy::url($item->produk->gambar ?? 'images/no-image.png') }}" class="product-img me-3">
                        
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0">{{ $item->produk->nama_produk }}</h6>
                            <p class="text-muted small mb-1">{{ $item->produk->merk->nama_merk ?? 'Trenmart' }}</p>
                            <h6 class="text-accent fw-bold mb-0">Rp {{ number_format($item->harga_at_time, 0, ',', '.') }}</h6>
                        </div>
                    @endif {{-- INI PENUTUP YANG SANGAT PENTING --}}

                    <div class="text-end">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="qty-container mb-2">
                            @csrf
                            @method('PUT')
                            <button class="btn-qty" type="submit" name="action" value="decrease">-</button>
                            <input type="text" class="qty-input" value="{{ $item->jumlah }}" readonly>
                            <button class="btn-qty" type="submit" name="action" value="increase">+</button>
                        </form>
                        <div class="fw-bold d-block">Rp {{ number_format($item->harga_at_time * $item->jumlah, 0, ',', '.') }}</div>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="m-0 mt-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link p-0 text-muted small shadow-none" onclick="return confirm('Hapus produk ini dari keranjang?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">Keranjang Anda kosong</div>
                @endforelse
            </div>
        </div>

        {{-- KOLOM KANAN: RINGKASAN PESANAN (STICKY) --}}
        <div class="col-lg-4">
            <div class="summary-card shadow-sm">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                
                <div id="items-list">
                    @foreach($items as $item)
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span class="text-truncate" style="max-width: 160px;">
                            {{-- LOGIKA PEMISAHAN NAMA DI RINGKASAN --}}
                            @if($item->bundling_id != null && $item->bundling)
                                {{ $item->bundling->name }}
                            @else
                                {{ $item->produk->nama_produk }}
                            @endif
                            ×{{ $item->jumlah }}
                        </span>
                        <span>Rp {{ number_format($item->harga_at_time * $item->jumlah, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4 text-muted small">
                    <span>Ongkos Kirim</span>
                    <span class="fw-bold text-dark">Dihitung di checkout</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0" id="total-label">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                </div>

                @if(count($items) > 0)
                <a href="{{ route('checkout.index') }}" class="btn-checkout shadow-sm">
                    Lanjut ke Pembayaran <i class="bi bi-chevron-right ms-2"></i>
                </a>
                @else
                <button class="btn btn-secondary w-100 py-3 fw-bold border-0 opacity-50" disabled style="border-radius:12px;">Keranjang Kosong</button>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection