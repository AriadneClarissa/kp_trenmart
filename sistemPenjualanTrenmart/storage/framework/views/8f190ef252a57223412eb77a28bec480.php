

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
        <h1 class="mb-0">Log Aktivitas Internal</h1>
        <a href="<?php echo e(route('beranda')); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>Pelaku</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($log->id); ?></td>
                        <td><?php echo e($log->created_at->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')); ?></td>
                        <td><?php echo e($log->actor ? $log->actor->name . ' (' . $log->actor->email . ')' : 'System'); ?></td>
                        <td><?php echo e($log->action); ?></td>
                        <td style="max-width:420px;overflow-wrap:break-word"><?php echo e($log->details); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada log</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <?php echo e($logs->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\logs\index.blade.php ENDPATH**/ ?>