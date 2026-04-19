<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Beranda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-2 border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('beranda') }}">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo Trenmart">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                {{-- Logika: Jika sedang di route 'beranda', tambahkan class 'active' --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}" href="{{ route('katalog') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pesanan') ? 'active' : '' }}" href="{{ route('pesanan') }}">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tentang') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang Kami</a>
                </li>
            </ul>
            
            <div class="d-flex align-items-center">
                <form action="{{ route('produk.search') }}" method="GET" class="d-flex me-3">
                    <input type="text" name="query" class="form-control search-bar" placeholder="Cari...">
                    <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <a href="/keranjang" class="text-dark me-3"><i class="bi bi-cart3 fs-4"></i></a>
                <a href="/login" class="text-dark"><i class="bi bi-person fs-4"></i></a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false">
        <div class="carousel-inner shadow-sm" style="border-radius: 20px;">
            <div class="carousel-item active">
                <img src="{{ asset('images/spanduktoko.png') }}" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/bannertokodepan.png') }}" class="d-block w-100" alt="Banner 2">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<div class="container mt-5 mb-5">
    <h5 class="text-center fw-bold mb-4">Produk Terbaru</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4">
        @forelse($produk as $item)
            <div class="col">
                <div class="card h-100 text-center card-produk">
                    <div class="p-3 bg-light" style="border-radius: 15px 15px 0 0;">
                        <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : 'https://via.placeholder.com/150' }}" 
                             class="card-img-top mx-auto" style="height: 120px; object-fit: contain;">
                    </div>
                    <div class="card-body">
                        <p class="card-title fw-semibold mb-1 small">{{ $item->nama_produk }}</p>
                        <p class="fw-bold mb-2">Rp {{ number_format($item->harga_tampil, 0, ',', '.') }}</p>
                        <form action="/keranjang/tambah" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->kd_produk }}">
                            <button type="submit" class="btn btn-tambah">Tambah</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted small">Belum ada produk yang tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>