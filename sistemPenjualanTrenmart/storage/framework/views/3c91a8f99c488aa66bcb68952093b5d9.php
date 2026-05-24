

<?php $__env->startSection('content'); ?>
<div class="container">
    <h3>Pengaturan Ongkir</h3>
    <form action="<?php echo e(route('admin.shipping.update')); ?>" method="POST" id="shippingForm">
    <?php echo csrf_field(); ?>
    <div class="mb-3">
    <label class="form-label">Gratis Ongkir (Jarak KM Pertama)</label>
    <input type="number" step="0.1" name="free_limit" id="inputLimit"
           value="<?php echo e(old('free_limit', $settings->free_limit ?? 1.0)); ?>" 
           class="form-control shipping-input" readonly required>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga per KM Berikutnya (Rp)</label>
        <input type="number" name="price_per_km" id="inputPrice"
            value="<?php echo e(old('price_per_km', $settings->price_per_km ?? 2000)); ?>" 
            class="form-control shipping-input" readonly required>
    </div>
    <button type="button" id="editBtn" class="btn btn-danger" onclick="enableEdit()">Edit</button>
    <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan Perubahan</button>
</form>

<script>
function enableEdit() {
    // Aktifkan input
    document.getElementById('inputLimit').removeAttribute('readonly');
    document.getElementById('inputPrice').removeAttribute('readonly');
    
    // Ganti tombol
    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('saveBtn').style.display = 'inline-block';
}

// Opsional: Jika ingin tombol kembali ke "Edit" setelah selesai mengetik (hanya jika diperlukan)
document.querySelectorAll('.shipping-input').forEach(input => {
    input.addEventListener('input', () => {
        // Logika tambahan jika perlu
    });
});
</script>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/shipping/edit.blade.php ENDPATH**/ ?>