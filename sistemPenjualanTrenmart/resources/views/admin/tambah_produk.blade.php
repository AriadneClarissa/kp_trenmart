<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Pemilik Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon-trenmart: #660000; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        /* Navbar Styling */
        .navbar { border-bottom: 2px solid #dee2e6; }
        .nav-link.active { color: var(--maroon-trenmart) !important; border-bottom: 2px solid var(--maroon-trenmart); }
        .search-input { border-radius: 20px 0 0 20px; border-right: none; background-color: #f1f1f1; }
        .search-btn { border-radius: 0 20px 20px 0; background-color: var(--maroon-trenmart); color: white; border: none; }

        /* Card Container */
        .main-card { border-radius: 15px; border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
        .section-card { border-radius: 12px; border: 1px solid #eee; padding: 20px; margin-bottom: 20px; }
        
        /* Upload Box */
        .upload-box {
            border: 2px dashed #ccc; 
            border-radius: 10px; 
            padding: 40px 20px;
            text-align: center; 
            color: #999; 
            cursor: pointer; 
            transition: 0.3s;
            /* Tambahan agar gambar tetap di tengah */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 250px; /* Menjaga tinggi kotak tetap konsisten */
            overflow: hidden;
        }
        .upload-box:hover { 
            border-color: var(--maroon-trenmart); 
            background-color: #fff5f5; 
        }

        #img-preview {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain; /* Agar gambar tidak gepeng */
            border-radius: 8px;
        }

        /* Form Styling */
        .form-label { font-weight: 600; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; background-color: #fcfcfc; }
        .btn-custom-select { background-color: var(--maroon-trenmart); color: white; border-radius: 8px; width: 100%; text-align: left; position: relative; }
        .btn-custom-select::after { content: '\F282'; font-family: 'bootstrap-icons'; position: absolute; right: 15px; }

        /* Buttons */
        .btn-batal { border-radius: 8px; padding: 10px 40px; border: 1px solid #ccc; background: white; }
        .btn-simpan { border-radius: 8px; padding: 10px 40px; background-color: var(--maroon-trenmart); color: white; border: none; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold text-danger" href="#">TRENMART</a>
        <div class="collapse navbar-collapse px-5">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                <li class="nav-item"><a class="nav-link px-3" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link px-3 active" href="#">Produk</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#">Pesanan</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <div class="input-group me-3">
                    <input type="text" class="form-control search-input" placeholder="Cari...">
                    <button class="btn search-btn px-3"><i class="bi bi-search"></i></button>
                </div>
                <a href="#" class="text-dark fs-4"><i class="bi bi-person-circle"></i></a>
            </div>
        </div>
    </div>
</nav>

<div class="container mb-5">
    <div class="card main-card p-4">
        <h4 class="fw-bold mb-4">Tambah Produk Baru</h4>

        <form action="#" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <div class="section-card bg-white">
                        <label class="form-label mb-3"><i class="bi bi-image me-2"></i> Tambah Gambar</label>
                        <div class="upload-box" id="drop-area" onclick="document.getElementById('gambar').click()">
                        <img id="img-preview" src="#" alt="Preview" class="img-fluid d-none mb-3" style="max-height: 200px; border-radius: 10px;">
                            <div id="upload-placeholder">
                                <i class="bi bi-cloud-arrow-up fs-1"></i>
                                <p class="mt-2 small">Drag & drop gambar di sini <br> <span class="text-muted">atau klik untuk memilih fail</span></p>
                                <span class="badge bg-light text-muted border">JPG, PNG, WEBP - Maks. 5 MB</span>
                            </div>
                            <input type="file" id="gambar" name="gambar" accept="image/*" hidden onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="section-card bg-white">
                        <label class="form-label">Kategori</label>
                        <select class="form-select mb-3" name="kd_kategori">
                            <option selected disabled>Pilih Kategori</option>
                            <option value="1">Puplen</option>
                            <option value="2">Pensil</option>
                            <option value="2">Penghapus</option>
                            <option value="2">Buku</option>
                        </select>

                        <label class="form-label mt-2">Merk</label>
                        <select class="form-select" name="kd_merk">
                            <option selected disabled>Pilih Merk</option>
                            <option value="1">FaberCastle</option>
                            <option value="2">Joyko</option>
                            <option value="2">Snowball</option>
                            <option value="2">Greebel</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="section-card bg-white">
                        <h6 class="fw-bold mb-3">Informasi Produk</h6>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Contoh: Penghapus Faber Castle Putih">
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control" rows="6" placeholder="Tuliskan deskripsi produk secara lengkap..."></textarea>
                        </div>
                    </div>

                    <div class="section-card bg-white">
                        <h6 class="fw-bold mb-3">Detail Produk</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label text-muted">Harga Produk</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">Rp.</span>
                                    <input type="number" name="harga_jual_umum" class="form-control border-start-0" placeholder="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted">Stok Produk</label>
                                <input type="number" name="stok_tersedia" class="form-control" placeholder="Contoh: 100">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="button" class="btn btn-batal fw-bold">Batal</button>
                        <button type="submit" class="btn btn-simpan fw-bold">Simpan Produk</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        function previewImage(input) {
            const preview = document.getElementById('img-preview');
            const placeholder = document.getElementById('upload-placeholder');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none'); // Munculkan gambar
                    placeholder.classList.add('d-none'); // Sembunyikan tulisan & ikon
                }

                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.classList.add('d-none');
                placeholder.classList.remove('d-none');
            }
        }
    </script>
</body>
</html>