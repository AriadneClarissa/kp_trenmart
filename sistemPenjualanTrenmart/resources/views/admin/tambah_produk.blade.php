<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Pemilik Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon: #800000; }
        
        /* Navbar Styles */
        .navbar { z-index: 1050; } /* Agar dropdown tidak tertutup banner */
        .navbar-brand img { height: 40px; }
        .navbar-nav {margin-left: 227px !important;}
        .nav-link { font-weight: 600; color: #333; }
        .search-bar { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; border: none; }

        .nav-link.active { 
            color: var(--maroon) !important; 
            border-bottom: 2px solid var(--maroon); 
        }
        
        .main-card { border-radius: 15px; border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
        .section-card { border-radius: 12px; border: 1px solid #eee; padding: 20px; margin-bottom: 20px; }
        
        .upload-box {
            border: 2px dashed #ccc; border-radius: 10px; padding: 40px 20px;
            text-align: center; color: #999; cursor: pointer; transition: 0.3s;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; min-height: 250px; overflow: hidden;
            background-color: #fff;
        }
        .upload-box:hover { border-color: var(--maroon-trenmart); background-color: #fff5f5; }
        #img-preview { max-width: 100%; max-height: 220px; object-fit: contain; border-radius: 8px; }
        
        .form-label { font-weight: 600; font-size: 0.9rem; color: #444; }
        .form-control, .form-select { border-radius: 8px; border: 1px solid #ddd; padding: 10px; }
        .form-control:focus { border-color: var(--maroon-trenmart); box-shadow: none; }
        
        .btn-batal { border-radius: 8px; padding: 10px 40px; border: 1px solid #ccc; background: white; text-decoration: none; color: black; transition: 0.2s; }
        .btn-batal:hover { background: #eee; }
        .btn-simpan { border-radius: 8px; padding: 10px 40px; background-color: var(--maroon-trenmart); color: white; border: none; transition: 0.2s; }
        .btn-simpan:hover { background-color: #4a0000; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $source == 'beranda' ? 'active' : '' }}" href="/">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $source == 'layar_produk' ? 'active' : '' }}" href="#">Produk</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <div style="width: 40px;"></div> <i class="bi bi-person-circle fs-4 text-dark"></i>
            </div>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="card main-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Tambah Produk Baru 
                <span class="badge bg-light text-dark border fw-normal ms-2" style="font-size: 0.4em; vertical-align: middle;">
                    Via {{ $source == 'beranda' ? 'Beranda' : 'Layar Produk' }}
                </span>
            </h4>
        </div>

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="origin" value="{{ $source }}">

            <div class="row">
                <div class="col-md-4">
                    <div class="section-card bg-white">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Foto Produk</label>
                        <div class="upload-box" id="drop-area" onclick="document.getElementById('gambar').click()">
                            <img id="img-preview" src="#" alt="Preview" class="d-none mb-2">
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                <p class="mt-2 mb-1 small fw-bold">Klik untuk unggah gambar</p>
                                <span class="text-muted" style="font-size: 0.75rem;">JPG, PNG, atau WEBP (Maks. 2MB)</span>
                            </div>
                            <input type="file" id="gambar" name="gambar" accept="image/*" hidden onchange="previewImage(this)" required>
                        </div>
                    </div>

                    <div class="section-card bg-white">
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

                <div class="col-md-8">
                    <div class="section-card bg-white">
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

                    <div class="section-card bg-white">
                        <h6 class="fw-bold mb-3"><i class="bi bi-tags me-2 text-success"></i>Detail Produk</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Harga Jual (Umum)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input type="number" name="harga_jual_umum" class="form-control border-start-0" placeholder="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Jumlah Stok</label>
                                <div class="input-group">
                                    <input type="number" name="stok_tersedia" class="form-control border-end-0" placeholder="0" required>
                                    <span class="input-group-text bg-light border-start-0">Pcs</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ $source == 'beranda' ? route('beranda') : route('produk.index') }}" class="btn btn-batal fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-simpan fw-bold shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Simpan Produk
                        </button>
                    </div>
                </div>
            </div>
        </form> 
    </div>
</div>

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
        } else {
            preview.classList.add('d-none');
            placeholder.classList.remove('d-none');
        }
    }
</script>

</body>
</html>