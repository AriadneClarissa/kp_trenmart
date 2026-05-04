@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row g-4">
        {{-- Sisi Kiri: Slider Gambar Produk --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div id="productCarousel" class="carousel slide bg-light" data-bs-ride="carousel" style="border-radius: 15px; overflow: hidden;">
                        
                        {{-- Indikator (Titik-titik di bawah foto) --}}
                        @if($produk->foto_2 || $produk->foto_3)
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="0" class="active bg-dark"></button>
                            @if($produk->foto_2) <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="1" class="bg-dark"></button> @endif
                            @if($produk->foto_3) <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="2" class="bg-dark"></button> @endif
                        </div>
                        @endif

                        <div class="carousel-inner" style="height: 450px;">
                            {{-- Foto Utama --}}
                            <div class="carousel-item active h-100">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <img src="{{ asset('storage/' . $produk->gambar) }}" 
                                         class="img-fluid main-product-image" 
                                         alt="{{ $produk->nama_produk }}"
                                         style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                </div>
                            </div>

                            {{-- Foto Tambahan 2 --}}
                            @if($produk->foto_2)
                            <div class="carousel-item h-100">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <img src="{{ asset('storage/' . $produk->foto_2) }}" 
                                         class="img-fluid main-product-image" 
                                         style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                </div>
                            </div>
                            @endif

                            {{-- Foto Tambahan 3 --}}
                            @if($produk->foto_3)
                            <div class="carousel-item h-100">
                                <div class="d-flex align-items-center justify-content-center h-100">
                                    <img src="{{ asset('storage/' . $produk->foto_3) }}" 
                                         class="img-fluid main-product-image" 
                                         style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Tombol Geser (Muncul jika ada lebih dari 1 foto) --}}
                        @if($produk->foto_2 || $produk->foto_3)
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>

                    {{-- Thumbnail Kecil di Bawah Carousel --}}
                    @if($produk->foto_2 || $produk->foto_3)
                    <div class="row mt-3 g-2 justify-content-center">
                        <div class="col-2">
                            <img src="{{ asset('storage/' . $produk->gambar) }}" class="img-fluid border rounded cursor-pointer opacity-hover" onclick="goToSlide(0)" style="height: 50px; width: 100%; object-fit: cover;">
                        </div>
                        @if($produk->foto_2)
                        <div class="col-2">
                            <img src="{{ asset('storage/' . $produk->foto_2) }}" class="img-fluid border rounded cursor-pointer opacity-hover" onclick="goToSlide(1)" style="height: 50px; width: 100%; object-fit: cover;">
                        </div>
                        @endif
                        @if($produk->foto_3)
                        <div class="col-2">
                            <img src="{{ asset('storage/' . $produk->foto_3) }}" class="img-fluid border rounded cursor-pointer opacity-hover" onclick="goToSlide(2)" style="height: 50px; width: 100%; object-fit: cover;">
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Detail & Aksi --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    {{-- Merk & Judul --}}
                    <div class="mb-3">
                        <span class="badge bg-secondary mb-2" style="border-radius: 6px;">{{ $produk->merk->nama_merk ?? 'Tanpa Merk' }}</span>
                        <h2 class="fw-bold text-dark mb-1">{{ $produk->nama_produk }}</h2>
                        <p class="text-muted small">Kategori: {{ $produk->kategori->nama_kategori ?? 'Tidak ada kategori' }}</p>
                    </div>

                    {{-- Harga --}}
                    <h3 class="fw-bold mb-4" style="color: #800000;">
                        Rp {{ number_format($produk->harga_tampil, 0, ',', '.') }}
                        <small class="text-muted fw-normal fs-6">/{{ $produk->satuanModel->nama_satuan ?? 'pcs' }}</small>
                    </h3>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <p class="mb-4 fw-semibold" style="color: #f08a24; font-size: 1rem;">
                                Langganan: Rp {{ number_format($produk->harga_jual_langganan ?? $produk->harga_jual_umum, 0, ',', '.') }}
                            </p>
                        @endif
                    @endauth
                    
                    {{-- Status Stok --}}
                    @if($produk->stok_tersedia > 0)
                        <div class="mb-4">
                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle" style="border-radius: 8px;">
                                <i class="bi bi-check-circle-fill me-1"></i>Stok Tersedia: {{ $produk->stok_tersedia }}
                            </span>
                        </div>
                    @else
                        <div class="mb-4">
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle" style="border-radius: 8px;">
                                <i class="bi bi-x-circle-fill me-1"></i>Stok Habis
                            </span>
                        </div>
                    @endif

                    {{-- Deskripsi --}}
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark">Deskripsi Produk</h6>
                        <p class="text-muted" style="line-height: 1.6;">
                            {{ $produk->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    <hr class="my-4 opacity-25">

                    {{-- BAGIAN AKSI --}}
                    <div class="action-section">
                        @auth
                            @if(auth()->user()->isAdmin())
                                <div class="alert alert-secondary border-0 d-flex align-items-center" style="border-radius: 12px; background-color: #f8f9fa;">
                                    <i class="bi bi-shield-lock-fill fs-4 me-3 text-dark"></i>
                                    <div>
                                        <div class="fw-bold">Mode Admin</div>
                                        <small class="text-muted">Gunakan tombol di bawah untuk mengelola data produk.</small>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('produk.edit', $produk->kd_produk) }}" class="btn btn-warning py-3 fw-bold rounded-3 shadow-sm">
                                        <i class="bi bi-pencil-square me-2"></i>Edit Data Produk
                                    </a>
                                </div>
                            @else
                                @if($produk->stok_tersedia > 0)
                                    <form action="{{ route('cart.add', $produk->kd_produk) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-buy w-100 py-3 shadow-sm">
                                            <i class="bi bi-cart-plus fs-5 me-2"></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button class="btn w-100 py-3 bg-light text-muted border fw-bold" disabled style="border-radius: 12px;">
                                        <i class="bi bi-cart-x fs-5 me-2"></i> Stok Habis
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-buy w-100 py-3 shadow-sm">
                                <i class="bi bi-box-arrow-in-right fs-5 me-2"></i> Login untuk Membeli
                            </a>
                        @endauth
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ request('from') === 'produk.index' ? route('produk.index') : (request('from') === 'katalog' ? route('katalog') : route('beranda')) }}" class="text-decoration-none text-muted small hover-maroon">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .opacity-hover:hover { opacity: 0.7; transition: 0.3s; }
    .carousel-control-prev, .carousel-control-next { width: 10%; }
    
    .card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    
    .btn-buy {
        background-color: #800000;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: 0.3s;
        border: none;
    }

    .btn-buy:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
    }

    .hover-maroon:hover { color: #800000 !important; }
</style>

<script>
    function goToSlide(index) {
        var myCarousel = document.querySelector('#productCarousel');
        var carousel = bootstrap.Carousel.getOrCreateInstance(myCarousel);
        carousel.to(index);
    }
</script>
@endsection