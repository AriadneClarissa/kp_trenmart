

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h3 class="fw-bold">Pesanan Saya</h3>
    <p class="text-muted">Lihat riwayat dan status pesanan Anda di sini.</p>

    <div class="mt-4">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card mb-3" style="border-radius:12px;">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div>
                            <div class="fw-bold"><?php echo e($order->order_number); ?></div>
                            <div class="small text-muted"><?php echo e($order->created_at->format('d M Y \p\u\k\u\l H:i')); ?></div>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="badge rounded-pill" style="background:#fff6e6;color:#b45309;"><?php echo e(ucfirst(str_replace('_',' ', $order->payment_status))); ?></div>
                            <div class="fw-bold mt-2">Rp <?php echo e(number_format($order->total,0,',','.')); ?></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo e(\App\Helpers\StorageProxy::url($it->produk->gambar ?? 'images/no-image.png')); ?>" style="width:56px;height:56px;object-fit:cover;border-radius:8px;" alt="">
                                <div class="ms-3">
                                    <div class="fw-semibold"><?php echo e($it->produk->nama_produk ?? '-'); ?></div>
                                    <div class="small text-muted"><?php echo e($it->quantity); ?> × Rp <?php echo e(number_format($it->price,0,',','.')); ?></div>
                                </div>
                                <div class="ms-auto small text-muted"><?php echo e($order->paymentMethod->name ?? '-'); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="<?php echo e(route('pesanan.show', $order->id)); ?>" class="btn btn-sm btn-outline-secondary">Lihat</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="card p-4 text-center">
                <div class="text-muted">Belum ada pesanan.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\pesanan\index.blade.php ENDPATH**/ ?>