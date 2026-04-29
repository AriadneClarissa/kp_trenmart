@extends('layouts.app')

@section('content')
<style>
    /* REVISI: 
       Menambahkan width: 100% agar elemen benar-benar melebar hingga 700px.
       Menggunakan !important untuk memastikan tidak tertimpa class bawaan.
    */
    .auth-card {
        width: 100% !important; 
        max-width: 700px !important; 
        margin: 50px auto; 
        border: none;
        border-radius: 25px; 
        background-color: #fcf8f8; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .form-label { font-weight: 600; color: #444; font-size: 0.9rem; }
    
    .form-control-custom { 
        border-radius: 12px; 
        border: 1.5px solid #eee; 
        padding: 12px 15px; 
        background-color: white;
        transition: 0.3s;
        width: 100%;
        display: block;
    }
    
    .form-control-custom:focus {
        border-color: #800000;
        box-shadow: 0 0 0 0.2rem rgba(128, 0, 0, 0.1);
        outline: none;
    }
    
    .btn-masuk-trenmart { 
        background-color: #800000 !important; 
        color: white !important; 
        border-radius: 12px; 
        padding: 14px; 
        width: 100%; 
        font-weight: bold; 
        border: none; 
        margin-top: 10px;
        margin-bottom: 20px;
        display: block; 
        transition: all 0.2s ease;
        letter-spacing: 1px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }
    
    .btn-masuk-trenmart:active { 
        background-color: #b52b2b !important; 
        transform: scale(0.98); 
    }

    .btn-masuk-trenmart:hover {
        background-color: #950000 !important; 
        color: white !important;
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
        z-index: 10;
    }

    .remember-row, .auth-links { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; }
    .forgot-link { font-size: 0.88rem; color: #800000; text-decoration: none; font-weight: 600; }
    .forgot-link:hover { text-decoration: underline; }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    {{-- Container card utama --}}
    <div class="auth-card p-5"> 
        <div class="text-center mb-4">
            <h4 class="fw-bold" style="color: #800000; font-size: 1.5rem;">Login Internal Admin</h4>
            <p class="text-muted small">PT Tren Abadi Stationeri</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger p-2 small text-center mb-3">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control-custom" placeholder="admin@trenmart.com" value="{{ old('email') }}" required>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control-custom" placeholder="Masukkan kata sandi" required>
                    <i class="bi bi-eye password-toggle" onclick="togglePassword()"></i>
                </div>
            </div>

            <div class="remember-row mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                </div>
                <a href="{{ Route::has('password.request') ? route('password.request') : url('/forgot-password') }}" class="forgot-link">Lupa password?</a>
            </div>

            <button type="submit" class="btn-masuk-trenmart">MASUK KE DASHBOARD</button>
        </form>
        
        <div class="text-center mt-2">
            <a href="{{ route('beranda') }}" class="text-decoration-none small text-secondary">← Kembali ke Toko</a>
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