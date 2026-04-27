@extends('layouts.app')

@section('content')
<style>
    /* CSS untuk menghilangkan spinner panah di beberapa browser */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Lengkapi Data Pelanggan</h4>
                    <p class="text-muted mb-4">Mohon lengkapi data toko/instansi Anda untuk diverifikasi oleh Admin.</p>

                    <form action="{{ route('profile.initial.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_type" value="langganan">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp</label>
                            <input type="text" 
                                name="phone_number" 
                                id="phone_number"
                                class="form-control" 
                                placeholder="Contoh: 081234567890" 
                                inputmode="numeric"
                                maxlength="13" 
                                oninput="formatPhoneNumber(this)"
                                required>
                            <div class="form-text text-muted">Format: 08... (11-13 digit). Jika input +62 otomatis menjadi 08.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Toko / Perusahaan / Instansi</label>
                            <input type="text" name="organization_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Organisasi</label>
                            <select name="organization_type" class="form-select" required>
                                <option value="">Pilih Jenis...</option>
                                <option value="Toko/UMKM">Toko / UMKM</option>
                                <option value="Perusahaan">Perusahaan (PT/CV)</option>
                                <option value="Instansi">Instansi Pemerintah</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="home_address" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn text-white fw-bold" style="background-color: #f05a28;">
                                Kirim untuk Persetujuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Fungsi untuk memformat input nomor telepon secara real-time
 */
function formatPhoneNumber(input) {
    let value = input.value;

    // 1. Izinkan karakter '+' hanya di awal untuk sementara proses replace
    value = value.replace(/[^+0-9]/g, '');

    // 2. Cek awalan internasional
    if (value.startsWith('+62')) {
        // Ganti +62 dengan 0
        value = '0' + value.slice(3);
    } else if (value.startsWith('62')) {
        // Ganti 62 dengan 0
        value = '0' + value.slice(2);
    }

    // 3. Hapus karakter non-angka yang tersisa (termasuk '+' jika ada di tengah)
    input.value = value.replace(/[^0-9]/g, '');
}
</script>
@endsection