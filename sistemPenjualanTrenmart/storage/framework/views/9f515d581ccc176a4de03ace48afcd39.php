

<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5">
    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h4 class="fw-bold mb-4">Tambah Paket Bundling</h4>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('bundling.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="row">
                
                <div class="col-md-5">
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <label class="form-label fw-bold">Tipe Paket Bundling</label>
                        <select id="bundling_type" class="form-select mb-3 border-primary fw-bold" onchange="adjustProductRows()">
                            <option value="2" <?php echo e(old('bundling_type') == '2' ? 'selected' : ''); ?>>Bundling 2 Barang</option>
                            <option value="3" <?php echo e(old('bundling_type') == '3' ? 'selected' : ''); ?>>Bundling 3 Barang</option>
                        </select>

                        <label class="form-label fw-bold">Nama Paket</label>
                        <input type="text" name="name" class="form-control mb-3" 
                               placeholder="Contoh: Paket Alat Tulis Hemat" required 
                               value="<?php echo e(old('name')); ?>">
                        
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo e(old('description')); ?></textarea>
                    </div>
                </div>

                
                <div class="col-md-7">
                    <div class="p-3 border rounded-3 shadow-sm bg-white">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Pilih Produk dalam Paket</h6>
                        <p class="small text-muted mb-3">Klik kotak di bawah, lalu langsung ketik nama produk atau merk untuk mencari barang.</p>
                        
                        <div id="bundling-container">
                            
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="card p-3 bg-light border-0">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga Normal:</span>
                            <div class="fw-bold h5 mb-0">
                                Rp <span id="display_total_normal">0</span>
                                <input type="hidden" name="total_normal_price" id="input_total_normal" value="<?php echo e(old('total_normal_price', 0)); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Harga Bundling (Harga Jual)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-0">Rp</span>
                                <input type="number" name="bundling_price" class="form-control border-success" 
                                       required value="<?php echo e(old('bundling_price')); ?>">
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary py-3 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Simpan Paket Bundling
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const produkData = <?php echo json_encode($produk, 15, 512) ?>;

    $(document).ready(function() {
        adjustProductRows(); 
    });

    function adjustProductRows() {
        const type = document.getElementById('bundling_type').value;
        const container = document.getElementById('bundling-container');
        
        const currentSelections = [];
        document.querySelectorAll('.product-select').forEach(select => {
            currentSelections.push(select.value);
        });

        // Kosongkan container
        container.innerHTML = '';

        for (let i = 1; i <= type; i++) {
            const oldValue = currentSelections[i-1] || '';
            const rowHtml = `
                <div class="row g-2 mb-3 align-items-end item-row">
                    <div class="col-8">
                        <label class="small text-muted fw-bold">Produk ${i}</label>
                        
                        <div class="select2-wrapper" style="position: relative;">
                            <select name="product_id[]" class="form-select product-select select2" required onchange="calculatePrices(this)">
                                <option value=""></option>
                                ${produkData.map(p => {
                                    let merkText = p.merk ? p.merk.nama_merk : 'Tanpa Merk';
                                    return `<option value="${p.kd_produk}" data-price="${p.harga_jual_umum}" ${oldValue == p.kd_produk ? 'selected' : ''}>
                                        ${p.nama_produk} (${merkText})
                                    </option>`;
                                }).join('')}
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control bg-light price-display" readonly placeholder="Rp 0">
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', rowHtml);
        }

        // Hancurkan Select2 lama jika ada (agar tidak bentrok saat ganti tipe)
        if ($('.select2').hasClass("select2-hidden-accessible")) {
            $('.select2').select2('destroy');
        }

        // Inisialisasi ulang Select2 dengan Dropdown Parent (Solusi Anti-Glitch)
        $('.select2').each(function() {
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Klik lalu ketik nama produk...',
                allowClear: true,
                dropdownParent: $(this).parent() // <-- INI KUNCI UTAMANYA
            });
        });

        document.querySelectorAll('.product-select').forEach(select => {
            if(select.value) calculatePrices(select);
        });
    }

    function calculatePrices(select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const row = select.closest('.item-row');
        row.querySelector('.price-display').value = "Rp " + price.toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.product-select').forEach(select => {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.getAttribute('data-price')) {
                total += parseFloat(selectedOption.getAttribute('data-price'));
            }
        });
        document.getElementById('display_total_normal').innerText = total.toLocaleString('id-ID');
        document.getElementById('input_total_normal').value = total;
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/manage_bundling.blade.php ENDPATH**/ ?>