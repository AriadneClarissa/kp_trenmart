@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="fs-1 mb-3">⏳</div>
            
            <h3 class="fw-bold">Akun pelanggan Anda sedang ditinjau</h3>
            <p class="text-muted">
                Terima kasih telah mendaftar, <strong>{{ $user->name }}</strong>.<br>
                Permintaan Anda sedang diproses oleh Admin. Kami akan segera mengaktifkan akun Anda.
            </p>
            
            <div class="card bg-light border-0 rounded-3 mb-4 shadow-sm">
                <div class="card-body p-4 text-start">
                    <small class="text-uppercase text-muted d-block mb-3 text-center fw-bold" style="letter-spacing: 1px;">
                        Data yang dikirim:
                    </small>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Nama Perusahaan</span>
                        <span class="fw-bold text-dark">{{ $user->organization_name }}</span>
                    </div>
                    
                    <hr class="my-2 opacity-25"> <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">WhatsApp</span>
                        <span class="fw-bold text-dark">{{ $user->phone_number }}</span>
                    </div>

                    <hr class="my-2 opacity-25"> <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Email</span>
                        <span class="fw-bold text-dark">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center align-items-center gap-3">
                <a href="{{ route('beranda') }}" class="btn btn-outline-secondary px-4 py-2">
                    Cek Status
                </a>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger text-decoration-none p-0">
                        Keluar
                    </button>
                </form>
            </div>

            <div class="mt-4">
                <p class="small text-muted">Butuh bantuan? <a href="https://wa.me/6282178504488" class="text-decoration-none" style="color: #f05a28;">Hubungi CS Trenmart</a></p>
            </div>
        </div>
    </div>
</div>
@endsection