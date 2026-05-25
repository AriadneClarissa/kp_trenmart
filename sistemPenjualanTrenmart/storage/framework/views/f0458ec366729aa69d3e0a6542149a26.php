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
                        
                        
                        <div class="upload-box mb-3" onclick="document.getElementById('multi_upload').click()"
                            style="border: 2px dashed #007bff; min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: #f0f7ff;">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                            <p class="mt-2 mb-1 small fw-bold text-center px-2">Klik untuk Pilih 1-3 Foto Sekaligus</p>
                            
                            <input type="file" id="multi_upload" name="files[]" accept="image/*" multiple hidden onchange="previewImages(this)" required>
                        </div>

                        
                        <div class="row g-2">
                            
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_utama" src="<?php echo e(isset($produk->gambar) ? \App\Helpers\StorageProxy::url($produk->gambar) : asset('images/placeholder.png')); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Utama</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_2" src="<?php echo e(isset($produk->foto_2) ? \App\Helpers\StorageProxy::url($produk->foto_2) : asset('images/placeholder.png')); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 2</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_3" src="<?php echo e(isset($produk->foto_3) ? \App\Helpers\StorageProxy::url($produk->foto_3) : asset('images/placeholder.png')); ?>" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 3</div>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-3 text-center" style="font-size: 0.7rem;">* Gunakan tombol <b>Ctrl</b> untuk memilih lebih dari 1 foto.</small>
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
        const previewIds = ['preview_utama', 'preview_2', 'preview_3'];
        
        // Reset semua gambar ke placeholder terlebih dahulu
        previewIds.forEach(id => {
            document.getElementById(id).src = "<?php echo e(asset('images/placeholder.png')); ?>";
        });

        if (input.files && input.files.length > 0) {
            // Validasi maksimal 3 foto
            if (input.files.length > 3) {
                alert("Maksimal hanya bisa memilih 3 foto sekaligus!");
                input.value = ""; 
                return;
            }

            // Loop file yang dipilih dan masukkan ke masing-masing ID preview
            Array.from(input.files).forEach((file, index) => {
                if (index < 3) { 
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewIds[index]).src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\tambah_produk.blade.php ENDPATH**/ ?>