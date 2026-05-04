<?php $__env->startSection('content'); ?>
<div class="container mb-5 mt-4">
    <div class="card main-card p-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Tambah Produk Baru</h4>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('produk.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            
            <div class="mb-4">
                <label class="form-label fw-bold">Kode Produk <span class="text-danger">*</span></label>
                <input type="text" name="kd_produk" class="form-control" placeholder="Masukkan Kode Produk (Contoh: PRD001)" required value="<?php echo e(old('kd_produk')); ?>">
            </div>

            <input type="hidden" name="origin" value="<?php echo e($source); ?>">

            <div class="row">
                
                <div class="col-md-4">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Foto Produk</label>
                        <div class="upload-box" id="drop-area" onclick="document.getElementById('multi_upload').click()" 
                             style="border: 2px dashed #ccc; min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer;">
                            
                            
                            <div id="preview-container" class="d-none w-100 p-2">
                                <div class="row g-2" id="preview-row"></div>
                            </div>

                            <div id="upload-placeholder" class="text-center">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                <p class="mt-2 mb-1 small fw-bold">Klik untuk unggah gambar</p>
                                <span class="text-muted" style="font-size: 0.75rem;">Maksimal 3 foto (JPG, PNG, WEBP)</span>
                            </div>

                            
                            <input type="file" id="multi_upload" name="files[]" accept="image/*" multiple hidden onchange="previewImages(this)" required>
                        </div>
                        <small class="text-muted d-block mt-2">Gunakan <b>Ctrl + Klik</b> untuk pilih hingga 3 foto.</small>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select mb-3" name="kd_kategori" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->kd_kategori); ?>" <?php echo e(old('kd_kategori') == $k->kd_kategori ? 'selected' : ''); ?>><?php echo e($k->nama_kategori); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <label class="form-label mt-2">Merk</label>
                        <select class="form-select mb-3" name="kd_merk" required>
                            <option value="" selected disabled>Pilih Merk</option>
                            <?php $__currentLoopData = $merks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->kd_merk); ?>" <?php echo e(old('kd_merk') == $m->kd_merk ? 'selected' : ''); ?>><?php echo e($m->nama_merk); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <label class="form-label mt-2">Satuan</label>
                        <select class="form-select" name="kd_satuan" required>
                            <option value="" selected disabled>Pilih Satuan</option>
                            <?php $__currentLoopData = $satuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sat->kd_satuan); ?>" <?php echo e(old('kd_satuan') == $sat->kd_satuan ? 'selected' : ''); ?>><?php echo e($sat->nama_satuan); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                
                <div class="col-md-8">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Produk</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Penghapus Faber Castle" required value="<?php echo e(old('nama_produk')); ?>">
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan spesifikasi produk..."><?php echo e(old('deskripsi')); ?></textarea>
                        </div>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tags me-2 text-success"></i>Detail Harga & Stok</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Umum)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_umum" class="form-control border-start-0" placeholder="0" required value="<?php echo e(old('harga_jual_umum')); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Langganan)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_langganan" class="form-control border-start-0" placeholder="0" value="<?php echo e(old('harga_jual_langganan')); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jumlah Stok</label>
                                <input type="number" name="stok_tersedia" class="form-control" placeholder="0" required value="<?php echo e(old('stok_tersedia')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="<?php echo e($source == 'beranda' ? route('beranda') : route('produk.index')); ?>" class="btn btn-outline-secondary px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn btn-simpan fw-bold shadow-sm px-4" style="background-color: #800000; color: white;">
                            <i class="bi bi-check-lg me-1"></i> Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function previewImages(input) {
        const previewRow = document.getElementById('preview-row');
        const container = document.getElementById('preview-container');
        const placeholder = document.getElementById('upload-placeholder');
        
        previewRow.innerHTML = ''; 
        
        if (input.files && input.files.length > 0) {
            if (input.files.length > 3) {
                alert("Maksimal hanya bisa memilih 3 foto sekaligus!");
                input.value = "";
                return;
            }

            placeholder.classList.add('d-none');
            container.classList.remove('d-none');

            Array.from(input.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-4';
                    col.innerHTML = `
                        <div class="text-center p-1 border rounded bg-light">
                            <img src="${e.target.result}" class="img-fluid" style="height: 100px; object-fit: cover; width: 100%;">
                            <div style="font-size: 0.6rem;" class="mt-1">${index === 0 ? 'Utama' : 'Foto ' + (index + 1)}</div>
                        </div>
                    `;
                    previewRow.appendChild(col);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/tambah_produk.blade.php ENDPATH**/ ?>