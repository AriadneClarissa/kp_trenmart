<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
                <h6 class="fw-bold mb-3"><i class="bi bi-tag-fill me-2"></i>Tambah Merk Baru</h6>
                <form action="<?php echo e(route('merk.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label small">Kode Merk</label>
                        <input type="text" name="kd_merk" class="form-control" placeholder="Contoh: M001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Nama Merk</label>
                        <input type="text" name="nama_merk" class="form-control" placeholder="Contoh: Faber-Castell" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Simpan Merk</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
                <h6 class="fw-bold mb-3">Daftar Merk Terdaftar</h6>
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Merk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $merk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><span class="badge bg-info text-dark"><?php echo e($item->kd_merk); ?></span></td>
                            <td><?php echo e($item->nama_merk); ?></td>
                            <td class="text-center">
                                <form action="<?php echo e(route('merk.destroy', $item->kd_merk)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus merk ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada data merk.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\kelola_merk.blade.php ENDPATH**/ ?>