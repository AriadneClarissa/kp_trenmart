<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Manage Bundling</title>
    <!-- Menggunakan Bootstrap 5 untuk layout dasar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --trenmart-red: #800000; /* Warna merah sesuai logo Trenmart */
            --trenmart-light: #f8f9fa;
        }

        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-custom {
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-label {
            font-weight: 600;
            color: #444;
        }

        .product-slot {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 15px;
        }

        .upload-placeholder {
            border: 2px dashed #ccc;
            border-radius: 8px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #888;
            cursor: pointer;
        }

        /* Warna Tombol sesuai Foto 3 */
        .btn-trenmart {
            background-color: var(--trenmart-red);
            color: white;
            border-radius: 8px;
            padding: 8px 20px;
            border: none;
        }

        .btn-trenmart:hover {
            background-color: #600000;
            color: white;
        }

        .btn-cancel {
            background-color: #fff;
            color: #666;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 8px 20px;
        }

        /* Tombol Tambah di Kanan Bawah Bundling Price */
        .btn-add-product {
            background-color: #0d6efd;
            color: white;
            border-radius: 8px;
            border: none;
            padding: 5px 15px;
            font-size: 0.9rem;
        }

        .calculation-box {
            background-color: var(--trenmart-light);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card card-custom p-4">
        <h4 class="mb-4">Buat Paket Bundling Baru</h4>

        <form id="bundlingForm">
            <!-- Nama Paket -->
            <div class="mb-4">
                <label class="form-label">Nama Paket (Contoh: Bundling Alat Tulis)</label>
                <input type="text" class="form-control" placeholder="Masukkan nama paket bundling">
            </div>

            <!-- List Produk -->
            <div id="product-container" class="row">
                <!-- Slot Produk 1 -->
                <div class="col-md-6 mb-3 product-item">
                    <div class="product-slot">
                        <small class="text-muted fw-bold d-block mb-2">Slot Produk 1</small>
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="upload-placeholder">
                                    <i class="bi bi-image"></i>
                                    <span style="font-size: 10px;">Foto</span>
                                </div>
                            </div>
                            <div class="col-8">
                                <select class="form-select form-select-sm mb-2">
                                    <option selected disabled>Pilih Produk</option>
                                    <option>Stationery Set A</option>
                                    <option>Office Supplies B</option>
                                </select>
                                <div class="row g-2">
                                    <div class="col-7"><input type="text" class="form-control form-control-sm" placeholder="Harga" readonly></div>
                                    <div class="col-5"><input type="number" class="form-control form-control-sm" placeholder="Qty" value="1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Slot Produk 2 -->
                <div class="col-md-6 mb-3 product-item">
                    <div class="product-slot">
                        <small class="text-muted fw-bold d-block mb-2">Slot Produk 2</small>
                        <div class="row align-items-center">
                            <div class="col-4">
                                <div class="upload-placeholder">
                                    <i class="bi bi-image"></i>
                                    <span style="font-size: 10px;">Foto</span>
                                </div>
                            </div>
                            <div class="col-8">
                                <select class="form-select form-select-sm mb-2">
                                    <option selected disabled>Pilih Produk</option>
                                </select>
                                <div class="row g-2">
                                    <div class="col-7"><input type="text" class="form-control form-control-sm" placeholder="Harga" readonly></div>
                                    <div class="col-5"><input type="number" class="form-control form-control-sm" placeholder="Qty" value="1"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kalkulasi dan Tombol Tambah -->
            <div class="calculation-box mt-3">
                <div class="row mb-3 align-items-center">
                    <label class="col-sm-3 fw-bold">Sub Total (Normal):</label>
                    <div class="col-sm-9">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control bg-white" value="550.000" readonly>
                        </div>
                    </div>
                </div>

                <div class="row align-items-end">
                    <div class="col-sm-9">
                        <label class="fw-bold mb-1">Bundling Price:</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-success text-white">Rp</span>
                            <input type="number" class="form-control" placeholder="Input harga promo">
                        </div>
                    </div>
                    <!-- TOMBOL TAMBAH DI KANAN BAWAH BUNDLING PRICE -->
                    <div class="col-sm-3 text-end">
                        <button type="button" id="addSlotBtn" class="btn btn-add-product">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan & Cancel di Paling Bawah -->
            <div class="mt-5 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-cancel">Batal</button>
                <button type="submit" class="btn btn-trenmart">
                    <i class="bi bi-check-lg"></i> Simpan Paket Bundling
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Script sederhana untuk menambah slot produk baru secara dinamis
    document.getElementById('addSlotBtn').addEventListener('click', function() {
        const container = document.getElementById('product-container');
        const slotCount = container.getElementsByClassName('product-item').length + 1;
        
        const newSlot = `
            <div class="col-md-6 mb-3 product-item">
                <div class="product-slot">
                    <small class="text-muted fw-bold d-block mb-2">Slot Produk ${slotCount}</small>
                    <div class="row align-items-center">
                        <div class="col-4">
                            <div class="upload-placeholder"><i class="bi bi-image"></i><span style="font-size: 10px;">Foto</span></div>
                        </div>
                        <div class="col-8">
                            <select class="form-select form-select-sm mb-2"><option selected disabled>Pilih Produk</option></select>
                            <div class="row g-2">
                                <div class="col-7"><input type="text" class="form-control form-control-sm" placeholder="Harga" readonly></div>
                                <div class="col-5"><input type="number" class="form-control form-control-sm" placeholder="Qty" value="1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newSlot);
    });
</script>

</body>
</html>