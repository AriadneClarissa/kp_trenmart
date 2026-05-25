

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pesanan Pelanggan</h4>
        <p class="text-muted small">Menunggu Konfirmasi: <span class="badge bg-warning"><?php echo e($orders->where('payment_status', 'waiting_confirmation')->count()); ?></span></p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Status Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong>#<?php echo e($order->order_number); ?></strong>
                            </td>
                            <td>
                                <?php echo e($order->user ? $order->user->name : 'Unknown'); ?>

                            </td>
                            <td><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                            <td>
                                <strong>Rp <?php echo e(number_format($order->total,0,',','.')); ?></strong>
                            </td>
                            <td>
                                <?php if($order->payment_status === 'waiting_confirmation'): ?>
                                    <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                <?php elseif($order->payment_status === 'confirmed'): ?>
                                    <span class="badge bg-success">Dikonfirmasi</span>
                                <?php elseif($order->payment_status === 'rejected'): ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($order->payment_status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($order->order_status === 'processing'): ?>
                                    <span class="badge bg-primary">Diproses</span>
                                <?php elseif($order->order_status === 'ready_to_ship'): ?>
                                    <span class="badge bg-info text-dark">Siap Dikirim</span>
                                <?php elseif($order->order_status === 'completed'): ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php elseif($order->order_status === 'payment_rejected'): ?>
                                    <span class="badge bg-danger">Pembayaran Ditolak</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $order->order_status ?? 'new'))); ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.orders.show', $order->id)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i> Lihat
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada pesanan.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>