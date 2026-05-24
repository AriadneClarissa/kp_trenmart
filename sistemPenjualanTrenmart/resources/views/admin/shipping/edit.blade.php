@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengaturan Ongkir</h3>
    <form action="{{ route('admin.shipping.update') }}" method="POST" id="shippingForm">
    @csrf
    <div class="mb-3">
    <label class="form-label">Gratis Ongkir (Jarak KM Pertama)</label>
    <input type="number" step="0.1" name="free_limit" id="inputLimit"
           value="{{ old('free_limit', $settings->free_limit ?? 1.0) }}" 
           class="form-control shipping-input" readonly required>
    </div>

    <div class="mb-3">
        <label class="form-label">Harga per KM Berikutnya (Rp)</label>
        <input type="number" name="price_per_km" id="inputPrice"
            value="{{ old('price_per_km', $settings->price_per_km ?? 2000) }}" 
            class="form-control shipping-input" readonly required>
    </div>
    <button type="button" id="editBtn" class="btn btn-danger" onclick="enableEdit()">Edit</button>
    <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan Perubahan</button>
</form>

<script>
function enableEdit() {
    // Aktifkan input
    document.getElementById('inputLimit').removeAttribute('readonly');
    document.getElementById('inputPrice').removeAttribute('readonly');
    
    // Ganti tombol
    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('saveBtn').style.display = 'inline-block';
}

// Opsional: Jika ingin tombol kembali ke "Edit" setelah selesai mengetik (hanya jika diperlukan)
document.querySelectorAll('.shipping-input').forEach(input => {
    input.addEventListener('input', () => {
        // Logika tambahan jika perlu
    });
});
</script>
</div>
@endsection