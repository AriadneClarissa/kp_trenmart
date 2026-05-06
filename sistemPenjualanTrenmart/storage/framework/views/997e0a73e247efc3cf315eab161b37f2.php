<?php $__env->startSection('content'); ?>
<div class="container mt-3 mt-md-4 mb-5">
    
    
    <div class="banner-wrapper mb-4 position-relative overflow-hidden" style="border-radius: 1rem;">
        <img id="bannerPreview" 
            src="<?php echo e(($admin && $admin->tentang_banner) ? asset('storage/' . $admin->tentang_banner) : asset('images/spanduktoko.png')); ?>" 
            class="w-100 shadow-sm img-banner-responsive object-fit-cover" 
            style="height: 300px;" 
            alt="Banner Trenmart">

        <?php if(Auth::check() && Auth::user()->role == 'admin'): ?>
            <form action="<?php echo e(route('admin.banner.update')); ?>" method="POST" enctype="multipart/form-data" id="bannerForm">
                <?php echo csrf_field(); ?>
                <input type="file" name="tentang_banner" id="bannerInput" class="d-none" accept="image/*">
                <label for="bannerInput" 
                    class="position-absolute top-50 start-50 translate-middle btn btn-light rounded-circle shadow-lg d-flex align-items-center justify-content-center hover-scale" 
                    style="width: 80px; height: 80px; opacity: 0.9; cursor: pointer; border: 3px solid white; z-index: 10;">
                    <div class="text-center">
                        <i class="bi bi-camera-fill fs-3 text-dark"></i>
                        <div style="font-size: 10px; font-weight: bold; color: #333;">Ubah Foto</div>
                    </div>
                </label>
            </form>
        <?php endif; ?>
    </div>

    
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAdmin()): ?>
        <div class="card shadow-sm mb-5 admin-panel-card border-0 bg-light">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-shield-lock-fill me-2 text-danger"></i>Panel Kontrol Admin
                        </h5>
                        <p class="text-muted small mb-0">Kelola stok produk dan pengaturan tampilan beranda</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <div class="d-grid d-md-inline-block gap-2">
                            <a href="<?php echo e(route('bundling.create', ['source' => 'beranda'])); ?>" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Bundling
                            </a>
                            <a href="<?php echo e(route('admin.judul.edit')); ?>" class="btn btn-warning rounded-pill px-4 shadow-sm">
                                <i class="bi bi-pencil-square me-1"></i> Edit Judul
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-4 fs-md-3">
            <i class="bi bi-stars text-warning me-2"></i>
            <?php echo e($settings['judul_terbaru'] ?? 'Produk Terbaru'); ?>

        </h4>
        
        <!-- Tambahkan d-flex di sini untuk memastikan elemen tetap sejajar horizontal -->
        <div class="row d-flex flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar" style="margin-right: 0; margin-left: 0;">
            <?php $__empty_1 = true; $__currentLoopData = $produk_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!-- Gunakan col-auto agar lebar kolom mengikuti isi (card-mobile-width) -->
                <div class="col-auto card-mobile-width" style="flex: 0 0 auto;"> 
                    <?php echo $__env->make('partials.item_produk', ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada produk untuk ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    
    <section class="mt-5 pt-3">
        <div class="text-center mb-5">
            <h4 class="fw-bold mb-2 fs-4 fs-md-3">
                <i class="bi bi-box2-heart text-danger me-2"></i> Paket Bundling Hemat
            </h4>
            <p class="text-muted small">Dapatkan kombinasi produk terbaik dengan harga lebih murah!</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php $__empty_1 = true; $__currentLoopData = $bundling; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4">
                    
                    <div class="card h-100 border-0 shadow-sm card-bundling-hover position-relative" style="border-radius: 20px;">
                        <div class="card-body p-3 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                
                                <a href="<?php echo e(route('bundling.show', $b->id)); ?>" class="text-decoration-none stretched-link">
                                    <h5 class="fw-bold text-dark mb-0 hover-maroon"><?php echo e($b->name); ?></h5>
                                </a>
                            </div>

                            <div class="bg-light p-3 rounded-4 mb-4">
                                <label class="small fw-bold text-primary mb-2 d-block">Isi Paket:</label>
                                <ul class="list-unstyled mb-0">
                                    <?php $__currentLoopData = $b->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="small d-flex align-items-center mb-2">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <span>
                                                <?php echo e($item->produk->nama_produk); ?> 
                                                <small class="text-muted">(<?php echo e($item->produk->merk->nama_merk ?? 'Tanpa Merk'); ?>)</small>
                                            </span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <small class="text-muted text-decoration-line-through">
                                        Rp <?php echo e(number_format($b->total_normal_price, 0, ',', '.')); ?>

                                    </small>
                                    <?php if($b->total_normal_price > $b->bundling_price): ?>
                                        <span class="badge bg-light text-danger border border-danger small" style="font-size: 0.7rem;">
                                            Hemat Rp <?php echo e(number_format($b->total_normal_price - $b->bundling_price, 0, ',', '.')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="fw-bold text-danger mb-0">
                                        Rp <?php echo e(number_format($b->bundling_price, 0, ',', '.')); ?>

                                    </h4>
                                    
                                    
                                    <?php if(!Auth::check() || (Auth::check() && Auth::user()->role !== 'admin')): ?>
                                        
                                        <span class="btn-tambah-card shadow-sm d-flex align-items-center justify-content-center" style="position: relative; z-index: 2;">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted italic">Belum ada paket bundling untuk saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>


<script>
    const bannerInput = document.getElementById('bannerInput');
    if(bannerInput) {
        bannerInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('bannerForm').submit();
            }
        });
    }
</script>

<style>
    .hover-scale:hover { transform: translate(-50%, -50%) scale(1.1); transition: 0.3s ease; }
    .object-fit-cover { object-fit: cover; }
    .img-banner-responsive { height: 160px; object-fit: cover; }
    @media (min-width: 768px) { .img-banner-responsive { height: 300px; } }

    .card-mobile-width { 
        width: 165px; 
        flex: 0 0 auto; /* Tambahkan ini agar kartu tidak menciut */
    }
    
    @media (min-width: 768px) { 
        .card-mobile-width { 
            width: 220px; 
            flex: 0 0 auto; 
        } 
    }

    .custom-scrollbar { 
        scrollbar-width: none; 
        -ms-overflow-style: none; 
        padding-left: 5px; /
        padding-right: 5px;
    }
    
    .custom-scrollbar::-webkit-scrollbar { 
        display: none; 
    }

    .flex-nowrap { 
        display: flex;
        flex-wrap: nowrap !important; /* Memaksa kartu tetap satu baris */
        -webkit-overflow-scrolling: touch; /* Scroll halus di iPhone/iOS */
    }

    .card-bundling-hover:hover {
        transform: translateY(-5px);
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Style untuk tombol Tambah di Card */
    .btn-tambah-card {
        background-color: #800000; /* Warna maroon Trenmart */
        color: white;
        border-radius: 8px; /* Bentuk tidak terlalu bulat (bukan pill) */
        font-size: 0.9rem; /* Ukuran font disesuaikan agar rapi */
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .btn-tambah-card:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2) !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/beranda.blade.php ENDPATH**/ ?>