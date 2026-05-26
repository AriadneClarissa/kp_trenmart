<?php $__env->startPush('styles'); ?>
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
    }
    /* Background & Font */
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* Rapatkan jarak ke Navbar */
    .main-container { padding-top: 15px !important; }

    /* Layout Wrapper: Menjaga Kiri dan Kanan Sejajar Sempurna */
    .cart-wrapper { 
        display: flex; 
        align-items: flex-start !important; 
    }

    /* Cards */
    .card-custom { border-radius: 15px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); background: white; margin-bottom: 20px; }
    .product-img { width: 85px; height: 85px; object-fit: cover; border-radius: 12px; background: #f1f1f1; }
    
    /* Qty Control */
    .qty-container { border: 1px solid #eee; border-radius: 10px; padding: 2px; background: #fff; display: inline-flex; }
    .qty-input { width: 40px; text-align: center; border: none; font-weight: 700; background: transparent; outline: none; }
    .btn-qty { border: none; background: transparent; width: 30px; height: 30px; border-radius: 8px; font-weight: bold; transition: 0.2s; cursor: pointer; }
    .btn-qty:hover { background: #fceaea; color: var(--maroon-trenmart); }

    /* Sidebar Sticky: Menempel saat scroll tanpa getar */
    .summary-card { 
        background: white; 
        border-radius: 18px; 
        padding: 24px; 
        position: -webkit-sticky;
        position: sticky; 
        top: 20px; 
        border: none; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        will-change: transform; 
    }

    /* Button Bayar */
    .btn-checkout { 
        background: var(--accent-red); 
        color: white !important; 
        border-radius: 12px; 
        padding: 16px; 
        width: 100%; 
        font-weight: 700; 
        border: none; 
        transition: 0.3s; 
        display: flex; 
        justify-content: center; 
        align-items: center;
        text-decoration: none;
    }
    .btn-checkout:hover { background: #c5163e; transform: translateY(-2px); }

    /* Utility */
    .text-maroon { color: var(--maroon-trenmart); }
    .text-accent { color: var(--accent-red); }
    .btn-link-custom { text-decoration: none; font-weight: 600; font-size: 0.85rem; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container main-container pb-5">
    
    
    <div class="mb-3">
        <a href="<?php echo e(route('katalog')); ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Belanja
        </a>
        <div class="d-flex align-items-center mt-1">
            <i class="bi bi-cart3 text-black-custom fs-2 me-3"></i> <div>
                <h3 class="fw-bold mb-0">Keranjang Belanja</h3>
            </div>
        </div>
    </div>

    <div class="row cart-wrapper g-4">
        
        
        <div class="col-lg-8">
            
            <div class="card card-custom p-4">
                
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h6 class="fw-bold mb-0">Produk (<?php echo e(count($items)); ?> item)</h6>
                    <?php if(count($items) > 0): ?>
                        <form action="<?php echo e(route('cart.clear')); ?>" method="POST" class="m-0">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-link text-danger btn-link-custom p-0 d-flex align-items-center" onclick="return confirm('Hapus semua item di keranjang?');">
                                <i class="bi bi-trash-fill me-1"></i> Hapus Semua
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center py-3 border-bottom <?php echo e($loop->last ? 'border-0' : ''); ?>">
                    
                    
                    <?php if($item->bundling_id != null && $item->bundling): ?>
                        
                        <?php
                            $gambarBundling = null;
                            if($item->bundling->items && $item->bundling->items->count() > 0) {
                                $produkPertama = $item->bundling->items->first()->produk;
                                $gambarBundling = $produkPertama ? $produkPertama->gambar : null;
                            }
                        ?>
                        <img src="<?php echo e(\App\Helpers\StorageProxy::url($gambarBundling ?? 'images/no-image.png')); ?>" class="product-img me-3" style="object-fit: cover;">
                        
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0 text-danger"><?php echo e($item->bundling->name); ?></h6>
                            <p class="text-muted small mb-1">Paket Bundling Hemat</p>
                            <h6 class="text-accent fw-bold mb-0">Rp <?php echo e(number_format($item->harga_at_time, 0, ',', '.')); ?></h6>
                        </div>
                    <?php else: ?>
                        
                        <img src="<?php echo e(\App\Helpers\StorageProxy::url($item->produk->gambar ?? 'images/no-image.png')); ?>" class="product-img me-3">
                        
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-0"><?php echo e($item->produk->nama_produk); ?></h6>
                            <p class="text-muted small mb-1"><?php echo e($item->produk->merk->nama_merk ?? 'Trenmart'); ?></p>
                            <h6 class="text-accent fw-bold mb-0">Rp <?php echo e(number_format($item->harga_at_time, 0, ',', '.')); ?></h6>
                        </div>
                    <?php endif; ?> 

                    <div class="text-end">
                        <form action="<?php echo e(route('cart.update', $item->id)); ?>" method="POST" class="qty-container mb-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button class="btn-qty" type="submit" name="action" value="decrease">-</button>
                            <input type="text" class="qty-input" value="<?php echo e($item->jumlah); ?>" readonly>
                            <button class="btn-qty" type="submit" name="action" value="increase">+</button>
                        </form>
                        <div class="fw-bold d-block">Rp <?php echo e(number_format($item->harga_at_time * $item->jumlah, 0, ',', '.')); ?></div>
                        <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST" class="m-0 mt-1">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-link p-0 text-muted small shadow-none" onclick="return confirm('Hapus produk ini dari keranjang?');">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-5 text-muted">Keranjang Anda kosong</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="summary-card shadow-sm">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                
                <div id="items-list">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span class="text-truncate" style="max-width: 160px;">
                            
                            <?php if($item->bundling_id != null && $item->bundling): ?>
                                <?php echo e($item->bundling->name); ?>

                            <?php else: ?>
                                <?php echo e($item->produk->nama_produk); ?>

                            <?php endif; ?>
                            ×<?php echo e($item->jumlah); ?>

                        </span>
                        <span>Rp <?php echo e(number_format($item->harga_at_time * $item->jumlah, 0, ',', '.')); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4 text-muted small">
                    <span>Ongkos Kirim</span>
                    <span class="fw-bold text-dark">Dihitung di checkout</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0" id="total-label">Rp <?php echo e(number_format($total, 0, ',', '.')); ?></h4>
                </div>

                <?php if(count($items) > 0): ?>
                <a href="<?php echo e(route('checkout.index')); ?>" class="btn-checkout shadow-sm">
                    Lanjut ke Pembayaran <i class="bi bi-chevron-right ms-2"></i>
                </a>
                <?php else: ?>
                <button class="btn btn-secondary w-100 py-3 fw-bold border-0 opacity-50" disabled style="border-radius:12px;">Keranjang Kosong</button>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/keranjang.blade.php ENDPATH**/ ?>