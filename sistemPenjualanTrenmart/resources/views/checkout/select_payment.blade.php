@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4>Pilih Metode Pembayaran</h4>

    <div class="row mt-4">
        <div class="col-md-8">
            <form action="{{ route('checkout.place_order') }}" method="POST">
                @csrf

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Metode Pembayaran</h6>
                    @foreach($paymentMethods as $method)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method_id" id="pm{{ $method->id }}" value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }}>
                        <label class="form-check-label" for="pm{{ $method->id }}">{{ $method->name }} @if($method->account_number) - {{ $method->account_number }} @endif</label>
                    </div>
                    @endforeach
                </div>

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Metode Pengambilan</h6>
                    @foreach($pickupOptions as $key => $label)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="pickup_method" id="pmode{{ $key }}" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                        <label class="form-check-label" for="pmode{{ $key }}">{{ $label }}</label>
                    </div>
                    @endforeach
                </div>

                <div class="card p-3 mb-3">
                    <h6 class="fw-bold">Ringkasan</h6>
                    <p>Total: <strong>Rp {{ number_format($total,0,',','.') }}</strong></p>
                    <button class="btn btn-primary">Lanjutkan dan Unggah Bukti</button>
                </div>
            </form>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h6 class="fw-bold">Item</h6>
                @foreach($items as $item)
                <div class="d-flex justify-content-between mb-2">
                    <div>{{ $item->produk->nama_produk }} x{{ $item->jumlah }}</div>
                    <div>Rp {{ number_format($item->harga_at_time * $item->jumlah,0,',','.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
