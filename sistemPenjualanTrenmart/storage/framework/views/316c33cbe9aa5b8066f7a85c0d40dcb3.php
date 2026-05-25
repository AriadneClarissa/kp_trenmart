<?php $__env->startPush('styles'); ?>
<style>
    :root { --maroon: #800000; --soft-bg: #f8f9fa; }
    body { background-color: var(--soft-bg); }

    /* --- SIDEBAR KATEGORI --- */
    .sidebar-katalog { background: white; border-radius: 20px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 90px; }
    .search-kategori { background-color: #f1f1f1; border: none; border-radius: 50px; padding: 10px 15px; font-size: 14px; }
    .kat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 12px;
        color: #555;
        text-decoration: none;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.2s;
        font-size: 14px;
        min-height: 44px;
        border-radius: 10px;
    }
    .kat-item:hover,
    .kat-item.active {
        color: var(--maroon);
        background-color: #fff5f5;
        font-weight: 500;
    }
    
    /* Panel Admin Card di Sidebar */
    .panel-admin-card { background-color: #fff9e6; border: 1px solid #ffecb3; border-radius: 15px; margin-bottom: 20px; }
    .btn-kelola { font-size: 13px; font-weight: 600; border-radius: 10px; width: 100%; margin-bottom: 8px; transition: 0.3s; }

    /* --- TOP FILTER PILL --- */
    .top-filter-pill { background: white; border-radius: 50px; padding: 8px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; align-items: center; width: 100%; }
    .top-filter-input { border: none; background: transparent; outline: none; width: 100%; font-size: 14px; padding-left: 10px; }
    .top-select-filter { 
        border: none; 
        background: white; 
        border-radius: 50px; 
        /* Padding kanan ditingkatkan menjadi 45px agar teks tidak menabrak panah */
        padding: 10px 45px 10px 20px; 
        cursor: pointer; 
        color: #666; 
        font-size: 14px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        width: 100%; 
        border: 1px solid #eee; 
        
        /* Mengatur panah custom */
        appearance: none; 
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        /* Geser panah 15px dari sisi kanan */
        background-position: right 15px center; 
        background-size: 14px;
    }
    /* --- CARD PRODUK --- */
    .card-produk { border-radius: 25px !important; transition: 0.3s; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.06); height: 100%; overflow: hidden; background: white; display: flex; flex-direction: column; }
    .card-produk:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .card-clickable { cursor: pointer; }
    .img-container { background-color: #f8f9fa; border-radius: 20px; padding: 25px; min-height: 190px; display: flex; align-items: center; justify-content: center; position: relative; margin: 10px; }
    
    .badge-stok { position: absolute; top: 10px; left: 10px; font-size: 10px; padding: 4px 10px; border-radius: 8px; }
    .product-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; }
    .price-text { color: var(--maroon); font-weight: 800; font-size: 1.2rem; }
    
    .btn-action { color: white; border-radius: 12px; border: none; font-weight: 600; padding: 12px; width: 100%; transition: 0.2s; font-size: 14px; text-decoration: none; display: block; text-align: center; }
    .btn-tambah { background-color: var(--maroon); }
    .btn-tambah:hover { background-color: #600000; box-shadow: 0 4px 15px rgba(128,0,0,0.3); color: white; }
    .btn-detail { background-color: #444; }
    .btn-detail:hover { background-color: #222; color: white; }

    /* Custom Scrollbar */
    .list-kategori { max-height: 400px; overflow-y: auto; scrollbar-width: none; }
    .list-kategori::-webkit-scrollbar { display: none; }

    /* Lebihkan kanvas katalog agar tidak terlihat terlalu ke tengah */
    .katalog-wrap { max-width: 1520px; margin: 0 auto; }

    /* Empty state harus selalu full row, tidak ikut row-cols */
    .empty-produk-state {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 260px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-3 px-lg-4 px-xl-5 mt-4 mb-5 katalog-wrap">
    <div class="row g-4">
        
        
        <div class="col-md-4 col-lg-3 col-xl-3 d-none d-md-block">
            
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                <div class="panel-admin-card p-3 shadow-sm mb-4">
                    <h6 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-shield-lock me-2"></i>
                        <?php if(auth()->user()->isOwner()): ?>
                            Panel Pemilik
                        <?php else: ?>
                            Panel Admin
                        <?php endif; ?>
                    </h6>
                    <button class="btn btn-info text-white btn-kelola shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">
                        <i class="bi bi-tag me-1"></i> Kelola Kategori
                    </button>
                    <button class="btn btn-success btn-kelola shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaMerk">
                        <i class="bi bi-building me-1"></i> Kelola Merk
                    </button>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="sidebar-katalog p-4 shadow-sm">
                <h6 class="fw-bold mb-3">Kategori</h6>
                <div class="mb-3">
                    <input type="text" id="inputSearchKat" class="form-control search-kategori" placeholder="Cari kategori...">
                </div>
                <div class="list-kategori" id="listKatSide">
                    <a href="<?php echo e(route('katalog', ['search' => request('search'), 'merk' => request('merk')])); ?>" class="kat-item <?php echo e(!request('kategori') ? 'active' : ''); ?>">
                        <span>Semua Produk</span> <i class="bi bi-chevron-right"></i>
                    </a>
                    <?php $__currentLoopData = $kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('katalog', ['kategori' => $kat->kd_kategori, 'search' => request('search'), 'merk' => request('merk')])); ?>" class="kat-item <?php echo e(request('kategori') == $kat->kd_kategori ? 'active' : ''); ?>">
                            <span class="nama-kat"><?php echo e($kat->nama_kategori); ?></span> <i class="bi bi-chevron-right"></i>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-8 col-lg-9 col-xl-9">
            
            <form action="<?php echo e(route('katalog')); ?>" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-7">
                    <div class="top-filter-pill">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" name="search" class="top-filter-input" placeholder="Cari produk..." value="<?php echo e(request('search')); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="kategori" value="<?php echo e(request('kategori')); ?>">
                    <select name="merk" class="top-select-filter" onchange="this.form.submit()">
                        <option value="">Semua Merek</option>
                        <?php $__currentLoopData = $merk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m->kd_merk); ?>" <?php echo e(request('merk') == $m->kd_merk ? 'selected' : ''); ?>><?php echo e($m->nama_merk); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2 text-end text-muted small">
                    <?php echo e(count($produk)); ?> produk ditemukan
                </div>
            </form>

            
            <div class="row row-cols-2 row-cols-md-3 g-4">
                <?php $__empty_1 = true; $__currentLoopData = $produk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col">
                    <div class="card card-produk <?php echo e(auth()->check() && !auth()->user()->isAdmin() ? 'card-clickable' : ''); ?>"
                        <?php if(auth()->check() && !auth()->user()->isAdmin()): ?>
                            onclick="if(event.target.closest('form') || event.target.closest('button') || event.target.closest('a')) return; window.location.href='<?php echo e(route('produk.detail', ['id' => $p->kd_produk, 'from' => 'katalog'])); ?>'"
                        <?php endif; ?>>
                        <?php
                            $stokMinimal = $p->stok_minimal ?? $p->satuanModel?->stok_minimal ?? 0;
                            $isLowStock = $stokMinimal > 0 && $p->stok_tersedia <= $stokMinimal;
                        ?>
                        <div class="img-container">
                            <?php if($p->stok_tersedia > 0): ?>
                                <span class="badge bg-success badge-stok shadow-sm">Tersedia</span>
                            <?php else: ?>
                                <span class="badge bg-danger badge-stok shadow-sm">Habis</span>
                            <?php endif; ?>
                            <?php if($isLowStock): ?>
                                <span class="badge bg-warning text-dark badge-stok shadow-sm" style="top: 44px;">Warning Stok</span>
                            <?php endif; ?>
                            <img src="<?php echo e(\App\Helpers\StorageProxy::url($p->gambar)); ?>" class="img-fluid" style="height: 150px; object-fit: contain;" alt="<?php echo e($p->nama_produk); ?>">
                        </div>
                        
                        <div class="product-info text-center">
                            <p class="text-muted small mb-1"><?php echo e($p->merk->nama_merk ?? 'No Brand'); ?></p>
                            <h6 class="fw-bold text-dark text-truncate mb-2" title="<?php echo e($p->nama_produk); ?>"><?php echo e($p->nama_produk); ?></h6>
                            
                            
                            <h5 class="price-text mb-1">
                                Rp <?php echo e(number_format(($p->harga_tampil > 0 ? $p->harga_tampil : $p->harga_jual_umum), 0, ',', '.')); ?>

                                <span class="text-muted small fw-normal" style="font-size: 11px;">/<?php echo e($p->satuan); ?></span>
                            </h5>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin()): ?>
                                    <p class="mb-3 fw-semibold" style="color: #f08a24; font-size: 0.95rem;">
                                        Langganan: Rp <?php echo e(number_format($p->harga_jual_langganan ?? $p->harga_jual_umum, 0, ',', '.')); ?>

                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->isAdmin()): ?>
                                    
                                    <a href="<?php echo e(route('produk.detail', ['id' => $p->kd_produk, 'from' => 'katalog'])); ?>" class="btn-action btn-detail shadow-sm">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                <?php else: ?>
                                    
                                    <form action="<?php echo e(route('cart.add', $p->kd_produk)); ?>" method="POST" class="mt-2 add-to-cart-form">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn-action btn-tambah shadow-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                
                                <form action="<?php echo e(route('cart.add', $p->kd_produk)); ?>" method="POST" class="mt-2 add-to-cart-form">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-action btn-tambah shadow-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-produk-state py-5">
                    <i class="bi bi-search text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">Produk tidak ditemukan</h5>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Live Search Kategori
    $('#inputSearchKat').on('keyup', function() {
        let val = $(this).val().toLowerCase();
        $("#listKatSide .kat-item").filter(function() {
            $(this).toggle($(this).find('.nama-kat').text().toLowerCase().indexOf(val) > -1)
        });
    });

});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\katalog.blade.php ENDPATH**/ ?>