@extends('layouts.app')

@push('styles')
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
        --text-accent: #800000;
    }
    /* Background & Font */
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    .main-container { padding-top: 15px !important; }

    .card-custom { border-radius: 15px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.04); background: white; margin-bottom: 20px; }

    /* UI Transfer Bank */
    .payment-option {
        border: 1.5px solid #eee;
        border-radius: 16px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
        background: #fff;
        margin-bottom: 12px;
    }

    .payment-option.active {
        border-color: var(--maroon-trenmart);
        background: #fffafa;
    }

    .bank-logo-wrapper {
        width: 45px;
        height: 45px;
        background: #f8f9fa;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: var(--maroon-trenmart);
    }

    /* Panel Nomor Rekening */
    .account-details {
        display: none;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-top: 15px;
        border: 1px solid #eee;
    }

    .payment-option.active .account-details {
        display: block;
    }

    .dashed-line {
        border-top: 1px dashed #ddd;
        margin: 12px 0;
    }

    .copy-btn {
        background: var(--maroon-trenmart);
        color: white;
        border: none;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: 0.2s;
    }

    .custom-radio-dot {
        width: 22px;
        height: 22px;
        border: 2px solid #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .payment-option.active .custom-radio-dot { border-color: var(--maroon-trenmart); }
    .payment-option.active .custom-radio-dot::after {
        content: "";
        width: 12px;
        height: 12px;
        background: var(--maroon-trenmart);
        border-radius: 50%;
    }

    /* Sticky Sidebar */
    .summary-card { 
        background: white; 
        border-radius: 18px; 
        padding: 24px; 
        position: sticky; 
        top: 20px; 
        border: none; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    .btn-checkout-custom { 
        background: var(--maroon-trenmart); 
        color: white !important; 
        border-radius: 12px; 
        padding: 16px; 
        width: 100%; 
        font-weight: 700; 
        border: none; 
        transition: 0.3s; 
        display: flex; 
        justify-content: center; 
        align-items: center;
        text-decoration: none;
    }
    .btn-checkout-custom:hover { background: #600000; transform: translateY(-2px); }
    .text-accent { color: var(--maroon-trenmart); }
</style>
@endpush

@section('content')
<div class="container main-container pb-5">
    
    <div class="mb-4">
        <a href="{{ route('cart.index') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Keranjang
        </a>
        <h3 class="fw-bold mt-2"><i class="bi bi-wallet2 me-2"></i>Pembayaran</h3>
    </div>

    <div class="row g-4">
        
        {{-- KOLOM KIRI: PILIHAN PEMBAYARAN --}}
        <div class="col-lg-8">
            <form action="{{ route('checkout.place_order') }}" method="POST" id="payment-form">
                @csrf
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold m-0">Pilih Metode Transfer Bank</h6>
                    </div>
                    
                    @foreach($paymentMethods as $method)
                    <div class="payment-option {{ $loop->first ? 'active' : '' }}" onclick="selectBank(this, 'pm{{ $method->id }}')">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bank-logo-wrapper me-3">
                                    {{ strtoupper(substr($method->name, 0, 3)) }}
                                </div>
                                <div>
                                    <div class="fw-bold small">Transfer {{ $method->name }}</div>
                                </div>
                            </div>
                            
                            <input type="radio" name="payment_method_id" id="pm{{ $method->id }}" value="{{ $method->id }}" 
                                   class="d-none" {{ $loop->first ? 'checked' : '' }}>
                            
                            <div class="custom-radio-dot"></div>
                        </div>

                        <div class="account-details">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small mb-1">Nomor Rekening:</div>
                                    <div class="fw-bold text-dark fs-5" id="num-{{ $method->id }}" style="letter-spacing: 1px;">
                                        {{ $method->account_number }}
                                    </div>
                                    <div class="small text-muted mt-1">a/n {{ $method->account_holder ?? 'Admin Trenmart' }}</div>
                                </div>
                                <button type="button" class="copy-btn" onclick="copyText('num-{{ $method->id }}', this)">
                                    <i class="bi bi-clipboard me-1"></i> Salin
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- KOLOM KANAN: RINGKASAN PESANAN --}}
        <div class="col-lg-4">
            <div class="summary-card">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                
                <div class="cart-items-preview mb-3">
                    @foreach($cartItems as $item)
                        @php
                            // Tentukan nama dan harga berdasarkan tipe (Reguler/Bundling)
                            $namaProduk = $item->bundling_id ? $item->bundling->name : ($item->produk->nama_produk ?? 'Produk');
                            $hargaSatuan = $item->bundling_id ? $item->bundling->bundling_price : ($item->harga_at_time ?? $item->produk->harga_jual_umum);
                        @endphp
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span class="text-truncate" style="max-width: 180px;">{{ $namaProduk }} ×{{ $item->jumlah }}</span>
                            <span>Rp {{ number_format($hargaSatuan * $item->jumlah, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4 small text-muted">
                    <span>Ongkos Kirim</span>
                    <span class="fw-bold text-dark">Rp 15.000</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0">Rp {{ number_format($total + 15000, 0, ',', '.') }}</h4>
                </div>

                <button type="button" onclick="submitPaymentForm()" class="btn-checkout-custom shadow-sm">
                    Konfirmasi Pesanan <i class="bi bi-chevron-right ms-2"></i>
                </button>
                
                <div class="mt-3 text-center">
                    <p class="donation-text mb-0"><i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terverifikasi</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function selectBank(element, inputId) {
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
        element.classList.add('active');
        document.getElementById(inputId).checked = true;
    }

    function submitPaymentForm() {
        const form = document.getElementById('payment-form');
        const selectedBank = document.querySelector('input[name="payment_method_id"]:checked');
        
        if (!selectedBank) {
            alert("Silakan pilih salah satu metode transfer bank!");
            return;
        }

        form.submit();
    }

    function copyText(elementId, btn) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin';
            btn.style.background = '#28a745';
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.style.background = '';
            }, 2000);
        });
    }
</script>
@endpush