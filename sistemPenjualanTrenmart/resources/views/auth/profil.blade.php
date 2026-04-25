@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-2">Profil Saya</h4>
                    <p class="text-muted mb-4">Lengkapi data profil agar proses pemesanan lebih mudah.</p>

                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label fw-semibold">Nomor Telepon</label>
                            <input
                                type="text"
                                id="phone_number"
                                name="phone_number"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                value="{{ old('phone_number', $user->phone_number) }}"
                                placeholder="Contoh: 081234567890"
                            >
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="home_address" class="form-label fw-semibold">Alamat Rumah</label>
                            <textarea
                                id="home_address"
                                name="home_address"
                                rows="4"
                                class="form-control @error('home_address') is-invalid @enderror"
                                placeholder="Masukkan alamat rumah Anda"
                            >{{ old('home_address', $user->home_address) }}</textarea>
                            @error('home_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn px-4 fw-semibold" style="background-color: #800000; color: #fff;">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
