<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => 'customers'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pelanggan (Langganan & Regular)</h4>
    </div>

    <div class="card shadow-sm w-100">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle w-100 mb-0" style="font-size: 0.95rem; table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1 px-2" style="width: 5%;">#</th>
                            <th class="py-1 px-2" style="width: 16%;">Nama</th>
                            <th class="py-1 px-2" style="width: 24%;">Email</th>
                            <th class="py-1 px-2" style="width: 12%;">Jenis</th>
                            <th class="py-1 px-2" style="width: 12%;">No. Telepon</th>
                            <th class="py-1 px-2" style="width: 15%;">Alamat</th>
                            <th class="py-1 px-2" style="width: 12%;">Organisasi (jika ada)</th>
                            <th class="py-1 px-2" style="width: 10%;">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->id); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->name); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->email); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e(strtoupper($c->customer_type ?? 'regular')); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->phone_number ?? '-'); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->home_address ?? '-'); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($c->organization_name ?? '-'); ?></td>
                            <td class="py-1 px-2"><?php echo e($c->created_at ? $c->created_at->format('d M Y') : '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/customers/index.blade.php ENDPATH**/ ?>