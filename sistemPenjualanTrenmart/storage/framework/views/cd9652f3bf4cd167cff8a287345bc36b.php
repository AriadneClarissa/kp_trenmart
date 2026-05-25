<?php $__env->startSection('content'); ?>
<style>
    .auth-card {
        max-width: 500px;
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
    .input-group-text {
        border-radius: 0 12px 12px 0;
        background-color: white;
        cursor: pointer;
        border-left: none;
    }
    .form-control-custom.with-eye { border-right: none; }
    .syarat-text { font-size: 11px; color: #dc3545; margin-top: 4px; }
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
            <h5 class="mb-0 fw-bold" style="color: #800000;">Reset Password</h5>
            <p class="mb-0 text-muted small">Silakan isi password baru Anda untuk akun yang terdaftar.</p>
        </div>

        <div class="card-body p-4">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger border-0 small mb-4">
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('password.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form-control-custom" value="<?php echo e(old('email', $request->email)); ?>" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control form-control-custom with-eye" placeholder="Masukkan password baru" required autocomplete="new-password">
                        <span class="input-group-text" onclick="togglePassword('password', 'icon-pass')">
                            <i class="bi bi-eye" id="icon-pass"></i>
                        </span>
                    </div>
                    <div class="syarat-text">*Minimal 8 karakter (samakan dengan aturan daftar).</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-custom with-eye" placeholder="Ulangi password baru" required autocomplete="new-password">
                        <span class="input-group-text" onclick="togglePassword('password_confirmation', 'icon-confirm')">
                            <i class="bi bi-eye" id="icon-confirm"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-masuk-trenmart shadow">SIMPAN PASSWORD BARU</button>
            </form>

            <div class="text-center mt-3">
                <a href="<?php echo e(route('login')); ?>" class="back-link">← Kembali ke Login</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\auth\reset-password.blade.php ENDPATH**/ ?>