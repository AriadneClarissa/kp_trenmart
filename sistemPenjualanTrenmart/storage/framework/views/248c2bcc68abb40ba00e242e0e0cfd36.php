<style>
    :root { --maroon: #660000; }
    .admin-header { background-color: #f8f9fa; border-bottom: 2px solid #e9ecef; }
    .admin-header h2 { color: var(--maroon); font-weight: bold; }
    .admin-nav-btn { 
        border-radius: 50px; 
        padding: 0.5rem 1rem; 
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
        min-width: 148px;
        min-height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .admin-header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
        justify-content: flex-end;
    }
    .admin-header-actions .admin-nav-btn {
        flex: 0 0 auto;
    }
    .admin-nav-btn.active {
        background-color: var(--maroon);
        color: white;
        border-color: var(--maroon);
        border-width: 2px;
    }
    .admin-nav-btn.active:hover {
        background-color: #550000;
        border-color: #550000;
    }
    .admin-nav-btn:not(.active) {
        border: 2px solid #dee2e6;
        color: #495057;
    }
    .admin-nav-btn:not(.active):hover {
        border-color: #adb5bd;
        background-color: #f8f9fa;
    }
</style>

<div class="admin-header py-3 mb-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">
                <?php if(auth()->user()->isOwner()): ?>
                    Panel Pemilik
                <?php elseif(method_exists(auth()->user(), 'isCashier') && auth()->user()->isCashier()): ?>
                    Panel Kasir
                <?php else: ?>
                    Panel Admin
                <?php endif; ?>
                - Trenmart
            </h2>
            <div class="admin-header-actions">
                <?php if(method_exists(auth()->user(), 'isCashier') && auth()->user()->isCashier()): ?>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-sm admin-nav-btn <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-receipt me-1"></i> Pesanan
                    </a>
                    <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-sm admin-nav-btn <?php echo e(request()->routeIs('produk.*') ? 'active' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-box-seam me-1"></i> Produk
                    </a>
                <?php elseif(auth()->user()->isOwner()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm admin-nav-btn <?php echo e($activePage === 'users' ? 'active btn-outline-secondary' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-people me-1"></i> Semua Pengguna
                    </a>
                    <a href="<?php echo e(route('admin.users.internal')); ?>" class="btn btn-sm admin-nav-btn <?php echo e($activePage === 'internal_users' ? 'active btn-outline-secondary' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-shield-lock me-1"></i> User Internal
                    </a>
                <?php endif; ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <a href="<?php echo e(route('admin.customers.index')); ?>" class="btn btn-sm admin-nav-btn <?php echo e($activePage === 'customers' ? 'active btn-outline-secondary' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-person-badge me-1"></i> Pelanggan
                    </a>
                    <a href="<?php echo e(route('admin.payment_methods.index')); ?>" class="btn btn-sm admin-nav-btn <?php echo e($activePage === 'payment' ? 'active btn-outline-secondary' : 'btn-outline-secondary'); ?>">
                        <i class="bi bi-credit-card-2-back me-1"></i> Metode Pembayaran
                    </a>
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-sm btn-primary admin-nav-btn">
                        <i class="bi bi-person-plus me-1"></i> Buat Pelanggan
                    </a>
                    <a href="<?php echo e(route('admin.admins.create')); ?>" class="btn btn-sm btn-warning admin-nav-btn text-dark">
                        <i class="bi bi-shield-check me-1"></i> Buat User Internal
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('beranda')); ?>" class="btn btn-sm btn-outline-secondary admin-nav-btn">
                    <i class="bi bi-house-door-fill me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/_header.blade.php ENDPATH**/ ?>