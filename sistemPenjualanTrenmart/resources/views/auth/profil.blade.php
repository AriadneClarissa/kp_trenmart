@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center gap-2">
                    <h3 class="fw-bold mb-0">Profil</h3>
                    <span class="badge rounded-pill bg-secondary text-uppercase py-2 px-3" style="font-size: 0.7rem;">
                        Internal Admin
                    </span>
                </div>

                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
                    <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
            @endif

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control profile-input" value="{{ old('name', $user->name) }}" disabled required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">Email</label>
                                {{-- Email tetap readonly/disabled permanen karena login via Google --}}
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                            </div>

                            @if(!$user->isAdmin())
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nomor WhatsApp</label>
                                    <input type="text" name="phone_number" class="form-control profile-input" value="{{ old('phone_number', $user->phone_number) }}" disabled required>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label small fw-bold">Alamat Rumah</label>
                                    <textarea name="home_address" class="form-control profile-input" rows="2" disabled required>{{ old('home_address', $user->home_address) }}</textarea>
                                </div>
                            @endif

                            @if($user->customer_type === 'langganan' && !$user->isAdmin())
                                <div class="col-12 mt-2"><hr class="opacity-25"></div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Nama Perusahaan/Toko</label>
                                    <input type="text" name="organization_name" class="form-control profile-input" value="{{ old('organization_name', $user->organization_name) }}" disabled required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Jenis Bidang Usaha</label>
                                    <input type="text" name="organization_type" class="form-control profile-input" value="{{ old('organization_type', $user->organization_type) }}" disabled required>
                                </div>
                            @endif

                            @if($user->isAdmin())
                                <div class="col-12 mt-2">
                                    <div class="alert alert-info border-0 small">
                                        Anda login sebagai <strong>Administrator</strong>. Anda memiliki akses penuh ke manajemen produk dan persetujuan pelanggan.
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 border-top pt-4 text-end" id="buttonGroup">
                            {{-- Tombol Edit --}}
                            <button type="button" class="btn btn-warning px-4 fw-bold text-white" style="background-color: #800000;" id="editBtn" onclick="enableEditing()">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profil
                            </button>

                            {{-- Grup Tombol Simpan & Batal (Hidden by default) --}}
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
        </div>
    </div>
</div>

<script>
    // Ambil semua input yang memiliki class profile-input
    const inputs = document.querySelectorAll('.profile-input');
    const editBtn = document.getElementById('editBtn');
    const saveGroup = document.getElementById('saveGroup');
    
    // Variabel untuk menyimpan data asli jika user menekan 'Batal'
    let originalData = {};

    function enableEditing() {
        // Simpan data saat ini dan aktifkan input
        inputs.forEach(input => {
            originalData[input.name] = input.value;
            input.disabled = false;
        });

        // Ganti tombol Edit menjadi Simpan/Batal
        editBtn.style.display = 'none';
        saveGroup.style.display = 'inline-block';
        
        // Fokuskan ke input pertama agar user bisa langsung mengetik
        inputs[0].focus();
    }

    function disableEditing() {
        // Kembalikan nilai input ke data asli dan matikan input
        inputs.forEach(input => {
            input.value = originalData[input.name] || input.value;
            input.disabled = true;
        });

        // Kembalikan tombol ke semula
        editBtn.style.display = 'inline-block';
        saveGroup.style.display = 'none';
    }
</script>
@endsection