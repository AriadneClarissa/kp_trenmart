@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Data Pengiriman</h4>
                    <p class="text-muted mb-4">Lengkapi data berikut untuk memudahkan proses pengiriman pesanan Anda.</p>

                    <form action="{{ route('profile.initial.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_type" value="regular">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="number" name="phone_number" class="form-control" placeholder="08..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="home_address" class="form-control" rows="4" placeholder="Jl. Nama Jalan, No. Rumah, Kecamatan, Kota..." required></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger fw-bold py-2">
                                Selesai & Mulai Belanja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection