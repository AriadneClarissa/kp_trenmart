@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => 'payment'])

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">Manajemen Metode Pembayaran</h4>
    </div>

    <div class="card p-3 mt-3">
        <form action="{{ route('admin.payment_methods.store') }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-md-4"><input name="name" class="form-control" placeholder="Nama metode" required></div>
                <div class="col-md-3"><input name="account_name" class="form-control" placeholder="Pemilik rekening"></div>
                <div class="col-md-3"><input name="account_number" class="form-control" placeholder="No. rekening"></div>
                <div class="col-md-2"><button class="btn btn-primary">Tambah</button></div>
            </div>
        </form>

        <hr>
        <ul class="list-group">
            @foreach($methods as $m)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $m->name }}</strong>
                    <div class="small text-muted">{{ $m->account_name }} - {{ $m->account_number }}</div>
                </div>
                <div>
                    <form action="{{ route('admin.payment_methods.destroy', $m->id) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
