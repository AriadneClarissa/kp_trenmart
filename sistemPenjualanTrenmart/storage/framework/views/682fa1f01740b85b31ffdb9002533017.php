<?php $__env->startPush('styles'); ?>
<style>
    :root { 
        --maroon-trenmart: #800000; 
        --soft-bg: #f8f9fa;
        --accent-red: #e61e4d;
    }
    body { background-color: var(--soft-bg); font-family: 'Inter', sans-serif; overflow-x: hidden; }

    /* Rapatkan jarak ke Navbar */
    .main-container { padding-top: 15px !important; }

    /* Layout Wrapper */
    .cart-wrapper { 
        display: flex; 
        align-items: flex-start !important; 
    }

    .card-custom { border-radius: 15px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: white; margin-bottom: 20px; }

    /* Area Upload Foto */
    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 15px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: 0.3s;
        background: #fcfcfc;
        position: relative;
    }
    .upload-area:hover { border-color: var(--accent-red); background: #fffafa; }
    #preview-img { max-width: 100%; border-radius: 10px; display: none; margin-top: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

    /* Sidebar Sticky */
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

    .btn-bayar-final { 
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
    .btn-bayar-final:hover { background: #c5163e; transform: translateY(-2px); }

    .text-accent { color: var(--accent-red); }
    .text-maroon { color: var(--maroon-trenmart); }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container main-container pb-5">
    
    
    <div class="mb-4">
        
        <a href="<?php echo e(route('checkout.index')); ?>" class="text-muted text-decoration-none small">
            <i class="bi bi-chevron-left"></i> Kembali ke Pilih Bank
        </a>
        <div class="d-flex align-items-center mt-1">
            <i class="bi bi-shield-check text-dark fs-2 me-3"></i>
            <div>
                <h3 class="fw-bold mb-0">Bukti Pembayaran</h3>
                <p class="text-muted small mb-0">No. Pesanan: <strong><?php echo e($order->order_number); ?></strong></p>
            </div>
        </div>
    </div>

    <div class="row cart-wrapper g-4">
        
        
        <div class="col-lg-8">
            
            <div class="card card-custom p-4 shadow-sm">
                <h6 class="fw-bold mb-3"><i class="bi bi-bank me-2"></i>Tujuan Transfer (<?php echo e($order->paymentMethod->name); ?>)</h6>
                <div class="p-4 rounded-3 mb-2" style="background: #f8f9fa; border: 1px solid #eee;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <small class="text-muted d-block mb-1">Nomor Rekening:</small>
                            <h2 class="fw-bold text-maroon mb-1" id="rekening-text"><?php echo e($order->paymentMethod->account_number); ?></h2>
                            <p class="small mb-0">Atas Nama: <strong><?php echo e($order->paymentMethod->account_name ?? '-'); ?></strong></p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <button class="btn btn-dark btn-sm px-3 rounded-pill fw-bold" onclick="copyText()">
                                <i class="bi bi-clipboard me-1"></i> Salin Rekening
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-3 p-2 px-3 rounded-3 small" style="background: #fff9e6; border: 1px solid #ffeeba; color: #856404;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Pastikan nominal transfer sesuai dengan total tagihan agar verifikasi lebih cepat.
                </div>
            </div>

            
            <form action="<?php echo e(route('checkout.store_proof', $order->id)); ?>" method="POST" enctype="multipart/form-data" id="final-form">
                <?php echo csrf_field(); ?>
                <div class="card card-custom p-4 shadow-sm">
                    <h6 class="fw-bold mb-3"><i class="bi bi-camera-fill me-2"></i>Unggah Bukti Foto</h6>
                    <div class="upload-area" onclick="document.getElementById('bukti_tf').click()">
                        <div id="upload-placeholder">
                            <i class="bi bi-image fs-1 text-muted"></i>
                            <p class="mt-2 fw-bold mb-0">Klik di sini untuk upload foto</p>
                            <small class="text-muted">Upload screenshot atau foto bukti transfer (JPG, PNG)</small>
                        </div>
                        <img id="preview-img" src="" alt="Preview">
                    </div>
                    
                    <input type="file" name="bukti_pembayaran" id="bukti_tf" class="d-none" accept="image/*" onchange="showPreview(this)" required>
                </div>
            </form>
        </div>

        
        <div class="col-lg-4">
            <div class="summary-card shadow-sm">
                <?php
                    $subtotal = $order->items->sum(function ($it) {
                        return ($it->price ?? 0) * ($it->quantity ?? 0);
                    });
                    $shippingCost = $order->shipping_cost ?? 0;
                ?>
                <h6 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran</h6>
                
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Subtotal Produk</span>
                    <span>Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4 text-muted small">
                    <span>Biaya Pengiriman</span>
                    <span>Rp <?php echo e(number_format($shippingCost, 0, ',', '.')); ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Total Bayar</h5>
                    <h4 class="fw-bold text-accent mb-0">Rp <?php echo e(number_format($order->total, 0, ',', '.')); ?></h4>
                </div>

                <hr class="my-4 opacity-25">

                <button type="button" id="confirm-btn" onclick="validateAndSubmit()" class="btn-bayar-final shadow-sm">
                    Konfirmasi Pembayaran <i class="bi bi-check-all ms-2"></i>
                </button>

                <div class="mt-4 text-center">
                    <p class="text-muted mb-0" style="font-size: 11px;">
                        <i class="bi bi-info-circle"></i> Pesanan Anda akan diproses oleh Admin<br>setelah bukti transfer divalidasi.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Fungsi menampilkan preview gambar yang diunggah
    function showPreview(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-img').style.display = 'block';
                document.getElementById('upload-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Fungsi salin nomor rekening ke clipboard
    function copyText() {
        const rek = document.getElementById('rekening-text').innerText;
        navigator.clipboard.writeText(rek).then(() => {
            alert("Nomor rekening <?php echo e($order->paymentMethod->name); ?> berhasil disalin!");
        });
    }

    // Fungsi validasi sebelum submit form (mencegah klik ganda)
    function validateAndSubmit() {
        const fileInput = document.getElementById('bukti_tf');
        const btn = document.getElementById('confirm-btn');

        if (!fileInput || !fileInput.files || !fileInput.files.length) {
            alert("Harap pilih foto bukti transfer terlebih dahulu!");
            return;
        }

        if (btn.disabled) return; // sudah diklik sebelumnya

        // Disable button dan tampilkan feedback singkat
        try {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerText = 'Mengunggah...';
        } catch (e) {
            // ignore
        }

        document.getElementById('final-form').submit();
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\checkout\upload_proof.blade.php ENDPATH**/ ?>