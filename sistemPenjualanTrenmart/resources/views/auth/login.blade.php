<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trenmart - Masuk Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --maroon: #800000; }
        body { background-color: #fff; font-family: 'Segoe UI', sans-serif; }

        /* Navbar Styles (Sama dengan Beranda) */
        .navbar { z-index: 1050; }
        .navbar-brand img { height: 40px; }
        .nav-link { font-weight: 600; color: #333; }
        .search-bar { border-radius: 50px; background-color: #f1f1f1; border: none; padding-left: 20px; }
        .btn-search { border-radius: 50px; background-color: var(--maroon); color: white; border: none; }
        
        /* Banner Style */
        .banner-container { 
            border-radius: 20px; 
            overflow: hidden; 
            margin-top: 20px; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        /* Card Auth Style */
        .auth-card {
            max-width: 450px; 
            margin: 50px auto; 
            border: none;
            border-radius: 20px; 
            background-color: #f8f1f1;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .card-header-custom { 
            border-bottom: 1px solid #eee; 
            padding: 20px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .form-label { font-weight: 600; color: #333; }
        .form-control { border-radius: 10px; border: 1px solid #ddd; padding: 12px; }
        
        .password-wrapper { position: relative; }
        .password-toggle { 
            position: absolute; 
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: var(--maroon); 
            cursor: pointer; 
            font-size: 1.2rem;
        }

        .btn-masuk { 
            background-color: var(--maroon); 
            color: white; 
            border-radius: 12px; 
            padding: 12px; 
            width: 70%; 
            font-weight: bold; 
            border: none; 
            margin: 20px auto; 
            display: block; 
            transition: 0.3s; 
        }
        .btn-masuk:hover { background-color: #5a0000; transform: translateY(-2px); }
        .register-link { text-align: center; font-size: 0.95rem; padding-bottom: 25px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/logotrenmart.png') }}" alt="Logo">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Tentang Kami</a></li>
            </ul>

            <form class="d-flex me-3" role="search">
                <div class="input-group">
                    <input class="form-control search-bar" type="search" placeholder="Cari produk...">
                    <button class="btn btn-search px-3" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <a href="{{ route('login') }}" class="text-maroon">
                <i class="bi bi-person-circle fs-4 text-dark"></i>
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="banner-container mb-5">
        <img src="{{ asset('images/spanduktoko.png') }}" class="w-100" style="height: 250px; object-fit: cover;" alt="Banner">
    </div>

    <div class="card auth-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold text-maroon">Masuk Akun</h5>
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
                    <label class="form-label">Nama Pengguna</label>
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
                <a href="{{ route('register') }}" class="text-maroon fw-bold text-decoration-none">Daftar Sekarang</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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