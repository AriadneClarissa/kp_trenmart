

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => $page === 'internal' ? 'internal_users' : 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">
            <?php echo e($page === 'internal' ? 'Daftar Pengguna Internal' : 'Daftar Pengguna (Admin & Pelanggan)'); ?>

        </h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Pelanggan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jenis Pelanggan</th>
                            <th>Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($u->id); ?></td>
                            <td><?php echo e($u->kd_pelanggan ?? '-'); ?></td>
                            <td><?php echo e($u->name); ?></td>
                            <td><?php echo e($u->email); ?></td>
                            <td><?php echo e($u->roleLabel()); ?></td>
                            <td><?php echo e($u->customer_type ?? '-'); ?></td>
                            <td><?php echo e($u->created_at ? $u->created_at->format('d M Y') : '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\users\index.blade.php ENDPATH**/ ?>