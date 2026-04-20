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
        body { background-color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Navbar Styling */
        .navbar-brand img { height: 40px; }
        
        /* Auth Card Styling */
        .auth-card {
            max-width: 600px; /* Lebih lebar dari login agar muat 2 kolom */
            margin: 40px auto;
            border: none;
            border-radius: 20px;
            background-color: #fcf8f8; /* Warna krem sangat muda */
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .card-header-custom { 
            border-bottom: 1px solid #eee; 
            padding: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
        .form-control, .form-select { 
            border-radius: 12px; 
            border: 1px solid #ddd; 
            padding: 10px 15px; 
            background-color: #fff;
        }
        .form-control:focus {
            border-color: var(--maroon-trenmart);
            box-shadow: 0 0 0 0.25rem rgba(102, 0, 0, 0.1);
        }

        /* Button Styling */
        .btn-daftar { 
            background-color: var(--maroon-trenmart); 
            color: white; 
            border-radius: 12px; 
            padding: 12px; 
            width: 100%; 
            font-weight: bold; 
            border: none; 
            transition: 0.3s;
        }
        .btn-daftar:hover { background-color: #440000; color: #fff; transform: translateY(-2px); }
        
        .login-link { text-align: center; font-size: 0.9rem; padding: 20px 0; }
    </style>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg bg-white py-3">
        <div class="container-fluid">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Trenmart Logo">
            <div class="ms-auto">
                <a href="/" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <div class="card auth-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold">Daftar Akun Baru</h5>
            <a href="/" class="text-muted fs-4 text-decoration-none">&times;</a>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Anda" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Contoh: angelim123" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Sandi</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Jenis Pelanggan</label>
                    <select name="role" class="form-select">
                        <option value="customer">Pelanggan Umum (Eceran)</option>
                        <option value="langganan">Pelanggan Grosir (Langganan)</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-daftar">Buat Akun Sekarang</button>
                </div>
            </form>

            <div class="login-link">
                <span class="text-muted">Sudah punya akun?</span> 
                <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>