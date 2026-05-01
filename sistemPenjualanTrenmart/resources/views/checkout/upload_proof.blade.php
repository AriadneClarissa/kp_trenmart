@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card p-4" style="border-radius: 14px;">
                <h5 class="fw-bold">Transfer & Upload Bukti</h5>

                <div class="card mt-3 p-3" style="border-radius:12px; background:#fff;">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">Transfer BCA</div>
                            <div class="text-muted small">Bank</div>
                            <div class="fw-bold mt-2">Bank BCA</div>
                            <div class="text-muted small">No. Rekening</div>
                            <div class="fw-bold">1234567890</div>
                            <div class="text-muted small mt-2">Atas Nama</div>
                            <div class="fw-bold">TREN MART</div>
                        </div>
                        <div class="text-end">
                            <div class="small text-muted">Total Transfer</div>
                            <div class="fw-bold text-danger" style="font-size:1.05rem;">Rp {{ number_format($order->total,0,',','.') }}</div>
                        </div>
                    </div>
                </div>

                <h6 class="mt-4">Upload Bukti Transfer</h6>
                <form action="{{ route('checkout.store_proof', $order->id) }}" method="POST" enctype="multipart/form-data" id="uploadProofForm">
                    @csrf
                    <div class="border rounded-3 p-3 mt-2" style="min-height:180px; background: #fbfffb; border-style:dashed; border-color:#d3f2dd; position:relative;">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" class="form-control" style="opacity:0; position:absolute; inset:0; width:100%; height:100%; cursor:pointer;">
                        <div id="previewArea" class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center text-muted">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                                <div class="mt-2">Klik area ini untuk memilih foto bukti transfer</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button id="submitProof" class="btn w-100 py-3" style="background-color:#800000; color:#fff; border-radius:10px;">Kirim Bukti & Konfirmasi Pesanan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-3" style="border-radius:14px;">
                <h6 class="fw-bold">Ringkasan Pesanan</h6>
                <hr>
                @foreach($order->items as $it)
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('storage/' . ($it->produk->gambar ?? 'images/no-image.png')) }}" style="width:64px; height:64px; object-fit:cover; border-radius:8px;" alt="">
                        <div class="ms-3">
                            <div class="fw-semibold">{{ $it->produk->nama_produk ?? '-' }}</div>
                            <div class="small text-muted">{{ $it->quantity }} × Rp {{ number_format($it->price,0,',','.') }}</div>
                        </div>
                        <div class="ms-auto fw-bold">Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</div>
                    </div>
                @endforeach

                <hr>
                <div class="d-flex justify-content-between fw-bold"> <div>Subtotal</div> <div>Rp {{ number_format($order->total,0,',','.') }}</div></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('payment_proof').addEventListener('change', function(e){
    const file = e.target.files[0];
    const preview = document.getElementById('previewArea');
    if (!file) return;
    const img = document.createElement('img');
    img.style.maxWidth = '100%';
    img.style.maxHeight = '100%';
    img.style.borderRadius = '10px';
    img.src = URL.createObjectURL(file);
    preview.innerHTML = '';
    preview.appendChild(img);
});

document.getElementById('uploadProofForm').addEventListener('submit', function(){
    const btn = document.getElementById('submitProof');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
});
</script>
@endpush

@endsection
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h4>Unggah Bukti Transfer - Pesanan #{{ $order->order_number }}</h4>

    <div class="card p-4 mt-3">
        <p>Metode: <strong>{{ $order->paymentMethod->name ?? '-' }}</strong></p>
        <p>Total: <strong>Rp {{ number_format($order->total,0,',','.') }}</strong></p>

        <form action="{{ route('checkout.store_proof', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="payment_proof" class="form-label">Foto Bukti Transfer (jpg, png)</label>
                <input type="file" name="payment_proof" id="payment_proof" class="form-control" required>
            </div>
            <button class="btn btn-success">Unggah dan Lanjut</button>
        </form>
    </div>
</div>
@endsection
