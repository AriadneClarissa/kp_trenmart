<?php $__env->startSection('content'); ?>
<style>
    .auth-card {
        max-width: 470px;
        margin: 50px auto;
        border: none;
        border-radius: 25px;
        background-color: #fcf8f8;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .card-header-custom {
        background-color: white;
        border-bottom: 1px solid #f1f1f1;
        padding: 25px;
    }
    .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
    .form-control-custom {
        border-radius: 12px;
        border: 1.5px solid #eee;
        padding: 12px 15px;
        background-color: white;
        transition: 0.3s;
    }
    .form-control-custom:focus {
        border-color: #800000;
        box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.1);
        outline: none;
    }
    .btn-masuk-trenmart {
        background-color: #800000 !important;
        color: white !important;
        border-radius: 12px;
        padding: 14px;
        width: 100%;
        font-weight: bold;
        border: none;
        margin-top: 10px;
        display: block;
        transition: all 0.2s ease;
        letter-spacing: 1px;
        cursor: pointer;
    }
    .btn-masuk-trenmart:hover { background-color: #950000 !important; }
    .helper-text { color: #6b7280; font-size: 0.9rem; }
    .back-link { color: #800000; text-decoration: none; font-weight: 600; }
    .back-link:hover { text-decoration: underline; }
</style>

<div class="container mt-4">
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <img src="<?php echo e(asset('images/spanduktoko.png')); ?>" class="w-100 rounded-4 shadow-sm" style="height: 200px; object-fit: cover;" alt="Banner">
        </div>
    </div>

    <div class="card auth-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold" style="color: #800000;">Lupa Password</h5>
            <p class="mb-0 helper-text">Masukkan email yang terdaftar untuk menerima tautan reset password.</p>
        </div>

        <div class="card-body p-4">
            <?php if(session('status')): ?>
                <div class="alert alert-success border-0 shadow-sm small mb-4">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger border-0 small mb-4">
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label class="form-label">Email Pengguna</label>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="Masukkan email Anda" value="<?php echo e(old('email')); ?>" required autofocus>
                </div>

                <button type="submit" class="btn-masuk-trenmart shadow">KIRIM LINK RESET</button>
            </form>

            <div class="text-center mt-3">
                <a href="<?php echo e(route('login')); ?>" class="back-link">← Kembali ke Login</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\auth\forgot-password.blade.php ENDPATH**/ ?>