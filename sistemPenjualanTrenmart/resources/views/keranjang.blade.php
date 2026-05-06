@extends('layouts.app')

@push('styles')
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
    }
    /* Background & Font */
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* Rapatkan jarak ke Navbar */
    .main-container { padding-top: 15px !important; }

    /* Layout Wrapper: Menjaga Kiri dan Kanan Sejajar Sempurna */
    .cart-wrapper { 
        display: flex; 
        align-items: flex-start !important; 
    }

    /* Cards */
    .card-custom { border-radius: 15px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.05); background: white; margin-bottom: 20px; }
    .product-img { width: 85px; height: 85px; object-fit: cover; border-radius: 12px; background: #f1f1f1; }
    
    /* Qty Control */
    .qty-container { border: 1px solid #eee; border-radius: 10px; padding: 2px; background: #fff; display: inline-flex; }
    .qty-input { width: 40px; text-align: center; border: none; font-weight: 700; background: transparent; outline: none; }
    .btn-qty { border: none; background: transparent; width: 30px; height: 30px; border-radius: 8px; font-weight: bold; transition: 0.2s; cursor: pointer; }
    .btn-qty:hover { background: #fceaea; color: var(--maroon-trenmart); }

    /* Sidebar Sticky: Menempel saat scroll tanpa getar */
    .summary-card { 
        background: white; 
        border-radius: 18px; 
        padding: 24px; 
        position: -webkit-sticky;
        position: sticky; 
        top: 20px; 
        border: none; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        will-change: transform; 
    }

    /* Button Bayar */
    .btn-checkout { 
        background: var(--accent-red); 
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
    .btn-checkout:hover { background: #c5163e; transform: translateY(-2px); }

    /* Delivery Options */
    .delivery-option { border: 1.5px solid #eee; border-radius: 12px; padding: 15px; cursor: pointer; transition: 0.2s; position: relative; margin-bottom: 10px; }
    .delivery-option.active { border-color: var(--accent-red); background: #fff5f7; }
    .delivery-option input[type="radio"] { accent-color: var(--accent-red); transform: scale(1.2); }

    /* Utility */
    .text-maroon { color: var(--maroon-trenmart); }
    .text-accent { color: var(--accent-red); }
    .btn-link-custom { text-decoration: none; font-weight: 600; font-size: 0.85rem; }
</style>
@endpush

@section('content')
<div class="container main-container pb-5">
    
    {{-- Header: Ikon Keranjang Belanja Warna Hitam Tanpa Bentuk Bulat --}}
    <div class="mb-3">
        <a href="{{ route('katalog') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Belanja
        </a>
        <div class="d-flex align-items-center mt-1">
            <i class="bi bi-cart3 text-black-custom fs-2 me-3"></i> <div>
                <h3 class="fw-bold mb-0">Keranjang Belanja</h3>
            </div>
        </div>
    </div>

    <div class="row cart-wrapper g-4">
        
        {{-- KOLOM KIRI: DAFTAR BARANG --}}
        <div class="col-lg-8">
            {{-- List Produk --}}
            <div class="card card-custom p-4">
                {{-- JUMLAH ITEM & HAPUS SEMUA: Sekarang tepat di atas list produk --}}
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                    <h6 class="fw-bold mb-0">Produk ({{ count($items) }} item)</h6>
                    @if(count($items) > 0)
                        <button class="btn btn-link text-danger btn-link-custom p-0 d-flex align-items-center">
                            <i class="bi bi-trash-fill me-1"></i> Hapus Semua
                        </button>
                    @endif
                </div>

                @forelse($items as $item)
                <div class="d-flex align-items-center py-3 border-bottom {{ $loop->last ? 'border-0' : '' }}">
                    <img src="{{ asset('storage/' . ($item->produk->gambar ?? 'default.jpg')) }}" class="product-img me-3">
                    
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-0">{{ $item->produk->nama_produk }}</h6>
                        <p class="text-muted small mb-1">{{ $item->produk->merk->nama_merk ?? 'Trenmart' }}</p>
                        <h6 class="text-accent fw-bold mb-0">Rp {{ number_format($item->harga_at_time, 0, ',', '.') }}</h6>
                    </div>

                    <div class="text-end">
                        <div class="qty-container mb-2">
                            <button class="btn-qty" type="button">-</button>
                            <input type="text" class="qty-input" value="{{ $item->jumlah }}" readonly>
                            <button class="btn-qty" type="button">+</button>
                        </div>
                        <div class="fw-bold d-block">Rp {{ number_format($item->harga_at_time * $item->jumlah, 0, ',', '.') }}</div>
                        <button class="btn btn-link p-0 text-muted mt-1 small shadow-none"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 text-muted">Keranjang Anda kosong</div>
                @endforelse
            </div>

            {{-- Metode Pengambilan --}}
            @if(count($items) > 0)
            <div class="card card-custom p-4">
                <h6 class="fw-bold mb-3">Metode Pengambilan</h6>
                
                <div class="delivery-option active d-flex align-items-center justify-content-between" id="opt-delivery">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-truck fs-4 me-3 text-accent"></i>
                        <div>
                            <div class="fw-bold">Delivery</div>
                        </div>
                    </div>
                    <input type="radio" name="pickup_method" value="delivery" checked>
                </div>

                <div class="delivery-option d-flex align-items-center justify-content-between" id="opt-pickup">
                    <div class="d-flex align-items-center text-muted">
                        <i class="bi bi-shop fs-4 me-3"></i>
                        <div>
                            <div class="fw-bold">Ambil di Toko</div>
                            <div class="small">Jl. Jenderal Ahmad Yani, Tangga Takat</div>
                        </div>
                    </div>
                    <input type="radio" name="pickup_method" value="pickup">
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="small fw-bold text-muted"><i class="bi bi-geo-alt-fill text-danger"></i> Alamat Pengiriman</label>
                        <button type="button" class="btn btn-link btn-sm btn-link-custom p-0 text-maroon shadow-none" data-bs-toggle="modal" data-bs-target="#modalAlamat">Ubah Alamat</button>
                    </div>
                    <textarea class="form-control bg-light border-0 small" id="displayAlamat" rows="2" readonly style="border-radius:10px; resize:none;">{{ auth()->user()->alamat ?? 'Jl. Sudirman No. 45, Jakarta Selatan' }}</textarea>
                </div>
            </div>
            @endif
        </div>

        {{-- KOLOM KANAN: RINGKASAN PESANAN (STICKY) --}}
        <div class="col-lg-4">
            <div class="summary-card shadow-sm">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                
                <div id="items-list">
                    @foreach($items as $item)
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span class="text-truncate" style="max-width: 160px;">{{ $item->produk->nama_produk }} ×{{ $item->jumlah }}</span>
                        <span>Rp {{ number_format($item->harga_at_time * $item->jumlah, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                
                <hr class="my-4 opacity-25">
                
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Subtotal</span>
                    <span class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-4 text-muted small">
                    <span>Ongkos Kirim</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0" id="total-label">Rp {{ number_format($total + 15000, 0, ',', '.') }}</h4>
                </div>

                @if(count($items) > 0)
                <a href="{{ route('checkout.index') }}" class="btn-checkout shadow-sm">
                    Lanjut ke Pembayaran <i class="bi bi-chevron-right ms-2"></i>
                </a>
                @else
                <button class="btn btn-secondary w-100 py-3 fw-bold border-0 opacity-50" disabled style="border-radius:12px;">Keranjang Kosong</button>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- MODAL UBAH ALAMAT --}}
<div class="modal fade" id="modalAlamat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Ubah Alamat Pengiriman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-4">
                <textarea class="form-control mb-3 border-light" id="newAlamat" rows="4" style="border-radius:12px; background: #fcfcfc;">{{ auth()->user()->alamat }}</textarea>
                <button type="button" class="btn btn-danger w-100 py-3 fw-bold" id="saveAlamatBtn" style="background: var(--maroon-trenmart); border:none; border-radius:12px;">Simpan Alamat Baru</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Alamat
        const btnSave = document.getElementById('saveAlamatBtn');
        if(btnSave) {
            btnSave.addEventListener('click', function() {
                const val = document.getElementById('newAlamat').value;
                document.getElementById('displayAlamat').value = val;
                bootstrap.Modal.getInstance(document.getElementById('modalAlamat')).hide();
            });
        }

        // Switch Delivery/Pickup & Update Harga
        const opts = document.querySelectorAll('.delivery-option');
        const ongkirLbl = document.getElementById('ongkir-label');
        const totalLbl = document.getElementById('total-label');
        const subtotal = {{ $total }};

        opts.forEach(opt => {
            opt.addEventListener('click', function() {
                opts.forEach(el => el.classList.remove('active'));
                this.classList.add('active');
                const radio = this.querySelector('input');
                radio.checked = true;

                if(radio.value === 'pickup') {
                    ongkirLbl.innerText = "Rp 0";
                    totalLbl.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(subtotal);
                } else {
                    ongkirLbl.innerText = "Rp 15.000";
                    totalLbl.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(subtotal + 15000);
                }
            });
        });
    });
</script>
@endpush