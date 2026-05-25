@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengaturan Ongkir</h3>
    <form action="{{ route('admin.shipping.update') }}" method="POST" id="shippingForm">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tarif Ongkir Flat (Rp)</label>
            <input type="number" name="flat_rate" id="flatRate"
                   value="{{ old('flat_rate', $settings->flat_rate ?? $settings->price_per_km ?? 15000) }}"
                   class="form-control" min="0" required readonly>
            <small class="text-muted d-block mt-2">Nominal ini berlaku untuk semua pesanan delivery.</small>
        </div>

        <button type="button" class="btn btn-warning me-2" id="editBtn" onclick="enableEdit()">Edit</button>
        <button type="submit" class="btn btn-success d-none" id="saveBtn">Simpan Perubahan</button>
    </form>
</div>

<script>
    function enableEdit() {
        const flatRate = document.getElementById('flatRate');
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');

        if (flatRate) {
            flatRate.removeAttribute('readonly');
            flatRate.focus();
        }

        if (editBtn) {
            editBtn.classList.add('d-none');
        }

        if (saveBtn) {
            saveBtn.classList.remove('d-none');
        }
    }
</script>
@endsection