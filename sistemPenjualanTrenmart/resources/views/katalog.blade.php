@extends('layouts.app')

@section('content')
{{-- 1. Styles Khusus Katalog --}}
<style>
    :root { --maroon: #800000; }
    
    /* Navigasi Kategori (Sub-Navbar) */
    .sticky-kategori {
        top: 65px; 
        z-index: 1010;
        background: white;
    }
    .kat-link { 
        font-weight: 500; 
        color: #666; 
        text-decoration: none;
        transition: 0.3s;
        padding-bottom: 5px;
    }
    .kat-link:hover { color: var(--maroon); }
    .kat-active { 
        color: var(--maroon) !important; 
        font-weight: bold; 
        border-bottom: 2px solid var(--maroon); 
    }

    /* Sidebar Merk */
    .sidebar-card { border-radius: 15px; }
    .merk-item { 
        font-size: 14px; 
        border: none !important;
        transition: 0.2s;
    }
    .merk-item:hover { padding-left: 25px; background-color: #fff5f5; }
    .merk-active { color: var(--maroon) !important; font-weight: bold; background-color: #f8e7e7 !important; border-radius: 8px; }

    /* Card Produk */
    .card-produk { 
        border: none; 
        border-radius: 15px; 
        transition: 0.3s; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .card-produk:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    .img-container { 
        height: 180px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        padding: 10px;
    }
    .product-name { 
        font-size: 15px; 
        font-weight: 600; 
        height: 40px; 
        overflow: hidden; 
        color: #333;
    }
    .btn-tambah { 
        background-color: var(--maroon); 
        color: white; 
        border-radius: 10px; 
        font-weight: 600; 
        border: none;
    }
    .btn-tambah:hover { background-color: #600000; color: white; }
</style>

{{-- 2. Sub-Navbar Kategori (Seperti Foto Kedua) --}}
<div class="border-bottom py-3 mb-4 sticky-top sticky-kategori shadow-sm">
    <div class="container">
        <div class="d-flex justify-content-center gap-4">
            <a href="{{ route('katalog') }}" class="kat-link {{ !request('kategori') ? 'kat-active' : '' }}">
                Semua Produk
            </a>
            @foreach($kategori as $kat)
                <a href="{{ route('katalog', ['kategori' => $kat->kd_kategori]) }}" 
                   class="kat-link {{ request('kategori') == $kat->kd_kategori ? 'kat-active' : '' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        {{-- 3. Sidebar Merk --}}
        <div class="col-md-3">
            @if(request('kategori'))
                <div class="card sidebar-card shadow-sm border-0 position-sticky" style="top: 130px;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3"><i class="bi bi-filter-left me-2"></i>Pilih Merk</h6>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('katalog', ['kategori' => request('kategori')]) }}" 
                               class="list-group-item merk-item {{ !request('merk') ? 'merk-active' : '' }}">
                                Semua Merk
                            </a>
                            @foreach($merk as $m)
                                <a href="{{ route('katalog', ['kategori' => request('kategori'), 'merk' => $m->kd_merk]) }}" 
                                   class="list-group-item merk-item {{ request('merk') == $m->kd_merk ? 'merk-active' : '' }}">
                                    {{ $m->nama_merk }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card sidebar-card shadow-sm border-0 text-white p-3 text-center" style="background-color: var(--maroon);">
                    <i class="bi bi-info-circle fs-3 mb-2"></i>
                    <p class="small mb-0">Silakan pilih kategori di atas untuk melihat pilihan merk.</p>
                </div>
            @endif
        </div>

        {{-- 4. Grid Produk --}}
        <div class="col-md-9">
            @if($produk->isEmpty())
                <div class="text-center py-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted fw-bold">Maaf, produk tidak ditemukan.</p>
                    <a href="{{ route('katalog') }}" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($produk as $item)
                        <div class="col-md-4">
                            <div class="card card-produk p-3 h-100">
                                <div class="img-container">
                                    <img src="{{ asset('storage/' . $item->gambar) }}" 
                                         class="img-fluid rounded" 
                                         alt="{{ $item->nama_produk }}"
                                         style="max-height: 100%; object-fit: contain;">
                                </div>
                                <div class="card-body p-2 mt-2 text-center">
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">
                                        {{ $item->merk->nama_merk ?? 'Tanpa Merk' }}
                                    </small>
                                    <h6 class="product-name my-2">{{ $item->nama_produk }}</h6>
                                    <h5 class="text-danger fw-bold mb-3">
                                        Rp {{ number_format($item->harga_tampil, 0, ',', '.') }}
                                    </h5>
                                    <a href="{{ route('produk.show', $item->kd_produk) }}" class="btn btn-tambah w-100 btn-sm py-2">
                                        <i class="bi bi-eye me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 