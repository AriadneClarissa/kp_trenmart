@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => 'customers'])

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Daftar Pelanggan (Langganan & Regular)</h4>
    </div>

    <div class="card shadow-sm w-100">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle w-100 mb-0" style="font-size: 0.95rem; table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1 px-2" style="width: 5%;">#</th>
                            <th class="py-1 px-2" style="width: 16%;">Nama</th>
                            <th class="py-1 px-2" style="width: 24%;">Email</th>
                            <th class="py-1 px-2" style="width: 12%;">Jenis</th>
                            <th class="py-1 px-2" style="width: 12%;">No. Telepon</th>
                            <th class="py-1 px-2" style="width: 15%;">Alamat</th>
                            <th class="py-1 px-2" style="width: 12%;">Organisasi (jika ada)</th>
                            <th class="py-1 px-2" style="width: 10%;">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <td class="py-1 px-2 text-truncate">{{ $c->id }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $c->name }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $c->email }}</td>
                            <td class="py-1 px-2 text-truncate">{{ strtoupper($c->customer_type ?? 'regular') }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $c->phone_number ?? '-' }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $c->home_address ?? '-' }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $c->organization_name ?? '-' }}</td>
                            <td class="py-1 px-2">{{ $c->created_at ? $c->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
