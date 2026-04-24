<div class="col">
    <div class="card h-100 border-0 shadow-sm p-3 product-card" style="border-radius: 20px; position: relative;">
        @if($item->stok_tersedia > 0)
            <div class="position-absolute" style="top: 15px; left: 15px; z-index: 10;">
                <span class="badge bg-success px-3 py-2" style="border-radius: 8px; font-size: 0.75rem;">
                    Tersedia
                </span>
            </div>
        @endif

        <div class="d-flex align-items-center justify-content-center bg-light mb-3"
             style="height: 200px; border-radius: 15px; overflow: hidden;">
            <img src="{{ asset('storage/' . $item->gambar) }}"
                 class="img-fluid"
                 alt="{{ $item->nama_produk }}"
                 style="max-height: 100%; object-fit: contain; mix-blend-mode: multiply;">
        </div>

        <div class="card-body p-0">
            <p class="text-muted mb-1" style="font-size: 0.85rem;">
                {{ $item->merk->nama_merk ?? 'Tanpa Merk' }}
            </p>

            <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">
                {{ $item->nama_produk }}
            </h5>

            <h4 class="fw-bold mb-3" style="color: #800000;">
                Rp {{ number_format($item->harga_tampil, 0, ',', '.') }}
                <small class="text-muted fw-normal" style="font-size: 0.7rem;">/{{ $item->satuan }}</small>
            </h4>

            <a href="{{ route('produk.detail', $item->kd_produk) }}" class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2 mb-2"
               style="background-color: #800000; color: white; border-radius: 12px; font-weight: 600; text-decoration: none;">
                <i class="bi bi-eye fs-5"></i> Lihat Detail
            </a>

            @auth
                @if(!auth()->user()->isAdmin())
                    <form action="{{ route('cart.add', $item->kd_produk) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn w-100 py-2 d-flex align-items-center justify-content-center gap-2"
                                style="background-color: #800000; color: white; border-radius: 12px; font-weight: 600;">
                            <i class="bi bi-plus-lg fs-5"></i> Tambah
                        </button>
                    </form>
                @endif
            @endauth
        </div>
    </div>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .product-card .btn:hover {
        background-color: #600000 !important;
        filter: brightness(1.1);
    }
</style>