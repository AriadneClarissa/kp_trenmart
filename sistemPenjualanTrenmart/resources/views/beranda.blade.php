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
        
        /* Navbar Styles */
        .navbar { z-index: 1050; } /* Agar dropdown tidak tertutup banner */
        .navbar-brand img { height: 40px; }
        .nav-link { font-weight: 600; color: #333; }
        .search-bar { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; border: none; }

        .nav-link.active { 
            color: var(--maroon) !important; 
            border-bottom: 2px solid var(--maroon); 
        }
        
        /* Content Styles */
        .banner-wrapper { 
            position: relative; 
            border-radius: 20px; 
            overflow: hidden; 
        }
        .card-produk { 
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
            transition: transform 0.2s;
        }
        .card-produk:hover { transform: translateY(-5px); }
        .btn-tambah-cart { 
            background-color: var(--maroon); 
            color: white; 
            border-radius: 10px; 
            width: 100%; 
            border: none; 
            padding: 8px; 
            font-weight: 600;
        }
        
        /* Dropdown Profile Style */
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/katalog">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>

            <form class="d-flex me-3" role="search">
                <div class="input-group">
                    <input class="form-control search-bar" type="search" placeholder="Cari produk...">
                    <button class="btn btn-search px-3" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

                <div class="dropdown">
                    <a href="#" class="text-dark" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" aria-labelledby="userMenu">
                        @auth
                            <li><div class="dropdown-header fw-bold text-dark">Halo, {{ auth()->user()->name }}</div></li>
                            <li><a class="dropdown-item rounded-3" href="#"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item rounded-3 text-primary" href="/admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item rounded-3" href="{{ route('login') }}">Masuk</a></li>
                            <li><a class="dropdown-item rounded-3" href="{{ route('register') }}">Daftar</a></li>
                        @endauth
                    </ul>
                </div>
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
        <div class="card shadow-sm border-0 mb-5 bg-light" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="fw-bold mb-0 text-maroon"><i class="bi bi-gear-fill me-2"></i>Panel Kontrol Admin</h5>
                        <p class="text-muted small mb-0">Atur produk dan stok toko Anda</p>
                    </div>
                    <div class="col-md-8 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('admin.tambah.beranda') }}" class="btn btn-success rounded-pill px-4 me-2">Tambah Produk</a>
                        <button class="btn btn-warning rounded-pill px-4 me-2">Edit Katalog</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endauth

    <h5 class="text-center fw-bold mb-4">Produk Terbaru</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4 mb-5">
        @forelse($produk_terbaru as $item)
        <div class="col">
            <div class="card h-100 card-produk text-center p-2">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top mx-auto p-2" style="height: 140px; object-fit: contain;" alt="{{ $item->nama_produk }}">
                <div class="card-body p-2">
                    <p class="small mb-1 text-truncate">{{ $item->nama_produk }}</p>
                    <p class="fw-bold text-maroon mb-2">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</p>
                    <button class="btn-tambah-cart shadow-sm">Tambah</button>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 w-100 text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Belum ada produk tersedia.</p>
                </div>
            </div>
        @endforelse
    </div>
    <h5 class="text-center fw-bold mb-4">Produk Terpopuler</h5>
    <div class="row row-cols-2 row-cols-md-5 g-4 mb-5">
        @forelse($produk_terpopuler as $item)
        <div class="col">
            <div class="card h-100 card-produk text-center p-2">
                <img src="{{ asset('storage/' . $item->gambar) }}" class="card-img-top mx-auto p-2" style="height: 140px; object-fit: contain;" alt="{{ $item->nama_produk }}">
                <div class="card-body p-2">
                    <p class="small mb-1 text-truncate">{{ $item->nama_produk }}</p>
                    <p class="fw-bold text-maroon mb-2">Rp {{ number_format($item->harga_jual_umum, 0, ',', '.') }}</p>
                    <button class="btn-tambah-cart shadow-sm">Tambah</button>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 w-100 text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Belum ada produk tersedia.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        function previewImage(input) {
            const preview = document.getElementById('img-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>