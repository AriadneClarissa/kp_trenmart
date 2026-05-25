<?php $__env->startSection('content'); ?>
<div class="container mt-3 mt-md-4 mb-5">

    <style>
        .admin-quick-btn {
            border-radius: 999px;
            padding: 0.55rem 1.05rem;
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            font-weight: 600;
            line-height: 1.1;
        }

        .admin-quick-btn i {
            font-size: 0.95rem;
        }
    </style>
    
    
    <div class="banner-wrapper mb-4 position-relative overflow-hidden" style="border-radius: 1rem;">
        <img id="bannerPreview" 
            src="<?php echo e((!empty($settings['tentang_banner'])) ? \App\Helpers\StorageProxy::url($settings['tentang_banner']) : (($admin && $admin->tentang_banner) ? \App\Helpers\StorageProxy::url($admin->tentang_banner) : asset('images/spanduktoko.png'))); ?>" 
            class="w-100 shadow-sm img-banner-responsive object-fit-cover" 
            style="height: 300px;" 
            alt="Banner Trenmart">
    </div>

    
    <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->isAdmin()): ?>
        <div class="card shadow-sm mb-5 admin-panel-card border-0 bg-light">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-shield-lock-fill me-2 text-danger"></i>
                            <?php if(auth()->user()->isOwner()): ?>
                                Panel Kontrol Pemilik
                            <?php else: ?>
                                Panel Kontrol Admin
                            <?php endif; ?>
                        </h5>
                        <p class="text-muted small mb-0">Kelola stok produk dan pengaturan tampilan beranda</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-end" style="gap:12px;">
                            <a href="<?php echo e(route('bundling.create', ['source' => 'beranda'])); ?>" class="btn btn-success shadow-sm admin-quick-btn">
                                <i class="bi bi-plus-lg me-2"></i> Tambah Bundling
                            </a>

                            <?php if(auth()->user()->isOwner()): ?>
                            <button id="btnOpenReports" class="btn btn-primary shadow-sm admin-quick-btn">
                                <i class="bi bi-file-earmark-text me-2"></i> Laporan
                            </button>
                            <?php endif; ?>

                            <?php if(auth()->user()->isOwner()): ?>
                                <a href="<?php echo e(route('admin.logs.index')); ?>" class="btn btn-secondary shadow-sm admin-quick-btn">
                                    <i class="bi bi-journal-text me-2"></i> Lihat Log Aktivitas
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>

    
    <?php if(Auth::check() && Auth::user()->isOwner() && !empty($chartLabels)): ?>
    <div class="mb-5">
        <!-- Key Metrics -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Total Pendapatan</p>
                                <h3 class="fw-bold text-primary mb-0">Rp <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?></h3>
                            </div>
                            <i class="bi bi-cash-coin text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Total Pesanan</p>
                                <h3 class="fw-bold text-success mb-0"><?php echo e($totalOrders); ?></h3>
                            </div>
                            <i class="bi bi-bag-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Rata-rata Pesanan</p>
                                <h3 class="fw-bold text-info mb-0">Rp <?php echo e(number_format($averageOrderValue, 0, ',', '.')); ?></h3>
                            </div>
                            <i class="bi bi-graph-up text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Grafik Penjualan (30 Hari Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <?php if($statusBreakdown->isNotEmpty()): ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Status Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $statusBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <?php switch($status):
                                                case ('pending'): ?>
                                                    <span class="badge bg-warning-subtle text-warning-emphasis">Menunggu</span>
                                                    <?php break; ?>
                                                <?php case ('processing'): ?>
                                                    <span class="badge bg-info-subtle text-info-emphasis">Diproses</span>
                                                    <?php break; ?>
                                                <?php case ('completed'): ?>
                                                    <span class="badge bg-success-subtle text-success-emphasis">Selesai</span>
                                                    <?php break; ?>
                                                <?php case ('cancelled'): ?>
                                                    <span class="badge bg-danger-subtle text-danger-emphasis">Dibatalkan</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary-emphasis"><?php echo e(ucfirst($status)); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td class="text-end fw-bold"><?php echo e($count); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0 fw-bold">Distribusi Status</h5>
                    </div>
                    <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 300px;">
                        <canvas id="statusChart" style="max-width: 300px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    
    <?php if(!auth()->check() || !auth()->user()->isOwner()): ?>
    <section class="mb-5">
        <h4 class="fw-bold mb-4 text-center fs-4 fs-md-3">
            <i class="bi bi-stars text-warning me-2"></i>
            <?php echo e($settings['judul_terbaru'] ?? 'Produk Terbaru'); ?>

        </h4>
        
        <div class="d-flex flex-nowrap overflow-auto pb-4 custom-scrollbar-visible" style="gap: 15px; padding-left: 5px; padding-right: 5px;">
            <?php $__empty_1 = true; $__currentLoopData = $produk_terbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card-mobile-width" style="flex: 0 0 auto;"> 
                    <?php echo $__env->make('partials.item_produk', ['item' => $item], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="w-100 text-center py-4">
                    <p class="text-muted">Belum ada produk untuk ditampilkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    
    <?php if(!auth()->check() || !auth()->user()->isOwner()): ?>
    <section class="mt-5 pt-3">
        <div class="text-center mb-5">
            <h4 class="fw-bold mb-2 fs-4 fs-md-3">
                <i class="bi bi-box2-heart text-danger me-2"></i> Paket Bundling Hemat
            </h4>
            <p class="text-muted small">Dapatkan kombinasi produk terbaik dengan harga lebih murah!</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php $__empty_1 = true; $__currentLoopData = $bundling; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-6 col-lg-4">
                    
                    <div class="card h-100 border-0 shadow-sm card-bundling-hover position-relative" style="border-radius: 20px;">
                        <div class="card-body p-3 d-flex flex-column">
                            
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                
                                <a href="<?php echo e(route('bundling.show', $b->id)); ?>" class="text-decoration-none stretched-link">
                                    <h5 class="fw-bold text-dark mb-0 hover-maroon"><?php echo e($b->name); ?></h5>
                                </a>
                            </div>

                            <?php if($b->promo_start_at || $b->promo_end_at): ?>
                                <div class="mb-3">
                                    <span class="badge bg-warning text-dark">
                                        Promo sampai <?php echo e($b->promo_end_at ? $b->promo_end_at->format('d M Y H:i') : 'selama tersedia'); ?>

                                    </span>
                                </div>
                            <?php endif; ?>

                            <div class="bg-light p-3 rounded-4 mb-4">
                                <label class="small fw-bold text-primary mb-2 d-block">Isi Paket:</label>
                                <ul class="list-unstyled mb-0">
                                    <?php $__currentLoopData = $b->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="small d-flex align-items-center mb-2">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <span>
                                                <?php echo e($item->produk->nama_produk); ?> 
                                                <small class="text-muted">(<?php echo e($item->produk->merk->nama_merk ?? 'Tanpa Merk'); ?>)</small>
                                            </span>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>

                            <div class="mt-auto">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <small class="text-muted text-decoration-line-through">
                                        Rp <?php echo e(number_format($b->total_normal_price, 0, ',', '.')); ?>

                                    </small>
                                    <?php if($b->total_normal_price > $b->bundling_price): ?>
                                        <span class="badge bg-light text-danger border border-danger small" style="font-size: 0.7rem;">
                                            Hemat Rp <?php echo e(number_format($b->total_normal_price - $b->bundling_price, 0, ',', '.')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="fw-bold text-danger mb-0">
                                        Rp <?php echo e(number_format($b->bundling_price, 0, ',', '.')); ?>

                                    </h4>
                                    
                                    
                                    <?php if(!Auth::check() || (Auth::check() && Auth::user()->isCustomer())): ?>
                                        
                                        <span class="btn-tambah-card shadow-sm d-flex align-items-center justify-content-center" style="position: relative; z-index: 2;">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted italic">Belum ada paket bundling untuk saat ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
</div>


<script>
    const bannerInput = document.getElementById('bannerInput');
    if(bannerInput) {
        bannerInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('bannerForm').submit();
            }
        });
    }
</script>

<style>
    .hover-scale:hover { transform: translate(-50%, -50%) scale(1.1); transition: 0.3s ease; }
    .object-fit-cover { object-fit: cover; }
    .img-banner-responsive { height: 160px; object-fit: cover; }
    @media (min-width: 768px) { .img-banner-responsive { height: 300px; } }

    .card-mobile-width { 
        width: 165px; 
        flex: 0 0 auto; 
    }
    
    @media (min-width: 768px) { 
        .card-mobile-width { 
            width: 220px; 
            flex: 0 0 auto; 
        } 
    }

    .flex-nowrap { 
        display: flex;
        flex-wrap: nowrap !important; /* Memaksa kartu tetap satu baris */
        -webkit-overflow-scrolling: touch; /* Scroll halus di iPhone/iOS */
    }

    .card-bundling-hover:hover {
        transform: translateY(-5px);
        transition: 0.3s ease;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    /* Style untuk tombol Tambah di Card */
    .btn-tambah-card {
        background-color: #800000; /* Warna maroon Trenmart */
        color: white;
        border-radius: 8px; /* Bentuk tidak terlalu bulat (bukan pill) */
        font-size: 0.9rem; /* Ukuran font disesuaikan agar rapi */
        padding: 0.5rem 1.25rem;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
    }

    .btn-tambah-card:hover {
        background-color: #600000;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2) !important;
    }

    /* Admin action buttons styling */
    .admin-actions .btn-with-icon{ 
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1.05rem;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(16,24,40,0.06);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .admin-actions .btn-with-icon .icon{ 
        display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:8px; background: rgba(255,255,255,0.9);
    }
    .admin-actions .btn-with-icon .icon i{ font-size: 0.95rem; }
    .admin-actions .btn-with-icon:hover{ transform: translateY(-3px); box-shadow: 0 12px 30px rgba(16,24,40,0.12); }

    @media (max-width:767px){
        .admin-actions{ flex-direction:column; gap:0.6rem; }
        .admin-actions .btn-with-icon{ width:100%; justify-content:center; }
    }
</style>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Chart - Only initialize if element exists
    const salesChartElement = document.getElementById('salesChart');
    if (salesChartElement) {
        const salesCtx = salesChartElement.getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?php echo json_encode($chartData, 15, 512) ?>,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID', {maximumFractionDigits: 0});
                            }
                        }
                    }
                }
            }
        });
    }

    // Status Chart (Pie Chart) - Only initialize if element exists
    const statusChartElement = document.getElementById('statusChart');
    if (statusChartElement) {
        const statusCtx = statusChartElement.getContext('2d');
        const colors = ['#ffc107', '#0dcaf0', '#198754', '#dc3545'];
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_map('ucfirst', $statusBreakdown->keys()->toArray()), 512) ?>,
                datasets: [{
                    data: <?php echo json_encode($statusBreakdown->values()->toArray(), 15, 512) ?>,
                    backgroundColor: colors,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    }
                }
            }
        });
    }
</script>

<?php echo $__env->make('partials.report_modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const monthlyBtn = document.getElementById('btnMonthlyReport');
    const weeklyBtn = document.getElementById('btnWeeklyReport');
    const reportModalEl = document.getElementById('reportModal');
    const reportModal = new bootstrap.Modal(reportModalEl);

    function renderReport(data, title) {
        const content = `
            <h6 class="mb-3">Periode: ${data.period}</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th class="text-end">Total (Rp)</th>
                            <th class="text-end">Jumlah Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Umum</td>
                            <td class="text-end">Rp ${Number(data.umum.total).toLocaleString('id-ID')}</td>
                            <td class="text-end">${data.umum.count}</td>
                        </tr>
                        <tr>
                            <td>Langganan</td>
                            <td class="text-end">Rp ${Number(data.langganan.total).toLocaleString('id-ID')}</td>
                            <td class="text-end">${data.langganan.count}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td>Gabungan</td>
                            <td class="text-end">Rp ${Number(data.total).toLocaleString('id-ID')}</td>
                            <td class="text-end">${(data.umum.count + data.langganan.count)}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        reportModalEl.querySelector('.modal-title').textContent = title;
        reportModalEl.querySelector('#reportContent').innerHTML = content;
        reportModal.show();
    }

    const openReportsBtn = document.getElementById('btnOpenReports');
    if(openReportsBtn){
        openReportsBtn.addEventListener('click', function(){
            window.open("<?php echo e(route('reports.index')); ?>", '_blank');
        });
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views/beranda.blade.php ENDPATH**/ ?>