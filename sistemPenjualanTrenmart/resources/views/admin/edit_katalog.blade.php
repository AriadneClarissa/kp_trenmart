@extends('layouts.app')

@push('styles')
<style>
    :root { --maroon: #800000; --light-bg: #f8f9fa; }
    body { background-color: var(--light-bg); }

    /* Sidebar Kategori & Admin Style */
    .sidebar-container { position: sticky; top: 90px; }
    .card-sidebar { border-radius: 15px; background: white; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    
    /* Search Kategori Style */
    .search-kategori { background-color: #f1f1f1; border: none; border-radius: 50px; padding: 8px 15px; font-size: 14px; }
    
    /* List Kategori Style (Sesuai Gambar Mockup) */
    .list-kategori { max-height: 350px; overflow-y: auto; }
    .kat-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 10px; color: #555; text-decoration: none; border-bottom: 1px solid #f8f9fa; transition: 0.2s; font-size: 14px; }
    .kat-item:hover, .kat-item.active { color: var(--maroon); background-color: #fff5f5; font-weight: 600; }
    
    /* Tombol Admin Panel */
    .sidebar-header-admin { font-weight: bold; font-size: 0.9rem; border-bottom: 1px solid #eee; padding: 12px 20px; background-color: #fff9e6; color: #333; }
    .btn-admin-panel { width: 100%; border-radius: 12px; font-weight: 600; font-size: 13px; padding: 10px; margin-bottom: 10px; border: none; color: white; display: block; text-align: center; text-decoration: none; }

    /* Filter Bar Pill Style (Atas) */
    .filter-pill { background: white; border-radius: 50px; padding: 5px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; align-items: center; }
    .input-filter { border: none; background: transparent; outline: none; padding: 8px 0; width: 100%; font-size: 14px; }
    .select-filter { border: none; background: white; border-radius: 50px; padding: 10px 20px; cursor: pointer; color: #666; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); width: 100%; }

    /* Card Produk Style */
    .card-produk { border-radius: 20px !important; transition: 0.3s; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: 100%; }
    .card-produk:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
    .card-produk-link { text-decoration: none; color: inherit; display: block; height: 100%; cursor: pointer; }
    .card-produk-link:hover { color: inherit; }
    .img-container { background-color: #f8f9fa; border-radius: 15px; padding: 20px; min-height: 180px; display: flex; align-items: center; justify-content: center; position: relative; }
    
    .btn-detail { background-color: var(--maroon); color: white; border-radius: 12px; border: none; font-weight: 600; padding: 10px; width: 100%; transition: 0.2s; display: block; text-align: center; text-decoration: none; }
    .btn-detail:hover { background-color: #600000; color: white; }
</style>
@endpush

@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        
        {{-- 1. SIDEBAR KIRI --}}
        <div class="col-md-3">
            <div class="sidebar-container">
                
                {{-- KHUSUS ADMIN: Tombol Kelola --}}
                @auth
                    @if(auth()->user()->isAdmin())
                    <div class="card-sidebar mb-4" style="border: 1px solid #ffc107;">
                        <div class="sidebar-header-admin"><i class="bi bi-shield-lock-fill me-2"></i>Panel Admin</div>
                        <div class="p-3">
                            <button class="btn-admin-panel shadow-sm" style="background-color: #55bdff;" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">
                                Kelola Kategori
                            </button>
                            <button class="btn-admin-panel shadow-sm" style="background-color: #198754;" data-bs-toggle="modal" data-bs-target="#modalKelolaMerk">
                                Kelola Merk
                            </button>
                        </div>
                    </div>
                    @endif
                @endauth

                {{-- SEMUA USER: Sidebar Kategori Sesuai Gambar --}}
                <div class="card-sidebar p-4">
                    <h6 class="fw-bold mb-3">Kategori</h6>
                    <div class="mb-3">
                        <input type="text" id="searchKategori" class="form-control search-kategori" placeholder="Cari kategori...">
                    </div>
                    <div class="list-kategori" id="kategoriList">
                        <a href="{{ route('katalog') }}" class="kat-item {{ !request('kategori') ? 'active' : '' }}">
                            Semua Produk <i class="bi bi-chevron-right small"></i>
                        </a>
                        @foreach($kategori as $kat)
                            <a href="{{ route('katalog', ['kategori' => $kat->kd_kategori]) }}" class="kat-item {{ request('kategori') == $kat->kd_kategori ? 'active' : '' }}">
                                {{ $kat->nama_kategori }} <i class="bi bi-chevron-right small"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. AREA UTAMA --}}
        <div class="col-md-9">
            <form action="{{ route('katalog') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-6">
                    <div class="filter-pill">
                        <i class="bi bi-search text-muted me-2"></i>
                        <input type="text" name="search" class="input-filter" placeholder="Cari produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="merk" class="select-filter" onchange="this.form.submit()">
                        <option value="">Semua Merek</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->kd_merk }}" {{ request('merk') == $m->kd_merk ? 'selected' : '' }}>{{ $m->nama_merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 text-end text-muted small d-none d-md-block">
                    {{ count($produk) }} produk ditemukan
                </div>
            </form>

            <div class="row row-cols-2 row-cols-md-4 g-4">
                @forelse($produk as $p)
                <div class="col">
                    <a href="{{ route('produk.detail', ['id' => $p->kd_produk, 'from' => 'katalog']) }}" class="card card-produk card-produk-link p-3">
                        <div class="img-container mb-3">
                            @if($p->stok_tersedia > 0)
                                <span class="badge bg-success position-absolute top-0 start-0 m-2 px-2 py-1 shadow-sm" style="font-size: 10px;">Tersedia</span>
                            @else
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2 px-2 py-1 shadow-sm" style="font-size: 10px;">Habis</span>
                            @endif
                            <img src="{{ asset('storage/' . $p->gambar) }}" class="img-fluid" style="height: 140px; object-fit: contain;">
                        </div>
                        <div class="product-info">
                            <p class="text-muted small mb-1">{{ $p->merk->nama_merk ?? 'Tanpa Merk' }}</p>
                            <h6 class="fw-bold text-dark text-truncate mb-2">{{ $p->nama_produk }}</h6>
                            <h5 class="fw-bold mb-3" style="color: var(--maroon);">
                                Rp {{ number_format(($p->harga_tampil > 0 ? $p->harga_tampil : $p->harga_jual_umum), 0, ',', '.') }}
                            </h5>
                            <span class="btn btn-detail">
                                <i class="bi bi-eye me-1"></i> Lihat Detail
                            </span>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3">Produk tidak tersedia</h5>
                </div>
                @endforelse
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
                    <form id="formTambahKategori">
                        @csrf
                        <div class="input-group mb-4 shadow-sm border rounded-pill overflow-hidden">
                            <input type="text" id="inputNamaKategori" name="nama_kategori" class="form-control border-0 px-3" placeholder="Nama kategori baru..." required>
                            <button class="btn btn-success border-0 px-4" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>
                    <div class="list-group border shadow-sm rounded-3 overflow-hidden" id="containerListKategori" style="max-height: 250px; overflow-y: auto;">
                        @foreach($kategori as $kat)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <span>{{ $kat->nama_kategori }}</span>
                                <button class="btn btn-sm btn-white border btn-toggle-visible-kat" data-id="{{ $kat->kd_kategori }}">
                                    <i class="bi {{ $kat->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button class="btn btn-danger w-100 py-2 fw-bold rounded-pill" onclick="window.location.reload();">Selesai</button>
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
                    <div class="input-group mb-3 shadow-sm border rounded-pill overflow-hidden bg-light">
                        <span class="input-group-text bg-transparent border-0 ps-3"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="searchMerk" class="form-control border-0 bg-transparent" placeholder="Cari merk...">
                    </div>
                    <div class="list-group border shadow-sm rounded-3 overflow-hidden" id="containerListMerk" style="max-height: 250px; overflow-y: auto;">
                        @foreach($merk as $m)
                            <div class="list-group-item d-flex justify-content-between align-items-center bg-light item-merk">
                                <span class="nama-merk-text">{{ $m->nama_merk }}</span>
                                <button class="btn btn-sm btn-white border btn-toggle-visible" data-id="{{ $m->kd_merk }}">
                                    <i class="bi {{ $m->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button class="btn btn-danger w-100 py-2 fw-bold rounded-pill" onclick="window.location.reload();">Selesai</button>
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
    // Live Search Kategori Sidebar
    $("#searchKategori").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#kategoriList .kat-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Fitur Pencarian Merk di Modal
    $('#searchMerk').on('keyup', function() {
        let value = $(this).val().toLowerCase();
        $("#containerListMerk .item-merk").filter(function() {
            $(this).toggle($(this).find('.nama-merk-text').text().toLowerCase().indexOf(value) > -1)
        });
    });

    // AJAX Tambah Kategori
    $('#formTambahKategori').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('kategori.store') }}", $(this).serialize(), function(res) {
            if(res.success) {
                $('#containerListKategori').prepend(`<div class="list-group-item d-flex justify-content-between align-items-center bg-light"><span>${res.data.nama_kategori}</span><i class="bi bi-eye-fill text-primary"></i></div>`);
                $('#inputNamaKategori').val('');
            }
        });
    });

    // AJAX Tambah Merk
    $('#formTambahMerk').on('submit', function(e) {
        e.preventDefault();
        $.post("{{ route('merk.store') }}", $(this).serialize(), function(res) {
            if(res.success) {
                $('#containerListMerk').prepend(`<div class="list-group-item d-flex justify-content-between align-items-center bg-light"><span>${res.data.nama_merk}</span><i class="bi bi-eye-fill text-primary"></i></div>`);
                $('#inputNamaMerk').val('');
            }
        });
    });
});
</script>
@endpush