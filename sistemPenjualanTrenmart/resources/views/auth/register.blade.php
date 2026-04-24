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
            max-width: 500px;
            margin: 40px auto;
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .text-maroon { color: var(--maroon-trenmart); }
        .form-control, .form-select { border-radius: 10px; padding: 12px; }
        
        /* Style untuk tombol intip password agar menyatu dengan input */
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
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card auth-card">
        <div class="card-body p-5">
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
                    <label class="form-label fw-semibold">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="name@example.com" required>
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
                        <div class="syarat-text">
                            *Minimal 8 karakter (Huruf & Angka)
                        </div>
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

                <div class="mb-4">
                    <label class="form-label fw-semibold">Daftar Sebagai</label>
                    <select name="customer_type" class="form-select" required>
                        <option value="" disabled {{ old('customer_type') ? '' : 'selected' }}>Pilih tipe pelanggan</option>
                        <option value="regular" {{ old('customer_type') === 'regular' ? 'selected' : '' }}>Pelanggan Umum (Eceran)</option>
                        <option value="langganan" {{ old('customer_type') === 'langganan' ? 'selected' : '' }}>Pelanggan Grosir (Langganan)</option>
                    </select>
                    <small class="text-muted" style="font-size: 10px;">
                        *Akun Grosir memerlukan verifikasi admin sebelum dapat digunakan.
                    </small>
                </div>

                <button type="submit" class="btn btn-maroon shadow-sm">Daftar Akun</button>
            </form>

            <div class="text-center mt-4">
                <p class="small text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-maroon fw-bold text-decoration-none">Masuk</a></p>
            </div>
        </div>
    </div>
</div>

<script>
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
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>