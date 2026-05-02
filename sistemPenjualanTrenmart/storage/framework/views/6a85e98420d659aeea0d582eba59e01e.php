<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Buat Akun Admin Baru</h4>
    </div>

    <div class="card p-4 shadow-sm" style="max-width: 600px;">
        <form action="<?php echo e(route('admin.admins.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Nama admin" required>
                </div>
                
                <div class="col-md-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Email admin" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Password Default (opsional)</label>
                    <input type="text" name="default_password" class="form-control" placeholder="Kosongkan untuk generate otomatis">
                    <small class="text-muted d-block mt-1">Jika kosong, password akan dibuat secara otomatis</small>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1" checked>
                        <label class="form-check-label" for="send_email">Kirim email berisi kredensial ke admin baru</label>
                    </div>
                </div>

                <div class="col-12 border-top pt-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-check-circle me-1"></i> Buat Akun Admin
                    </button>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </a>
                </div>
            </div>
        </form>
    </div>

    <?php if($errors->any()): ?>
    <div class="alert alert-danger mt-3" role="alert">
        <strong>Error:</strong>
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/admins/create.blade.php ENDPATH**/ ?>