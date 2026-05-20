@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => 'customers'])

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pelanggan (Langganan & Regular)</h4>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kode Pelanggan</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis</th>
                            <th>No. Telepon</th>
                            <th>Alamat</th>
                            <th>Organisasi (jika ada)</th>
                            <th>Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->kd_pelanggan ?? '-' }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ strtoupper($c->customer_type ?? 'regular') }}</td>
                            <td>{{ $c->phone_number ?? '-' }}</td>
                            <td>{{ $c->home_address ?? '-' }}</td>
                            <td>{{ $c->organization_name ?? '-' }}</td>
                            <td>{{ $c->created_at ? $c->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
