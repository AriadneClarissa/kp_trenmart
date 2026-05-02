@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card p-5" style="max-width:520px; border-radius:14px; text-align:center;">
        <div style="width:84px;height:84px;border-radius:84px;background:#e9f9ef;display:inline-flex;align-items:center;justify-content:center;margin-bottom:18px;">
            <i class="bi bi-check-lg" style="color:#10b981;font-size:34px;"></i>
        </div>
        <h4 class="fw-bold">Bukti Pembayaran Terkirim!</h4>
        <p class="text-muted">Nomor pesanan Anda:</p>
        <div class="fw-bold text-maroon mb-3">{{ $order->order_number }}</div>

        <div class="alert alert-warning border-0 small" style="background:#fff6e6;">
            Pembayaran Anda sedang diverifikasi oleh tim kami. Pesanan akan diproses setelah konfirmasi.
        </div>

        <div class="d-grid gap-2 mt-3">
            <a href="{{ route('pesanan.show', $order->id) }}" class="btn" style="background:#800000;color:#fff;">Lihat Status Pesanan</a>
            <a href="{{ route('katalog') }}" class="btn btn-outline-secondary">Lanjut Belanja</a>
        </div>
    </div>
</div>
@endsection
