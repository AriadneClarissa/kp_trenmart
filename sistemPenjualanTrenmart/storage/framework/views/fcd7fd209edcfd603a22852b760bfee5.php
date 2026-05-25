<?php $__env->startSection('content'); ?>
<div class="container my-5">
    <div class="row mb-3">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h3 class="fw-bold">Laporan Penjualan</h3>
            <a href="<?php echo e(route('beranda')); ?>" class="btn btn-outline-secondary">&larr; Kembali ke Beranda</a>
        </div>
    </div>

    <div class="card p-4 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <label class="form-label small">Periode</label>
                <select id="presetPeriod" class="form-select form-select-sm">
                    <option value="this_month" selected>Bulan Ini</option>
                    <option value="last_week">Minggu Lalu</option>
                    <option value="custom">Pilih Rentang</option>
                </select>
            </div>

            <div class="col-md-3 d-none" id="customRange">
                <label class="form-label small">Dari</label>
                <input type="date" id="startDate" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-none" id="customRangeTo">
                <label class="form-label small">Sampai</label>
                <input type="date" id="endDate" class="form-control form-control-sm">
            </div>

            <div class="col-md-3">
                <label class="form-label small">Tipe Pelanggan</label>
                <select id="typeSelect" class="form-select form-select-sm">
                    <option value="all">Gabungan</option>
                    <option value="umum">Pelanggan Umum</option>
                    <option value="langganan">Pelanggan Langganan</option>
                </select>
            </div>

            <div class="col-md-3 text-start">
                <label class="form-label small d-block mb-2">Aksi</label>
                <div class="btn-group">
                    <button id="btnViewPrint" class="btn btn-outline-primary">Tampilkan</button>
                    <button id="btnDownloadPdf" class="btn btn-primary">Download PDF</button>
                </div>
            </div>
        </div>
    </div>

    <div id="previewArea" class="mt-3"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const preset = document.getElementById('presetPeriod');
    const customRange = document.getElementById('customRange');
    const customRangeTo = document.getElementById('customRangeTo');
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const btnView = document.getElementById('btnViewPrint');
    const btnPdf = document.getElementById('btnDownloadPdf');
    const typeSelect = document.getElementById('typeSelect');

    preset.addEventListener('change', function(){
        if(this.value === 'custom'){
            customRange.classList.remove('d-none');
            customRangeTo.classList.remove('d-none');
        } else {
            customRange.classList.add('d-none');
            customRangeTo.classList.add('d-none');
        }
    });

    function buildQuery(){
        const type = typeSelect.value;
        if(preset.value === 'custom'){
            const s = startDate.value; const e = endDate.value;
            return `?type=${encodeURIComponent(type)}&start=${encodeURIComponent(s)}&end=${encodeURIComponent(e)}`;
        }
        if(preset.value === 'this_month'){
            return `?type=${encodeURIComponent(type)}`;
        }
        if(preset.value === 'last_week'){
            const now = new Date();
            const lastWeekStart = new Date(now.getFullYear(), now.getMonth(), now.getDate() - 7 - now.getDay() + 1);
            const qs = `?type=${encodeURIComponent(type)}&start=${lastWeekStart.toISOString().slice(0,10)}`;
            return qs;
        }
        return `?type=${encodeURIComponent(type)}`;
    }

    btnView.addEventListener('click', function(){
        const q = buildQuery();
        // open printable view in new tab
        window.open("<?php echo e(route('reports.monthly.print')); ?>" + q, '_blank');
    });

    btnPdf.addEventListener('click', function(){
        const q = buildQuery();
        window.open("<?php echo e(route('reports.monthly.pdf')); ?>" + q, '_blank');
    });
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\reports\index.blade.php ENDPATH**/ ?>