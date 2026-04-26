@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-2">Profil Saya</h4>
                    <p class="text-muted mb-4">Lengkapi data profil Anda</p>
                    @php
                        $startInEditMode = $errors->any();
                    @endphp

                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" id="profileForm" data-edit-mode="{{ $startInEditMode ? '1' : '0' }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama</label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control profile-field @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                required
                                @unless($startInEditMode) readonly @endunless
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
                                class="form-control profile-field @error('phone_number') is-invalid @enderror"
                                value="{{ old('phone_number', $user->phone_number) }}"
                                placeholder="Contoh: 081234567890"
                                @unless($startInEditMode) readonly @endunless
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
                                class="form-control profile-field @error('home_address') is-invalid @enderror"
                                placeholder="Masukkan alamat rumah Anda"
                                @unless($startInEditMode) readonly @endunless
                            >{{ old('home_address', $user->home_address) }}</textarea>
                            @error('home_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" id="btnEditProfile" class="btn btn-outline-secondary px-4 fw-semibold {{ $startInEditMode ? 'd-none' : '' }}">
                                Edit Profil
                            </button>
                            <button type="button" id="btnCancelEdit" class="btn btn-light border px-4 fw-semibold {{ $startInEditMode ? '' : 'd-none' }}">
                                Batal
                            </button>
                            <button type="submit" id="btnSaveProfile" class="btn px-4 fw-semibold {{ $startInEditMode ? '' : 'd-none' }}" style="background-color: #800000; color: #fff;" {{ $startInEditMode ? '' : 'disabled' }}>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('profileForm');
    if (!form) return;

    var fields = form.querySelectorAll('.profile-field');
    var btnEdit = document.getElementById('btnEditProfile');
    var btnCancel = document.getElementById('btnCancelEdit');
    var btnSave = document.getElementById('btnSaveProfile');

    function setEditMode(isEdit) {
        fields.forEach(function(field) {
            field.readOnly = !isEdit;
        });

        if (btnEdit) btnEdit.classList.toggle('d-none', isEdit);
        if (btnCancel) btnCancel.classList.toggle('d-none', !isEdit);
        if (btnSave) {
            btnSave.classList.toggle('d-none', !isEdit);
            btnSave.disabled = !isEdit;
        }
    }

    if (btnEdit) {
        btnEdit.addEventListener('click', function() {
            setEditMode(true);
            var firstField = document.getElementById('name');
            if (firstField) firstField.focus();
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            window.location.reload();
        });
    }

    setEditMode(form.dataset.editMode === '1');
});
</script>
@endpush
