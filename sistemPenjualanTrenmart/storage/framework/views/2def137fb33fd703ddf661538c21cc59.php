<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin._header', ['activePage' => 'users'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Buat Akun Pelanggan Langganan</h4>
    </div>

    <div class="card p-4 shadow-sm">
        <form action="<?php echo e(route('admin.users.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Kode Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="kd_pelanggan" class="form-control" placeholder="Contoh: PL0001" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Pelanggan</label>
                    <input type="text" class="form-control bg-light text-muted" value="Langganan (Grosir)" style="pointer-events: none;" readonly>
                    <input type="hidden" name="customer_type" value="langganan">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Default (opsional)</label>
                    <input type="text" name="default_password" class="form-control" placeholder="Kosongkan untuk generate otomatis">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Organisasi <span class="text-danger">*</span></label>
                    <input type="text" name="organization_name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipe Organisasi (opsional)</label>
                    <input type="text" name="organization_type" class="form-control">
                </div>

                <div class="col-12 text-end">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary me-2">Batal</a>
                    <button class="btn btn-primary">Buat Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
function validateEmail(input) {
    const val = input.value;
    const errorElement = document.getElementById('email-error');
    
    // Regex standar untuk validasi format email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (val.length > 0) {
        if (!emailPattern.test(val)) {
            // Jika format salah
            errorElement.style.display = 'block';
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        } else {
            // Jika format sudah benar
            errorElement.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    } else {
        // Jika kosong
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\users\create.blade.php ENDPATH**/ ?>