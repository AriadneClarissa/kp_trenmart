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

    <script>
        // Otomatis submit form saat file dipilih
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
        .hover-scale:hover {
            transform: translate(-50%, -50%) scale(1.1);
            transition: 0.3s ease;
        }
        .object-fit-cover {
            object-fit: cover;
        }
    </style>

    
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
                            
                            <a href="<?php echo e(route('produk.create')); ?>" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Produk
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
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4">
            <i class="bi bi-stars text-warning me-2"></i>
            <?php echo e($settings['judul_terbaru'] ?? 'Produk Terbaru'); ?>

        </h4>
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            <?php $__empty_1 = true; $__currentLoopData = $produk_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-auto card-mobile-width"> 
                <?php echo $__env->make('partials.item_produk', ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada produk untuk ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-5 fs-md-4">
            <i class="bi bi-fire text-danger me-2"></i>
            <?php echo e($settings['judul_terpopuler'] ?? 'Produk Terpopuler'); ?>

        </h4>
        <div class="row flex-nowrap overflow-auto g-3 g-md-4 pb-3 custom-scrollbar">
            <?php $__empty_1 = true; $__currentLoopData = $produk_terpopuler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-auto card-mobile-width">
                <?php echo $__env->make('partials.item_produk', ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Produk populer akan segera hadir.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>
<script>
    document.getElementById('bannerInput').onchange = function() {
        document.getElementById('bannerForm').submit();
    };
</script>
<style>
    /* Mengatur tinggi banner agar tidak terlalu besar di HP */
    .img-banner-responsive { 
        height: 160px; 
        object-fit: cover; 
    }
    
    @media (min-width: 768px) { 
        .img-banner-responsive { height: auto; } 
    }

    /* Mengatur lebar kartu produk agar bisa di-scroll menyamping di HP */
    .card-mobile-width { width: 165px; }
    
    @media (min-width: 768px) { 
        .card-mobile-width { width: 220px; } 
    }

    /* Menghilangkan scrollbar tapi tetap bisa di-scroll */
    .custom-scrollbar { 
        scrollbar-width: none; 
        -ms-overflow-style: none; 
    }
    .custom-scrollbar::-webkit-scrollbar { 
        display: none; 
    }

    .flex-nowrap { 
        scroll-snap-type: x mandatory; 
        -webkit-overflow-scrolling: touch; 
    }
    
    .col-auto { 
        scroll-snap-align: start; 
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/beranda.blade.php ENDPATH**/ ?>