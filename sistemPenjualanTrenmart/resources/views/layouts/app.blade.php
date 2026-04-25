<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - PT Tren Abadi Stationeri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon-trenmart: #800000; }
        html { overflow-y: scroll; }

        /* --- NAVBAR --- */
        .navbar { padding-top: 12px !important; padding-bottom: 12px !important; background-color: #ffffff !important; }
        .navbar-brand img { height: 48px; }
        .navbar-nav { margin-left: auto !important; margin-right: auto !important; }

        .nav-link { 
            font-weight: 600; font-size: 1.05rem; color: #444 !important; 
            padding: 8px 18px !important; transition: 0.2s; position: relative;
        }
        
        .nav-link:hover, .nav-link.active { color: var(--maroon-trenmart) !important; }

        /* Efek Garis Bawah Menu Aktif */
        @media (min-width: 992px) {
            .nav-link.active::after {
                content: ""; position: absolute; bottom: 2px; left: 18px; right: 18px;
                height: 3px; background-color: var(--maroon-trenmart); border-radius: 10px;
            }
        }

        /* --- SEARCH BAR --- */
        .search-bar { 
            border-radius: 50px 0 0 50px !important; background-color: #f3f4f6 !important; 
            border: 1px solid #e5e7eb !important; padding-left: 20px; height: 42px; width: 220px; flex: 0 0 220px; transition: background-color 0.3s, border-color 0.3s;
        }
        .search-bar:focus { background-color: #fff !important; border-color: var(--maroon-trenmart) !important; box-shadow: none; outline: none; }
        .btn-search { border-radius: 0 50px 50px 0 !important; background-color: var(--maroon-trenmart) !important; color: white !important; height: 42px; border: none; padding: 0 18px; }

        /* --- ICONS & DROPDOWN --- */
        .icon-nav { font-size: 1.5rem; color: #333; transition: 0.2s; text-decoration: none; }
        .icon-nav:hover { color: var(--maroon-trenmart); }
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 10px; margin-top: 15px !important; }

        @media (max-width: 991px) {
            .navbar-nav { margin-left: 0 !important; margin-bottom: 15px; text-align: center; }
            .search-bar { width: 100%; flex: 1 1 auto; }
            .nav-link.active::after { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('beranda') }}">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                </li>
                
                {{-- LOGIKA NAVIGASI PRODUK BERDASARKAN ROLE --}}
                <li class="nav-item">
                    @auth
                        @if(auth()->user()->isAdmin())
                            {{-- Admin ke halaman Manajemen Stok --}}
                            <a class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Produk</a>
                        @else
                            {{-- Pelanggan ke halaman Katalog Belanja --}}
                            <a class="nav-link {{ Request::is('katalog*') ? 'active' : '' }}" href="{{ route('katalog') }}">Produk</a>
                        @endif
                    @else
                        {{-- Guest ke halaman Katalog Belanja --}}
                        <a class="nav-link {{ Request::is('katalog*') ? 'active' : '' }}" href="{{ route('katalog') }}">Produk</a>
                    @endauth
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('pesanan*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tentang*') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang Kami</a>
                </li>
            </ul>

            <div class="d-flex align-items-center ms-auto">
                {{-- Form Cari (Hanya muncul di Desktop) --}}
                <form class="d-flex me-3 d-none d-lg-flex" action="{{ Auth::check() && Auth::user()->isAdmin() ? route('produk.index') : route('katalog') }}" method="GET">
                    <div class="input-group">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari barang..." value="{{ request('search') }}">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                {{-- Ikon Keranjang (Hanya untuk non-admin) --}}
                @if(!Auth::check() || (Auth::check() && !Auth::user()->isAdmin()))
                    <a href="{{ route('cart.index') }}" class="me-3 position-relative icon-nav">
                        <i class="bi bi-cart3"></i>
                    </a>
                @endif

                {{-- Menu User --}}
                <div class="dropdown">
                    <a href="#" class="icon-nav" id="userMenu" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @auth
                            <li>
                                <div class="dropdown-header text-dark border-bottom mb-2">
                                    <div class="fw-bold">Halo, {{ auth()->user()->name }}</div>
                                    @if(auth()->user()->isAdmin())
                                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle mt-1">Admin</span>
                                    @elseif(auth()->user()->customer_type === 'langganan')
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">Pelanggan Langganan</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle mt-1">Pelanggan Umum</span>
                                    @endif
                                </div>
                            </li>
                            <li><a class="dropdown-item rounded-3" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                            
                            @if(auth()->user()->isAdmin())
                                <li><a class="dropdown-item rounded-3 text-primary" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
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