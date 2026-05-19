<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Data Pengiriman</h4>
                    <p class="text-muted mb-4">Lengkapi data berikut untuk memudahkan proses pengiriman pesanan Anda.</p>

                    <form action="<?php echo e(route('profile.initial.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="<?php echo e($user->name); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="text" 
                                name="phone_number" 
                                id="phone_number"
                                class="form-control" 
                                placeholder="Contoh: 081234567890" 
                                inputmode="numeric"
                                maxlength="13"
                                oninput="validateWA(this)"
                                required>
                            <!-- Tempat munculnya pesan error -->
                            <div id="phone-error" class="text-danger small mt-1" style="display: none;">
                                Nomor telepon tidak valid! Harus diawali 08.
                            </div>
                            <div class="form-text text-muted">Format: 08... (11-13 digit). Input +62 otomatis menjadi 08.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="home_address" class="form-control" rows="4" placeholder="Jl. Nama Jalan, No. Rumah, Kecamatan, Kota..." required></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger fw-bold py-2">
                                Selesai & Mulai Belanja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Validasi WA -->
<script>
function validateWA(input) {
    let val = input.value;
    const errorElement = document.getElementById('phone-error');

    // 1. Tangani +62 atau 62 di awal secara instan
    if (val.startsWith('+62')) {
        val = '08' + val.substring(3);
    } else if (val.startsWith('62')) {
        val = '08' + val.substring(2);
    }

    // 2. Hapus semua karakter yang bukan angka
    val = val.replace(/\D/g, '');

    // 3. Paksa limit 13 karakter
    val = val.substring(0, 13);

    // 4. Update nilai input sebelum validasi visual
    input.value = val;

    // 5. Validasi Aturan "Wajib 08"
    if (val.length >= 2) {
        if (!val.startsWith('08')) {
            // Jika sudah ngetik tapi bukan 08, kasih tanda merah
            errorElement.style.display = 'block';
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        } else {
            // Jika sudah benar 08
            errorElement.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    } else if (val.length === 1) {
        // Jika baru ngetik 1 angka dan bukan 0, langsung error
        if (val !== '0') {
            errorElement.style.display = 'block';
            input.classList.add('is-invalid');
        }
    } else {
        // Jika kosong, bersihkan semua tanda
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\auth\form_umum.blade.php ENDPATH**/ ?>