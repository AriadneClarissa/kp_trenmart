@extends('layouts.app')

@section('content')
<style>
    /* Card Auth Style */
    .auth-card {
        max-width: 450px; 
        margin: 50px auto; 
        border: none;
        border-radius: 25px; 
        background-color: #fcf8f8; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .card-header-custom { 
        background-color: white;
        border-bottom: 1px solid #f1f1f1; 
        padding: 25px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }

    .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
    
    .form-control-custom { 
        border-radius: 12px; 
        border: 1.5px solid #eee; 
        padding: 12px 15px; 
        background-color: white;
        transition: 0.3s;
    }
    
    .form-control-custom:focus {
        border-color: #800000;
        box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.1);
        outline: none;
    }
    
    /* Tombol Login Utama */
    .btn-masuk-trenmart { 
        background-color: #800000 !important; 
        color: white !important; 
        border-radius: 12px; 
        padding: 14px; 
        width: 100%; 
        font-weight: bold; 
        border: none; 
        margin-top: 10px;
        display: block; 
        transition: all 0.2s ease;
        letter-spacing: 1px;
        cursor: pointer;
    }
    
    .btn-masuk-trenmart:active { 
        background-color: #b52b2b !important; 
        transform: scale(0.98); 
    }

    /* Pembatas ATAU di Tengah */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        color: #888;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }

    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }

    /* Tombol Google Bar */
    .btn-google-login {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        color: #444;
        border: 1.5px solid #eee;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        margin-bottom: 20px;
    }

    .btn-google-login:hover {
        background-color: #f8f9fa;
        border-color: #800000;
        color: #800000;
    }

    .btn-google-login img {
        margin-right: 10px;
    }

    .password-wrapper { position: relative; }
    .password-toggle { 
        position: absolute; 
        right: 15px; 
        top: 50%; 
        transform: translateY(-50%); 
        color: #800000; 
        cursor: pointer; 
        font-size: 1.2rem;
    }

    .register-link { text-align: center; font-size: 0.9rem; padding-bottom: 10px; }
</style>

<div class="container mt-4">
    {{-- Banner Atas --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <img src="{{ asset('images/spanduktoko.png') }}" class="w-100 rounded-4 shadow-sm" style="height: 200px; object-fit: cover;" alt="Banner">
        </div>
    </div>

    {{-- Card Login --}}
    <div class="card auth-card">
        <div class="card-header-custom">
            <h5 class="mb-0 fw-bold" style="color: #800000;">Masuk Akun</h5>
            <a href="/" class="text-muted fs-4 text-decoration-none">&times;</a>
        </div>
        
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger border-0 small mb-4">
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
                    <label class="form-label">Email Pengguna</label>
                    <input type="email" name="email" class="form-control form-control-custom" placeholder="Masukkan email Anda" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label">Kata Sandi</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" class="form-control form-control-custom" placeholder="Masukkan kata sandi" required>
                        <i class="bi bi-eye password-toggle" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-masuk-trenmart shadow">MASUK</button>
            </form>

            {{-- PEMBATAS ATAU DI TENGAH --}}
            <div class="divider">ATAU</div>

            {{-- TOMBOL GOOGLE BAR --}}
            <a href="{{ route('auth.google') }}" class="btn-google-login">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google Logo">
                Masuk dengan Google
            </a>

            <div class="register-link">
                <span class="text-muted">Belum punya akun?</span> 
                <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: #800000;">Daftar Sekarang</a>
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
@endsection