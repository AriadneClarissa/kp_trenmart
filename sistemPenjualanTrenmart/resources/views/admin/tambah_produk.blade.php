<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Baru - Trenmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Tambah Produk Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Produk</label>
                                <input type="text" name="kd_produk" class="form-control" placeholder="Contoh: P001" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Umum (Retail)</label>
                                <input type="number" name="harga_jual_umum" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Langganan (Grosir)</label>
                                <input type="number" name="harga_jual_langganan" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Produk</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*" required>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('beranda') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Simpan Produk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>