@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3>Lengkapi profil Anda</h3>
    <p class="text-muted">Pilih jenis akun yang sesuai. Anda dapat melanjutkan setelah memilih.</p>

    <form action="{{ route('pilih.jenis.post') }}" method="POST">
        @csrf
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <input type="radio" name="jenis" value="regular" id="umum" class="btn-check" required>
                <label class="card p-4 h-100 shadow-sm border-2 clickable-card" for="umum">
                    <div class="d-flex justify-content-between">
                        <span class="fs-1">📦</span>
                        <div class="check-icon"></div>
                    </div>
                    <h5 class="fw-bold mt-3">Pembeli Umum</h5>
                    <p class="text-muted small">Untuk pembelian satuan. Aktif langsung.</p>
                </label>
            </div>

            <div class="col-md-6 mb-3">
                <input type="radio" name="jenis" value="langganan" id="langganan" class="btn-check">
                <label class="card p-4 h-100 shadow-sm border-2 clickable-card" for="langganan">
                    <div class="d-flex justify-content-between">
                        <span class="fs-1">🤝</span>
                        <div class="check-icon"></div>
                    </div>
                    <h5 class="fw-bold mt-3">Pelanggan Grosir</h5>
                    <p class="text-muted small">Untuk toko atau perusahaan, butuh persetujuan admin.</p>
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-danger px-5 py-2 fw-bold">Lanjut</button>
        </div>
    </form>
</div>

<style>
    .clickable-card { cursor: pointer; transition: 0.3s; }
    .btn-check:checked + .card { border-color: #f05a28 !important; background-color: #fff5f2; }
    .btn-check:checked + .card .check-icon::after { content: "✓"; color: #f05a28; font-weight: bold; }
</style>
@endsection