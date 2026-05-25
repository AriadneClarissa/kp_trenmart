<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5">
    <div class="row g-4">
        
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                <div class="card-body p-4">
                    
                    <?php if(isset($is_bundling) && $is_bundling): ?>
                        
                        <?php if($produk->items->count() > 0): ?>
                            <div id="productCarousel" class="carousel slide bg-light" data-bs-ride="carousel" style="border-radius: 15px; overflow: hidden;">
                                <div class="carousel-inner" style="height: 450px;">
                                    <?php $__currentLoopData = $produk->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="carousel-item <?php echo e($index == 0 ? 'active' : ''); ?> h-100">
                                            
                                            
                                            <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                                <span class="badge bg-dark opacity-75 p-2 px-3 shadow-sm" style="border-radius: 10px;">
                                                    Harga Asli: Rp <?php echo e(number_format($item->price_at_snapshot, 0, ',', '.')); ?>

                                                </span>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-center h-100 position-relative">
                                                <?php if($item->produk && $item->produk->gambar): ?>
                                                    <img src="<?php echo e(\App\Helpers\StorageProxy::url($item->produk->gambar)); ?>" 
                                                         class="img-fluid main-product-image" 
                                                         alt="<?php echo e($item->produk->nama_produk); ?>"
                                                         style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                                    
                                                    <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3 bg-white px-3 py-1 rounded-pill shadow-sm border small fw-bold text-dark">
                                                        <?php echo e($item->produk->nama_produk); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                
                                <?php if($produk->items->count() > 1): ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="row mt-3 g-2 justify-content-center">
                                <?php $__currentLoopData = $produk->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-2">
                                     <img src="<?php echo e(\App\Helpers\StorageProxy::url($item->produk->gambar)); ?>" 
                                         class="img-fluid border rounded cursor-pointer opacity-hover" 
                                         onclick="goToSlide(<?php echo e($index); ?>)" 
                                         style="height: 50px; width: 100%; object-fit: cover;">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        
                        <div id="productCarousel" class="carousel slide bg-light" data-bs-ride="carousel" style="border-radius: 15px; overflow: hidden;">
                            <div class="carousel-inner" style="height: 450px;">
                                <div class="carousel-item active h-100">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="<?php echo e(\App\Helpers\StorageProxy::url($produk->gambar)); ?>" class="img-fluid main-product-image" alt="<?php echo e($produk->nama_produk); ?>" style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                    </div>
                                </div>
                                <?php if($produk->foto_2): ?>
                                <div class="carousel-item h-100">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="<?php echo e(\App\Helpers\StorageProxy::url($produk->foto_2)); ?>" class="img-fluid main-product-image" style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if($produk->foto_3): ?>
                                <div class="carousel-item h-100">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <img src="<?php echo e(\App\Helpers\StorageProxy::url($produk->foto_3)); ?>" class="img-fluid main-product-image" style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <?php if($produk->foto_2 || $produk->foto_3): ?>
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon p-3 bg-dark rounded-circle" aria-hidden="true" style="background-size: 50%;"></span>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    
                    <?php if(isset($is_bundling) && $is_bundling): ?>
                        
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2" style="border-radius: 6px;">Bundling Hemat</span>
                            <h2 class="fw-bold text-dark mb-1"><?php echo e($produk->name); ?></h2>
                            <p class="text-muted small">
                                Isi Paket: 
                                <?php $__currentLoopData = $produk->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="fw-bold text-dark"><?php echo e($item->produk->nama_produk); ?></span> 
                                    <span class="text-muted">1 (<?php echo e($item->produk->satuanModel->nama_satuan ?? 'pcs'); ?>)</span>
                                    <?php echo e(!$loop->last ? ' + ' : ''); ?>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </p>
                        </div>

                        
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <?php $has_divergence = false; ?>
                                <?php $__currentLoopData = $produk->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($item->price_at_snapshot != $item->produk->harga_jual_umum): ?>
                                        <?php $has_divergence = true; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($has_divergence): ?>
                                    <div class="alert alert-danger border-0 mb-4 shadow-sm" style="border-radius: 15px;">
                                        <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Perbandingan Harga Terbaru:</h6>
                                        <ul class="list-unstyled mb-0 small">
                                            <?php $__currentLoopData = $produk->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($item->price_at_snapshot != $item->produk->harga_jual_umum): ?>
                                                    <li class="mb-1">
                                                        <strong><?php echo e($item->produk->nama_produk); ?>:</strong> 
                                                        <span class="text-decoration-line-through text-muted">Rp <?php echo e(number_format($item->price_at_snapshot, 0, ',', '.')); ?></span> 
                                                        &rarr; <span class="text-danger fw-bold">Rp <?php echo e(number_format($item->produk->harga_jual_umum, 0, ',', '.')); ?></span>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                        <p class="mt-2 mb-0 x-small text-muted">Klik "Edit Data Bundling" untuk menyesuaikan harga paket.</p>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <h3 class="fw-bold mb-4" style="color: #800000;">
                            Rp <?php echo e(number_format($produk->bundling_price, 0, ',', '.')); ?>

                            <small class="text-muted fw-normal fs-6">/Paket</small>
                        </h3>

                        <?php if($produk->total_normal_price > $produk->bundling_price): ?>
                            <p class="text-warning fw-bold mb-3" style="margin-top: -15px;">
                                Harga Normal: <span class="text-decoration-line-through text-muted fw-normal">Rp <?php echo e(number_format($produk->total_normal_price, 0, ',', '.')); ?></span> 
                            </p>
                        <?php endif; ?>

                    <?php else: ?>
                        
                        <div class="mb-3">
                            <span class="badge bg-secondary mb-2" style="border-radius: 6px;"><?php echo e($produk->merk->nama_merk ?? 'Tanpa Merk'); ?></span>
                            <h2 class="fw-bold text-dark mb-1"><?php echo e($produk->nama_produk); ?></h2>
                            <p class="text-muted small">Kategori: <?php echo e($produk->kategori->nama_kategori ?? 'Tidak ada kategori'); ?></p>
                        </div>

                        <h3 class="fw-bold mb-4" style="color: #800000;">
                            Rp <?php echo e(number_format($produk->harga_tampil, 0, ',', '.')); ?>

                            <small class="text-muted fw-normal fs-6">/<?php echo e($produk->satuanModel->nama_satuan ?? 'pcs'); ?></small>
                        </h3>

                        
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <p class="mb-4 fw-semibold text-orange" style="font-size: 1rem;">
                                    Langganan: Rp <?php echo e(number_format($produk->harga_jual_langganan ?? $produk->harga_jual_umum, 0, ',', '.')); ?>

                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    
                    <?php $stok_check = (isset($is_bundling) && $is_bundling) ? $stok_tersedia : $produk->stok_tersedia; ?>
                    <div class="mb-4">
                        <?php if($stok_check > 0): ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle" style="border-radius: 8px;">
                                <i class="bi bi-check-circle-fill me-1"></i>Stok Tersedia: <?php echo e($stok_check); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger px-3 py-2 border border-danger-subtle" style="border-radius: 8px;">
                                <i class="bi bi-x-circle-fill me-1"></i>Stok Habis
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if(!isset($is_bundling) || !$is_bundling): ?>
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark">Deskripsi Produk</h6>
                            <p class="text-muted" style="line-height: 1.6;">
                                <?php echo e($produk->deskripsi ?? 'Tidak ada deskripsi untuk produk ini.'); ?>

                            </p>
                        </div>
                    <?php endif; ?>

                    <hr class="my-4 opacity-25">

                    
                    <div class="action-section">
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <div class="alert alert-secondary border-0 d-flex align-items-center" style="border-radius: 12px; background-color: #f8f9fa;">
                                    <i class="bi bi-shield-lock-fill fs-4 me-3 text-dark"></i>
                                    <div>
                                        <div class="fw-bold">Mode Admin</div>
                                        <small class="text-muted">Kelola data melalui tombol di bawah.</small>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <?php if(isset($is_bundling) && $is_bundling): ?>
                                        <a href="#" class="btn btn-warning py-3 fw-bold rounded-3 shadow-sm">
                                            <i class="bi bi-pencil-square me-2"></i>Edit Data Bundling
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('produk.edit', $produk->kd_produk)); ?>" class="btn btn-warning py-3 fw-bold rounded-3 shadow-sm">
                                            <i class="bi bi-pencil-square me-2"></i>Edit Data Produk
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <?php if($stok_check > 0): ?>
                                    <form action="<?php echo e(route('cart.add', isset($is_bundling) && $is_bundling ? $produk->id : $produk->kd_produk)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        
                                        <input type="hidden" name="type" value="<?php echo e((isset($is_bundling) && $is_bundling) ? 'bundling' : 'reguler'); ?>">
                                        
                                        <button type="submit" class="btn btn-buy w-100 py-3 shadow-sm">
                                            <i class="bi bi-cart-plus fs-5 me-2"></i> Tambah ke Keranjang
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn w-100 py-3 bg-light text-muted border fw-bold" disabled style="border-radius: 12px;">
                                        <i class="bi bi-cart-x fs-5 me-2"></i> Stok Habis
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-buy w-100 py-3 shadow-sm">
                                <i class="bi bi-box-arrow-in-right fs-5 me-2"></i> Login untuk Membeli
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="<?php echo e(url()->previous()); ?>" class="text-decoration-none text-muted small hover-maroon">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer { cursor: pointer; }
    .opacity-hover:hover { opacity: 0.7; transition: 0.3s; }
    .carousel-control-prev, .carousel-control-next { width: 10%; }
    .text-orange { color: #f08a24; }
    .btn-buy {
        background-color: #800000;
        color: white;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: 0.3s;
        border: none;
    }
    .btn-buy:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
    }
    .hover-maroon:hover { color: #800000 !important; }
</style>

<script>
    function goToSlide(index) {
        var myCarousel = document.querySelector('#productCarousel');
        var carousel = bootstrap.Carousel.getOrCreateInstance(myCarousel);
        carousel.to(index);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\produk\detail.blade.php ENDPATH**/ ?>