<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Trenmart - PT Tren Abadi Stationeri</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon-trenmart: #800000; }
        
        /* --- STICKY FOOTER CORE --- */
        html, body { height: 100%; }
        body { 
            display: flex; 
            flex-direction: column; 
            background-color: #f8f9fa; 
        }
        /* Mendorong footer ke bawah jika konten sedikit */
        main.main-container { 
            flex: 1 0 auto; 
        }
        /* Pastikan footer tidak menyusut */
        .main-footer { 
            flex-shrink: 0; 
        }

        html { overflow-y: scroll; }

        /* --- NAVBAR --- */
        .navbar { padding-top: 12px !important; padding-bottom: 12px !important; background-color: #ffffff !important; }
        .navbar-brand img { height: 40px; transition: 0.3s; }
        .navbar-nav { margin-left: auto !important; margin-right: auto !important; }
        .nav-link { font-weight: 600; font-size: 1.05rem; color: #444 !important; padding: 8px 18px !important; transition: 0.2s; position: relative; }
        .nav-link:hover, .nav-link.active { color: var(--maroon-trenmart) !important; }

        @media (min-width: 992px) {
            .navbar-brand img { height: 48px; }
            .nav-link.active::after {
                content: ""; position: absolute; bottom: 2px; left: 18px; right: 18px;
                height: 3px; background-color: var(--maroon-trenmart); border-radius: 10px;
            }
        }

        /* --- SEARCH BAR --- */
        .search-bar { border-radius: 50px 0 0 50px !important; background-color: #f3f4f6 !important; border: 1px solid #e5e7eb !important; padding-left: 20px; height: 42px; width: 100%; transition: 0.3s; }
        @media (min-width: 992px) { .search-bar { width: 220px; flex: 0 0 220px; } }
        .search-bar:focus { background-color: #fff !important; border-color: var(--maroon-trenmart) !important; box-shadow: none; outline: none; }
        .btn-search { border-radius: 0 50px 50px 0 !important; background-color: var(--maroon-trenmart) !important; color: white !important; height: 42px; border: none; padding: 0 18px; }

        /* --- ICONS & DROPDOWN --- */
        .icon-nav { font-size: 1.4rem; color: #333; transition: 0.2s; text-decoration: none; display: flex; align-items: center; cursor: pointer; }
        .icon-nav:hover { color: var(--maroon-trenmart); }
        .notification-link { position: relative; }
        .notification-badge {
            position: absolute;
            top: -6px;
            right: -8px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            background: #d11a1a;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            line-height: 18px;
            text-align: center;
            box-shadow: 0 0 0 2px #fff;
        }
        .notification-menu { min-width: 320px; padding: 0; overflow: hidden; }
        .notification-menu .dropdown-header { background: linear-gradient(135deg, #fff7f7 0%, #ffffff 100%); padding: 16px 18px; }
        .notification-menu .dropdown-body { padding: 16px 18px 18px; }
        .notification-menu .notification-title { color: #800000; font-weight: 700; font-size: 0.95rem; }
        .notification-menu .notification-text { color: #4b5563; font-size: 0.92rem; line-height: 1.5; }
        .notification-menu .btn-admin-dashboard { background: var(--maroon-trenmart); border-color: var(--maroon-trenmart); }
        .dropdown-menu { border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); padding: 10px; margin-top: 15px !important; z-index: 1050; }

        /* --- FOOTER STYLE --- */
        .main-footer { background-color: var(--maroon-trenmart); color: #ffffff; padding: 50px 0 20px; margin-top: auto; }
        .footer-content h5 { font-weight: 700; margin-bottom: 20px; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 1px; }
        .footer-info-item { display: flex; align-items: flex-start; margin-bottom: 12px; }
        .footer-info-item i { font-size: 1.1rem; margin-right: 12px; color: rgba(255, 255, 255, 0.8); }
        
        @media (min-width: 992px) {
            .footer-divider { border-left: 1px solid rgba(255, 255, 255, 0.2); padding-left: 40px; height: 100%; }
        }

        .table-jam { color: rgba(255, 255, 255, 0.9); font-size: 0.875rem; width: 100%; border-collapse: collapse; }
        .table-jam td { padding: 2px 0; vertical-align: top; }
        .td-hari { width: 110px; }
        .td-pemisah { width: 15px; }

        .social-box { width: 40px; height: 40px; background-color: rgba(255, 255, 255, 0.1); display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; color: white; margin-right: 12px; transition: all 0.3s ease; text-decoration: none; }
        .social-box:hover { background-color: rgba(255, 255, 255, 0.25); transform: translateY(-3px); color: white; }
        .border-top-footer { border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 25px; margin-top: 40px; }
        
        /* --- SCROLLBAR MAROON CUSTOM --- */
        /* 1. STANDAR FIREFOX */
        .custom-scrollbar {
            scrollbar-width: thin !important; 
            scrollbar-color: #800000 #f1f1f1 !important; 
        }

        /* 2. STANDAR CHROME, SAFARI, EDGE, & MOBILE */
        .custom-scrollbar::-webkit-scrollbar {
            height: 10px !important;
            display: block !important;
            -webkit-appearance: none !important;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background-color: #f1f1f1 !important; 
            border-radius: 10px !important;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #800000 !important; 
            border-radius: 10px !important;
            border: 2px solid #f1f1f1 !important; 
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { 
            background-color: #600000 !important; 
        }

        /* --- MODERN FLASH TOAST --- */
        .flash-toast-shell {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3000;
            width: min(420px, calc(100vw - 2rem));
            pointer-events: none;
            animation: flashToastIn 320ms cubic-bezier(.2,.9,.2,1.1) both;
        }
        .flash-toast-card {
            pointer-events: auto;
            border: 1px solid rgba(255,255,255,0.22);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 18px 50px rgba(0,0,0,0.24);
            border-radius: 22px;
            overflow: hidden;
        }
        .flash-toast-card.success {
            background: linear-gradient(135deg, rgba(20, 184, 106, 0.96) 0%, rgba(16, 163, 91, 0.96) 100%);
        }
        .flash-toast-card.error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.96) 0%, rgba(190, 24, 93, 0.96) 100%);
        }
        .flash-toast-badge {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.16);
            flex: 0 0 44px;
        }
        .flash-toast-title {
            font-size: 0.72rem;
            letter-spacing: 0.12em;
        }
        .flash-toast-body {
            font-size: 0.95rem;
            line-height: 1.45;
        }
        .flash-toast-progress {
            height: 3px;
            width: 100%;
            background: rgba(255,255,255,0.18);
            overflow: hidden;
        }
        .flash-toast-progress::before {
            content: "";
            display: block;
            height: 100%;
            width: 100%;
            background: rgba(255,255,255,0.9);
            transform-origin: left;
            animation: flashToastProgress 2500ms linear forwards;
        }
        @keyframes flashToastIn {
            from { opacity: 0; transform: translate(-50%, -42%) scale(0.94); }
            to { opacity: 1; transform: translate(-50%, -50%) scale(1); }
        }
        @keyframes flashToastOut {
            from { opacity: 1; transform: translate(-50%, -50%) scale(1); }
            to { opacity: 0; transform: translate(-50%, -56%) scale(0.96); }
        }
        @keyframes flashToastProgress {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

        @media (max-width: 576px) {
            .flash-toast-shell { top: 42%; }
        }

        /* --- RESPONSIVE --- */
        @media (max-width: 991px) {
            .navbar-nav { margin-top: 15px; margin-bottom: 15px; text-align: left; }
            .nav-link { padding: 12px 0 !important; border-bottom: 1px solid #f1f1f1; }
            .navbar-collapse { background: white; padding: 15px; border-radius: 10px; }
        }

        .main-container { width: 100%; padding-right: .75rem; padding-left: .75rem; margin-right: auto; margin-left: auto; }
        .main-container.full-width-page {
            width: calc(100vw - 1rem) !important;
            max-width: calc(100vw - 1rem) !important;
        }
        @media (min-width: 576px) { .main-container { max-width: 540px; } }
        @media (min-width: 768px) { .main-container { max-width: 720px; } }
        @media (min-width: 992px) { .main-container { max-width: 960px; } }
        @media (min-width: 1200px) { .main-container { max-width: 1140px; } }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ route('beranda') }}">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <div class="d-flex d-lg-none ms-auto me-2 align-items-center">
            @if(!Auth::check() || (Auth::check() && Auth::user()->isCustomer()))
                <?php $cartCount = Auth::check() ? \App\Models\Keranjang::where('user_id', Auth::id())->sum('jumlah') : 0; ?>
                <a href="{{ route('cart.index') }}" class="me-3 icon-nav position-relative">
                    <i class="bi bi-cart3"></i>
                    @if($cartCount > 0)
                        <span id="cart-count" class="notification-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    @else
                        <span id="cart-count" class="notification-badge" style="display:none">0</span>
                    @endif
                </a>
            @endif
        </div>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('beranda') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    @auth
                        @if(auth()->user()->isInternalStaff())
                            <a class="nav-link {{ Request::is('admin/produk*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Produk</a>
                        @else
                            <a class="nav-link {{ Request::is('katalog*') ? 'active' : '' }}" href="{{ route('katalog') }}">Produk</a>
                        @endif
                    @else
                        <a class="nav-link {{ Request::is('katalog*') ? 'active' : '' }}" href="{{ route('katalog') }}">Produk</a>
                    @endauth
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ auth()->check() && auth()->user()->isInternalStaff() ? (Request::is('admin/orders*') ? 'active' : '') : (Request::is('pesanan*') ? 'active' : '') }}"
                       href="{{ auth()->check() && auth()->user()->isInternalStaff() ? route('admin.orders.index') : route('pesanan.index') }}">
                        Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('tentang*') ? 'active' : '' }}" href="{{ route('tentang') }}">Tentang Kami</a>
                </li>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center ms-auto">
                <form class="d-flex mb-3 mb-lg-0 me-lg-3 w-100" action="{{ Auth::check() && Auth::user()->isInternalStaff() ? route('produk.index') : route('katalog') }}" method="GET">
                    <div class="input-group w-100">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <div class="d-flex align-items-center justify-content-between justify-content-lg-end">
                    @if(!Auth::check() || (Auth::check() && Auth::user()->isCustomer()))
                        <a href="{{ route('cart.index') }}" class="me-3 position-relative icon-nav d-none d-lg-flex">
                            <i class="bi bi-cart3"></i>
                            @if(isset($cartCount) && $cartCount > 0)
                                <span id="cart-count" class="notification-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                            @else
                                <span id="cart-count" class="notification-badge" style="display:none">0</span>
                            @endif
                        </a>
                    @endif

                    @auth
                    <div class="dropdown me-3 d-none d-lg-flex">
                        <a href="#" class="icon-nav notification-link" id="notificationMenu" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                            <i class="bi bi-bell"></i>
                            @php 
                                // Menghitung total notifikasi (Sistem + Peringatan Bundling)
                                $totalNotif = ($notificationUnreadCount ?? 0) + (isset($bundling_warnings) ? $bundling_warnings->count() : 0);
                            @endphp
                            @if($totalNotif > 0)
                                <span class="notification-badge">{{ $totalNotif > 9 ? '9+' : $totalNotif }}</span>
                            @endif
                        </a>
                        
                        <div class="dropdown-menu dropdown-menu-end notification-menu shadow-sm" aria-labelledby="notificationMenu" style="min-width: 340px;">
                            
                            {{-- Header Dropdown --}}
                            <div class="dropdown-header border-bottom d-flex justify-content-between align-items-center py-3 bg-light">
                                <span class="fw-bold text-dark">Notifikasi</span>
                                @if(($notificationUnreadCount ?? 0) > 0)
                                    <form action="{{ route('notifications.mark_all_read') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-decoration-none small text-primary" style="font-size: 0.8rem;">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- Body Dropdown --}}
                            <div class="dropdown-body p-2 custom-scrollbar" style="max-height: 380px; overflow-y: auto;">
                                
                                {{-- 1. Peringatan Harga Bundling (Khusus Admin) --}}
                                @if(auth()->user()->isAdmin() && isset($bundling_warnings) && $bundling_warnings->count() > 0)
                                    @foreach($bundling_warnings as $bw)
                                        <a href="{{ route('bundling.show', $bw->id) }}" class="d-block text-decoration-none mb-2">
                                            <div class="p-2 rounded-3 border border-warning bg-warning-subtle text-wrap">
                                                <div class="d-flex align-items-start gap-2">
                                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5 mt-1"></i>
                                                    <div>
                                                        <div class="fw-bold small text-dark">Harga Produk Berubah!</div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">Paket: {{ $bw->name }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                {{-- 2. Notifikasi Sistem Lainnya --}}
                                @forelse(($recentNotifications ?? collect()) as $notification)
                                    @php
                                        $payload = $notification->data ?? [];
                                        $isUnread = is_null($notification->read_at);
                                    @endphp
                                    <a href="{{ $payload['url'] ?? '#' }}" class="d-block text-decoration-none mb-2">
                                        <div class="p-2 rounded-3 border {{ $isUnread ? 'border-primary bg-primary-subtle' : 'border-light bg-white hover-bg-light' }}">
                                            <div class="d-flex align-items-start justify-content-between gap-2">
                                                <div>
                                                    <div class="fw-semibold text-dark small">{{ $payload['title'] ?? 'Notifikasi' }}</div>
                                                    <div class="text-muted" style="font-size: 0.8rem;">{{ $payload['body'] ?? '' }}</div>
                                                </div>
                                                @if($isUnread)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.65rem;">Baru</span>
                                                @endif
                                            </div>
                                            <div class="text-muted mt-1" style="font-size: 0.7rem;">
                                                {{ $notification->created_at ? $notification->created_at->diffForHumans() : '' }}
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    {{-- Tampilkan pesan kosong hanya jika tidak ada notifikasi sistem DAN tidak ada peringatan bundling --}}
                                    @if(!isset($bundling_warnings) || $bundling_warnings->count() == 0)
                                        <div class="text-center text-muted py-4" style="font-size: 0.85rem;">
                                            Belum ada notifikasi.
                                        </div>
                                    @endif
                                @endforelse
                                
                            </div>
                        </div>
                    </div>
                    @endauth

                    <div class="dropdown">
                        <a href="#" class="icon-nav" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span class="ms-2 d-lg-none">Akun Saya</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            @auth
                                <li>
                                    <div class="dropdown-header text-dark border-bottom mb-2">
                                        <div class="fw-bold">Halo, {{ auth()->user()->name }}</div>
                                                @if(auth()->user()->isOwner())
                                                    <span class="badge rounded-pill bg-dark text-white border border-dark mt-1">Pemilik</span>
                                                @elseif(auth()->user()->isAdmin())
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis border border-primary-subtle mt-1">Admin</span>
                                                @elseif(auth()->user()->isCashier())
                                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">Kasir</span>
                                        @elseif(auth()->user()->customer_type === 'langganan')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle mt-1">Pelanggan Langganan</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle mt-1">Pelanggan Umum</span>
                                        @endif
                                    </div>
                                </li>
                                <li><a class="dropdown-item rounded-3" href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                                @if(auth()->user()->role === 'admin')
                                    <li><a class="dropdown-item rounded-3" href="{{ route('admin.shipping.edit') }}"><i class="bi bi-truck me-2"></i>Pengaturan Ongkir</a></li>
                                @endif
                                @if(auth()->user()->isOwner())
                                    <li><a class="dropdown-item rounded-3 text-primary" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Pemilik</a></li>
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
    </div>
</nav>

@if(session('success') || session('error'))
    <div id="floatingFlashMessage" class="position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 10600;">
        <div class="toast show align-items-center text-white {{ session('error') ? 'bg-danger' : 'bg-success' }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <div class="fw-bold mb-1">{{ session('error') ? 'Gagal' : 'Berhasil' }}</div>
                    <div class="small">{{ session('error') ?? session('success') }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" id="closeFloatingFlash"></button>
            </div>
        </div>
    </div>
@endif
<div id="statusToast" class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999; display: none;">
    <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                ✅ Status berhasil diperbarui!
            </div>
        </div>
    </div>
</div>
<main class="main-container {{ isset($page) && in_array($page, ['all', 'internal', 'customers'], true) ? 'full-width-page' : '' }} mt-4 mb-5">
    @yield('content')
</main>

<footer class="main-footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4">
                <h5>Hubungi Kami</h5>
                <div class="footer-info-item">
                    <i class="bi bi-geo-alt"></i>
                    <span class="small opacity-90">
                        Jl. Jenderal Ahmad Yani, Tangga Takat, Kec. Seberang Ulu II, Palembang, Sumatera Selatan. 30265
                    </span>
                </div>
                <div class="footer-info-item">
                    <i class="bi bi-whatsapp"></i>
                    <span class="small opacity-90">0821-7850-4488</span>
                </div>
            </div>

            <div class="col-lg-4 footer-divider">
                <h5>Jam Operasional</h5>
                <div class="footer-info-item">
                    <i class="bi bi-clock"></i>
                    <table class="table-jam">
                        <tr>
                            <td class="td-hari">Senin - Jumat</td>
                            <td class="td-pemisah">:</td>
                            <td>08.00 - 21.00</td>
                        </tr>
                        <tr>
                            <td class="td-hari">Sabtu</td>
                            <td class="td-pemisah">:</td>
                            <td>08.00 - 20.00</td>
                        </tr>
                        <tr>
                            <td class="td-hari">Minggu</td>
                            <td class="td-pemisah">:</td>
                            <td>09.00 - 20.00</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-lg-4 footer-divider">
                <h5>Ikuti Kami</h5>
                <p class="small opacity-75 mb-3">Tetap terhubung dengan PT Tren Abadi Stationeri melalui media sosial resmi kami.</p>
                <div class="d-flex">
                    <a href="https://www.instagram.com/tren.mart?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="social-box">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@trenmart_1?is_from_webapp=1&sender_device=pc" target="_blank" class="social-box">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center border-top-footer">
            <p class="footer-text mb-0 text-white opacity-75 small">
                © 2026 PT TREN ABADI STATIONERI. Hak Cipta Dilindungi.
            </p>
            <div class="mt-2">
                <small class="opacity-50" style="font-size: 0.7rem;">v1.0.4-stable</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

@if(session('success') || session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flash = document.getElementById('floatingFlashMessage');
        const closeButton = document.getElementById('closeFloatingFlash');

        if (closeButton) {
            closeButton.addEventListener('click', function () {
                if (flash) flash.remove();
            });
        }

        if (flash) {
            // auto remove after 2.5s
            setTimeout(function () {
                if (flash) flash.remove();
            }, 2500);
        }
    });
</script>
@endif
</body>
@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function showBadge(count) {
        const el = document.getElementById('cart-count');
        if (!el) return;
        if (count > 0) {
            el.style.display = 'inline-block';
            el.textContent = count > 99 ? '99+' : count;
        } else {
            el.style.display = 'none';
        }
    }

    // Attach to all add-to-cart forms
    document.querySelectorAll('form.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = form.getAttribute('action');
            const formData = new FormData(form);

            fetch(action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : document.querySelector('input[name="_token"]').value
                },
                body: formData,
                credentials: 'same-origin'
            }).then(async response => {
                const contentType = response.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') !== -1) {
                    const data = await response.json();
                    if (data.success) {
                        showBadge(data.cartCount || 0);
                        // Optional: small toast
                        const toast = document.createElement('div');
                        toast.className = 'flash-toast-shell';
                        toast.innerHTML = `<div class="flash-toast-card success p-3"><div class="d-flex align-items-center"><div class="flash-toast-badge me-3">✓</div><div><div class="flash-toast-title">Berhasil</div><div class="flash-toast-body">Produk ditambahkan ke keranjang.</div></div></div><div class="flash-toast-progress mt-3"></div></div>`;
                        document.body.appendChild(toast);
                        setTimeout(()=>{ toast.remove(); }, 2000);
                    } else if (data.message) {
                        alert(data.message);
                    }
                } else {
                    // Jika menerima halaman login (redirect), pindah ke halaman tersebut
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    // Fallback: reload untuk menampilkan perubahan
                    window.location.reload();
                }
            }).catch(err => {
                console.error(err);
                window.location.reload();
            });
        });
    });
});
</script>
</html>