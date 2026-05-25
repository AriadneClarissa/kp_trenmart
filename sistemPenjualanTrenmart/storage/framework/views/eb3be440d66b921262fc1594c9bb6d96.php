<?php $__env->startSection('content'); ?>
<div class="container mt-4 mb-5">
    <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
        <h4 class="fw-bold mb-4">Tambah Paket Bundling</h4>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('bundling.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="source" value="<?php echo e($source); ?>">
            <div class="row">
                
                <div class="col-md-5">
                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <label class="form-label fw-bold">Tipe Paket Bundling</label>
                        <select id="bundling_type" class="form-select mb-3 border-primary fw-bold" onchange="adjustProductRows()">
                            <option value="2" <?php echo e(old('bundling_type') == '2' ? 'selected' : ''); ?>>Bundling 2 Barang</option>
                            <option value="3" <?php echo e(old('bundling_type') == '3' ? 'selected' : ''); ?>>Bundling 3 Barang</option>
                        </select>

                        <label class="form-label fw-bold">Nama Paket</label>
                        <input type="text" name="name" class="form-control mb-3" 
                               placeholder="Contoh: Paket Alat Tulis Hemat" required 
                               value="<?php echo e(old('name')); ?>">

                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label fw-bold">Mulai Promo</label>
                                <input type="datetime-local" name="promo_start_at" class="form-control mb-3"
                                       value="<?php echo e(old('promo_start_at', now()->format('Y-m-d\TH:i'))); ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Selesai Promo</label>
                                <input type="datetime-local" name="promo_end_at" class="form-control mb-3"
                                       value="<?php echo e(old('promo_end_at')); ?>">
                            </div>
                        </div>

                        <div class="small text-muted mb-2">
                            Jika diisi, bundling hanya tampil selama periode promo ini aktif.
                        </div>
                    </div>
                </div>

                
                <div class="col-md-7">
                    <div class="p-3 border rounded-3 shadow-sm bg-white mb-3">
                        <h6 class="fw-bold mb-2"><i class="bi bi-search me-2 text-primary"></i>Cari & Tambah Produk Cepat</h6>
                        <p class="text-muted small mb-3">Cari berdasarkan nama atau merk, lalu klik produk untuk memasukkannya ke baris paket.</p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <input type="text" id="inputNamaProduk" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" placeholder="Ketik Nama Produk...">
                            </div>
                            <div class="col-md-6">
                                <input type="text" id="inputMerkProduk" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" placeholder="Ketik Merk...">
                            </div>
                        </div>

                        
                        <div class="position-relative">
                            <div id="hasilPencarian" class="list-group shadow position-absolute w-100" style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto; border-radius: 12px;"></div>
                        </div>
                    </div>

                    
                    <div class="p-3 border rounded-3 shadow-sm bg-white">
                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-box-seam me-2"></i>Daftar Produk dalam Paket</h6>
                        <div id="bundling-container">
                            
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4 opacity-25">

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="card p-3 bg-light border-0 shadow-sm">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Total Harga Normal:</span>
                            <div class="fw-bold h5 mb-0">
                                Rp <span id="display_total_normal">0</span>
                                <input type="hidden" name="total_normal_price" id="input_total_normal" value="<?php echo e(old('total_normal_price', 0)); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-success">Harga Bundling (Harga Jual Baru)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-0">Rp</span>
                                <input type="number" name="bundling_price" class="form-control border-success" 
                                       required value="<?php echo e(old('bundling_price')); ?>">
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <a href="<?php echo e($source == 'beranda' ? route('beranda') : route('produk.index')); ?>" class="btn btn-outline-secondary px-4 fw-bold py-2">Batal</a>
                            <button type="submit" class="btn btn-primary py-3 fw-bold rounded-3 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Simpan Paket Bundling
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    const produkData = <?php echo json_encode($produk, 15, 512) ?>;

    $(document).ready(function() {
        adjustProductRows(); 
    });

    function adjustProductRows() {
        const type = document.getElementById('bundling_type').value;
        const container = document.getElementById('bundling-container');
        
        const currentSelections = [];
        $('.product-select').each(function() {
            currentSelections.push($(this).val());
        });

        container.innerHTML = '';

        for (let i = 1; i <= type; i++) {
            const oldValue = currentSelections[i-1] || '';
            const rowHtml = `
                <div class="row g-2 mb-3 align-items-end item-row">
                    <div class="col-8">
                        <label class="small text-muted fw-bold">Slot Produk ${i}</label>
                        <div class="select2-wrapper" style="position: relative;">
                            <select name="product_id[]" class="form-select product-select select2" required onchange="calculatePrices(this)">
                                <option value=""></option>
                                ${produkData.map(p => {
                                    let merkText = p.merk ? p.merk.nama_merk : 'Tanpa Merk';
                                    return `<option value="${p.kd_produk}" data-price="${p.harga_jual_umum}" ${oldValue == p.kd_produk ? 'selected' : ''}>
                                        ${p.nama_produk} (${merkText})
                                    </option>`;
                                }).join('')}
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control bg-light price-display fw-bold text-dark" readonly placeholder="Rp 0">
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', rowHtml);
        }

        $('.select2').each(function() {
            $(this).select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Cari atau pilih produk...',
                allowClear: true,
                dropdownParent: $(this).parent() 
            });
        });

        $('.product-select').each(function() {
            if($(this).val()) calculatePrices(this);
        });
    }

    function calculatePrices(select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = (selectedOption && selectedOption.getAttribute('data-price')) ? parseFloat(selectedOption.getAttribute('data-price')) : 0;
        const row = select.closest('.item-row');
        row.querySelector('.price-display').value = "Rp " + price.toLocaleString('id-ID');
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        $('.product-select').each(function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && selectedOption.getAttribute('data-price')) {
                total += parseFloat(selectedOption.getAttribute('data-price'));
            }
        });
        document.getElementById('display_total_normal').innerText = total.toLocaleString('id-ID');
        document.getElementById('input_total_normal').value = total;
    }
    // --- LOGIC PENCARIAN AJAX DENGAN HARGA ---
    $('#inputNamaProduk, #inputMerkProduk').on('keyup', function() {
        let nama = $('#inputNamaProduk').val();
        let merk = $('#inputMerkProduk').val();

        if (nama.length >= 3 || merk.length >= 3) {
            $.ajax({
                url: "<?php echo e(route('bundling.search_ajax')); ?>", // Pastikan nama route ini sesuai di web.php
                method: "GET",
                data: { 
                    q: $('#inputNamaProduk').val(),
                    merk: $('#inputMerkProduk').val()
                },
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            // Mengambil nilai price langsung dari respons Controller
                            let rawPrice = item.price; 
                            
                            // Format ke Rupiah
                            let formattedPrice = new Intl.NumberFormat('id-ID', {
                                style: 'currency', 
                                currency: 'IDR', 
                                minimumFractionDigits: 0
                            }).format(rawPrice);

                            // Gunakan item.text, item.id, item.merk, dan rawPrice
                            html += `
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action item-pencarian" 
                                    data-id="${item.id}" 
                                    data-nama="${item.text}" 
                                    data-price="${rawPrice}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold d-block text-dark">${item.text}</span>
                                            <small class="text-muted">ID: ${item.id} | Merk: ${item.merk}</small>
                                            <small class="d-block text-success fw-bold">${formattedPrice}</small>
                                        </div>
                                        <i class="bi bi-plus-circle-fill text-success fs-5"></i>
                                    </div>
                                </a>`;
                        });
                        $('#hasilPencarian').html(html).show();
                    } else {
                        $('#hasilPencarian').html('<div class="list-group-item text-danger small">Produk tidak ditemukan.</div>').show();
                    }
                },
                error: function(xhr) {
                    console.log("Error AJAX: ", xhr.responseText);
                }
            });
        } else {
            $('#hasilPencarian').hide();
        }
    });

    // MASUKKAN PRODUK KE BARIS SAAT HASIL PENCARIAN DIKLIK
    $(document).on('click', '.item-pencarian', function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        let price = $(this).data('price');

        let targetSelect = null;
        $('.product-select').each(function() {
            if (!$(this).val()) {
                targetSelect = $(this);
                return false; 
            }
        });

        if (targetSelect) {
            // Pastikan opsi ada di Select2 agar bisa terpilih
            if (targetSelect.find("option[value='" + id + "']").length === 0) {
                let newOption = new Option(nama, id, true, true);
                $(newOption).attr('data-price', price);
                targetSelect.append(newOption).trigger('change');
            } else {
                targetSelect.val(id).trigger('change');
            }
            
            $('#hasilPencarian').hide();
            $('#inputNamaProduk, #inputMerkProduk').val('');
        } else {
            alert('Semua baris bundling sudah terisi!');
        }
    });

    // Klik di luar hasil pencarian untuk menutup dropdown
    $(document).on('click', function (e) {
        if (!$(e.target).closest("#hasilPencarian, #inputNamaProduk, #inputMerkProduk").length) {
            $("#hasilPencarian").hide();
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\admin\manage_bundling.blade.php ENDPATH**/ ?>