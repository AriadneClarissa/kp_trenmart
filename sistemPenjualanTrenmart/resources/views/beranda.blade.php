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

    {{-- 2. Panel Kontrol Admin --}}
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
                            <a href="{{ route('bundling.create', ['source' => 'beranda']) }}" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bundling
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

    {{-- 3. Section Produk Terbaru --}}
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-4 fs-md-3">
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

    {{-- 4. SECTION BUNDLING --}}
    <section class="mt-5 pt-3">
        <div class="text-center mb-5">
            <h4 class="fw-bold mb-2 fs-4 fs-md-3">
                <i class="bi bi-box2-heart text-danger me-2"></i> Paket Bundling Hemat
            </h4>
            <p class="text-muted small">Dapatkan kombinasi produk terbaik dengan harga lebih murah!</p>
        </div>

        <div class="row g-4 justify-content-center">
            @forelse($bundling as $b)
                <div class="col-md-6 col-lg-4">
                    {{-- Tambahkan position-relative pada card agar link mencakup seluruh area card --}}
                    <div class="card h-100 border-0 shadow-sm card-bundling-hover position-relative" style="border-radius: 20px;">
                        <div class="card-body p-3 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                {{-- Gunakan class stretched-link di sini --}}
                                <a href="{{ route('bundling.show', $b->id) }}" class="text-decoration-none stretched-link">
                                    <h5 class="fw-bold text-dark mb-0 hover-maroon">{{ $b->name }}</h5>
                                </a>
                            </div>

                            <div class="bg-light p-3 rounded-4 mb-4">
                                <label class="small fw-bold text-primary mb-2 d-block">Isi Paket:</label>
                                <ul class="list-unstyled mb-0">
                                    @foreach($b->items as $item)
                                        <li class="small d-flex align-items-center mb-2">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <span>
                                                {{ $item->produk->nama_produk }} 
                                                <small class="text-muted">({{ $item->produk->merk->nama_merk ?? 'Tanpa Merk' }})</small>
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <small class="text-muted text-decoration-line-through">
                                        Rp {{ number_format($b->total_normal_price, 0, ',', '.') }}
                                    </small>
                                    @if($b->total_normal_price > $b->bundling_price)
                                        <span class="badge bg-light text-danger border border-danger small" style="font-size: 0.7rem;">
                                            Hemat Rp {{ number_format($b->total_normal_price - $b->bundling_price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="fw-bold text-danger mb-0">
                                        Rp {{ number_format($b->bundling_price, 0, ',', '.') }}
                                    </h4>
                                    
                                    {{-- Tombol Tambah (khusus pelanggan) --}}
                                    @if(!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin'))
                                        {{-- Gunakan span karena aksi klik sudah diambil alih oleh stretched-link di atas --}}
                                        <span class="btn-tambah-card shadow-sm d-flex align-items-center justify-content-center" style="position: relative; z-index: 2;">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted italic">Belum ada paket bundling untuk saat ini.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>

{{-- SCRIPT & STYLE --}}
<script>
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
    .hover-scale:hover { transform: translate(-50%, -50%) scale(1.1); transition: 0.3s ease; }
    .object-fit-cover { object-fit: cover; }
    .img-banner-responsive { height: 160px; object-fit: cover; }
    @media (min-width: 768px) { .img-banner-responsive { height: 300px; } }

    .card-mobile-width { width: 165px; }
    @media (min-width: 768px) { .card-mobile-width { width: 220px; } }

    .custom-scrollbar { scrollbar-width: none; -ms-overflow-style: none; }
    .custom-scrollbar::-webkit-scrollbar { display: none; }
    .flex-nowrap { scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; }
    .col-auto { scroll-snap-align: start; }

    .card-bundling-hover:hover {
        transform: translateY(-5px);
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Style untuk tombol Tambah di Card */
    .btn-tambah-card {
        background-color: #800000; /* Warna maroon Trenmart */
        color: white;
        border-radius: 8px; /* Bentuk tidak terlalu bulat (bukan pill) */
        font-size: 0.9rem; /* Ukuran font disesuaikan agar rapi */
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .btn-tambah-card:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2) !important;
    }
</style>
@endsection