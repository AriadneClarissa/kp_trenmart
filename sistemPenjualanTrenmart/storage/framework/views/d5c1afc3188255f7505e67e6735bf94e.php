<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Trenmart - PT Tren Abadi Stationeri</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon-trenmart: #800000; }
        
        html, body { height: 100%; }
        body { 
            display: flex; 
            flex-direction: column; 
            background-color: #f8f9fa; 
        }
        main.main-container { flex: 1 0 auto; }
        .main-footer { flex-shrink: 0; }
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
        .notification-menu { min-width: 320px; padding: 0; overflow: hidden; border-radius: 15px !important; border: none !important; box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
        .dropdown-item:active { background-color: var(--maroon-trenmart); }

        /* --- FOOTER --- */
        .main-footer { background-color: var(--maroon-trenmart); color: #ffffff; padding: 50px 0 20px; margin-top: auto; }
        .footer-content h5 { font-weight: 700; margin-bottom: 20px; text-transform: uppercase; font-size: 0.95rem; letter-spacing: 1px; }
        
        /* --- FLASH TOAST --- */
        .flash-toast-shell { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 3000; width: min(420px, calc(100vw - 2rem)); pointer-events: none; }
        .flash-toast-card { pointer-events: auto; backdrop-filter: blur(16px); border-radius: 22px; overflow: hidden; box-shadow: 0 18px 50px rgba(0,0,0,0.24); }
        .flash-toast-card.success { background: linear-gradient(135deg, rgba(20, 184, 106, 0.96) 0%, rgba(16, 163, 91, 0.96) 100%); }
        .flash-toast-card.error { background: linear-gradient(135deg, rgba(239, 68, 68, 0.96) 0%, rgba(190, 24, 93, 0.96) 100%); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
            <img src="<?php echo e(asset('images/logotrenmart.png')); ?>" alt="Logo">
        </a>
        
        <div class="d-flex d-lg-none ms-auto me-2 align-items-center">
            <?php if(!Auth::check() || (Auth::check() && !Auth::user()->isAdmin())): ?>
                <?php $cartCount = Auth::check() ? \App\Models\Keranjang::where('user_id', Auth::id())->sum('jumlah') : 0; ?>
                <a href="<?php echo e(route('cart.index')); ?>" class="me-3 icon-nav position-relative">
                    <i class="bi bi-cart3"></i>
                    <?php if($cartCount > 0): ?>
                        <span id="cart-count" class="notification-badge"><?php echo e($cartCount > 99 ? '99+' : $cartCount); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link <?php echo e(Request::is('/') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Beranda</a></li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/produk*') || Request::is('katalog*') ? 'active' : ''); ?>" 
                       href="<?php echo e(auth()->check() && auth()->user()->isAdmin() ? route('produk.index') : route('katalog')); ?>">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/orders*') || Request::is('pesanan*') ? 'active' : ''); ?>"
                       href="<?php echo e(auth()->check() && auth()->user()->isAdmin() ? route('admin.orders.index') : route('pesanan.index')); ?>">Pesanan</a>
                </li>
                <li class="nav-item"><a class="nav-link <?php echo e(Request::is('tentang*') ? 'active' : ''); ?>" href="<?php echo e(route('tentang')); ?>">Tentang Kami</a></li>
            </ul>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center ms-auto">
                <form class="d-flex mb-3 mb-lg-0 me-lg-3 w-100" action="<?php echo e(Auth::check() && Auth::user()->isAdmin() ? route('produk.index') : route('katalog')); ?>" method="GET">
                    <div class="input-group w-100">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari produk..." value="<?php echo e(request('search')); ?>">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>

                <div class="d-flex align-items-center justify-content-between justify-content-lg-end">
                    <?php if(!Auth::check() || (Auth::check() && !Auth::user()->isAdmin())): ?>
                        <a href="<?php echo e(route('cart.index')); ?>" class="me-3 position-relative icon-nav d-none d-lg-flex">
                            <i class="bi bi-cart3"></i>
                            <?php if(isset($cartCount) && $cartCount > 0): ?>
                                <span id="cart-count" class="notification-badge"><?php echo e($cartCount > 99 ? '99+' : $cartCount); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>

                    <?php if(auth()->guard()->check()): ?>
                        
                        <div class="dropdown me-3 d-none d-lg-flex">
                            <a href="#" class="icon-nav notification-link" id="notificationMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell"></i>
                                <?php 
                                    $totalNotif = ($notificationUnreadCount ?? 0) + (isset($bundling_warnings) ? $bundling_warnings->count() : 0);
                                ?>
                                <?php if($totalNotif > 0): ?>
                                    <span class="notification-badge"><?php echo e($totalNotif > 9 ? '9+' : $totalNotif); ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationMenu">
                                <div class="dropdown-header border-bottom d-flex justify-content-between align-items-center py-3 bg-light">
                                    <span class="fw-bold text-dark">Notifikasi</span>
                                    <?php if(($notificationUnreadCount ?? 0) > 0): ?>
                                        <form action="<?php echo e(route('notifications.mark_all_read')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-link p-0 text-decoration-none small" style="font-size: 0.7rem;">Baca Semua</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                <div class="p-2" style="max-height: 380px; overflow-y: auto;">
                                    
                                    <?php if(auth()->user()->isAdmin() && isset($bundling_warnings) && $bundling_warnings->count() > 0): ?>
                                        <?php $__currentLoopData = $bundling_warnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e(route('bundling.show', $bw->id)); ?>" class="dropdown-item p-2 mb-2 rounded-3 border border-warning bg-warning-subtle text-wrap">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-5"></i>
                                                    <div>
                                                        <div class="fw-bold small text-dark">Harga Produk Berubah!</div>
                                                        <div class="text-muted" style="font-size: 0.75rem;">Paket: <?php echo e($bw->name); ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>

                                    
                                    <?php $__empty_1 = true; $__currentLoopData = ($recentNotifications ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <?php $payload = $notification->data; ?>
                                        <a href="<?php echo e($payload['url'] ?? '#'); ?>" class="dropdown-item p-2 mb-1 rounded-3 <?php echo e(is_null($notification->read_at) ? 'bg-primary-subtle' : ''); ?>">
                                            <div class="fw-semibold small"><?php echo e($payload['title'] ?? 'Info'); ?></div>
                                            <div class="text-muted x-small text-truncate" style="font-size: 0.7rem;"><?php echo e($payload['body'] ?? ''); ?></div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php if(!isset($bundling_warnings) || $bundling_warnings->count() == 0): ?>
                                            <div class="text-center py-4 text-muted small">Tidak ada notifikasi</div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="dropdown">
                            <a href="#" class="icon-nav" id="userMenu" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold small"><?php echo e(auth()->user()->name); ?></div>
                                    <span class="badge bg-light text-dark border small mt-1"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                                </li>
                                <li><a class="dropdown-item small mt-2" href="<?php echo e(route('profile.edit')); ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <li><a class="dropdown-item small text-primary" href="<?php echo e(route('admin.dashboard')); ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="dropdown-item small text-danger"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-maroon btn-sm rounded-pill px-3">Masuk</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</nav>


<?php if(session('success') || session('error')): ?>
    <div class="flash-toast-shell" id="floatingFlashMessage">
        <div class="flash-toast-card <?php echo e(session('error') ? 'error' : 'success'); ?> text-white p-3 p-md-4">
            <div class="d-flex align-items-center gap-3">
                <i class="bi <?php echo e(session('error') ? 'bi-exclamation-triangle' : 'bi-check-circle'); ?> fs-3"></i>
                <div>
                    <div class="fw-bold small opacity-75 text-uppercase">Informasi</div>
                    <div class="fw-medium"><?php echo e(session('error') ?? session('success')); ?></div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<main class="main-container mt-4">
    <?php echo $__env->yieldContent('content'); ?>
</main>

<footer class="main-footer">
    <div class="container text-center">
        <p class="small opacity-75 mb-0">© 2026 PT TREN ABADI STATIONERI. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const flash = document.getElementById('floatingFlashMessage');
        if (flash) {
            setTimeout(() => {
                flash.style.opacity = '0';
                flash.style.transition = '0.5s';
                setTimeout(() => flash.remove(), 500);
            }, 3000);
        }
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/layouts/app.blade.php ENDPATH**/ ?>