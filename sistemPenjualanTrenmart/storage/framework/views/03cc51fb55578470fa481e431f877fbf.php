<div class="card mt-3 p-3">
    <h6>Obrolan Pesanan</h6>
    <div id="order-chat-<?php echo e($order->id); ?>" style="max-height:300px;overflow:auto;" class="mb-3">
        <?php $__currentLoopData = $order->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-2">
                <div class="small text-muted"><?php echo e($m->created_at->format('d M H:i')); ?> · <?php echo e($m->user ? $m->user->name : 'Admin'); ?></div>
                <div class="p-2" style="background:#f7f7f9;border-radius:8px;"><?php echo e($m->message); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form action="<?php echo e(route('orders.messages.store', $order->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="input-group">
            <input name="message" class="form-control" placeholder="Ketik pesan..." required>
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
</div>
<?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/partials/order_chat.blade.php ENDPATH**/ ?>