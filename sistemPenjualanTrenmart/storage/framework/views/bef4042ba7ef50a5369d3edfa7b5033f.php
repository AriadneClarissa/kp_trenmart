

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h4 class="fw-bold">Pesanan <?php echo e($order->order_number); ?></h4>

    <div class="card mt-3 p-4" style="border-radius:12px;">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <div class="fw-bold"><?php echo e($order->order_number); ?></div>
                <div class="small text-muted"><?php echo e($order->created_at->format('d M Y \p\u\k\u\l H:i')); ?></div>
            </div>
            <div class="text-end">
                <div class="badge rounded-pill mb-2" style="background:#fff6e6;color:#b45309;"><?php echo e(ucfirst(str_replace('_',' ', $order->payment_status))); ?></div>
                <div class="badge rounded-pill d-block" style="background:#e8f3ff;color:#1d4ed8;">
                    <?php if($order->order_status === 'processing'): ?>
                        Diproses
                    <?php elseif($order->order_status === 'ready_to_ship'): ?>
                        Siap Dikirim
                    <?php elseif($order->order_status === 'completed'): ?>
                        Selesai
                    <?php elseif($order->order_status === 'payment_rejected'): ?>
                        Pembayaran Ditolak
                    <?php else: ?>
                        <?php echo e(ucfirst(str_replace('_',' ', $order->order_status ?? 'new'))); ?>

                    <?php endif; ?>
                </div>
                <div class="fw-bold mt-2">Rp <?php echo e(number_format($order->total,0,',','.')); ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-semibold">Produk</h6>
                <div class="mt-2">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-2 p-2" style="border-radius:10px;">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(asset('storage/' . ($it->produk->gambar ?? 'images/no-image.png'))); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:8px;" alt="">
                                <div class="ms-3">
                                    <div class="fw-semibold"><?php echo e($it->produk->nama_produk ?? '-'); ?></div>
                                    <div class="small text-muted"><?php echo e($it->quantity); ?> × Rp <?php echo e(number_format($it->price,0,',','.')); ?></div>
                                </div>
                                <div class="ms-auto fw-bold">Rp <?php echo e(number_format($it->price * $it->quantity,0,',','.')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <h6 class="mt-4 fw-semibold">Bukti Transfer</h6>
                <?php if($order->payment_proof): ?>
                    <div class="mt-2">
                        <img src="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" style="max-width:220px;border-radius:10px;" alt="">
                    </div>
                <?php else: ?>
                    <div class="text-muted">Belum ada bukti transfer.</div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <h6 class="fw-semibold">Detail Pesanan</h6>
                <div class="mt-2 small text-muted">Metode: <?php echo e($order->paymentMethod->name ?? '-'); ?></div>
                <div class="mt-2 small text-muted">Status pembayaran: <?php echo e($order->payment_status); ?></div>
                <div class="mt-2 small text-muted">Status pesanan: <?php echo e($order->order_status === 'processing' ? 'Diproses' : ($order->order_status === 'ready_to_ship' ? 'Siap Dikirim' : ($order->order_status === 'completed' ? 'Selesai' : ucfirst(str_replace('_',' ', $order->order_status ?? 'new'))))); ?></div>
                <?php if($order->pickup_method === 'delivery'): ?>
                    <div class="mt-2 small text-muted">Alamat kirim: <?php echo e($order->shipping_address ?? '-'); ?></div>
                    <div class="mt-2 small text-muted">Jarak: <?php echo e($order->shipping_distance_km !== null ? number_format($order->shipping_distance_km, 2, ',', '.') . ' km' : '-'); ?></div>
                    <div class="mt-2 small text-muted">Ongkir: Rp <?php echo e(number_format($order->shipping_cost ?? 0,0,',','.')); ?></div>
                <?php endif; ?>
                <div class="mt-3 fw-bold">Total: Rp <?php echo e(number_format($order->total,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.order_chat', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\pesanan\show.blade.php ENDPATH**/ ?>