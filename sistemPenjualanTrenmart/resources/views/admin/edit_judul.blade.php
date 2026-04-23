@extends('layouts.app')

@push('styles')
<style>
    :root { --maroon: #800000; --light-gray: #f8f9fa; }
    body { background-color: var(--light-gray); }
    .card-custom { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden; }
    .card-header-maroon { background-color: var(--maroon); color: white; padding: 20px; border: none; }
    .form-label { font-weight: 600; color: #444; }
    .input-pill { border-radius: 50px; padding: 12px 20px; border: 1px solid #ddd; }
    .input-pill:focus { border-color: var(--maroon); box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.15); }
    .btn-save { background-color: var(--maroon); color: white; border-radius: 50px; padding: 12px 35px; font-weight: bold; border: none; transition: 0.3s; }
    .btn-save:hover { background-color: #600000; transform: translateY(-2px); }
    .table-promo thead { background-color: #f1f1f1; }
    .form-check-input:checked { background-color: var(--maroon); border-color: var(--maroon); }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Pengaturan Tampilan</h3>
                    <p class="text-muted">Kelola judul halaman utama dan produk promo</p>
                </div>
                <a href="{{ route('beranda') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <form action="{{ route('admin.judul.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card card-custom mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold"><i class="bi bi-type me-2 text-primary"></i>Judul Section Beranda</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Judul Untuk Produk Terbaru</label>
                                <input type="text" name="judul_terbaru" class="form-control input-pill" 
                                       value="{{ $settings['judul_terbaru'] ?? 'Produk Terbaru' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small">Judul Untuk Produk Terpopuler</label>
                                <input type="text" name="judul_terpopuler" class="form-control input-pill" 
                                       value="{{ $settings['judul_terpopuler'] ?? 'Produk Terpopuler' }}" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-custom mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold"><i class="bi bi-star-fill me-2 text-warning"></i>Pilih Produk Promo</h5>
                        <p class="text-muted small">Produk yang diceklis akan muncul di section khusus promo</p>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-promo align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4" width="100">Promo?</th>
                                        <th>Nama Produk</th>
                                        <th>Merk</th>
                                        <th class="pe-4">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produk as $p)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="produk_pilihan[]" 
                                                       value="{{ $p->kd_produk }}" {{ $p->is_highlight ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $p->nama_produk }}</td>
                                        <td>{{ $p->merk->nama_merk ?? '-' }}</td>
                                        <td class="pe-4 fw-bold text-maroon">Rp {{ number_format($p->harga_tampil, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-save shadow-lg">
                        <i class="bi bi-cloud-check me-2"></i>Simpan Perubahan Tampilan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection