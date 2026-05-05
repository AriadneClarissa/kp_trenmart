@extends('layouts.app')

@section('content')
<div class="container mb-5 mt-4">
    <div class="card main-card p-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Edit Produk</h4>
        </div>

        {{-- Menampilkan Pesan Error Validasi --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.update', $produk->kd_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- Kolom Kiri: Upload & Kategori --}}
                <div class="col-md-4">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Foto Produk</label>
                        
                        {{-- Area Klik --}}
                        <div class="upload-box mb-3" onclick="document.getElementById('multi_upload').click()"
                            style="border: 2px dashed #007bff; min-height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; background: #f0f7ff;">
                            <i class="bi bi-cloud-arrow-up fs-1 text-primary"></i>
                            <p class="mt-2 mb-1 small fw-bold text-center px-2">Klik untuk Pilih 1-3 Foto Baru</p>
                            <input type="file" id="multi_upload" name="files[]" accept="image/*" multiple="multiple" hidden onchange="handleMultiplePreview(this)">
                        </div>

                        {{-- Row Preview --}}
                        <div class="row g-2">
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_utama" src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/placeholder.png') }}" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Utama</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_2" src="{{ $produk->foto_2 ? asset('storage/' . $produk->foto_2) : asset('images/placeholder.png') }}" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 2</div>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="p-1 border rounded bg-light">
                                    <img id="preview_3" src="{{ $produk->foto_3 ? asset('storage/' . $produk->foto_3) : asset('images/placeholder.png') }}" class="img-fluid rounded" style="height: 60px; width: 100%; object-fit: cover;">
                                    <div style="font-size: 0.6rem;" class="mt-1">Foto 3</div>
                                </div>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-3 text-center" style="font-size: 0.7rem;">* Kosongkan jika tidak ingin mengubah foto.</small>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kd_kategori" required>
                                @foreach($kategoris as $k)
                                    <option value="{{ $k->kd_kategori }}" {{ old('kd_kategori', $produk->kd_kategori) == $k->kd_kategori ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <select class="form-select" name="kd_merk" required>
                                @foreach($merks as $m)
                                    <option value="{{ $m->kd_merk }}" {{ old('kd_merk', $produk->kd_merk) == $m->kd_merk ? 'selected' : '' }}>{{ $m->nama_merk }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Satuan</label>
                            <select class="form-select" name="kd_satuan" required>
                                @foreach($satuan as $sat)
                                    <option value="{{ $sat->kd_satuan }}" {{ old('kd_satuan', $produk->kd_satuan) == $sat->kd_satuan ? 'selected' : '' }}>{{ $sat->nama_satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Informasi --}}
                <div class="col-md-8">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Produk</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" required value="{{ old('nama_produk', $produk->nama_produk) }}">
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="6">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                        </div>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tags me-2 text-success"></i>Detail Harga & Stok</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Umum)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_umum" class="form-control border-start-0" required value="{{ old('harga_jual_umum', $produk->harga_jual_umum) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Langganan)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_langganan" class="form-control border-start-0" value="{{ old('harga_jual_langganan', $produk->harga_jual_langganan) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jumlah Stok</label>
                                <input type="number" name="stok_tersedia" class="form-control" required value="{{ old('stok_tersedia', $produk->stok_tersedia) }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary px-4 fw-bold">Batal</a>
                        <button type="submit" class="btn fw-bold px-4 shadow-sm" style="background-color: #800000; color: white;">
                            Update Produk
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function handleMultiplePreview(input) {
    const files = input.files;
    const previews = ['preview_utama', 'preview_2', 'preview_3'];
    
    if (files.length > 3) {
        alert("Maksimal hanya bisa memilih 3 foto.");
        input.value = ""; 
        return;
    }

    // Reset ke placeholder asli dulu jika input berubah
    const originalPaths = [
        "{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/placeholder.png') }}",
        "{{ $produk->foto_2 ? asset('storage/' . $produk->foto_2) : asset('images/placeholder.png') }}",
        "{{ $produk->foto_3 ? asset('storage/' . $produk->foto_3) : asset('images/placeholder.png') }}"
    ];

    for (let i = 0; i < previews.length; i++) {
        document.getElementById(previews[i]).src = originalPaths[i];
    }

    // Tampilkan preview baru
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const target = document.getElementById(previews[i]);
            if (target) {
                target.src = e.target.result;
            }
        }
        reader.readAsDataURL(files[i]);
    }
}
</script>
@endsection