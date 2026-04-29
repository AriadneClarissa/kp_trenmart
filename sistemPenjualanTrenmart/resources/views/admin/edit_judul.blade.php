@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pengaturan Tampilan</h3>
            <p class="text-muted">Kelola judul dan isi setiap section beranda</p>
        </div>
        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
            <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
        </a>
    </div>

    <form action="{{ route('admin.judul.update') }}" method="POST" id="mainForm">
        @csrf
        @method('PUT')

        {{-- 1. EDIT JUDUL SECTION BERANDA --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">1. Judul Section Beranda</h5>

                @php
                    $judulTerbaru = old('judul_terbaru', $settings['judul_terbaru'] ?? 'Produk Terbaru');
                    $judulTerpopuler = old('judul_terpopuler', $settings['judul_terpopuler'] ?? 'Produk Terpopuler');
                @endphp

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Judul 1</label>
                        <input type="text" name="judul_terbaru" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" value="{{ $judulTerbaru }}" placeholder="Contoh: Produk Terbaru">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small">Judul 2</label>
                        <input type="text" name="judul_terpopuler" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" value="{{ $judulTerpopuler }}" placeholder="Contoh: Produk Terpopuler">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PILIH TARGET SECTION --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">2. Pilih Section yang Ingin Diisi</h5>
                <select name="target_section" id="dropdownTargetSection" class="form-select rounded-pill px-4 shadow-sm border-0 bg-light">
                    @php $targetOld = old('target_section', $selectedSection ?? 'section_3'); @endphp
                    <option value="section_3" {{ $targetOld == 'section_3' ? 'selected' : '' }}>Section 3 (Custom)</option>
                    <option value="terpopuler" {{ $targetOld == 'terpopuler' ? 'selected' : '' }}>Section Terpopuler</option>
                    <option value="terbaru" {{ $targetOld == 'terbaru' ? 'selected' : '' }}>Section Terbaru</option>
                </select>
            </div>
        </div>

        {{-- 3. SEARCH PRODUK (2 KOLOM) --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2">3. Pilih Produk yang Akan Dimasukkan</h5>
                <p class="text-muted small mb-3">Cari produk berdasarkan nama atau merk (min. 3 karakter).</p>
                
                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <input type="text" id="inputNamaProduk" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" placeholder="Cari Nama Produk...">
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="inputMerkProduk" class="form-control rounded-pill px-4 shadow-sm border-0 bg-light" placeholder="Cari Merk...">
                    </div>
                </div>

                {{-- Dropdown Hasil Pencarian --}}
                <div class="position-relative">
                    <div id="hasilPencarian" class="list-group shadow position-absolute w-100" style="z-index: 1050; display: none; max-height: 250px; overflow-y: auto;">
                    </div>
                </div>

                {{-- Badge Produk Terpilih --}}
                <div class="mt-4">
                    <label class="form-label fw-semibold text-muted small">Produk Terpilih:</label>
                    <div id="listProdukTerpilih" class="d-flex flex-wrap gap-2">
                        @php
                            $produkTerpilih = old('produk_pilihan')
                                ? $produk->whereIn('kd_produk', old('produk_pilihan'))
                                : ($sectionProdukMap[$targetOld] ?? collect());
                        @endphp

                        @foreach($produkTerpilih as $item)
                            <div class="badge bg-primary rounded-pill px-3 py-2 d-flex align-items-center gap-2 item-tag" data-id="{{ $item->kd_produk }}">
                                <span>{{ $item->nama_produk }}</span>
                                <input type="hidden" name="produk_pilihan[]" value="{{ $item->kd_produk }}">
                                <i class="bi bi-x-circle-fill text-white btn-hapus-produk" style="cursor:pointer"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div class="text-end mb-5">
            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-lg">
                <i class="bi bi-save2 me-2"></i>Simpan Perubahan Tampilan
            </button>
        </div>
    </form>
</div>

<style>
    .item-pencarian:hover { background-color: #f8f9fa; cursor: pointer; }
    #hasilPencarian { border-radius: 15px; border: 1px solid #dee2e6; overflow: hidden; }
    .item-tag { transition: transform 0.2s; }
    .item-tag:hover { transform: translateY(-2px); }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const targetOld = "{{ old('target_section', $selectedSection ?? 'section_3') }}";
    const sectionProducts = @json(collect($sectionProdukMap)->map(fn ($items) => $items->map(fn ($item) => [
        'id' => $item->kd_produk,
        'name' => $item->nama_produk,
    ])->values())->toArray());
    const initialSelectedProducts = @json($produkTerpilih->map(fn ($item) => [
        'id' => $item->kd_produk,
        'name' => $item->nama_produk,
    ])->values());

    function renderSelectedProducts(items) {
        const list = $('#listProdukTerpilih');

        list.empty();

        items.forEach(function(item) {
            list.append(`
                <div class="badge bg-primary rounded-pill px-3 py-2 d-flex align-items-center gap-2 item-tag" data-id="${item.id}">
                    <span>${item.name}</span>
                    <input type="hidden" name="produk_pilihan[]" value="${item.id}">
                    <i class="bi bi-x-circle-fill text-white btn-hapus-produk" style="cursor:pointer"></i>
                </div>
            `);
        });
    }

    // 2. Logika Live Search (Min 3 Karakter)
    $('#inputNamaProduk, #inputMerkProduk').on('keyup', function() {
        let nama = $('#inputNamaProduk').val();
        let merk = $('#inputMerkProduk').val();

        if (nama.length >= 3 || merk.length >= 3) {
            $.ajax({
                url: "{{ route('admin.produk.search_ajax') }}",
                method: "GET",
                data: { q: nama, merk: merk },
                beforeSend: function() {
                    $('#hasilPencarian').html('<div class="list-group-item small text-muted">Mencari...</div>').show();
                },
                success: function(data) {
                    let html = '';
                    if (data.length > 0) {
                        data.forEach(function(item) {
                            html += `
                                <a href="javascript:void(0)" class="list-group-item list-group-item-action item-pencarian" 
                                   data-id="${item.id}" data-nama="${item.text}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-bold d-block">${item.text}</span>
                                            <small class="text-muted">ID: ${item.id}</small>
                                        </div>
                                        <i class="bi bi-plus-circle-fill text-success fs-5"></i>
                                    </div>
                                </a>`;
                        });
                        $('#hasilPencarian').html(html).show();
                    } else {
                        $('#hasilPencarian').html('<div class="list-group-item text-danger small">Produk tidak ditemukan.</div>').show();
                    }
                }
            });
        } else {
            $('#hasilPencarian').hide();
        }
    });

    // Pilih Produk
    $(document).on('click', '.item-pencarian', function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');

        if ($(`.item-tag[data-id="${id}"]`).length === 0) {
            let tag = `
                <div class="badge bg-primary rounded-pill px-3 py-2 d-flex align-items-center gap-2 item-tag" data-id="${id}">
                    <span>${nama}</span>
                    <input type="hidden" name="produk_pilihan[]" value="${id}">
                    <i class="bi bi-x-circle-fill text-white btn-hapus-produk" style="cursor:pointer"></i>
                </div>`;
            $('#listProdukTerpilih').append(tag);
        }
        $('#hasilPencarian').hide();
        $('#inputNamaProduk').val('');
        $('#inputMerkProduk').val('');
    });

    // Hapus Produk
    $(document).on('click', '.btn-hapus-produk', function() {
        $(this).parent().remove();
    });

    $('#dropdownTargetSection').on('change', function() {
        renderSelectedProducts(sectionProducts[$(this).val()] || []);
    });

    // Tutup pencarian jika klik di luar
    $(document).click(function(e) {
        if (!$(e.target).closest('#inputNamaProduk, #inputMerkProduk, #hasilPencarian').length) {
            $('#hasilPencarian').hide();
        }
    });

    // Validasi Submit (Mencegah double click & cek data)
    $('#mainForm').on('submit', function() {
        let btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
    });

    // Inisialisasi awal dropdown
    if (targetOld) {
        $('#dropdownTargetSection').val(targetOld);
    }

    renderSelectedProducts(initialSelectedProducts.length > 0 ? initialSelectedProducts : (sectionProducts[$('#dropdownTargetSection').val()] || []));
});
</script>
@endsection