@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    {{-- 1. Banner --}}
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/spanduktoko.png') }}" class="w-100 rounded-4 shadow-sm" alt="Banner">
    </div>

    {{-- 2. Panel Kontrol Admin --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="card shadow-sm border-0 mb-5 bg-light" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-0" style="color: #800000;"><i class="bi bi-gear-fill me-2"></i>Panel Kontrol Admin</h5>
                        <p class="text-muted small mb-0">Atur produk dan stok toko Anda</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        {{-- Tombol Tambah Produk --}}
                        <a href="{{ route('produk.create') }}" class="btn btn-success rounded-pill px-4 me-2">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                        </a>

                        {{-- Tombol Edit Katalog --}}
                        <a href="{{ route('admin.judul.edit') }}" class="btn btn-warning rounded-pill px-4">
                            <i class="bi bi-pencil-square me-1"></i> Edit Judul Section
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth

    {{-- 3. Section Produk Terbaru --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center"><i class="bi bi-stars text-warning me-2"></i>Produk Terbaru</h4>
        <div class="row flex-nowrap overflow-auto g-4 pb-3 custom-scrollbar">
            @forelse($produk_terbaru as $item)
            <div class="col-auto" style="width: 250px;"> {{-- Mengunci lebar kartu agar tidak mengecil --}}
                @include('partials.item_produk', ['item' => $item])
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada produk terbaru.</p>
            </div>
        @endforelse
    </div>
</section>

    {{-- 4. Section Produk Terpopuler --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center"><i class="bi bi-fire text-danger me-2"></i>Produk Terpopuler</h4>
        <div class="row flex-nowrap overflow-auto g-4 pb-3 custom-scrollbar">
            @forelse($produk_terpopuler as $item)
                <div class="col-auto" style="width: 250px;">
                @include('partials.item_produk', ['item' => $item])
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <p class="text-muted">Belum ada produk populer.</p>
            </div>
        @endforelse
    </div>
</section>

<style>
    /* Menghaluskan tampilan scrollbar */
    .custom-scrollbar {
        scrollbar-width: thin; /* Untuk Firefox */
        scrollbar-color: #800000 #f1f1f1;
    }

    /* Untuk Chrome, Safari, dan Edge */
    .custom-scrollbar::-webkit-scrollbar {
        height: 6px; /* Tinggi scrollbar horizontal */
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #800000; /* Warna Maroon Trenmart */
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #600000;
    }

    /* Mencegah text selection saat geser-geser */
    .flex-nowrap {
        -webkit-overflow-scrolling: touch;
    }
</style>

    
</div>
@endsection