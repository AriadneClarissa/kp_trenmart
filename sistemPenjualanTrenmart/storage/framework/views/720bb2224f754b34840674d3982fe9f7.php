<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <style>
                .no-native-password-reveal::-ms-reveal,
                .no-native-password-reveal::-ms-clear {
                    display: none;
                }

                .no-native-password-reveal::-webkit-credentials-auto-fill-button,
                .no-native-password-reveal::-webkit-password-toggle-button {
                    display: none !important;
                    visibility: hidden;
                }
            </style>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <h3 class="fw-bold mb-0">Profil</h3>
                    <?php
                        $statusLabel = $user->roleLabel();
                        $statusClass = $user->isOwner()
                            ? 'bg-dark'
                            : ($user->isCashier() ? 'bg-warning text-dark' : ($user->isAdmin() ? 'bg-primary' : ($user->customer_type === 'langganan' ? 'bg-warning text-dark' : 'bg-secondary')));
                    ?>
                    <span class="badge rounded-pill <?php echo e($statusClass); ?> text-uppercase py-2 px-3 shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.04em;">
                        <?php echo e($statusLabel); ?>

                    </span>
                </div>

                <a href="<?php echo e(route('beranda')); ?>" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
                    <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo e(session('success')); ?></div>
            <?php endif; ?>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="<?php echo e(route('profile.update')); ?>" method="POST" id="profileForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control profile-input" value="<?php echo e(old('name', $user->name)); ?>" disabled required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Email</label>
                                <?php if($user->customer_type === 'langganan' && $user->isCustomer()): ?>
                                    <input type="email" name="email" class="form-control profile-input" value="<?php echo e(old('email', $user->email)); ?>" disabled required>
                                    <small class="text-muted d-block mt-1">Email ini bisa diganti dengan email asli Anda.</small>
                                <?php else: ?>
                                    <input type="email" class="form-control bg-light" value="<?php echo e($user->email); ?>" disabled>
                                <?php endif; ?>
                            </div>

                                <?php if($user->isCustomer()): ?>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nomor WhatsApp</label>
                                    <input type="text" name="phone_number" class="form-control profile-input" value="<?php echo e(old('phone_number', $user->phone_number)); ?>" disabled required>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label small fw-bold">Alamat Rumah</label>
                                    <textarea name="home_address" class="form-control profile-input" rows="2" disabled required><?php echo e(old('home_address', $user->home_address)); ?></textarea>
                                </div>
                            <?php endif; ?>

                            <?php if($user->customer_type === 'langganan' && $user->isCustomer()): ?>
                                <div class="col-12 mt-2"><hr class="opacity-25"></div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nama Perusahaan/Toko</label>
                                    <input type="text" name="organization_name" class="form-control profile-input" value="<?php echo e(old('organization_name', $user->organization_name)); ?>" disabled required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Jenis Bidang Usaha</label>
                                    <input type="text" name="organization_type" class="form-control profile-input" value="<?php echo e(old('organization_type', $user->organization_type)); ?>" disabled required>
                                </div>
                            <?php endif; ?>

                            <?php if($user->isInternalStaff()): ?>
                                <div class="col-12 mt-2">
                                    <div class="alert alert-info border-0 small">
                                        Anda login sebagai <strong><?php echo e($user->roleLabel()); ?></strong>. Anda memiliki akses ke area internal toko sesuai role.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 border-top pt-4 text-end" id="buttonGroup">
                            
                            <button type="button" class="btn btn-warning px-4 fw-bold text-white" style="background-color: #800000;" id="editBtn" onclick="enableEditing()">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </button>

                            
                            <div id="saveGroup" style="display: none;">
                                <button type="button" class="btn btn-outline-secondary px-4 fw-bold me-2" onclick="disableEditing()">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-success px-4 fw-bold">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body p-4">
                    <h5 class="mb-3">Ganti Kata Sandi</h5>
                    <form action="<?php echo e(route('profile.password.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kata Sandi Saat Ini</label>
                            <div class="input-group">
                                <input type="password" name="current_password" id="current_password" class="form-control no-native-password-reveal" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password', 'icon-current')">
                                    <i class="bi bi-eye" id="icon-current"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password" id="new_password" class="form-control no-native-password-reveal" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password', 'icon-new')">
                                    <i class="bi bi-eye" id="icon-new"></i>
                                </button>
                            </div>
                            <div class="form-text text-muted">
                                Syarat: minimal 8 karakter. Pastikan juga sama dengan konfirmasi kata sandi baru.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Konfirmasi Kata Sandi Baru</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control no-native-password-reveal" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation', 'icon-confirm')">
                                    <i class="bi bi-eye" id="icon-confirm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" id="btnSubmitPassword" class="btn btn-danger fw-bold px-4" style="background-color: #800000;">Ubah Kata Sandi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        var input = document.getElementById(inputId);
        var icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    var inputs = document.querySelectorAll('.profile-input');
    var editBtn = document.getElementById('editBtn');
    var saveGroup = document.getElementById('saveGroup');
    var originalData = {};

    function enableEditing() {
        inputs.forEach(function(input) {
            originalData[input.name] = input.value;
            input.disabled = false;
        });

        if(editBtn) editBtn.style.display = 'none';
        if(saveGroup) saveGroup.style.display = 'inline-block';
        
        if (inputs.length > 0) {
            inputs[0].focus();
        }
    }

    function disableEditing() {
        inputs.forEach(function(input) {
            input.value = originalData[input.name] || input.value;
            input.disabled = true;
        });

        if(editBtn) editBtn.style.display = 'inline-block';
        if(saveGroup) saveGroup.style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
        var passwordInputs = document.querySelectorAll('#current_password, #new_password, #new_password_confirmation');
        var btnSubmitPassword = document.getElementById('btnSubmitPassword');

        if (passwordInputs.length > 0 && btnSubmitPassword) {
            passwordInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    var isAnyFilled = Array.from(passwordInputs).some(inp => inp.value.trim() !== '');
                    
                    if (isAnyFilled) {
                        btnSubmitPassword.textContent = 'Simpan Perubahan';
                        btnSubmitPassword.className = 'btn btn-success fw-bold px-4'; 
                        // Menghapus warna marun saat tombol menjadi hijau
                        btnSubmitPassword.style.backgroundColor = ''; 
                    } else {
                        btnSubmitPassword.textContent = 'Ubah Kata Sandi';
                        btnSubmitPassword.className = 'btn btn-danger fw-bold px-4'; 
                        // Mengembalikan warna marun saat input kosong
                        btnSubmitPassword.style.backgroundColor = '#800000'; 
                    }
                });
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\asus\OneDrive\Documents\GitHub\kp_trenmart\sistemPenjualanTrenmart\resources\views\auth\profil.blade.php ENDPATH**/ ?>