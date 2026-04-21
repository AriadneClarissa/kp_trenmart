<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Katalog Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon: #800000; --light-bg: #f8f9fa; }
        body { background-color: var(--light-bg); font-family: 'Segoe UI', sans-serif; }

        /* Navbar Styles (Sesuai Beranda) */
        .navbar-brand img { height: 40px; }
        .nav-link { font-weight: 600; color: #333; }
        .nav-link.active { color: var(--maroon) !important; border-bottom: 2px solid var(--maroon); }
        .search-bar-nav { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; border: none; }

        /* Sidebar Category */
        .sidebar-card { border: none; border-radius: 15px; background: white; overflow: hidden; }
        .list-group-item { border: none; padding: 12px 20px; font-size: 14px; color: #666; }
        .list-group-item.active { background-color: #f8e7e7 !important; color: var(--maroon) !important; font-weight: 600; }
        .list-group-item:hover:not(.active) { background-color: #f1f1f1; }

        /* Filter Controls */
        .filter-input { border-radius: 10px; border: 1px solid #ddd; padding: 10px; font-size: 14px; }
        .filter-select { border-radius: 10px; border: 1px solid #ddd; padding: 10px; font-size: 14px; background-color: white; }

        /* Product Card */
        .card-produk { border: none; border-radius: 15px; transition: 0.3s; background: white; }
        .card-produk:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .img-container { background-color: #fff; border-radius: 12px; padding: 15px; position: relative; }
        .badge-stok { position: absolute; top: 10px; left: 10px; font-size: 10px; border-radius: 5px; padding: 4px 8px; }
        .bg-tersedia { background-color: #d4edda; color: #155724; }
        .bg-habis { background-color: #f8d7da; color: #721c24; }
        .bg-terbatas { background-color: #fff3cd; color: #856404; }
        
        .brand-text { font-size: 11px; color: #999; margin-bottom: 5px; }
        .product-name { font-size: 15px; font-weight: 600; color: #333; height: 45px; overflow: hidden; }
        .price-text { color: var(--maroon); font-weight: bold; font-size: 16px; }
        
        .btn-tambah { background-color: var(--maroon); color: white; border-radius: 10px; border: none; width: 100%; padding: 8px; font-weight: 600; transition: 0.2s; }
        .btn-tambah:hover { background-color: #600000; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/"><img src="{{ asset('images/logotrenmart.png') }}" alt="Logo"></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link active" href="/katalog">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-dark"><i class="bi bi-cart3 fs-4"></i></a>
                <a href="{{ route('login') }}" class="text-dark"><i class="bi bi-person-circle fs-4"></i></a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar-card shadow-sm">
                <div class="p-3 fw-bold border-bottom">Kategori</div>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action active">Semua</a>
                    @foreach($kategori as $kat)
                        <a href="?kategori={{ $kat->kd_kategori }}" class="list-group-item list-group-item-action">{{ $kat->nama_kategori }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h3 class="fw-bold mb-4">Katalog Produk</h3>
            
            <div class="row g-3 mb-4 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 filter-input"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 filter-input" placeholder="Cari produk...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-select">
                        <option selected>Semua Merek</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->kd_merk }}">{{ $m->nama_merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select filter-select">
                        <option selected>Status Stok</option>
                        <option>Tersedia</option>
                        <option>Habis</option>
                    </select>
                </div>
                <div class="col-md-2 text-end text-muted small">
                    {{ count($produk) }} produk ditemukan
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-4 g-4">
                @foreach($produk as $item)
                <div class="col">
                    <div class="card h-100 card-produk p-3">
                        <div class="img-container">
                            @if($item->stok > 10)
                                <span class="badge-stok bg-tersedia">Tersedia</span>
                            @elseif($item->stok > 0)
                                <span class="badge-stok bg-terbatas">Terbatas</span>
                            @else
                                <span class="badge-stok bg-habis">Stok Habis</span>
                            @endif

                            <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid d-block mx-auto" style="height: 150px; object-fit: contain;">
                        </div>
                        <div class="mt-3">
                            <div class="brand-text">{{ $item->merk->nama_merk ?? 'Tanpa Merk' }}</div>
                            <div class="product-name">{{ $item->nama_produk }}</div>
                            <div class="price-text mt-2 mb-3">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</div>
                            
                            <a href="{{ route('login') }}" class="btn btn-tambah">
                                <i class="bi bi-plus"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>