<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Masuk Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon-trenmart: #660000; }
        body { background-color: #fff; font-family: 'Segoe UI', sans-serif; }
        .navbar-brand img { height: 40px; }
        .banner-container { border-radius: 15px; overflow: hidden; margin-top: 20px; border: 3px solid #00a2e8; }
        .auth-card {
            max-width: 450px; margin: 50px auto; border: none;
            border-radius: 20px; background-color: #f8f1f1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .card-header-custom { border-bottom: 1px solid #ddd; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .form-label { font-weight: 600; color: #333; }
        .form-control { border-radius: 10px; border: 1px solid #ccc; padding: 10px 15px; }
        .password-wrapper { position: relative; }
        .password-toggle { position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--maroon-trenmart); cursor: pointer; }
        .btn-masuk { background-color: var(--maroon-trenmart); color: white; border-radius: 10px; padding: 12px; width: 60%; font-weight: bold; border: none; margin: 20px auto; display: block; transition: 0.3s; }
        .btn-masuk:hover { background-color: #440000; transform: translateY(-2px); }
        .register-link { text-align: center; font-size: 0.9rem; padding-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-expand-lg bg-white py-3">
        <div class="container-fluid">
            <a href="/"><img src="{{ asset('images/logotrenmart.png') }}" height="40"></a>
            <div class="mx-auto d-none d-lg-flex fw-bold">
                <a href="/" class="nav-link px-3 text-danger">Beranda</a>
                <a href="#" class="nav-link px-3">Katalog</a>
                <a href="#" class="nav-link px-3">Pesanan</a>
                <a href="#" class="nav-link px-3">Tentang Kami</a>
            </div>
        </div>
    </nav>

    <div class="banner-container mb-5">
        <img src="{{ asset('images/spanduktoko.png') }}" class="w-100" style="height: 250px; object-fit: cover;">
    </div>

    <div class="card auth-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold text-maroon-trenmart">Masuk Akun</h5>
            <a href="/" class="text-dark fs-4 text-decoration-none">&times;</a>
        </div>
        <div class="card-body px-4 pt-4">

            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-3 small">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kata Sandi</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan kata sandi" required>
                        <i class="bi bi-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-masuk shadow-sm">Masuk</button>
            </form>

            <div class="register-link">
                <span class="text-muted">Belum punya akun?</span> 
                <a href="{{ route('register') }}" class="text-danger fw-bold text-decoration-none">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const passInput = document.getElementById('password');
        const icon = document.querySelector('.password-toggle');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passInput.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }
</script>
</body>
</html>