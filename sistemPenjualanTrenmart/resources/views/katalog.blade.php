<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Katalog Produk</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('beranda') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('katalog') ? 'active' : '' }}" href="{{ route('katalog') }}">Katalog</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
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

    <div class="container mt-4 mb-5">
        <h2 class="fw-bold mb-4">Kategori Produk</h2>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 10px;">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Kategori</h6>
                    <ul class="list-unstyled mt-2">
                        <li class="mb-2">
                            <a href="{{ route('katalog') }}" class="text-decoration-none {{ !request('kategori') ? 'text-maroon fw-bold' : 'text-dark' }}">
                                Semua Kategori
                            </a>
                        </li>
                        @foreach($kategori as $kat)
                            <li class="mb-2">
                                <a href="{{ route('katalog', ['kategori' => $kat->kd_kategori, 'merk' => request('merk')]) }}" 
                                   class="text-decoration-none {{ request('kategori') == $kat->kd_kategori ? 'text-maroon fw-bold' : 'text-dark' }}">
                                    {{ $kat->nama_kategori }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="card border-0 shadow-sm p-3" style="border-radius: 10px;">
                    <h6 class="fw-bold border-bottom pb-2 mb-3">Merk</h6>
                    <ul class="list-unstyled mt-2">
                        <li class="mb-2">
                            <a href="{{ route('katalog') }}" class="text-decoration-none {{ !request('merk') ? 'text-maroon fw-bold' : 'text-dark' }}">
                                Semua Merk
                            </a>
                        </li>
                        @foreach($merk as $m)
                            <li class="mb-2">
                                <a href="{{ route('katalog', ['merk' => $m->kd_merk, 'kategori' => request('kategori')]) }}" 
                                   class="text-decoration-none {{ request('merk') == $m->kd_merk ? 'text-maroon fw-bold' : 'text-dark' }}">
                                    {{ $m->nama_merk }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row row-cols-2 row-cols-md-4 g-4">
                    @forelse($produk as $item)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm text-center card-produk">
                                <div class="p-3 bg-light" style="border-radius: 10px 10px 0 0;">
                                    <img src="{{ $item->gambar ? asset('storage/'.$item->gambar) : 'https://via.placeholder.com/150' }}" 
                                         class="card-img-top mx-auto" style="height: 120px; object-fit: contain;">
                                </div>
                                <div class="card-body">
                                    <p class="small fw-semibold mb-1" style="height: 40px; overflow: hidden;">{{ $item->nama_produk }}</p>
                                    
                                    <p class="fw-bold text-dark mb-1">Rp {{ number_format($item->harga_tampil, 0, ',', '.') }}</p>
                                    
                                    <small class="text-muted d-block mb-3" style="font-size: 10px;">{{ $item->label_status }}</small>
                                    
                                    <button class="btn btn-tambah btn-sm w-100">Tambah</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-search fs-1 text-muted"></i>
                            <p class="text-muted mt-2">Produk tidak ditemukan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>