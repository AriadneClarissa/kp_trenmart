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
        .card { border: none; border-radius: 15px; }
        .btn-maroon { background-color: var(--maroon); color: white; }
        .badge-admin { background-color: #dc3545; font-weight: 600; }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-maroon mb-0">Panel Admin - Trenmart</h2>
        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
            <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

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
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-info text-dark px-3">{{ $user->customer_type }}</span></td>
                            <td>
                                <form action="{{ route('admin.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">Setujui</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Tidak ada permintaan persetujuan baru.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white pt-4 px-4 border-0">
            <h5 class="fw-bold"><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Manajemen Hak Akses (Role)</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role Saat Ini</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsers as $u)
                        <tr>
                            <td class="fw-semibold">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>
                                @if($u->role == 'admin')
                                    <span class="badge badge-admin px-3">ADMIN</span>
                                @else
                                    <span class="badge bg-secondary px-3">{{ strtoupper($u->role) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($u->role !== 'admin')
                                    <form action="{{ route('admin.promote', $u->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" 
                                                onclick="return confirm('Jadikan {{ $u->name }} sebagai Admin?')">
                                            Jadikan Admin
                                        </button>
                                    </form>
                                @else
                                    <span class="text-success small fw-bold">
                                        <i class="bi bi-patch-check-fill me-1"></i> Admin Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="