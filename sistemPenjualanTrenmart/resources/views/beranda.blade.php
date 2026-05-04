@extends('layouts.app')

@section('content')
<div class="container mt-3 mt-md-4 mb-5">
    
    {{-- 1. Banner Utama --}}
    <div class="banner-wrapper mb-4 position-relative overflow-hidden" style="border-radius: 1rem;">
        <img id="bannerPreview" 
            src="{{ ($admin && $admin->tentang_banner) ? asset('storage/' . $admin->tentang_banner) : asset('images/spanduktoko.png') }}" 
            class="w-100 shadow-sm img-banner-responsive object-fit-cover" 
            style="height: 300px;" 
            alt="Banner Trenmart">

        {{-- Tombol ini hanya muncul jika yang login adalah Admin --}}
        @if(Auth::check() && Auth::user()->role == 'admin')
            <form action="{{ route('admin.banner.update') }}" method="POST" enctype="multipart/form-data" id="bannerForm">
                @csrf
                
                <input type="file" name="tentang_banner" id="bannerInput" class="d-none" accept="image/*">

                <label for="bannerInput" 
                    class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center hover-scale" 
                    style="width: 80px; height: 80px; opacity: 0.9; cursor: pointer; border: 3px solid white; z-index: 10;">
                    <div class="text-center">
                        <i class="bi bi-camera-fill fs-3 text-dark"></i>
                        <div style="font-size: 10px; font-weight: bold; color: #333;">Ubah Foto</div>
                    </div>
                </label>
            </form>
        @endif
    </div>

    <script>
        // Otomatis submit form saat file dipilih
        const bannerInput = document.getElementById('bannerInput');
        if(bannerInput) {
            bannerInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    document.getElementById('bannerForm').submit();
                }
            });
        }
    </script>

    <style>
        .hover-scale:hover {
            transform: translate(-50%, -50%) scale(1.1);
            transition: 0.3s ease;
        }
        .object-fit-cover {
            object-fit: cover;
        }
    </style>

    {{-- 2. Panel Kontrol Admin (Hanya muncul jika login sebagai Admin) --}}
    @auth
        @if(auth()->user()->isAdmin())
        <div class="card shadow-sm mb-5 admin-panel-card border-0 bg-light">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-shield-lock-fill me-2 text-danger"></i>Panel Kontrol Admin
                        </h5>
                        <p class="text-muted small mb-0">Kelola stok produk dan pengaturan tampilan beranda</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <div class="d-grid d-md-inline-block gap-2">
                            {{-- Route untuk Tambah Bundling --}}
                            <a href="{{ route('bundling.create') }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bundling
                            </a>
                            {{-- FIX: Route admin.judul.edit agar tidak error Route Not Found --}}
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

    {{-- 3. Section Produk Terbaru --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4">
            <i class="bi bi-stars text-warning me-2"></i>
            {{ $settings['judul_terbaru'] ?? 'Produk Terbaru' }}
        </h4>
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            @forelse($produk_terbaru as $item)
            <div class="col-auto card-mobile-width"> 
                @include('partials.item_produk', ['item' => $item])
            </div>
            @empty
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada produk untuk ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </section>
    {{-- 4. Section Paket Bundling Hemat --}}
    @if(isset($bundlings) && $bundlings->count() > 0)
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4" style="color: #800000;">
            <i class="bi bi-box-seam me-2"></i> Paket Bundling Hemat
        </h4>
        
        {{-- Menggunakan class flex-nowrap agar bisa di-scroll ke samping seperti Produk Terbaru --}}
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            @foreach($bundlings as $b)
            <div class="col-auto card-mobile-width">
                <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden; background: white; transition: 0.3s;">
                    
                    {{-- Bagian Gambar / Ikon Box --}}
                    <div class="position-relative d-flex justify-content-center align-items-center bg-light" style="height: 150px;">
                        <span class="badge position-absolute" style="top: 10px; left: 10px; background-color: #e63946; border-radius: 8px; font-size: 10px; padding: 4px 8px; z-index: 2;">
                            Promo
                        </span>
                        <i class="bi bi-boxes" style="font-size: 4rem; color: #dee2e6;"></i>
                    </div>

                    {{-- Info Bundling --}}
                    <div class="card-body p-3 d-flex flex-column">
                        <p class="text-muted small mb-1" style="font-size: 11px;">Spesial Trenmart</p>
                        <h6 class="fw-bold text-dark text-truncate mb-2" style="font-size: 14px;" title="{{ $b->name }}">
                            {{ $b->name }}
                        </h6>
                        
                        {{-- Isi Paket Singkat --}}
                        <div class="mb-2 flex-grow-1" style="font-size: 11px; color: #6c757d; line-height: 1.4;">
                            @foreach($b->items->take(2) as $item)
                                <div class="text-truncate">• {{ $item->produk->nama_produk ?? 'Produk Dihapus' }}</div>
                            @endforeach
                            @if($b->items->count() > 2)
                                <div>• ...dan lainnya</div>
                            @endif
                        </div>
                        
                        {{-- Harga & Tombol --}}
                        <div class="mt-auto pt-2">
                            <div class="text-muted text-decoration-line-through mb-0" style="font-size: 11px;">
                                Rp {{ number_format($b->total_normal_price, 0, ',', '.') }}
                            </div>
                            <h5 class="fw-bold mb-3" style="color: #800000; font-size: 1.1rem;">
                                Rp {{ number_format($b->bundling_price, 0, ',', '.') }}
                            </h5>
                            
                            {{-- Sesuaikan action route dengan fitur keranjang milikmu --}}
                            <form action="#" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn w-100 fw-bold shadow-sm" style="background-color: #800000; color: white; border-radius: 10px; font-size: 12px; padding: 8px;">
                                    <i class="bi bi-cart-plus me-1"></i> Beli Paket
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
</div>

<script>
    // Otomatis submit form saat file banner dipilih
    const bannerInput = document.getElementById('bannerInput');
    if(bannerInput) {
        bannerInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('bannerForm').submit();
            }
        });
    }
</script>

<style>
    /* Animasi Banner Hover */
    .hover-scale:hover {
        transform: translate(-50%, -50%) scale(1.1);
        transition: 0.3s ease;
    }
    .object-fit-cover {
        object-fit: cover;
    }

    /* Mengatur tinggi banner agar tidak terlalu besar di HP */
    .img-banner-responsive { 
        height: 160px; 
        object-fit: cover; 
    }
    
    @media (min-width: 768px) { 
        .img-banner-responsive { height: auto; } 
    }

    /* Mengatur lebar kartu produk agar bisa di-scroll menyamping di HP */
    .card-mobile-width { width: 165px; }
    
    @media (min-width: 768px) { 
        .card-mobile-width { width: 220px; } 
    }

    /* Menghilangkan scrollbar tapi tetap bisa di-scroll */
    .custom-scrollbar { 
        scrollbar-width: none; 
        -ms-overflow-style: none; 
    }
    .custom-scrollbar::-webkit-scrollbar { 
        display: none; 
    }

    .flex-nowrap { 
        scroll-snap-type: x mandatory; 
        -webkit-overflow-scrolling: touch; 
    }
    
    .col-auto { 
        scroll-snap-align: start; 
    }
</style>
@endsection