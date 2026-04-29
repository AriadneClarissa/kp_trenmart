<?php $__env->startSection('content'); ?>
<div class="container my-4 my-md-5">
    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($isAdminEditMode): ?>
        <form action="<?php echo e(route('admin.tentang.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
                <div>
                    <h1 class="fw-bold mb-1" style="font-size: 2rem;">Halaman Tentang Kami</h1>
                    <p class="text-muted mb-0">Atur informasi toko, foto, lokasi, dan pengumuman yang tampil di website pelanggan.</p>
                </div>
                <a href="<?php echo e(route('tentang', ['preview' => 1])); ?>" class="btn btn-outline-danger fw-semibold" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Pelanggan
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="bi bi-image me-2 text-danger"></i>Foto Banner Toko</h4>
                    <div class="border rounded-4 p-3" style="border-style: dashed !important;">
                        <?php if(!empty($data['tentang_banner'])): ?>
                            <img src="<?php echo e(asset('storage/' . $data['tentang_banner'])); ?>" alt="Banner Toko" class="img-fluid rounded-4 mb-3" style="max-height: 320px; width: 100%; object-fit: cover;">
                        <?php endif; ?>
                        <input type="file" class="form-control <?php $__errorArgs = ['tentang_banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tentang_banner" accept="image/png,image/jpeg,image/jpg,image/webp">
                        <small class="text-muted">JPG, PNG, WEBP - Maks. 5 MB</small>
                        <?php $__errorArgs = ['tentang_banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Informasi Utama</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Toko</label>
                            <input type="text" name="tentang_nama_toko" class="form-control <?php $__errorArgs = ['tentang_nama_toko'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tentang_nama_toko', $data['tentang_nama_toko'])); ?>" required>
                            <?php $__errorArgs = ['tentang_nama_toko'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tagline / Slogan</label>
                            <input type="text" name="tentang_tagline" class="form-control <?php $__errorArgs = ['tentang_tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tentang_tagline', $data['tentang_tagline'])); ?>">
                            <?php $__errorArgs = ['tentang_tagline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi Toko</label>
                            <textarea name="tentang_deskripsi" rows="4" class="form-control <?php $__errorArgs = ['tentang_deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('tentang_deskripsi', $data['tentang_deskripsi'])); ?></textarea>
                            <?php $__errorArgs = ['tentang_deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Kontak & Lokasi</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Toko</label>
                            <textarea name="tentang_alamat" rows="2" class="form-control <?php $__errorArgs = ['tentang_alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('tentang_alamat', $data['tentang_alamat'])); ?></textarea>
                            <?php $__errorArgs = ['tentang_alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link Google Maps (opsional)</label>
                            <input type="text" name="tentang_maps_link" class="form-control <?php $__errorArgs = ['tentang_maps_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tentang_maps_link', $data['tentang_maps_link'])); ?>" placeholder="https://maps.google.com/...">
                            <small class="text-muted">Jika diisi, lokasi ini diprioritaskan untuk tampilan peta.</small>
                            <?php $__errorArgs = ['tentang_maps_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="tentang_telepon" class="form-control <?php $__errorArgs = ['tentang_telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tentang_telepon', $data['tentang_telepon'])); ?>">
                            <?php $__errorArgs = ['tentang_telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="tentang_email" class="form-control <?php $__errorArgs = ['tentang_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('tentang_email', $data['tentang_email'])); ?>">
                            <?php $__errorArgs = ['tentang_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jam Operasional</label>
                            <textarea name="tentang_jam_operasional" rows="3" class="form-control <?php $__errorArgs = ['tentang_jam_operasional'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('tentang_jam_operasional', $data['tentang_jam_operasional'])); ?></textarea>
                            
                            <?php $__errorArgs = ['tentang_jam_operasional'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Fitur Unggulan</h4>
                        <button type="button" id="addFeatureBtn" class="btn btn-outline-danger fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    </div>

                    <div id="featureContainer" class="d-flex flex-column gap-3">
                        <?php $__currentLoopData = $fiturUnggulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fitur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="feature-row border rounded-3 p-3 bg-light-subtle">
                                <div class="row g-2 align-items-start">
                                    <div class="col-md-2">
                                        <select class="form-select" name="feature_icon[]">
                                            <?php
                                                $icons = ['shop' => 'Store', 'truck' => 'Truck', 'patch-check' => 'ShieldCheck', 'headset' => 'Headphones', 'bag-check' => 'Bag Check', 'stars' => 'Stars'];
                                            ?>
                                            <?php $__currentLoopData = $icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value); ?>" <?php echo e(($fitur['icon'] ?? 'shop') === $value ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="feature_title[]" placeholder="Judul fitur" value="<?php echo e($fitur['title'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="feature_description[]" placeholder="Deskripsi fitur" value="<?php echo e($fitur['description'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-link text-danger remove-feature" title="Hapus fitur">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn px-4 py-2 fw-semibold" style="background-color: #981b3f; color: #fff; border-radius: 12px;">
                    <i class="bi bi-floppy me-2"></i> Simpan Informasi Toko
                </button>
            </div>
        </form>
    <?php else: ?>
        <div class="text-center mb-5">
            <?php if(!empty($data['tentang_banner'])): ?>
                <img src="<?php echo e(asset('storage/' . $data['tentang_banner'])); ?>" alt="Banner <?php echo e($data['tentang_nama_toko']); ?>" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 360px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fw-bold mb-2"><?php echo e($data['tentang_nama_toko']); ?></h1>
                        <p class="text-danger fw-semibold mb-4"><?php echo e($data['tentang_tagline']); ?></p>
                        <p class="text-muted mb-0" style="line-height: 1.8;"><?php echo e($data['tentang_deskripsi']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Kontak & Lokasi</h4>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2 text-danger"></i><?php echo e($data['tentang_alamat']); ?></li>
                            <li class="mb-2"><i class="bi bi-telephone me-2 text-danger"></i><?php echo e($data['tentang_telepon']); ?></li>
                            <li class="mb-2"><i class="bi bi-envelope me-2 text-danger"></i><?php echo e($data['tentang_email']); ?></li>
                            <li><i class="bi bi-clock me-2 text-danger"></i><?php echo e($data['tentang_jam_operasional']); ?></li>
                        </ul>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                            <iframe src="<?php echo e($mapEmbedUrl); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="row g-3">
                <?php $__currentLoopData = $fiturUnggulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fitur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 42px; height: 42px; background-color: #fce7ef; color: #981b3f;">
                                    <i class="bi bi-<?php echo e($fitur['icon'] ?? 'shop'); ?>"></i>
                                </div>
                                <h5 class="fw-bold" style="font-size: 1rem;"><?php echo e($fitur['title'] ?? ''); ?></h5>
                                <p class="text-muted mb-0" style="font-size: 0.92rem;"><?php echo e($fitur['description'] ?? ''); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($isAdminEditMode): ?>
<script>
(function () {
    const container = document.getElementById('featureContainer');
    const addButton = document.getElementById('addFeatureBtn');

    const createRow = () => {
        const row = document.createElement('div');
        row.className = 'feature-row border rounded-3 p-3 bg-light-subtle';
        row.innerHTML = `
            <div class="row g-2 align-items-start">
                <div class="col-md-2">
                    <select class="form-select" name="feature_icon[]">
                        <option value="shop">Store</option>
                        <option value="truck">Truck</option>
                        <option value="patch-check">ShieldCheck</option>
                        <option value="headset">Headphones</option>
                        <option value="bag-check">Bag Check</option>
                        <option value="stars">Stars</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="feature_title[]" placeholder="Judul fitur">
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="feature_description[]" placeholder="Deskripsi fitur">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-link text-danger remove-feature" title="Hapus fitur">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        return row;
    };

    addButton.addEventListener('click', function () {
        container.appendChild(createRow());
    });

    container.addEventListener('click', function (event) {
        const removeBtn = event.target.closest('.remove-feature');
        if (!removeBtn) {
            return;
        }

        const rows = container.querySelectorAll('.feature-row');
        if (rows.length <= 1) {
            return;
        }

        removeBtn.closest('.feature-row').remove();
    });
})();
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/tentang-kami.blade.php ENDPATH**/ ?>