<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h4>Pilih Metode Pembayaran</h4>

    <div class="row mt-4">
        <div class="col-md-8">
            <form action="<?php echo e(route('checkout.place_order')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Metode Pembayaran</h6>
                    <?php $__currentLoopData = $paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method_id" id="pm<?php echo e($method->id); ?>" value="<?php echo e($method->id); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="pm<?php echo e($method->id); ?>"><?php echo e($method->name); ?> <?php if($method->account_number): ?> - <?php echo e($method->account_number); ?> <?php endif; ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Metode Pengambilan</h6>
                    <?php $__currentLoopData = $pickupOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="pickup_method" id="pmode<?php echo e($key); ?>" value="<?php echo e($key); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="pmode<?php echo e($key); ?>"><?php echo e($label); ?></label>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Ringkasan</h6>
                    <p>Total: <strong>Rp <?php echo e(number_format($total,0,',','.')); ?></strong></p>
                    <button class="btn btn-primary">Lanjutkan dan Unggah Bukti</button>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="fw-bold">Item</h6>
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between mb-2">
                    <div><?php echo e($item->produk->nama_produk); ?> x<?php echo e($item->jumlah); ?></div>
                    <div>Rp <?php echo e(number_format($item->harga_at_time * $item->jumlah,0,',','.')); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/checkout/select_payment.blade.php ENDPATH**/ ?>