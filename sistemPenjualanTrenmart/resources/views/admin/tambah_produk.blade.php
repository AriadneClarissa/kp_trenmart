@extends('layouts.app')

@section('content')
<div class="container mb-5 mt-4">
    <div class="card main-card p-4 border-0 shadow-sm" style="border-radius: 15px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Tambah Produk</h4>
        </div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="origin" value="{{ $source }}">

            <div class="row">
                {{-- Kolom Kiri: Upload & Kategori --}}
                <div class="col-md-4">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Foto Produk</label>
                        <div class="upload-box" id="drop-area" onclick="document.getElementById('gambar').click()" 
                             style="border: 2px dashed #ccc; min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer;">
                            <img id="img-preview" src="#" alt="Preview" class="d-none mb-2 img-fluid" style="max-height: 200px;">
                            <div id="upload-placeholder" class="text-center">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                <p class="mt-2 mb-1 small fw-bold">Klik untuk unggah gambar</p>
                                <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, atau WEBP (Maks. 2MB)</span>
                            </div>
                            <input type="file" id="gambar" name="gambar" accept="image/*" hidden onchange="previewImage(this)" required>
                        </div>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-select mb-3" name="kd_kategori" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            @foreach($kategoris as $k)
                                <option value="{{ $k->kd_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>

                        <label class="form-label mt-2">Merk</label>
                        <select class="form-select" name="kd_merk" required>
                            <option value="" selected disabled>Pilih Merk</option>
                            @foreach($merks as $m)
                                <option value="{{ $m->kd_merk }}">{{ $m->nama_merk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kolom Kanan: Detail Informasi --}}
                <div class="col-md-8">
                    <div class="section-card bg-white mb-3 p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Produk</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Penghapus Faber Castle Putih" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan keunggulan produk Anda di sini..."></textarea>
                        </div>
                    </div>

                    <div class="section-card bg-white p-3 border rounded-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tags me-2 text-success"></i>Detail Harga, Stok & Satuan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Umum)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_umum" class="form-control border-start-0" placeholder="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Langganan)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_langganan" class="form-control border-start-0" placeholder="0">
                                </div>
                                <small class="text-muted">Kosongkan jika sama dengan harga umum</small>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jumlah Stok</label>
                                <input type="number" name="stok_tersedia" class="form-control" placeholder="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Satuan</label>
                                <select name="satuan" class="form-select" id="satuan_select" onchange="toggleSatuanManual()" required>
                                    <option value="" disabled selected>Pilih...</option>
                                    <option value="Pcs">Pcs</option>
                                    <option value="Pak">Pak</option>
                                    <option value="Lusin">Lusin</option>
                                    <option value="Karton">Karton</option>
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                <input type="text" name="satuan_custom" id="satuan_manual" class="form-control mt-2" style="display:none;" placeholder="Ketik satuan...">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ $source == 'beranda' ? route('beranda') : route('produk.index') }}" class="btn btn-outline-secondary px-4 fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-simpan fw-bold shadow-sm px-4" style="background-color: #800000; color: white;">
                            <i class="bi bi-check-lg me-1"></i> Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('img-preview');
        const placeholder = document.getElementById('upload-placeholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                placeholder.classList.add('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleSatuanManual() {
        var select = document.getElementById('satuan_select');
        var manualInput = document.getElementById('satuan_manual');
        if (select.value === 'Lainnya') {
            manualInput.style.display = 'block';
            manualInput.required = true;
            manualInput.focus();
        } else {
            manualInput.style.display = 'none';
            manualInput.required = false;
        }
    }
</script>
@endpush