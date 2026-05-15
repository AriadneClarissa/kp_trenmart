

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => 'payment'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Manajemen Metode Pembayaran</h4>
    </div>

    <div class="card p-3 mt-3">
        <form action="<?php echo e(route('admin.payment_methods.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-2">
                <div class="col-md-4"><input name="name" class="form-control" placeholder="Nama metode" required></div>
                <div class="col-md-3"><input name="account_name" class="form-control" placeholder="Pemilik rekening"></div>
                <div class="col-md-3"><input name="account_number" class="form-control" placeholder="No. rekening"></div>
                <div class="col-md-2"><button class="btn btn-primary">Tambah</button></div>
            </div>
        </form>

        <hr>
        <ul class="list-group">
            <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong><?php echo e($m->name); ?></strong>
                    <div class="small text-muted"><?php echo e($m->account_name); ?> - <?php echo e($m->account_number); ?></div>
                </div>
                <div>
                    <form action="<?php echo e(route('admin.payment_methods.destroy', $m->id)); ?>" method="POST" style="display:inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/payment_methods/index.blade.php ENDPATH**/ ?>