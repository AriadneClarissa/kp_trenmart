<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon: #800000; }
        /* Style Navbar */
        .navbar-brand img { height: 40px; }
        .nav-link { font-weight: 600; color: #333; }
        .nav-link.active { color: var(--maroon) !important; }
        .search-bar { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; }
        
        /* Style Konten */
        .banner-wrapper { position: relative; border-radius: 20px; overflow: hidden; border: 3px solid #00a2e8; }
        .card-produk { border-radius: 15px; border: none; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .btn-tambah-cart { background-color: var(--maroon); color: white; border-radius: 10px; width: 100%; border: none; padding: 5px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>
            <form class="d-flex me-3" role="search">
                <div class="input-group">
                    <input class="form-control search-bar" type="search" placeholder="Cari...">
                    <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-dark"><i class="bi bi-cart3 fs-4"></i></a>
                <a href="{{ route('login') }}" class="text-dark"><i class="bi bi-person-circle fs-4"></i></a>
            </div>
        </div>
    </div>
</nav>
<div class="container mt-4">
    <div class="banner-wrapper mb-4">
        <img src="{{ asset('images/spanduktoko.png') }}" class="w-100" alt="Banner">
    </div>

    @auth
        @if(auth()->user()->isAdmin())
        <div class="card shadow-sm border-0 mb-5" style="border-radius: 20px;">
            <div class="card-body p-4 text-center text-md-start">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-0">Panel Kontrol Admin</h5>
                        <p class="text-muted small">Kelola Inventaris dan Katalog Toko</p>
                    </div>
                    <div class="col-md-8 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.produk.create') }}" class="btn btn-success rounded-pill px-4">Tambah Produk</a>
                        <button class="btn btn-warning rounded-pill px-4 fw-bold">Edit / Hapus Produk</button>
                        <button class="btn btn-info text-white rounded-pill px-4">Kelola Kategori</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth

    <h5 class="text-center fw-bold mb-4">Produk Terbaru</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4">
        @foreach($produk_terbaru as $item)
        <div class="col">
            <div class="card h-100 card-produk text-center p-2">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top mx-auto p-2" style="height: 120px; object-fit: contain;">
                <div class="card-body">
                    <p class="small mb-1">{{ $item->nama_produk }}</p>
                    <p class="fw-bold">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</p>
                    <button class="btn-tambah-cart">Tambah</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>