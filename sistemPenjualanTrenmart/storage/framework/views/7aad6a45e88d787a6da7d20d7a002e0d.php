

<?php $__env->startPush('styles'); ?>
<style>
    .order-shell {
        background: linear-gradient(180deg, #ffffff 0%, #fffafa 100%);
        border: 1px solid #ececec;
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
    }

    .order-status-card {
        border: 1px solid #f1d3d3;
        border-radius: 18px;
        background: linear-gradient(180deg, #fffdfd 0%, #fff7f7 100%);
        padding: 18px;
    }

    .order-stepper {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        position: relative;
        margin-top: 10px;
    }

    .order-stepper::before {
        content: "";
        position: absolute;
        left: 8%;
        right: 8%;
        top: 18px;
        height: 4px;
        background: #eadada;
        border-radius: 999px;
    }

    .order-step {
        position: relative;
        text-align: center;
        z-index: 1;
    }

    .order-step-circle {
        width: 38px;
        height: 38px;
        margin: 0 auto 8px;
        border-radius: 999px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #d6d6d6;
        background: #fff;
        color: #9ca3af;
        font-weight: 700;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
    }

    .order-step.completed .order-step-circle,
    .order-step.active .order-step-circle {
        border-color: #8b0000;
        background: #8b0000;
        color: #fff;
    }

    .order-step.completed .order-step-label,
    .order-step.active .order-step-label {
        color: #8b0000;
        font-weight: 700;
    }

    .order-step .order-step-label {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.2;
    }

    .order-step.completed::after,
    .order-step.active::after {
        content: "";
        position: absolute;
        top: 18px;
        left: 50%;
        width: 100%;
        height: 4px;
        background: #8b0000;
        z-index: -1;
    }

    .order-step:first-child.completed::after,
    .order-step:first-child.active::after {
        left: 50%;
        width: 100%;
    }

    .order-step:first-child.completed::before,
    .order-step:first-child.active::before {
        content: "";
        position: absolute;
        top: 18px;
        left: 32%;
        width: 18%;
        height: 4px;
        background: #8b0000;
        border-radius: 999px;
        z-index: -1;
    }

    .order-step:last-child.completed::after,
    .order-step:last-child.active::after {
        width: 50%;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pill--pending {
        background: #fff4d6;
        color: #b45309;
    }

    .status-pill--success {
        background: #fce8e8;
        color: #8b0000;
    }

    .status-pill--info {
        background: #e8f1ff;
        color: #1d4ed8;
    }

    .status-note {
        color: #6b7280;
        font-size: 13px;
        margin-top: 8px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container mt-5">
    <h4 class="fw-bold">Pesanan <?php echo e($order->order_number); ?></h4>

    <?php
        $stepLabels = ['Terkonfirmasi', 'Diproses', 'Siap Dikirim', 'Selesai'];
        $currentStep = 0;

        if ($order->payment_status === 'confirmed') {
            $currentStep = 1;
        }
        if ($order->order_status === 'processing') {
            $currentStep = max($currentStep, 2);
        }
        if ($order->order_status === 'ready_to_ship') {
            $currentStep = max($currentStep, 3);
        }
        if ($order->order_status === 'completed') {
            $currentStep = 4;
        }

        $stepClass = function (int $index) use ($currentStep) {
            if ($currentStep >= $index) return 'completed';
            return '';
        };

    ?>

    <div class="card mt-3 p-4 order-shell">
        <div class="d-flex justify-content-between align-items-start mb-3 gap-3 flex-wrap">
            <div>
                <div class="fw-bold"><?php echo e($order->order_number); ?></div>
                <div class="small text-muted"><?php echo e($order->created_at->format('d M Y \p\u\k\u\l H:i')); ?></div>
            </div>
            <div class="text-end">
                <div class="status-pill status-pill--info d-block mt-2" style="justify-content:center;">
                    <?php echo e($order->order_status === 'processing' ? 'Diproses' : ($order->order_status === 'ready_to_ship' ? 'Siap Dikirim' : ($order->order_status === 'completed' ? 'Selesai' : ucfirst(str_replace('_',' ', $order->order_status ?? 'new'))))); ?>

                </div>
                <div class="fw-bold mt-2">Rp <?php echo e(number_format($order->total,0,',','.')); ?></div>
                <?php if($order->payment_status === 'confirmed' && in_array($order->order_status, ['ready_to_ship', 'processing'], true)): ?>
                    <form action="<?php echo e(route('pesanan.complete', $order->id)); ?>" method="POST" class="mt-3">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Tandai pesanan ini sudah selesai diterima?');">
                            <i class="bi bi-check2-circle me-1"></i> Pesanan Selesai
                        </button>
                    </form>
                <?php elseif($order->order_status === 'completed'): ?>
                    <div class="mt-3 badge rounded-pill bg-success-subtle text-success-emphasis py-2 px-3">Pesanan sudah selesai</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="order-status-card mb-4">
            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                <div>
                    <h6 class="fw-bold mb-1">Status Pengiriman</h6>
                    <div class="status-note">Ikuti tahapan pesanan sampai selesai. Tahap aktif ditandai merah.</div>
                </div>
                <div class="small text-muted">
                    Terakhir diperbarui: <?php echo e($order->updated_at->format('d M Y H:i')); ?>

                </div>
            </div>

            <div class="order-stepper mt-4">
                <?php $__currentLoopData = $stepLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $stepState = $stepClass($index + 1); ?>
                    <div class="order-step <?php echo e($stepState); ?>">
                        <div class="order-step-circle"><?php echo e($index + 1); ?></div>
                        <div class="order-step-label"><?php echo e($label); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="status-note mt-3">
                <?php if($order->payment_status === 'confirmed'): ?>
                    Pesanan telah dikonfirmasi dan siap masuk ke proses berikutnya.
                <?php elseif($order->payment_status === 'waiting_confirmation'): ?>
                    Menunggu konfirmasi pembayaran dari admin.
                <?php elseif($order->payment_status === 'rejected'): ?>
                    Pembayaran ditolak. Silakan unggah ulang bukti transfer.
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <h6 class="fw-semibold">Produk</h6>
                <div class="mt-2">
                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-2 p-2" style="border-radius:10px;">
                            <div class="d-flex align-items-center">
                                <img src="<?php echo e(\App\Helpers\StorageProxy::url($it->produk->gambar ?? 'images/no-image.png')); ?>" style="width:64px;height:64px;object-fit:cover;border-radius:8px;" alt="">
                                <div class="ms-3">
                                    <div class="fw-semibold"><?php echo e($it->produk->nama_produk ?? '-'); ?></div>
                                    <div class="small text-muted"><?php echo e($it->quantity); ?> × Rp <?php echo e(number_format($it->price,0,',','.')); ?></div>
                                </div>
                                <div class="ms-auto fw-bold">Rp <?php echo e(number_format($it->price * $it->quantity,0,',','.')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <h6 class="mt-4 fw-semibold">Bukti Transfer</h6>
                <?php
                    $proofPath = $order->payment_proof ?? $order->bukti_pembayaran ?? null;
                    $proofExists = $proofPath ? \Illuminate\Support\Facades\Storage::disk('public')->exists($proofPath) : false;
                    $proofMime = $proofExists ? (\Illuminate\Support\Facades\Storage::disk('public')->mimeType($proofPath) ?: 'image/jpeg') : null;
                    $proofBase64 = $proofExists ? base64_encode(\Illuminate\Support\Facades\Storage::disk('public')->get($proofPath)) : null;
                ?>
                <?php if($proofBase64): ?>
                    <div class="mt-2">
                        <img src="data:<?php echo e($proofMime); ?>;base64,<?php echo e($proofBase64); ?>" style="max-width:220px;border-radius:10px;" alt="Bukti Transfer">
                    </div>
                <?php elseif($proofPath): ?>
                    <div class="text-warning">Bukti transfer tercatat di data pesanan, tetapi file gambar tidak ditemukan di storage.</div>
                    <div class="small text-muted mt-1">Path: <?php echo e($proofPath); ?></div>
                <?php else: ?>
                    <div class="text-muted">Belum ada bukti transfer.</div>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <h6 class="fw-semibold">Detail Pesanan</h6>
                <div class="mt-2 small text-muted">Metode: <?php echo e($order->paymentMethod->name ?? '-'); ?></div>
                <div class="mt-2 small text-muted">Status pembayaran: <?php echo e($order->payment_status); ?></div>
                <div class="mt-2 small text-muted">Status pesanan: <?php echo e($order->order_status === 'processing' ? 'Diproses' : ($order->order_status === 'ready_to_ship' ? 'Siap Dikirim' : ($order->order_status === 'completed' ? 'Selesai' : ucfirst(str_replace('_',' ', $order->order_status ?? 'new'))))); ?></div>
                    <?php if($order->pickup_method === 'delivery'): ?>
                    <div class="mt-2 small text-muted">Alamat kirim: <?php echo e($order->shipping_address ?? '-'); ?></div>
                    <div class="mt-2 small text-muted">Tarif ongkir flat: Rp <?php echo e(number_format($order->shipping_cost ?? 0,0,',','.')); ?></div>
                <?php endif; ?>
                <div class="mt-3 fw-bold">Total: Rp <?php echo e(number_format($order->total,0,',','.')); ?></div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('partials.order_chat', ['order' => $order], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/pesanan/show.blade.php ENDPATH**/ ?>