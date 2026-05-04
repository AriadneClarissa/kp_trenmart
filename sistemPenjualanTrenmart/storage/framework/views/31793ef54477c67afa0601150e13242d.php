<?php $__env->startSection('content'); ?>
<div class="container mb-5 mt-4">
    <div class="card main-card p-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Edit Produk</h4>
        </div>

        <form action="<?php echo e(route('produk.update', $produk->kd_produk)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row">
                
                <div class="col-md-4">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Foto Produk</label>
                        
                        
                        <div class="upload-box mb-3" onclick="document.getElementById('multi_upload').click()"
                            style="border: 2px dashed #007bff; min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: #f0f7ff;">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                            <p class="mt-2 mb-1 small fw-bold text-center px-2">Klik untuk Pilih 1-3 Foto Sekaligus</p>
                            
                            <input type="file" id="multi_upload" name="files[]" accept="image/*" multiple="multiple" hidden onchange="handleMultiplePreview(this)">
                        </div>

                        
                        <div class="row g-2">
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_utama" src="<?php echo e(asset('storage/' . $produk->gambar)); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Utama</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_2" src="<?php echo e($produk->foto_2 ? asset('storage/' . $produk->foto_2) : asset('images/placeholder.png')); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 2</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_3" src="<?php echo e($produk->foto_3 ? asset('storage/' . $produk->foto_3) : asset('images/placeholder.png')); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 3</div>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-3 text-center" style="font-size: 0.7rem;">* Gunakan tombol <b>Ctrl</b> untuk memilih lebih dari 1 foto.</small>
                    </div>

                    
                    <div class="section-card bg-white p-3 border rounded-3">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kd_kategori" required>
                                <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k->kd_kategori); ?>" <?php echo e($produk->kd_kategori == $k->kd_kategori ? 'selected' : ''); ?>><?php echo e($k->nama_kategori); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <select class="form-select" name="kd_merk" required>
                                <?php $__currentLoopData = $merks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->kd_merk); ?>" <?php echo e($produk->kd_merk == $m->kd_merk ? 'selected' : ''); ?>><?php echo e($m->nama_merk); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Satuan</label>
                            <select class="form-select" name="kd_satuan" required>
                                <?php $__currentLoopData = $satuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sat->kd_satuan); ?>" <?php echo e($produk->kd_satuan == $sat->kd_satuan ? 'selected' : ''); ?>><?php echo e($sat->nama_satuan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div class="col-md-8">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-2"></i>Informasi Produk</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" value="<?php echo e($produk->nama_produk); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="5"><?php echo e($produk->deskripsi); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Harga Jual (Umum)</label>
                                <input type="number" name="harga_jual_umum" class="form-control" value="<?php echo e($produk->harga_jual_umum); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small">Stok Tersedia</label>
                                <input type="number" name="stok_tersedia" class="form-control" value="<?php echo e($produk->stok_tersedia); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="<?php echo e(route('produk.index')); ?>" class="btn btn-outline-secondary px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn fw-bold px-4 shadow-sm" style="background-color: #800000; color: white;">
                            Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function handleMultiplePreview(input) {
    const files = input.files;
    const previews = ['preview_utama', 'preview_2', 'preview_3'];
    
    if (files.length > 3) {
        alert("Maksimal hanya bisa memilih 3 foto.");
        input.value = ""; 
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const target = document.getElementById(previews[i]);
            if (target) {
                target.src = e.target.result;
            }
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/edit_produk.blade.php ENDPATH**/ ?>