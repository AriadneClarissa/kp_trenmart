<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Laporan Penjualan'); ?></title>
    <style>
        @page {
            margin: 18mm 14mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .header .period {
            margin-top: 6px;
            font-size: 12px;
        }

        .meta {
            width: 100%;
            border-bottom: 2px solid #222;
            margin-bottom: 14px;
            padding-bottom: 8px;
        }

        .meta td {
            vertical-align: top;
        }

        .meta .right {
            text-align: right;
            white-space: nowrap;
        }

        .company-name {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead th {
            background: #efefef;
            border: 1px solid #d1d5db;
            padding: 8px 6px;
            font-size: 10.5px;
            text-align: left;
        }

        table.report td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            vertical-align: top;
        }

        table.report tfoot th {
            border: 1px solid #d1d5db;
            background: #e5e7eb;
            padding: 8px 6px;
        }

        .num {
            width: 34px;
            text-align: center;
        }

        .order-no {
            width: 120px;
        }

        .date {
            width: 128px;
            white-space: nowrap;
        }

        .customer {
            width: 110px;
        }

        .total {
            width: 110px;
            text-align: right;
            white-space: nowrap;
        }

        .items ul {
            margin: 0;
            padding-left: 16px;
        }

        .items li {
            margin: 0 0 2px 0;
        }

        .signature {
            margin-top: 28px;
            width: 100%;
        }

        .signature .box {
            width: 220px;
            margin-left: auto;
            text-align: left;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <div class="period"><?php echo e($period); ?></div>
    </div>

    <table class="meta">
        <tr>
            <td>
                <div class="company-name">Trenmart</div>
                Jl. Pasar Baru No. 123<br>
                Telp: (021) 000-0000
            </td>
            <td class="right muted">Dicetak: <?php echo e($generated_at->format('d M Y H:i')); ?></td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th class="num">No.</th>
                <th class="date">Tanggal/Waktu Selesai</th>
                <th class="order-no">No. Pesanan</th>
                <th class="customer">Pelanggan</th>
                <th>Isi Pesanan</th>
                <th class="total">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php $idx = 1; $grand = 0; ?>
            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $grand += $o->total;
                    $completedAt = $o->completed_at ?? $o->updated_at;
                ?>
                <tr>
                    <td class="num"><?php echo e($idx++); ?></td>
                    <td class="date"><?php echo e($completedAt ? $completedAt->format('d M Y H:i') : '-'); ?></td>
                    <td class="order-no">#<?php echo e($o->order_number); ?></td>
                    <td class="customer"><?php echo e($o->user?->name ?? 'Guest'); ?></td>
                    <td class="items">
                        <ul>
                            <?php $__empty_2 = true; $__currentLoopData = $o->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <li><?php echo e($item->produk?->nama_produk ?? $item->kd_produk); ?> x <?php echo e($item->quantity); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <li>-</li>
                            <?php endif; ?>
                        </ul>
                    </td>
                    <td class="total"><?php echo e(number_format($o->total, 0, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding: 18px 6px;">Tidak ada pesanan selesai pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="total" style="text-align:right;">Grand Total</th>
                <th class="total">Rp <?php echo e(number_format($grand, 0, ',', '.')); ?></th>
            </tr>
        </tfoot>
    </table>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br><br><br>
                Pemilik Trenmart
            </td>
        </tr>
    </table>
</body>
</html><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/reports/print.blade.php ENDPATH**/ ?>