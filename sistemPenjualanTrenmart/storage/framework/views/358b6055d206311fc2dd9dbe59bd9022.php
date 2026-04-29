<div class="col">
    <div class="card h-100 border-0 shadow-sm p-2 product-card" style="border-radius: 16px; position: relative; cursor: pointer;"
             onclick="if(event.target.closest('form')) return; window.location.href='<?php echo e(route('produk.detail', ['id' => $item->kd_produk, 'from' => 'beranda'])); ?>'">
        <?php if($item->stok_tersedia > 0): ?>
            <div class="position-absolute" style="top: 12px; left: 12px; z-index: 10;">
                <span class="badge bg-success px-2 py-1" style="border-radius: 7px; font-size: 0.68rem;">
                    Tersedia
                </span>
            </div>
        <?php endif; ?>

        <div class="d-flex align-items-center justify-content-center bg-light mb-3"
             style="height: 150px; border-radius: 12px; overflow: hidden;">
            <img src="<?php echo e(asset('storage/' . $item->gambar)); ?>"
                 class="img-fluid"
                 alt="<?php echo e($item->nama_produk); ?>"
                 style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
        </div>

        <div class="card-body p-0">
            <p class="text-muted mb-1" style="font-size: 0.78rem;">
                <?php echo e($item->merk->nama_merk ?? 'Tanpa Merk'); ?>

            </p>

            <h5 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                <?php echo e($item->nama_produk); ?>

            </h5>

            <h4 class="fw-bold mb-2" style="color: #800000; font-size: 1.15rem;">
                Rp <?php echo e(number_format($item->harga_tampil, 0, ',', '.')); ?>

                <small class="text-muted fw-normal" style="font-size: 0.62rem;">/<?php echo e($item->satuan); ?></small>
            </h4>

            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->isAdmin()): ?>
                    <p class="mb-2 fw-semibold" style="color: #f08a24; font-size: 0.9rem;">
                        Langganan: Rp <?php echo e(number_format($item->harga_jual_langganan ?? $item->harga_jual_umum, 0, ',', '.')); ?>

                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if(auth()->guard()->check()): ?>
                <?php if(!auth()->user()->isAdmin()): ?>
                    <form action="<?php echo e(route('cart.add', $item->kd_produk)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-1"
                                style="background-color: #800000; color: white; border-radius: 10px; font-weight: 600; font-size: 0.9rem;">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .product-card .btn:hover {
        background-color: #600000 !important;
        filter: brightness(1.1);
    }

    .product-card form {
        position: relative;
        z-index: 2;
    }
</style><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/partials/item_produk.blade.php ENDPATH**/ ?>