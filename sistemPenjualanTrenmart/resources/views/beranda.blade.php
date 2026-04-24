@extends('layouts.app')

@section('content')
<div class="container mt-3 mt-md-4 mb-5"> {{-- Margin lebih kecil di HP --}}
    
    {{-- 1. Banner yang Diperbaiki --}}
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/spanduktoko.png') }}" 
             class="w-100 rounded-4 shadow-sm img-banner-responsive" 
             alt="Banner">
    </div>

    {{-- 2. Panel Kontrol Admin --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="card shadow-sm mb-5 admin-panel-card">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h5 class="fw-bold mb-1 panel-title">
                        <i class="bi bi-shield-lock-fill me-2"></i>Panel Kontrol Admin
                    </h5>
                    <p class="text-muted small mb-0">Manajemen produk, kategori, dan stok toko Anda</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="d-grid d-md-inline-block gap-2">
                        <a href="{{ route('produk.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                        </a>
                        <a href="{{ route('admin.judul.edit') }}" class="btn btn-warning rounded-pill px-4 shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Edit Judul
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endif
    @endauth

    {{-- Section Produk (Tetap pakai flex-nowrap) --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4"><i class="bi bi-stars text-warning me-2"></i>{{ $settings['judul_terbaru'] ?? 'Produk Terbaru' }}</h4>
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            @forelse($produk_terbaru as $item)
            <div class="col-auto card-mobile-width"> 
                @include('partials.item_produk', ['item' => $item])
            </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada produk terbaru.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- Section Produk Terpopuler / Promo --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4"><i class="bi bi-fire text-danger me-2"></i>{{ $settings['judul_terpopuler'] ?? 'Produk Terpopuler' }}</h4>
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            @forelse($produk_terpopuler as $item)
            <div class="col-auto card-mobile-width">
                @include('partials.item_produk', ['item' => $item])
            </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada produk promo yang dipilih.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

<style>
    /* 1. Responsive Banner */
    .img-banner-responsive {
        height: 180px; /* Tinggi di HP */
        object-fit: cover; /* Agar tidak gepeng */
    }

    @media (min-width: 768px) {
        .img-banner-responsive {
            height: auto; /* Ikuti proporsi asli di laptop */
        }
    }

    /* 2. Lebar Kartu Produk di HP */
    .card-mobile-width {
        width: 168px;
    }

    @media (min-width: 768px) {
        .card-mobile-width {
            width: 218px;
        }
    }

    /* 3. Haluskan Scroll */
    .custom-scrollbar {
        scrollbar-width: none; /* Sembunyikan scrollbar di Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .custom-scrollbar::-webkit-scrollbar {
        display: none; /* Sembunyikan scrollbar di Chrome/Safari */
    }

    /* Agar scrolling di HP terasa smooth */
    .flex-nowrap {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }
    .col-auto {
        scroll-snap-align: start;
    }
    .admin-panel-card {
        transition: none !important; 
        transform: none !important;
    }

    
</style>
@endsection