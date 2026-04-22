@extends('layouts.app')

@section('content')
<div class="container mt-4">
    {{-- 1. Banner --}}
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/spanduktoko.png') }}" class="w-100 rounded-4" alt="Banner">
    </div>

    {{-- 2. Panel Kontrol Admin (Hanya muncul jika sudah login & role admin) --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="card shadow-sm border-0 mb-5 bg-light" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-0 text-maroon"><i class="bi bi-gear-fill me-2"></i>Panel Kontrol Admin</h5>
                        <p class="text-muted small mb-0">Atur produk dan stok toko Anda</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.tambah.beranda') }}" class="btn btn-success rounded-pill px-4 me-2">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                        </a>
                        <button class="btn btn-warning rounded-pill px-4">
                            <i class="bi bi-pencil-square me-1"></i> Edit Katalog
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth

    {{-- 3. Section Produk Terbaru --}}
    <h5 class="text-center fw-bold mb-4">Produk Terbaru</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4 mb-5">
        @forelse($produk_terbaru as $item)
        <div class="col">
            <div class="card h-100 card-produk text-center p-2">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top p-2" style="height: 140px; object-fit: contain;" alt="{{ $item->nama_produk }}">
                <div class="card-body p-2">
                    <p class="small mb-1 text-truncate">{{ $item->nama_produk }}</p>
                    <p class="fw-bold text-maroon mb-2">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</p>
                    <button class="btn-tambah-cart shadow-sm">Tambah</button>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 w-100 text-center py-5">
                <p class="text-muted">Belum ada produk tersedia.</p>
            </div>
        @endforelse
    </div>

    {{-- 4. Section Produk Terpopuler --}}
    <h5 class="text-center fw-bold mb-4">Produk Terpopuler</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4 mb-5">
        @forelse($produk_terpopuler as $item)
        <div class="col">
            <div class="card h-100 card-produk text-center p-2">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top p-2" style="height: 140px; object-fit: contain;" alt="{{ $item->nama_produk }}">
                <div class="card-body p-2">
                    <p class="small mb-1 text-truncate">{{ $item->nama_produk }}</p>
                    <p class="fw-bold text-maroon mb-2">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</p>
                    <button class="btn-tambah-cart shadow-sm">Tambah</button>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 w-100 text-center py-5">
                <p class="text-muted">Belum ada produk populer tersedia.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection