@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => 'dashboard'])

<div class="container-fluid">
    <!-- Page Title -->
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Dashboard Penjualan</h4>
        <p class="text-muted">Data penjualan 30 hari terakhir</p>
    </div>

    <!-- Key Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1">Total Pendapatan</p>
                            <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
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
                            <h3 class="fw-bold text-success mb-0">{{ $totalOrders }}</h3>
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
                            <h3 class="fw-bold text-info mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
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
    @if($statusBreakdown->isNotEmpty())
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
                                @foreach($statusBreakdown as $status => $count)
                                <tr>
                                    <td>
                                        @switch($status)
                                            @case('pending')
                                                <span class="badge bg-warning-subtle text-warning-emphasis">Menunggu</span>
                                                @break
                                            @case('processing')
                                                <span class="badge bg-info-subtle text-info-emphasis">Diproses</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-success-subtle text-success-emphasis">Selesai</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-danger-subtle text-danger-emphasis">Dibatalkan</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary-subtle text-secondary-emphasis">{{ ucfirst($status) }}</span>
                                        @endswitch
                                    </td>
                                    <td class="text-end fw-bold">{{ $count }}</td>
                                </tr>
                                @endforeach
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
    @endif

</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($chartData),
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

    // Status Chart (Pie Chart)
    @if($statusBreakdown->isNotEmpty())
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const colors = ['#ffc107', '#0dcaf0', '#198754', '#dc3545'];
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json(array_map('ucfirst', $statusBreakdown->keys()->toArray())),
            datasets: [{
                data: @json($statusBreakdown->values()->toArray()),
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
    @endif
</script>

@endsection
