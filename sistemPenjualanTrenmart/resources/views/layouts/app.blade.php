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
        
        /* FIX: Memaksa scrollbar selalu ada agar navbar tidak bergeser saat pindah halaman */
        html {
            overflow-y: scroll;
        }

        /* Navbar Core */
        .navbar-brand img { height: 40px; }
        
        /* Penyesuaian Margin Menu agar tetap konsisten */
        .navbar-nav { margin-left: 227px !important; } 
        
        .nav-link { font-weight: 600; color: #333; transition: 0.2s; position: relative; }
        
        /* Styling Garis Bawah Aktif */
        .nav-link.active { 
            color: var(--maroon-trenmart) !important; 
        }
        .nav-link.active::after {
            content: "";
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--maroon-trenmart);
        }

        /* Search Bar Presisi */
        .search-bar { 
            border-radius: 50px 0 0 50px !important; 
            background-color: #f1f1f1 !important; 
            border: none !important; 
            padding-left: 20px; 
            height: 35px; 
        }

        .btn-search { 
            border-radius: 0 50px 50px 0 !important; 
            background-color: var(--maroon-trenmart) !important; 
            color: white !important; 
            border: none !important; 
            padding: 0 15px !important;
            height: 35px;
            display: flex;
            align-items: center;
        }

        /* Card & UI Utility */
        .card-produk { border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.08); transition: 0.2s; }
        .card-produk:hover { transform: translateY(-5px); }
        
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    </style>
    @stack('styles')
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
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') || Request::is('admin*') ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('katalog*') || Request::is('produk*') ? 'active' : '' }}" href="/katalog">Produk</a>
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
                <form class="d-flex me-3" action="/katalog" method="GET">
                    <div class="input-group">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari...">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                {{-- Ikon Keranjang (Hanya untuk Non-Admin atau Guest) --}}
                @if(!auth()->check() || (auth()->check() && !auth()->user()->isAdmin()))
                    <a href="/keranjang" class="text-dark me-3 position-relative">
                        <i class="bi bi-cart3 fs-4"></i>
                    </a>
                @endif

                {{-- Menu User --}}
                <div class="dropdown">
                    <a href="#" class="text-dark" id="userMenu" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-4"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2 mt-2">
                        @auth
                            <li><div class="dropdown-header fw-bold">Halo, {{ auth()->user()->name }}</div></li>
                            <li><a class="dropdown-item rounded-3" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item rounded-3 text-primary" href="/admin/dashboard"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-3 text-danger">Keluar</button>
                                </form>
                            </li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Masuk</a></li>
                            <li><a class="dropdown-item" href="{{ route('register') }}">Daftar</a></li>
                        @endauth
                    </ul>
                </div>
            </div> {{-- Tutup d-flex --}}
        </div> {{-- Tutup collapse --}}
    </div> {{-- Tutup container --}}
</nav>

<main>
    @yield('content')
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>