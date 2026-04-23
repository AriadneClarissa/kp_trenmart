@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-center bg-light" 
                         style="height: 400px; border-radius: 15px; overflow: hidden;">
                        <img src="{{ asset('storage/' . $produk->gambar) }}" 
                             class="img-fluid" 
                             alt="{{ $produk->nama_produk }}"
                             style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <p class="text-muted mb-1">{{ $produk->merk->nama_merk ?? 'Tanpa Merk' }}</p>
                    <h2 class="fw-bold text-dark mb-3">{{ $produk->nama_produk }}</h2>
                    <h3 class="fw-bold mb-3" style="color: #800000;">
                        Rp {{ number_format($produk->harga_tampil, 0, ',', '.') }}
                        <small class="text-muted fw-normal">/{{ $produk->satuan }}</small>
                    </h3>
                    
                    @if($produk->stok_tersedia > 0)
                        <div class="mb-3">
                            <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">
                                <i class="bi bi-check-circle me-1"></i>Stok Tersedia: {{ $produk->stok_tersedia }}
                            </span>
                        </div>
                    @else
                        <div class="mb-3">
                            <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">
                                <i class="bi bi-x-circle me-1"></i>Stok Habis
                            </span>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h5 class="fw-bold">Deskripsi Produk</h5>
                        <p class="text-muted">{{ $produk->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="fw-bold">Kategori</h5>
                        <p class="text-muted">{{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori.' }}</p>
                    </div>

                    @auth
                        @if($produk->stok_tersedia > 0)
                            <form action="{{ route('cart.add', $produk->kd_produk) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn w-100 py-3 d-flex align-items-center justify-content-center gap-2" 
                                        style="background-color: #800000; color: white; border-radius: 12px; font-weight: 600; font-size: 1.1rem;">
                                    <i class="bi bi-cart-plus fs-5"></i> Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <button class="btn w-100 py-3" disabled 
                                    style="background-color: #ccc; color: white; border-radius: 12px; font-weight: 600; font-size: 1.1rem;">
                                <i class="bi bi-cart-x fs-5"></i> Stok Habis
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn w-100 py-3 d-flex align-items-center justify-content-center gap-2" 
                           style="background-color: #800000; color: white; border-radius: 12px; font-weight: 600; font-size: 1.1rem;">
                            <i class="bi bi-box-arrow-in-right fs-5"></i> Login untuk Membeli
                        </a>
                    @endauth

                    <div class="mt-3 text-center">
                        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .btn:hover:not(:disabled) {
        background-color: #600000 !important;
    }
</style>
@endsection