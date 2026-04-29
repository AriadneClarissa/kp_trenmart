@extends('layouts.app')

@push('styles')
<style>
    :root { --maroon: #800000; --soft-bg: #f8f9fa; }
    body { background-color: var(--soft-bg); }

    /* --- SIDEBAR KATEGORI --- */
    .sidebar-katalog { background: white; border-radius: 20px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: sticky; top: 90px; }
    .search-kategori { background-color: #f1f1f1; border: none; border-radius: 50px; padding: 10px 15px; font-size: 14px; }
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
    .kat-item:hover,
    .kat-item.active {
        color: var(--maroon);
        background-color: #fff5f5;
        font-weight: 500;
    }
    
    /* Panel Admin Card di Sidebar */
    .panel-admin-card { background-color: #fff9e6; border: 1px solid #ffecb3; border-radius: 15px; margin-bottom: 20px; }
    .btn-kelola { font-size: 13px; font-weight: 600; border-radius: 10px; width: 100%; margin-bottom: 8px; transition: 0.3s; }

    /* --- TOP FILTER PILL --- */
    .top-filter-pill { background: white; border-radius: 50px; padding: 8px 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; display: flex; align-items: center; width: 100%; }
    .top-filter-input { border: none; background: transparent; outline: none; width: 100%; font-size: 14px; padding-left: 10px; }
    .top-select-filter { border: none; background: white; border-radius: 50px; padding: 10px 20px; cursor: pointer; color: #666; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); width: 100%; border: 1px solid #eee; }

    /* --- CARD PRODUK --- */
    .card-produk { border-radius: 25px !important; transition: 0.3s; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.06); height: 100%; overflow: hidden; background: white; display: flex; flex-direction: column; }
    .card-produk:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
    .card-clickable { cursor: pointer; }
    .img-container { background-color: #f8f9fa; border-radius: 20px; padding: 25px; min-height: 190px; display: flex; align-items: center; justify-content: center; position: relative; margin: 10px; }
    
    .badge-stok { position: absolute; top: 10px; left: 10px; font-size: 10px; padding: 4px 10px; border-radius: 8px; }
    .product-info { padding: 15px; flex-grow: 1; display: flex; flex-direction: column; }
    .price-text { color: var(--maroon); font-weight: 800; font-size: 1.2rem; }
    
    .btn-action { color: white; border-radius: 12px; border: none; font-weight: 600; padding: 12px; width: 100%; transition: 0.2s; font-size: 14px; text-decoration: none; display: block; text-align: center; }
    .btn-tambah { background-color: var(--maroon); }
    .btn-tambah:hover { background-color: #600000; box-shadow: 0 4px 15px rgba(128,0,0,0.3); color: white; }
    .btn-detail { background-color: #444; }
    .btn-detail:hover { background-color: #222; color: white; }

    /* Custom Scrollbar */
    .list-kategori { max-height: 400px; overflow-y: auto; scrollbar-width: none; }
    .list-kategori::-webkit-scrollbar { display: none; }

    /* Lebihkan kanvas katalog agar tidak terlihat terlalu ke tengah */
    .katalog-wrap { max-width: 1520px; margin: 0 auto; }

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
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-lg-4 px-xl-5 mt-4 mb-5 katalog-wrap">
    <div class="row g-4">
        
        {{-- SIDEBAR KIRI --}}
        <div class="col-md-4 col-lg-3 col-xl-3 d-none d-md-block">
            
            @auth
                @if(auth()->user()->isAdmin())
                <div class="panel-admin-card p-3 shadow-sm mb-4">
                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-shield-lock me-2"></i>Panel Admin</h6>
                    <button class="btn btn-info text-white btn-kelola shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">
                        <i class="bi bi-tag me-1"></i> Kelola Kategori
                    </button>
                    <button class="btn btn-success btn-kelola shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKelolaMerk">
                        <i class="bi bi-building me-1"></i> Kelola Merk
                    </button>
                </div>
                @endif
            @endauth

            <div class="sidebar-katalog p-4 shadow-sm">
                <h6 class="fw-bold mb-3">Kategori</h6>
                <div class="mb-3">
                    <input type="text" id="inputSearchKat" class="form-control search-kategori" placeholder="Cari kategori...">
                </div>
                <div class="list-kategori" id="listKatSide">
                    <a href="{{ route('katalog', ['search' => request('search'), 'merk' => request('merk')]) }}" class="kat-item {{ !request('kategori') ? 'active' : '' }}">
                        <span>Semua Produk</span> <i class="bi bi-chevron-right"></i>
                    </a>
                    @foreach($kategori as $kat)
                        <a href="{{ route('katalog', ['kategori' => $kat->kd_kategori, 'search' => request('search'), 'merk' => request('merk')]) }}" class="kat-item {{ request('kategori') == $kat->kd_kategori ? 'active' : '' }}">
                            <span class="nama-kat">{{ $kat->nama_kategori }}</span> <i class="bi bi-chevron-right"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- AREA UTAMA --}}
        <div class="col-md-8 col-lg-9 col-xl-9">
            {{-- Filter Bar --}}
            <form action="{{ route('katalog') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <div class="col-md-7">
                    <div class="top-filter-pill">
                        <i class="bi bi-search text-muted"></i>
                        <input type="text" name="search" class="top-filter-input" placeholder="Cari produk..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <select name="merk" class="top-select-filter" onchange="this.form.submit()">
                        <option value="">Semua Merek</option>
                        @foreach($merk as $m)
                            <option value="{{ $m->kd_merk }}" {{ request('merk') == $m->kd_merk ? 'selected' : '' }}>{{ $m->nama_merk }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-end text-muted small">
                    {{ count($produk) }} produk ditemukan
                </div>
            </form>

            {{-- Grid Produk --}}
            <div class="row row-cols-2 row-cols-md-3 g-4">
                @forelse($produk as $p)
                <div class="col">
                    <div class="card card-produk {{ auth()->check() && !auth()->user()->isAdmin() ? 'card-clickable' : '' }}"
                        @if(auth()->check() && !auth()->user()->isAdmin())
                            onclick="if(event.target.closest('form') || event.target.closest('button') || event.target.closest('a')) return; window.location.href='{{ route('produk.detail', ['id' => $p->kd_produk, 'from' => 'katalog']) }}'"
                        @endif>
                        <div class="img-container">
                            @if($p->stok_tersedia > 0)
                                <span class="badge bg-success badge-stok shadow-sm">Tersedia</span>
                            @else
                                <span class="badge bg-danger badge-stok shadow-sm">Habis</span>
                            @endif
                            <img src="{{ asset('storage/' . $p->gambar) }}" class="img-fluid" style="height: 150px; object-fit: contain;" alt="{{ $p->nama_produk }}">
                        </div>
                        
                        <div class="product-info text-center">
                            <p class="text-muted small mb-1">{{ $p->merk->nama_merk ?? 'No Brand' }}</p>
                            <h6 class="fw-bold text-dark text-truncate mb-2" title="{{ $p->nama_produk }}">{{ $p->nama_produk }}</h6>
                            
                            {{-- Perbaikan Harga agar tidak Rp 0 --}}
                            <h5 class="price-text mb-1">
                                Rp {{ number_format(($p->harga_tampil > 0 ? $p->harga_tampil : $p->harga_jual_umum), 0, ',', '.') }}
                                <span class="text-muted small fw-normal" style="font-size: 11px;">/{{ $p->satuan }}</span>
                            </h5>
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <p class="mb-3 fw-semibold" style="color: #f08a24; font-size: 0.95rem;">
                                        Langganan: Rp {{ number_format($p->harga_jual_langganan ?? $p->harga_jual_umum, 0, ',', '.') }}
                                    </p>
                                @endif
                            @endauth
                            
                            {{-- LOGIKA TOMBOL ADMIN VS USER --}}
                            @auth
                                @if(auth()->user()->isAdmin())
                                    {{-- Jika Admin, tampilkan Lihat Detail (Gak bisa beli sendiri) --}}
                                    <a href="{{ route('produk.detail', ['id' => $p->kd_produk, 'from' => 'katalog']) }}" class="btn-action btn-detail shadow-sm">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                @else
                                    {{-- Jika Pelanggan, tampilkan Tambah --}}
                                    <form action="{{ route('cart.add', $p->kd_produk) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn-action btn-tambah shadow-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Tambah
                                        </button>
                                    </form>
                                @endif
                            @else
                                {{-- Jika Guest (Belum Login), tampilkan Tambah (Akan diarahkan ke login) --}}
                                <form action="{{ route('cart.add', $p->kd_produk) }}" method="POST" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn-action btn-tambah shadow-sm">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-produk-state py-5">
                    <i class="bi bi-search text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                    <h5 class="mt-3 text-muted">Produk tidak ditemukan</h5>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Live Search Kategori
    $('#inputSearchKat').on('keyup', function() {
        let val = $(this).val().toLowerCase();
        $("#listKatSide .kat-item").filter(function() {
            $(this).toggle($(this).find('.nama-kat').text().toLowerCase().indexOf(val) > -1)
        });
    });

});
</script>
@endpush