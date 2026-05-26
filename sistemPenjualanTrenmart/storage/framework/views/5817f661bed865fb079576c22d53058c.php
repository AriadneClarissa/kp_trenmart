

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => $page === 'internal' ? 'internal_users' : 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">
            <?php echo e($page === 'internal' ? 'Daftar Pengguna Internal' : 'Daftar Pengguna (Admin & Pelanggan)'); ?>

        </h4>
    </div>

    <div class="card shadow-sm w-100">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle w-100 mb-0" style="font-size: 0.95rem; table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1 px-2" style="width: 5%;">#</th>
                            <th class="py-1 px-2" style="width: 11%;">Kode Pelanggan</th>
                            <th class="py-1 px-2" style="width: 18%;">Nama</th>
                            <th class="py-1 px-2" style="width: 26%;">Email</th>
                            <th class="py-1 px-2" style="width: 12%;">Role</th>
                            <th class="py-1 px-2" style="width: 12%;">Jenis Pelanggan</th>
                            <?php if($page === 'internal'): ?>
                                <th class="py-1 px-2" style="width: 8%;">Status</th>
                                <th class="py-1 px-2" style="width: 10%;">Aksi</th>
                            <?php endif; ?>
                            <th class="py-1 px-2" style="width: 8%;">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->id); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->kd_pelanggan ?? '-'); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->name); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->email); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->roleLabel()); ?></td>
                            <td class="py-1 px-2 text-truncate"><?php echo e($u->customer_type ?? '-'); ?></td>
                            <?php if($page === 'internal'): ?>
                                <td class="py-1 px-2">
                                    <?php if($u->isActive()): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-1 px-2">
                                    <?php if(auth()->user()->isOwner() && $u->id !== auth()->id()): ?>
                                        <form action="<?php echo e(route('admin.users.toggle_active', $u->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm <?php echo e($u->isActive() ? 'btn-outline-danger' : 'btn-outline-success'); ?>"
                                                onclick="return confirm('Yakin ingin <?php echo e($u->isActive() ? 'menonaktifkan' : 'mengaktifkan'); ?> user ini?')">
                                                <?php echo e($u->isActive() ? 'Nonaktifkan' : 'Aktifkan'); ?>

                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                            <td class="py-1 px-2"><?php echo e($u->created_at ? $u->created_at->format('d M Y') : '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/users/index.blade.php ENDPATH**/ ?>