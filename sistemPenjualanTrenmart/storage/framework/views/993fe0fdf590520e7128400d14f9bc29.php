<?php $__env->startSection('content'); ?>
<div class="container">
    <h3>Pengaturan Ongkir</h3>
    <form action="<?php echo e(route('admin.shipping.update')); ?>" method="POST" id="shippingForm">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Tarif Ongkir Flat (Rp)</label>
            <input type="number" name="flat_rate" id="flatRate"
                   value="<?php echo e(old('flat_rate', $settings->flat_rate ?? $settings->price_per_km ?? 15000)); ?>"
                   class="form-control" min="0" required readonly>
            <small class="text-muted d-block mt-2">Nominal ini berlaku untuk semua pesanan delivery.</small>
        </div>

        <button type="button" class="btn btn-warning me-2" id="editBtn" onclick="enableEdit()">Edit</button>
        <button type="submit" class="btn btn-success d-none" id="saveBtn">Simpan Perubahan</button>
    </form>
</div>

<script>
    function enableEdit() {
        const flatRate = document.getElementById('flatRate');
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');

        if (flatRate) {
            flatRate.removeAttribute('readonly');
            flatRate.focus();
        }

        if (editBtn) {
            editBtn.classList.add('d-none');
        }

        if (saveBtn) {
            saveBtn.classList.remove('d-none');
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\shipping\edit.blade.php ENDPATH**/ ?>