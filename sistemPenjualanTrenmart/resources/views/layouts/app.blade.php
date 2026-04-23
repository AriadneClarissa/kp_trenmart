<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon-trenmart: #800000; }
        
        /* Memaksa scrollbar agar layout tidak goyang saat pindah halaman */
        html { overflow-y: scroll; }

        /* --- NAVBAR ENHANCEMENT --- */
        .navbar {
            padding-top: 12px !important;    /* Memberi ruang atas */
            padding-bottom: 12px !important; /* Memberi ruang bawah */
            background-color: #ffffff !important;
            transition: all 0.3s ease;
        }

        /* Logo lebih tegas */
        .navbar-brand img { 
            height: 48px; 
            transition: 0.3s;
        }
        
        /* Menu Navigasi di Tengah & Lebih Besar */
        .navbar-nav { 
            margin-left: auto !important; 
            margin-right: auto !important; 
        }

        .nav-link { 
            font-weight: 600; 
            font-size: 1.05rem; /* Font sedikit lebih besar */
            color: #444 !important; 
            padding: 8px 18px !important; /* Jarak antar menu lebih lega */
            transition: 0.2s; 
            position: relative;
        }
        
        /* Efek Active & Hover */
        .nav-link:hover, .nav-link.active { 
            color: var(--maroon-trenmart) !important; 
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: 2px;
            left: 18px;
            right: 18px;
            height: 3px;
            background-color: var(--maroon-trenmart);
            border-radius: 10px;
        }

        /* --- SEARCH BAR MODERNIZE --- */
        .search-bar { 
            border-radius: 50px 0 0 50px !important; 
            background-color: #f3f4f6 !important; 
            border: 1px solid #e5e7eb !important; 
            padding-left: 20px; 
            height: 42px; /* Lebih tinggi agar seimbang */
            width: 200px;
            transition: 0.3s;
        }

        .search-bar:focus {
            background-color: #fff !important;
            border-color: var(--maroon-trenmart) !important;
            box-shadow: none;
            width: 250px; /* Melebar saat diklik */
        }

        .btn-search { 
            border-radius: 0 50px 50px 0 !important; 
            background-color: var(--maroon-trenmart) !important; 
            color: white !important; 
            border: none !important; 
            padding: 0 18px !important;
            height: 42px;
            display: flex;
            align-items: center;
        }

        /* --- UTILITY --- */
        .icon-nav {
            font-size: 1.5rem;
            color: #333;
            transition: 0.2s;
        }
        
        .icon-nav:hover {
            color: var(--maroon-trenmart);
        }

        .dropdown-menu { 
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            padding: 10px;
        }

        /* Responsif untuk HP */
        @media (max-width: 991px) {
            .navbar-nav { margin-left: 0 !important; margin-bottom: 15px; }
            .search-bar { width: 100%; }
            .nav-link.active::after { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin/produk*') || Request::is('katalog*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('pesanan*') ? 'active' : '' }}" href="#">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tentang*') ? 'active' : '' }}" href="#">Tentang Kami</a>
                </li>
            </ul>

            <div class="d-flex align-items-center ms-auto">
                {{-- Form Cari --}}
                <form class="d-flex me-3 d-none d-lg-flex" action="/katalog" method="GET">
                    <div class="input-group">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari barang..." aria-label="Search">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                {{-- Ikon Keranjang --}}
                @if(!auth()->check() || (auth()->check() && !auth()->user()->isAdmin()))
                    <a href="/keranjang" class="me-3 position-relative icon-nav">
                        <i class="bi bi-cart3"></i>
                        {{-- Contoh Badge Notif (Opsional) --}}
                        {{-- <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">2</span> --}}
                    </a>
                @endif

                {{-- Menu User --}}
                <div class="dropdown">
                    <a href="#" class="icon-nav" id="userMenu" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-3">
                        @auth
                            <li><div class="dropdown-header fw-bold text-dark">Halo, {{ auth()->user()->name }}</div></li>
                            <li><a class="dropdown-item rounded-3" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item rounded-3 text-primary" href="/admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item rounded-3" href="{{ route('login') }}">Masuk</a></li>
                            <li><a class="dropdown-item rounded-3" href="{{ route('register') }}">Daftar Baru</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>