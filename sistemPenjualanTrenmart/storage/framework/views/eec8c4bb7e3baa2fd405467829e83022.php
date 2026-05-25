<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => 'customers'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pelanggan (Langganan & Regular)</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Organisasi (jika ada)</th>
                            <th>Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($c->id); ?></td>
                            <td><?php echo e($c->name); ?></td>
                            <td><?php echo e($c->email); ?></td>
                            <td><?php echo e(strtoupper($c->customer_type ?? 'regular')); ?></td>
                            <td><?php echo e($c->phone_number ?? '-'); ?></td>
                            <td><?php echo e($c->home_address ?? '-'); ?></td>
                            <td><?php echo e($c->organization_name ?? '-'); ?></td>
                            <td><?php echo e($c->created_at ? $c->created_at->format('d M Y') : '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>