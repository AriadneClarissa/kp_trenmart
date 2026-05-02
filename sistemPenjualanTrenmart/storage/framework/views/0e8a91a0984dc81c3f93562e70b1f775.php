<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Admin Dashboard</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon: #660000; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .text-maroon { color: var(--maroon); }
        .bg-maroon { background-color: var(--maroon); color: white; }
        .card { border: none; border-radius: 15px; }
        .btn-maroon { background-color: var(--maroon); color: white; }
        .badge-admin { background-color: #dc3545; font-weight: 600; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-maroon mb-0">Panel Admin - Trenmart</h2>
        <a href="<?php echo e(route('beranda')); ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
            <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white pt-4 px-4 border-0">
            <h5 class="fw-bold"><i class="bi bi-person-check-fill me-2 text-success"></i>Persetujuan Pelanggan Grosir</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th class="text-center">Informasi</th> 
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pendingUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModalPending<?php echo e($user->id); ?>" class="text-primary small fw-bold text-decoration-none">
                                Lihat Detail >
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada permintaan persetujuan baru.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__currentLoopData = $pendingUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailModalPending<?php echo e($user->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header bg-maroon text-white border-0 py-3">
                <h6 class="modal-title fw-bold small"><i class="bi bi-person-badge me-2"></i>PROFIL LENGKAP PEMOHON</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle text-secondary" style="font-size: 70px;"></i>
                    <h4 class="fw-bold mt-2 mb-1"><?php echo e($user->name); ?></h4>
                    <p class="text-muted small mb-0"><?php echo e($user->email); ?></p>
                </div>

                <hr class="my-3 opacity-25">
                
                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Nomor Telepon / WA</label>
                        <p class="fw-bold mb-0"><?php echo e($user->phone_number ?? 'Tidak ada data'); ?></p>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Alamat Lengkap</label>
                        <p class="fw-bold mb-0 small text-wrap"><?php echo e($user->home_address ?? 'Alamat belum diisi'); ?></p>
                    </div>
                    
                    <div class="col-12 border-bottom pb-2 bg-light p-2 rounded">
                        <label class="text-maroon small d-block mb-1 fw-bold">Data Instansi/Toko (Verifikasi Grosir)</label>
                        <p class="fw-bold mb-0 text-uppercase"><?php echo e($user->organization_name ?? '-'); ?></p>
                        <p class="small mb-0 text-muted">Tipe: <?php echo e($user->organization_type ?? '-'); ?></p>
                    </div>

                    <div class="col-6">
                        <label class="text-muted small d-block mb-1">Jenis Pelanggan</label>
                        <span class="badge bg-primary rounded-pill px-3">
                            <?php echo e(strtoupper($user->customer_type)); ?>

                        </span>
                    </div>
                    <div class="col-6 text-end">
                        <label class="text-muted small d-block mb-1">Waktu Daftar</label>
                        <span class="fw-bold small"><?php echo e($user->created_at ? $user->created_at->format('d M Y') : '-'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pb-4">
                <div class="row w-100 g-2">
                    <div class="col-6">
                        <form action="<?php echo e(route('admin.reject', $user->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?> 
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold" 
                                    onclick="return confirm('Apakah Anda yakin ingin menolak pendaftaran ini?')">
                                Tolak
                            </button>
                        </form>
                    </div>
                    
                    <div class="col-6">
                        <form action="<?php echo e(route('admin.approve', $user->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                                Terima
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <div class="card shadow-sm">
        <div class="card-header bg-white pt-4 px-4 border-0">
            <h5 class="fw-bold"><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Manajemen Pengguna & Hak Akses</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role & Jenis Pelanggan</th>
                            <th>Status Akses</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-danger text-dark fw-bold">
                            <td colspan="5" class="small py-2"><i class="bi bi-star-fill me-1"></i> ADMINISTRATOR</td>
                        </tr>
                        <?php $__currentLoopData = $allUsers->where('role', 'admin'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($u->name); ?></td>
                                <td><?php echo e($u->email); ?></td>
                                <td><span class="badge badge-admin px-3">ADMIN</span></td>
                                <td><span class="text-success small fw-bold"><i class="bi bi-patch-check-fill me-1"></i> Admin Aktif</span></td>
                                <td class="text-center">
                                    
                                    <span class="text-muted small">-</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr class="table-info text-dark fw-bold">
                            <td colspan="5" class="small py-2"><i class="bi bi-award-fill me-1"></i> PELANGGAN LANGGANAN (GROSIR)</td>
                        </tr>
                        <?php $__currentLoopData = $allUsers->where('role', 'customer')->where('customer_type', 'langganan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($u->name); ?></td>
                            <td><?php echo e($u->email); ?></td>
                            <td><span class="badge bg-primary px-3">LANGGANAN</span></td>
                            <td><span class="text-muted small italic">Akses Terbatas</span></td>
                            <td class="text-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($u->id); ?>" class="text-primary small fw-bold text-decoration-none">Lihat Detail ></a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <tr class="table-warning text-dark fw-bold">
                            <td colspan="5" class="small py-2"><i class="bi bi-person-fill me-1"></i> PELANGGAN REGULAR (ECERAN)</td>
                        </tr>
                        <?php $__currentLoopData = $allUsers->where('role', 'customer')->where('customer_type', 'regular'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="fw-semibold"><?php echo e($u->name); ?></td>
                            <td><?php echo e($u->email); ?></td>
                            <td><span class="badge bg-warning text-dark px-3">REGULAR</span></td>
                            <td><span class="text-muted small italic">Akses Terbatas</span></td>
                            <td class="text-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal<?php echo e($u->id); ?>" class="text-primary small fw-bold text-decoration-none">Lihat Detail ></a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $allUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailModal<?php echo e($u->id); ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header bg-maroon text-white border-0 py-3">
                <h6 class="modal-title fw-bold small"><i class="bi bi-person-badge me-2"></i>PROFIL LENGKAP</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle text-secondary" style="font-size: 70px;"></i>
                    <h4 class="fw-bold mt-2 mb-1"><?php echo e($u->name); ?></h4>
                    <p class="text-muted small mb-0"><?php echo e($u->email); ?></p>
                </div>

                <hr class="my-3 opacity-25">
                
                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Nama Lengkap</label>
                        <p class="fw-bold mb-0"><?php echo e($u->name); ?></p>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Nomor Telepon / WA</label>
                        <p class="fw-bold mb-0"><?php echo e($u->phone_number ?? 'Tidak ada data'); ?></p>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Alamat</label>
                        <p class="fw-bold mb-0 small text-wrap"><?php echo e($u->home_address ?? 'Alamat belum diisi'); ?></p>
                    </div>

                    <?php if($u->customer_type === 'langganan'): ?>
                    <div class="col-12 border-bottom pb-2">
                        <label class="text-muted small d-block mb-1">Nama Toko / PT / CV</label>
                        <p class="fw-bold mb-0 text-success"><?php echo e($u->organization_name ?? '-'); ?> (<?php echo e($u->organization_type ?? '-'); ?>)</p>
                    </div>
                    <?php endif; ?>

                    <div class="col-6">
                        <label class="text-muted small d-block mb-1">Jenis Pelanggan</label>
                        <span class="badge <?php echo e($u->customer_type == 'langganan' ? 'bg-primary' : 'bg-warning text-dark'); ?> rounded-pill px-3">
                            <?php echo e(strtoupper($u->customer_type)); ?>

                        </span>
                    </div>
                    <div class="col-6 text-end">
                        <label class="text-muted small d-block mb-1">Terdaftar Pada</label>
                        <span class="fw-bold small"><?php echo e($u->created_at ? $u->created_at->format('d M Y') : '-'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 pb-4">
                <button type="button" class="btn btn-secondary w-100 rounded-pill fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\Users\Lenovo LOQ\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>