@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => 'users'])

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Buat Akun Pelanggan Langganan</h4>
    </div>

    <div class="card p-4 shadow-sm">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Pelanggan</label>
                    <select name="customer_type" class="form-select">
                        <option value="langganan">Langganan (Grosir)</option>
                        <option value="regular">Regular (Eceran)</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password Default (opsional)</label>
                    <input type="text" name="default_password" class="form-control" placeholder="Kosongkan untuk generate otomatis">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama Organisasi (opsional)</label>
                    <input type="text" name="organization_name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipe Organisasi (opsional)</label>
                    <input type="text" name="organization_type" class="form-control">
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1" checked>
                        <label class="form-check-label" for="send_email">Kirim email berisi kredensial ke pelanggan</label>
                    </div>
                </div>

                <div class="col-12 text-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-2">Batal</a>
                    <button class="btn btn-primary">Buat Akun</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
