@extends('layouts.app')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card p-5 shadow-sm border-0" style="max-width:520px; border-radius:20px; text-align:center;">
        <div style="width:100px; height:100px; border-radius:50%; background:#e9f9ef; display:inline-flex; align-items:center; justify-content:center; margin-bottom:25px; margin-left: auto; margin-right: auto;">
            <i class="bi bi-shield-check" style="color:#10b981; font-size:45px;"></i>
        </div>
        
        <h3 class="fw-bold">Pesanan Berhasil Dikirim!</h3>
        <p class="text-muted">Bukti pembayaran Anda telah kami terima.</p>
        
        <div class="bg-light p-3 rounded-3 mb-4">
            <span class="text-muted d-block small">Nomor Pesanan:</span>
            <span class="fw-bold text-danger h5">{{ $order->order_number }}</span>
        </div>

        <div class="alert alert-warning border-0 small text-start shadow-sm" style="background:#fff9e6; border-radius: 12px;">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Mohon Tunggu:</strong> Admin <strong>Trenmart</strong> akan melakukan verifikasi pembayaran dalam 1x24 jam. Anda akan menerima notifikasi jika pesanan sudah diproses.
        </div>

        <div class="d-grid gap-2 mt-4">
            <a href="{{ route('pesanan.show', $order->id) }}" class="btn py-3 fw-bold" style="background:#800000; color:#fff; border-radius:12px;">Cek Status Pesanan</a>
            <a href="{{ route('katalog') }}" class="btn btn-link text-muted text-decoration-none small">Kembali ke Katalog</a>
        </div>
    </div>
</div>
@endsection