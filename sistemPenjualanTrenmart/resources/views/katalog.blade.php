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

        /* Navbar Styles */
        .navbar-brand img { height: 40px; }
        .nav-link { font-weight: 600; color: #333; }
        .nav-link.active { color: var(--maroon) !important; border-bottom: 2px solid var(--maroon); }
        .search-bar-nav { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; border: none; }

        /* Sidebar Category */
        .sidebar-card { border: none; border-radius: 15px; background: white; overflow: hidden; }
        .list-group-item { border: none; padding: 12px 20px; font-size: 14px; color: #666; transition: 0.2s; }
        .list-group-item.active { background-color: #f8e7e7 !important; color: var(--maroon) !important; font-weight: 600; }
        .list-group-item:hover:not(.active) { background-color: #f1f1f1; padding-left: 25px; }

        /* Filter Controls */
        .filter-input { border-radius: 10px; border: 1px solid #ddd; padding: 10px; font-size: 14px; }
        .filter-select { border-radius: 10px; border: 1px solid #ddd; padding: 10px; font-size: 14px; background-color: white; }

        /* Product Card */
        .card-produk { border: none; border-radius: 15px; transition: 0.3s; background: white; height: 100%; }
        .card-produk:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .img-container { background-color: #fff; border-radius: 12px; padding: 15px; position: relative; min-height: 180px; display: flex; align-items: center; }
        
        .badge-stok { position: absolute; top: 10px; left: 10px; font-size: 10px; border-radius: 5px; padding: 4px 8px; font-weight: bold; }
        .bg-tersedia { background-color: #d4edda; color: #155724; }
        .bg-habis { background-color: #f8d7da; color: #721c24; }
        .bg-terbatas { background-color: #fff3cd; color: #856404; }
        
        .brand-text { font-size: 11px; color: #999; margin-bottom: 5px; text-transform: uppercase; font-weight: bold; }
        .product-name { font-size: 15px; font-weight: 600; color: #333; height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        .price-text { color: var(--maroon); font-weight: bold; font-size: 17px; }
        
        .btn-tambah { background-color: var(--maroon); color: white; border-radius: 10px; border: none; width: 100%; padding: 10px; font-weight: 600; transition: 0.2s; }
        .btn-tambah:hover { background-color: #600000; transform: scale(1.02); }
        .btn-tambah.disabled { background-color: #ccc; cursor: not-allowed; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link active" href="/katalog">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('pesanan.index') }}">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('cart.index') }}" class="text-dark position-relative">
                    <i class="bi bi-cart3 fs-4"></i>
                </a>
                <a href="{{ route('login') }}" class="text-dark">
                    <i class="bi bi-person-circle fs-4"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="sidebar-card shadow-sm">
                <div class="p-3 fw-bold border-bottom bg-light">Kategori</div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('katalog') }}" class="list-group-item list-group-item-action {{ !request('kategori') ? 'active' : '' }}">Semua Produk</a>
                    @foreach($kategori as $kat)
                        <a href="?kategori={{ $kat->kd_kategori }}" 
                           class="list-group-item list-group-item-action {{ request('kategori') == $kat->kd_kategori ? 'active' : '' }}">
                            {{ $kat->nama_kategori }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <h3 class="fw-bold mb-4">Katalog Produk</h3>
            
            <form action="{{ route('produk.search') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-5">
                    <div class="input-group shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="query" class="form-control border-0 filter-input" placeholder="Cari alat tulis..." value="{{ request('query') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="merk" class="form-select filter-select shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Merek</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->kd_merk }}" {{ request('merk') == $m->kd_merk ? 'selected' : '' }}>{{ $m->nama_merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="stok" class="form-select filter-select shadow-sm" onchange="this.form.submit()">
                        <option value="">Status Stok</option>
                        <option value="tersedia" {{ request('stok') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="habis" {{ request('stok') == 'habis' ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>
                <div class="col-md-2 text-end text-muted small">
                    <span class="fw-bold text-dark">{{ count($produk) }}</span> produk
                </div>
            </form>

            <div class="row row-cols-2 row-cols-md-4 g-4">
                @forelse($produk as $item)
                <div class="col">
                    <div class="card card-produk p-3">
                        <div class="img-container">
                            @if($item->stok_tersedia > 10)
                                <span class="badge-stok bg-tersedia">Tersedia</span>
                            @elseif($item->stok_tersedia > 0)
                                <span class="badge-stok bg-terbatas">Terbatas</span>
                            @else
                                <span class="badge-stok bg-habis">Stok Habis</span>
                            @endif

                            <img src="{{ asset('storage/' . $item->gambar) }}" class="img-fluid d-block mx-auto" style="max-height: 140px; object-fit: contain;" alt="{{ $item->nama_produk }}">
                        </div>
                        <div class="mt-3">
                            <div class="brand-text">{{ $item->merk->nama_merk ?? 'Generic' }}</div>
                            <div class="product-name" title="{{ $item->nama_produk }}">{{ $item->nama_produk }}</div>
                            
                            <div class="price-text mt-2 mb-3">Rp {{ number_format($item->harga_tampil, 0, ',', '.') }}</div>
                            
                            @auth
                                <form action="{{ route('cart.add', $item->kd_produk) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-tambah shadow-sm {{ $item->stok_tersedia <= 0 ? 'disabled' : '' }}" {{ $item->stok_tersedia <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-1"></i> Tambah
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-tambah shadow-sm d-flex align-items-center justify-content-center">
                                    <i class="bi bi-cart-plus me-1"></i> Tambah
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search text-muted fs-1"></i>
                    <p class="mt-3 text-muted">Maaf, produk tidak ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>