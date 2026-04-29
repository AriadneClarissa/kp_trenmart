<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Daftar Akun Baru</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root { --maroon-trenmart: #660000; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        
        .auth-card {
            max-width: 550px;
            margin: 40px auto;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .text-maroon { color: var(--maroon-trenmart); }
        .form-control, .form-select { border-radius: 10px; padding: 12px; }
        
        .input-group-text {
            border-radius: 0 10px 10px 0;
            background-color: white;
            cursor: pointer;
            border-left: none;
        }
        .form-control.with-eye { border-right: none; }

        .btn-maroon { 
            background-color: var(--maroon-trenmart); 
            color: white; 
            border-radius: 10px; 
            padding: 12px; 
            font-weight: bold; 
            border: none;
            width: 100%;
        }
        .btn-maroon:hover { background-color: #440000; color: white; }
        
        .syarat-text { font-size: 11px; color: #dc3545; margin-top: 4px; }

        /* Style untuk garis pemisah dengan label di tengah */
        .section-divider { 
            border-top: 1px solid #e9ecef; 
            margin: 30px 0 20px 0; 
            position: relative; 
            display: flex;
            justify-content: center;
        }
        .section-label { 
            position: absolute; 
            top: -12px; 
            left: 50%;
            transform: translateX(-50%); 
            background: #ffffff; 
            padding: 0 15px; 
            font-size: 12px; 
            color: #6c757d; 
            font-weight: bold; 
            white-space: nowrap;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card auth-card">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo" style="height: 50px;">
                <h4 class="fw-bold mt-3 text-maroon">Buat Akun Baru</h4>
                <p class="text-muted">Bergabunglah dengan Trenmart</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger p-2 small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Contoh: Ariadne Clarissa" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email</label>
                    <input type="email" 
                        name="email" 
                        id="email"
                        class="form-control" 
                        placeholder="nama@email.com" 
                        oninput="validateEmail(this)"
                        required>
                    <div id="email-error" class="text-danger small mt-1" style="display: none;">
                        Format email tidak valid (contoh: user@gmail.com)
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Kata Sandi</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control with-eye" required>
                            <span class="input-group-text" onclick="togglePassword('password', 'icon-pass')">
                                <i class="bi bi-eye" id="icon-pass"></i>
                            </span>
                        </div>
                        <div class="syarat-text">*Min. 8 karakter</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Konfirmasi Sandi</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control with-eye" required>
                            <span class="input-group-text" onclick="togglePassword('password_confirmation', 'icon-confirm')">
                                <i class="bi bi-eye" id="icon-confirm"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="section-divider">
                    <span class="section-label">DETAIL KONTAK & ALAMAT</span>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Nomor WhatsApp</label>
                    <input type="text" 
                        name="phone_number" 
                        id="phone_number"
                        class="form-control" 
                        placeholder="Contoh: 081234567890" 
                        inputmode="numeric"
                        maxlength="13" 
                        oninput="validateWA(this)"
                        required>
                    <div id="phone-error" class="text-danger small mt-1" style="display: none;">
                        Nomor telepon tidak valid! Harus diawali 08.
                    </div>
                    <div class="form-text text-muted">Format: 08... (11-13 digit). Input +62 otomatis menjadi 08.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat Pengiriman</label>
                    <textarea name="home_address" class="form-control" rows="2" placeholder="Jl. Nama Jalan No. 123, Kota..." required>{{ old('home_address') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Daftar Sebagai</label>
                    <select name="customer_type" id="customer_type" class="form-select" onchange="toggleGrosirFields()" required>
                        <option value="regular" {{ old('customer_type') === 'regular' ? 'selected' : '' }}>Pelanggan Umum (Eceran)</option>
                        <option value="langganan" {{ old('customer_type') === 'langganan' ? 'selected' : '' }}>Pelanggan Grosir (Langganan)</option>
                    </select>
                </div>

                <div id="grosir_fields" style="display: {{ old('customer_type') === 'langganan' ? 'block' : 'none' }};">
                    <div class="section-divider">
                        <span class="section-label">DATA USAHA / TOKO</span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Toko / Perusahaan / Instansi</label>
                        <input type="text" name="organization_name" id="organization_name" class="form-control" value="{{ old('organization_name') }}" placeholder="Contoh: Toko Berkah Jaya">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Jenis Organisasi</label>
                        <select name="organization_type" id="organization_type" class="form-select">
                            <option value="">Pilih Jenis...</option>
                            <option value="Toko/UMKM" {{ old('organization_type') === 'Toko/UMKM' ? 'selected' : '' }}>Toko / UMKM</option>
                            <option value="Perusahaan" {{ old('organization_type') === 'Perusahaan' ? 'selected' : '' }}>Perusahaan (PT/CV)</option>
                            <option value="Instansi" {{ old('organization_type') === 'Instansi' ? 'selected' : '' }}>Instansi Pemerintah</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-maroon shadow-sm mt-2">Daftar Akun</button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-maroon fw-bold text-decoration-none">Masuk</a></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi intip password
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    }

    // Fungsi menampilkan field organisasi secara dinamis
    function toggleGrosirFields() {
        const customerType = document.getElementById('customer_type').value;
        const grosirSection = document.getElementById('grosir_fields');
        const orgNameInput = document.getElementById('organization_name');
        const orgTypeInput = document.getElementById('organization_type');

        if (customerType === 'langganan') {
            grosirSection.style.display = 'block';
            orgNameInput.required = true;
            orgTypeInput.required = true;
        } else {
            grosirSection.style.display = 'none';
            orgNameInput.required = false;
            orgTypeInput.required = false;
            // Kosongkan nilai jika tidak jadi memilih langganan
            orgNameInput.value = "";
            orgTypeInput.value = "";
        }
    }

    // Jalankan pengecekan saat halaman dimuat (untuk old input)
    window.onload = toggleGrosirFields;
</script>

@push('scripts')
<script>
function validateEmail(input) {
    const val = input.value;
    const errorElement = document.getElementById('email-error');
    
    // Regex standar untuk validasi format email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (val.length > 0) {
        if (!emailPattern.test(val)) {
            // Jika format salah
            errorElement.style.display = 'block';
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        } else {
            // Jika format sudah benar
            errorElement.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    } else {
        // Jika kosong
        errorElement.style.display = 'none';
        input.classList.remove('is-invalid', 'is-valid');
    }
}
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