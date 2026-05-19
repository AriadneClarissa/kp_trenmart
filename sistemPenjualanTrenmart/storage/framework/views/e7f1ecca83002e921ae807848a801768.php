

<?php $__env->startSection('content'); ?>
<div class="container my-5">
    <div class="row">
        <div class="col-12 text-center mb-4">
            <img src="<?php echo e(asset('images/logo-trenmart.png')); ?>" alt="Trenmart" style="height:60px;" />
            <h2 class="fw-bold mt-2">Laporan Penjualan</h2>
            <div class="small text-muted">Periode: <?php echo e($period); ?></div>
            <div class="small text-muted">Tipe Pelanggan: <?php echo e(request()->query('type','all') === 'all' ? 'Gabungan' : (request()->query('type') === 'langganan' ? 'Langganan' : 'Umum')); ?></div>
        </div>
    </div>

    <div class="mb-3 no-print">
        <a href="<?php echo e(route('beranda')); ?>" class="btn btn-outline-secondary">&larr; Kembali ke Beranda</a>
    </div>

    <div class="mb-4">
        <table class="table table-borderless" style="border-bottom: 3px solid #333;">
            <tr>
                <td>
                    <strong>Trenmart</strong><br>
                    Jl. Pasar Baru No. 123<br>
                    Telp: (021) 000-0000
                </td>
                <td class="text-end small text-muted">Dicetak: <?php echo e($generated_at->format('d M Y H:i')); ?></td>
            </tr>
        </table>
    </div>

    <div class="mb-4">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th class="text-end">Total (Rp)</th>
                    <th>Tipe Pelanggan</th>
                </tr>
            </thead>
            <tbody>
                <?php $idx = 1; $grand = 0; ?>
                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $grand += $o->total; ?>
                    <tr>
                        <td><?php echo e($idx++); ?></td>
                        <td>#<?php echo e($o->order_number); ?></td>
                        <td><?php echo e($o->created_at->format('d M Y H:i')); ?></td>
                        <td><?php echo e($o->user?->name ?? 'Guest'); ?></td>
                        <td class="text-end"><?php echo e(number_format($o->total,0,',','.')); ?></td>
                        <td><?php echo e($o->user?->customer_type ?? 'umum'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Grand Total</th>
                    <th class="text-end">Rp <?php echo e(number_format($grand,0,',','.')); ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mt-5 d-flex justify-content-between">
        <div>
            <p class="mb-0">Mengetahui,</p>
            <p class="fw-bold mt-4">Pemilik Trenmart</p>
        </div>
        <div class="text-end">
            <?php $type = request()->query('type','all'); ?>
            <?php if(str_contains(request()->path(), 'monthly')): ?>
                <a href="<?php echo e(route('reports.monthly.pdf')); ?>?type=<?php echo e($type); ?>" class="btn btn-primary">Download PDF</a>
            <?php else: ?>
                <a href="<?php echo e(route('reports.weekly.pdf')); ?>?type=<?php echo e($type); ?>" class="btn btn-primary">Download PDF</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<style>
    @media print {
        .btn, .navbar, .no-print { display: none !important; }
    }
</style>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\reports\print.blade.php ENDPATH**/ ?>