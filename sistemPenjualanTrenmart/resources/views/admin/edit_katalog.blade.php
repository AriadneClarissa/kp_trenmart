@extends('layouts.app')

@push('styles')
<style>
    :root { --maroon: #800000; --soft-bg: #f8f9fa; }
    body { background-color: var(--soft-bg); }

    /* Sidebar Kategori & Admin Style */
    .sidebar-container { position: sticky; top: 90px; }
    .card-sidebar { border-radius: 15px; background: white; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* Search Kategori Style */
    .search-kategori { background-color: #f1f1f1; border: none; border-radius: 50px; padding: 10px 15px; font-size: 14px; }
    
    /* Table Responsive & Full Width */
    .table-responsive { overflow-x: auto; }
    .table { font-size: 14px; }
    .table th { background-color: #f8f9fa; font-weight: 600; white-space: nowrap; }
    .table td { padding: 12px 8px; }

    .table thead th {
        background-color: #4a4f54 !important;
        color: white !important; 
        white-space: nowrap; 
        padding: 15px 12px !important;
        font-weight: 600;
        border: none;
        text-align: center;
    }
    .table tbody td {
        padding: 12px 10px !important;
        white-space: nowrap; /* Mencegah teks harga/stok bertumpuk */
        background-color: white;
        text-align: center !important;
    }
    
    /* Column Width Optimization */
    .table thead th:nth-child(1) { width: 70px; min-width: 70px; }
    .table thead th:nth-child(2) { width: 100px; min-width: 100px }
    .table thead th:nth-child(3) { width: auto; min-width: 150px; }
    .table thead th:nth-child(4) { width: 90px; min-width: 90px; }
    .table thead th:nth-child(5) { width: 100px; min-width: 100px; }
    .table thead th:nth-child(6) { width: 110px; min-width: 110px; }
    .table thead th:nth-child(7) { width: 120px; min-width: 120px; }
    .table thead th:nth-child(8) { width: 70px; min-width: 70px; }
    .table thead th:nth-child(9) { width: 110px; min-width: 110px; }
    .table thead th:nth-child(10) { width: 80px; min-width: 80px; }
    .table thead th:nth-child(11) { width: 140px; min-width: 140px; text-align: center !important;}
    
    
    /* List Kategori Style (Sesuai Gambar Mockup) */
    .list-kategori { max-height: 400px; overflow-y: auto; scrollbar-width: none; }
    .list-kategori::-webkit-scrollbar { display: none; }
    .kat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 12px;
        color: #555;
        text-decoration: none;
        border-bottom: 1px solid #f1f1f1;
        transition: 0.2s;
        font-size: 14px;
        min-height: 44px;
        border-radius: 10px;
    }
    .kat-item:hover, .kat-item.active {
        color: var(--maroon);
        background-color: #fff5f5;
        font-weight: 500;
    }
    .list-group {
    display: block !important;
    overflow-y: auto !important;
    -webkit-overflow-scrolling: touch;
    }
    /* Tombol Admin Panel */
    .sidebar-header-admin { font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid #eee; padding: 12px 20px; background-color: #fff9e6; color: #333; }
    .btn-admin-panel { width: 100%; border-radius: 12px; font-weight: 600; font-size: 13px; padding: 10px; margin-bottom: 10px; border: none; color: white; display: block; text-align: center; text-decoration: none; }

    /* Filter Bar Pill Style (Atas) */
    .filter-pill { background: white; border-radius: 50px; padding: 8px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; align-items: center; width: 100%; }
    .input-filter { border: none; background: transparent; outline: none; padding: 8px 0; width: 100%; font-size: 14px; padding-left: 10px; }
    .select-filter { 
            border: none; 
            background: white; 
            border-radius: 50px; 
            padding: 10px 40px 10px 20px; /* Tambahkan padding-right agar tidak bertabrakan dengan panah */
            cursor: pointer; 
            color: #666; 
            font-size: 14px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
            width: 100%; 
            border: 1px solid #eee; 
            
            /* Menambahkan kustomisasi panah agar tidak menempel di pojok kanan */
            appearance: none; 
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center; /* Kamu bisa atur angka 15px ini sesuai keinginan */
            background-size: 14px;}

    /* Card Produk Style */
    .card-produk { border-radius: 25px !important; transition: 0.3s; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.06); height: 100%; overflow: hidden; background: white; display: flex; flex-direction: column; }
    .card-produk:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .img-container { background-color: #f8f9fa; border-radius: 20px; padding: 25px; min-height: 190px; display: flex; align-items: center; justify-content: center; position: relative; margin: 10px; }
    .product-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; }
    
    .btn-detail { background-color: var(--maroon); color: white; border-radius: 12px; border: none; font-weight: 600; padding: 10px; width: 100%; transition: 0.2s; display: block; text-align: center; text-decoration: none; }
    .btn-detail:hover { background-color: #600000; color: white; }

    /* Empty state harus selalu full row, tidak ikut row-cols */
    .empty-produk-state {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 260px;
    }
    .main-container {
    max-width: 1500px !important; /* Membatasi lebar maksimal di 1400px */
    padding-left: 1rem !important;
    padding-right: 1rem !important;
    margin: 0 auto !important; /* Memastikan posisinya tetap tepat di tengah layar */
}
    
</style>
@endpush

@section('content')
<div class="container-fluid mt-4 mb-5 px-0">
    <div class="row">
        @php
            $canManageProducts = auth()->check() && auth()->user()->isAdmin();
        @endphp

        {{-- ADMIN PANEL BUTTONS (Full Width) --}}
        @auth
            @if($canManageProducts)
            <div class="col-12 mb-4">
                <div class="card-sidebar p-3 d-flex gap-2 align-items-center flex-wrap" style="border: 1px solid #ffc107;">
                    <div class="sidebar-header-admin flex-grow-1">
                        <i class="bi bi-shield-lock-fill me-2"></i>
                        @if(auth()->user()->isOwner())
                            Panel Pemilik
                        @else
                            Panel Admin
                        @endif
                    </div>
                    <button type="button" class="btn btn-sm btn-primary" style="background-color: #55bdff; border: none;" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">
                        <i class="bi bi-plus-circle me-1"></i> Kelola Kategori
                    </button>

                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalKelolaMerk">
                        <i class="bi bi-plus-circle me-1"></i> Kelola Merk
                    </button>

                    <button type="button" class="btn btn-sm btn-warning" style="border: none; color: white;" data-bs-toggle="modal" data-bs-target="#modalKelolaSatuan">
                        <i class="bi bi-plus-circle me-1"></i> Kelola Satuan
                    </button>
                    <a href="{{ route('produk.create') }}" class="btn btn-sm btn-danger">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk
                    </a>
                </div>
            </div>
            @endif
        @endauth

        {{-- FILTERS (Full Width) --}}
        <div class="col-12">
            <form action="{{ route('produk.index') }}" method="GET" class="row g-2 mb-4 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="filter-pill">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" name="search" class="input-filter" placeholder="Cari produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="kategori" class="select-filter" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->kd_kategori }}" {{ request('kategori') == $kat->kd_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="merk" class="select-filter" onchange="this.form.submit()">
                        <option value="">Semua Merek</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->kd_merk }}" {{ request('merk') == $m->kd_merk ? 'selected' : '' }}>{{ $m->nama_merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 text-end text-muted small">
                    {{ count($produk) }} produk ditemukan
                </div>
            </form>
        </div>

        {{-- TABLE (Full Width) --}}
        <div class="col-12"> 
            <div class="card shadow-sm border-0 w-100" style="border-radius: 18px; overflow: hidden;"> 
                <div class="table-responsive" style="width: 100%;">
                    <table class="table table-hover align-middle mb-0 "> 
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">Foto</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Merek</th>
                                <th>Kategori</th>
                                <th>Harga Umum</th>
                                <th>Harga Langganan</th>
                                <th>Stok</th>
                                <th>Satuan</th>
                                <th>Status</th>
                                @if($canManageProducts)
                                    <th class="text-end">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produk as $p)
                                @php
                                    $stokMinimal = $p->stok_minimal ?? $p->satuanModel?->stok_minimal ?? 0;
                                    $isLowStock = $stokMinimal > 0 && $p->stok_tersedia <= $stokMinimal;
                                @endphp
                                <tr class="{{ $isLowStock ? 'table-danger' : '' }}">
                                    <td>
                                        <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; overflow: hidden;">
                                            <img src="{{ \App\Helpers\StorageProxy::url($p->gambar) }}" alt="{{ $p->nama_produk }}" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    </td>
                                    <td class="fw-semibold">{{ $p->kd_produk }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $p->nama_produk }}</div>
                                    </td>
                                    <td>{{ $p->merk->nama_merk ?? 'Tanpa Merk' }}</td>
                                    <td>{{ $p->kategori->nama_kategori ?? '-' }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: var(--maroon);">
                                            Rp {{ number_format($p->harga_jual_umum ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold" style="color: #f08a24;">
                                            Rp {{ number_format($p->harga_jual_langganan ?? $p->harga_jual_umum ?? 0, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $p->stok_tersedia }}</span>
                                        <span class="text-muted small d-block">{{ $p->satuan ?? 'pcs' }}</span>
                                        @if($isLowStock)
                                            <div class="small text-danger fw-bold">Stok mencapai batas warning ({{ $stokMinimal }})</div>
                                        @endif
                                    </td>
                                    <td>{{ $p->satuanModel?->nama_satuan ?? $p->satuan ?? '-' }}</td>
                                    <td>
                                        @if($canManageProducts)
                                            <select class="status-dropdown form-select {{ $p->status === 'aktif' ? 'text-success' : 'text-danger' }}" 
                                                style="min-width: 130px; width: auto;" 
                                                data-id="{{ $p->kd_produk }}"> 
                                                <option value="aktif" {{ $p->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                <option value="nonaktif" {{ $p->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                            </select>
                                        @else
                                            <span class="badge {{ $p->status === 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    @if($canManageProducts)
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('produk.edit', $p->kd_produk) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-pencil-square me-1"></i> Edit Produk
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $canManageProducts ? 11 : 10 }}" class="text-center py-5">
                                        <i class="bi bi-box-seam text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                        <div class="mt-2 fw-semibold">Produk tidak tersedia</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ADMIN (DITEMPEL LANGSUNG DI SINI) --}}
@auth
    @if(auth()->user()->isAdmin())
    <div class="modal fade" id="modalKelolaKategori" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="fw-bold">Kelola Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <form id="formTambahKategori" action="{{ route('kategori.store') }}" method="POST">
                        @csrf
                        <div class="input-group mb-4 shadow-sm border rounded-pill overflow-hidden">
                            <input type="text" id="inputNamaKategori" name="nama_kategori" class="form-control border-0 px-3" placeholder="Nama kategori baru..." required>
                            <button class="btn btn-success border-0 px-4" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>
                    <div class="input-group mb-2 shadow-sm border rounded-pill overflow-hidden bg-light">
                        <input type="text" id="searchKategoriModal" class="form-control border-0 bg-transparent ps-3" placeholder="Cari kategori...">
                        <button type="button" id="btnSearchKategoriModal" class="btn btn-light border-0 px-4"><i class="bi bi-search text-muted"></i></button>
                    </div>
                    <div id="searchKategoriRekomendasi" class="list-group shadow-sm rounded-3 overflow-hidden mb-3 d-none" style="max-height: 180px; overflow-y: auto;"></div>
                    <div class="small text-muted mb-2 d-none" id="kategoriNoResult">Tidak ada kategori yang cocok.</div>
                    <div class="small text-muted mb-2 d-none" id="kategoriHint">Ketik nama kategori untuk melihat rekomendasi.</div>
                    <div class="list-group border shadow-sm rounded-3" 
                        id="containerListKategori" 
                        style="max-height: 300px; overflow-y: auto !important; display: block !important;">
                        
                        @if(isset($kategori) && count($kategori) > 0)
                            @foreach($kategori as $kat)
                                <div class="list-group-item d-flex justify-content-between align-items-center bg-light item-kategori" data-search="{{ strtolower($kat->nama_kategori) }}">
                                    <span class="nama-kategori-text">{{ $kat->nama_kategori }}</span>
                                    <button type="button" class="btn btn-sm btn-white border btn-toggle-visible-kat" data-id="{{ $kat->kd_kategori }}">
                                        <i class="bi {{ $kat->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="list-group-item text-muted">Data kategori kosong.</div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-danger w-100 py-2 fw-bold rounded-pill" data-bs-dismiss="modal">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKelolaMerk" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="fw-bold">Kelola Merk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <form id="formTambahMerk">
                        @csrf
                        <div class="input-group mb-3 shadow-sm border rounded-pill overflow-hidden">
                            <input type="text" id="inputNamaMerk" name="nama_merk" class="form-control border-0 px-3" placeholder="Tambah merk baru..." required>
                            <button class="btn btn-success border-0 px-4" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>
                    <div class="input-group mb-2 shadow-sm border rounded-pill overflow-hidden bg-light">
                        <input type="text" id="searchMerk" class="form-control border-0 bg-transparent ps-3" placeholder="Cari merk...">
                        <button type="button" id="btnSearchMerk" class="btn btn-light border-0 px-4"><i class="bi bi-search text-muted"></i></button>
                    </div>
                    <div id="searchMerkRekomendasi" class="list-group shadow-sm rounded-3 overflow-hidden mb-3 d-none" style="max-height: 180px; overflow-y: auto;"></div>
                    <div class="small text-muted mb-2 d-none" id="merkNoResult">Tidak ada merk yang cocok.</div>
                    <div class="small text-muted mb-2 d-none" id="merkHint">Ketik nama merk untuk melihat rekomendasi.</div>
                    <div class="list-group border shadow-sm rounded-3 overflow-hidden" id="containerListMerk" style="max-height: 250px; overflow-y: auto;">
                        @foreach($merk as $m)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-light item-merk" data-search="{{ strtolower($m->nama_merk) }}">
                                <span class="nama-merk-text">{{ $m->nama_merk }}</span>
                                <button type="button" class="btn btn-sm btn-white border btn-toggle-visible" data-id="{{ $m->kd_merk }}">
                                    <i class="bi {{ $m->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-danger w-100 py-2 fw-bold rounded-pill" data-bs-dismiss="modal">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKelolaSatuan" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="fw-bold">Kelola Satuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <form id="formTambahSatuan">
                        @csrf
                        <div class="row g-2 mb-3">
                            <div class="col-md-8">
                                <div class="input-group shadow-sm border rounded-pill overflow-hidden h-100">
                                    <input type="text" id="inputNamaSatuan" name="nama_satuan" class="form-control border-0 px-3" placeholder="Nama satuan, mis. pcs, box, rim" required>
                                    <button class="btn btn-success border-0 px-4" type="submit"><i class="bi bi-plus-lg"></i></button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group shadow-sm border rounded-pill overflow-hidden h-100">
                                    <span class="input-group-text bg-white border-0 ps-3 text-muted small">Batas</span>
                                    <input type="number" id="inputStokMinimalSatuan" name="stok_minimal" min="0" step="1" class="form-control border-0 px-3" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="input-group mb-2 shadow-sm border rounded-pill overflow-hidden bg-light">
                        <input type="text" id="searchSatuan" class="form-control border-0 bg-transparent ps-3" placeholder="Cari satuan...">
                        <button type="button" id="btnSearchSatuan" class="btn btn-light border-0 px-4"><i class="bi bi-search text-muted"></i></button>
                    </div>
                    <div id="searchSatuanRekomendasi" class="list-group shadow-sm rounded-3 overflow-hidden mb-3 d-none" style="max-height: 180px; overflow-y: auto;"></div>
                    <div class="small text-muted mb-2 d-none" id="satuanNoResult">Tidak ada satuan yang cocok.</div>
                    <div class="small text-muted mb-2 d-none" id="satuanHint">Ketik nama satuan untuk melihat rekomendasi.</div>
                    <div class="list-group border shadow-sm rounded-3 overflow-hidden" id="containerListSatuan" style="max-height: 250px; overflow-y: auto;">
                        @foreach($satuan as $sat)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-light item-satuan" data-search="{{ strtolower($sat->nama_satuan) }}">
                                <div>
                                    <span class="nama-satuan-text fw-semibold">{{ $sat->nama_satuan }}</span>
                                    <div class="small text-muted">Batas warning: {{ $sat->stok_minimal ?? 0 }}</div>
                                </div>
                                <button type="button" class="btn btn-sm btn-white border btn-toggle-visible-satuan" data-id="{{ $sat->kd_satuan }}">
                                    <i class="bi {{ $sat->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-danger w-100 py-2 fw-bold rounded-pill" data-bs-dismiss="modal">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endauth
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Setup CSRF Token sekali saja untuk semua request AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // 2. Event tunggal untuk semua tombol toggle (Kategori, Merk, Satuan)
    // Menggunakan $(document).off().on() untuk memastikan tidak ada event yang ganda
    $(document).off('click', '.btn-toggle-visible-kat, .btn-toggle-visible, .btn-toggle-visible-satuan')
               .on('click', '.btn-toggle-visible-kat, .btn-toggle-visible, .btn-toggle-visible-satuan', function(e) {
        e.preventDefault();
        
        const btn = $(this);
        const id = btn.data('id');
        const icon = btn.find('i');
        
        let url = "";
        if (btn.hasClass('btn-toggle-visible-kat')) url = "{{ route('kategori.toggle') }}";
        else if (btn.hasClass('btn-toggle-visible')) url = "{{ route('merk.toggle') }}";
        else if (btn.hasClass('btn-toggle-visible-satuan')) url = "{{ route('satuan.toggle') }}";

        $.ajax({
            url: url,
            method: "POST",
            data: { id: id },
            success: function(res) {
                if(res.success) {
                    // Update tampilan icon secara aman
                    if(res.is_hidden) {
                        icon.attr('class', 'bi bi-eye-slash-fill text-danger');
                    } else {
                        icon.attr('class', 'bi bi-eye-fill text-primary');
                    }
                }
            },
            error: function(xhr) {
                console.log("Error:", xhr.responseText);
            }
        });
    });
    const kategoriIndexUrl = "{{ route('produk.index') }}";

    function setupSearchRecommendation(inputSelector, buttonSelector, listSelector, itemSelector, textSelector, recommendationSelector, noResultSelector, hintSelector) {
        const input = $(inputSelector);
        const button = $(buttonSelector);
        const list = $(listSelector);
        const recommendation = $(recommendationSelector);
        const noResult = $(noResultSelector);
        const hint = $(hintSelector);

        list.find(itemSelector).each(function(index) {
            $(this).attr('data-order', index);
        });

        function sortList(allItems, matchedItems) {
            const matchedIds = new Set(matchedItems.map(function() {
                return $(this).attr('data-order');
            }).get());

            const orderedItems = allItems.get().sort(function(a, b) {
                const aOrder = parseInt($(a).attr('data-order') || 0, 10);
                const bOrder = parseInt($(b).attr('data-order') || 0, 10);
                const aMatched = matchedIds.has(String(aOrder));
                const bMatched = matchedIds.has(String(bOrder));

                if (aMatched !== bMatched) {
                    return aMatched ? -1 : 1;
                }

                return aOrder - bOrder;
            });

            list.append(orderedItems);
        }

        function runSearch() {
            const value = input.val().trim().toLowerCase();

            if (!value) {
                list.find(itemSelector).show();
                recommendation.addClass('d-none').empty();
                noResult.addClass('d-none');
                hint.removeClass('d-none');
                sortList(list.find(itemSelector), list.find(itemSelector));
                return;
            }

            hint.addClass('d-none');

            const matched = list.find(itemSelector).filter(function() {
                const text = $(this).find(textSelector).text().toLowerCase();
                const dataSearch = ($(this).data('search') || text).toString().toLowerCase();
                return text.includes(value) || dataSearch.includes(value);
            });

            list.find(itemSelector).show();
            sortList(list.find(itemSelector), matched);

            recommendation.empty();

            if (matched.length > 0) {
                matched.each(function() {
                    const item = $(this).clone();
                    item.removeClass('item-kategori item-merk bg-light').addClass('list-group-item-action');
                    item.find('button').remove();
                    item.on('click', function() {
                        $(inputSelector).val($(this).find(textSelector).text());
                        runSearch();
                        $(inputSelector).trigger('focus');
                    });
                    recommendation.append(item);
                });
                recommendation.removeClass('d-none');
                noResult.addClass('d-none');
            } else {
                recommendation.addClass('d-none');
                noResult.removeClass('d-none');
            }
        }

        input.on('input', runSearch);
        button.on('click', runSearch);
        input.on('focus', runSearch);
    }

    // Live Search Kategori Sidebar
    $("#searchKategori").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#kategoriList .kat-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    setupSearchRecommendation(
        '#searchKategoriModal',
        '#btnSearchKategoriModal',
        '#containerListKategori',
        '.item-kategori',
        '.nama-kategori-text',
        '#searchKategoriRekomendasi',
        '#kategoriNoResult',
        '#kategoriHint'
    );

    setupSearchRecommendation(
        '#searchMerk',
        '#btnSearchMerk',
        '#containerListMerk',
        '.item-merk',
        '.nama-merk-text',
        '#searchMerkRekomendasi',
        '#merkNoResult',
        '#merkHint'
    );

    setupSearchRecommendation(
        '#searchSatuan',
        '#btnSearchSatuan',
        '#containerListSatuan',
        '.item-satuan',
        '.nama-satuan-text',
        '#searchSatuanRekomendasi',
        '#satuanNoResult',
        '#satuanHint'
    );

    // AJAX Tambah Kategori
    $('#formTambahKategori').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('kategori.store') }}", $(this).serialize())
            .done(function(res) {
            if(res.success) {
                const kategoriItem = $('<div>', {
                    class: 'list-group-item d-flex justify-content-between align-items-center bg-light item-kategori'
                });
                $('<span>', { class: 'nama-kategori-text' }).text(res.data.nama_kategori).appendTo(kategoriItem);

                const tombolVisibilitas = $('<button>', {
                    type: 'button',
                    class: 'btn btn-sm btn-white border btn-toggle-visible-kat',
                    'data-id': res.data.kd_kategori
                });
                $('<i>', { class: 'bi bi-eye-fill text-primary' }).appendTo(tombolVisibilitas);
                kategoriItem.append(tombolVisibilitas);
                $('#containerListKategori').prepend(kategoriItem);

                const sidebarItem = $('<a>', {
                    href: kategoriIndexUrl + '?kategori=' + encodeURIComponent(res.data.kd_kategori),
                    class: 'kat-item'
                });
                sidebarItem.append(document.createTextNode(res.data.nama_kategori + ' '));
                sidebarItem.append($('<i>', { class: 'bi bi-chevron-right small' }));
                $('#kategoriList').append(sidebarItem);
                $('#inputNamaKategori').val('');
            }
        })
            .fail(function(xhr) {
                const pesan = xhr.responseJSON?.message || 'Kategori gagal ditambahkan. Coba lagi.';
            });
    });

    // AJAX Tambah Merk
    $('#formTambahMerk').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('merk.store') }}", $(this).serialize(), function(res) {
            if(res.success) {
                $('#containerListMerk').prepend(`<div class="list-group-item d-flex justify-content-between align-items-center bg-light item-merk" data-search="${res.data.nama_merk.toLowerCase()}"><span class="nama-merk-text">${res.data.nama_merk}</span><i class="bi bi-eye-fill text-primary"></i></div>`);
                $('select[name="merk"]').append(`<option value="${res.data.kd_merk}">${res.data.nama_merk}</option>`);
                $('#inputNamaMerk').val('');
            }
        });
    });

    // AJAX Tambah Satuan
    $('#formTambahSatuan').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('satuan.store') }}", $(this).serialize(), function(res) {
            if(res.success) {
                $('#containerListSatuan').prepend(`<div class="list-group-item d-flex justify-content-between align-items-center bg-light item-satuan" data-search="${res.data.nama_satuan.toLowerCase()}"><div><span class="nama-satuan-text fw-semibold">${res.data.nama_satuan}</span><div class="small text-muted">Batas warning: ${res.data.stok_minimal ?? 0}</div></div><button type="button" class="btn btn-sm btn-white border btn-toggle-visible-satuan" data-id="${res.data.kd_satuan}"><i class="bi bi-eye-fill text-primary"></i></button></div>`);
                $('select[name="kd_satuan"]').append(`<option value="${res.data.kd_satuan}">${res.data.nama_satuan}</option>`);
                $('#inputNamaSatuan').val('');
                $('#inputStokMinimalSatuan').val('');
            }
        }).fail(function(xhr) {
            const pesan = xhr.responseJSON?.message || 'Satuan gagal ditambahkan. Coba lagi.';
        });
    });

    // AJAX Update Status Produk
    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // Gunakan .off('change') sebelum .on('change') untuk mencegah duplikasi
        $('.status-dropdown').off('change').on('change', function() {
            const dropdown = $(this);
            const status = dropdown.val();
            const id = dropdown.data('id');

            dropdown.prop('disabled', true);

            $.ajax({
                url: "{{ route('produk.updateStatus') }}",
                method: "POST",
                data: { id: id, status: status },
                success: function(response) {
                    dropdown.prop('disabled', false);
                    
                    // Update warna
                    dropdown.removeClass('text-success text-danger')
                            .addClass(status === 'aktif' ? 'text-success' : 'text-danger');

                    if (response.success) {
                        alert(response.message); 
                    }
                },
                error: function(xhr) {
                    dropdown.prop('disabled', false);
                    console.log(xhr.responseText);
                    alert('Gagal memperbarui status.');
                }
            });
        });
    });
});
</script>
@endpush