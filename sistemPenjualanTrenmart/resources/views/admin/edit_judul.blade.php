@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header dengan tombol kembali ikon rumah --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Pengaturan Tampilan</h3>
            <p class="text-muted">Kelola judul dan isi setiap section beranda</p>
        </div>
        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm border-2">
            <i class="bi bi-house-door-fill me-2"></i>Kembali ke Beranda
        </a>
    </div>

    <form action="{{ route('admin.judul.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 1. TAMBAH JUDUL SECTION --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">1. Judul Section</h5>
                    <button type="button" id="btnAddSection" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Section
                    </button>
                </div>
                
                <div id="judulContainer">
                    <div class="mb-3 input-group-judul">
                        <label class="form-label fw-semibold text-muted small">Nama Judul (Contoh: Promo Bundling / Diskon Kilat)</label>
                        <input type="text" name="judul_custom[]" class="form-control judul-input rounded-pill px-4 shadow-sm border-0 bg-light" 
                               value="{{ $settings['judul_custom'] ?? 'Promo Bundling' }}" placeholder="Masukkan nama section...">
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. PILIH TARGET SECTION --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">2. Pilih Section yang Ingin Diisi</h5>
                <select name="target_section" id="dropdownTargetSection" class="form-select rounded-pill px-4 shadow-sm border-0 bg-light">
                    <option value="section_3">Section 3 (Custom)</option>
                    <option value="terpopuler">Section Terpopuler</option>
                    <option value="terbaru">Section Terbaru</option>
                    {{-- Opsi dinamis dari Step 1 akan masuk ke sini --}}
                </select>
            </div>
        </div>

        {{-- 3. SEARCH BAR RAMPING (Solusi Bar Putih) --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-2">3. Pilih Produk yang Akan Dimasukkan</h5>
                <p class="text-muted small mb-3">Ketik nama produk untuk mencari secara otomatis.</p>
                
                <div class="input-group w-100">
                        <input name="search" class="form-control search-bar" type="search" placeholder="Cari produk..." value="{{ request('search') }}">
                        <button class="btn btn-search" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                        {{-- ID harus produkSearch agar sesuai dengan JavaScript di bawah --}}
                        <select name="produk_pilihan[]" id="produkSearch" class="form-control border-0 bg-transparent" multiple="multiple" style="width: 100%;">
                            {{-- Produk yang sudah terpilih akan muncul di sini secara otomatis --}}
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-5 py-3 fw-bold rounded-pill shadow-lg">
                <i class="bi bi-save2 me-2"></i>Simpan Perubahan Tampilan
            </button>
        </div>
    </form>
</div>

<style>
    /* Mengatasi bar putih dan merampingkan search bar */
    .search-wrapper { 
        height: 48px; 
        overflow: hidden;
        border: 1px solid #dee2e6 !important;
    }

    .select2-container--default .select2-selection--multiple {
        border: none !important;
        background: transparent !important;
        min-height: 40px !important;
        max-height: 40px !important;
        display: flex;
        align-items: center;
        padding: 0 !important;
    }

    .select2-selection__rendered {
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 5px;
        background: transparent !important;
    }

    /* Styling Tag Produk */
    .select2-selection__choice {
        background-color: #f1f3f5 !important;
        border: none !important;
        border-radius: 20px !important;
        padding: 2px 12px !important;
        font-size: 0.8rem !important;
        margin: 0 !important;
        color: #495057 !important;
    }

    /* Hilangkan background putih pada area pencarian inline select2 */
    .select2-search--inline, 
    .select2-search__field {
        background: transparent !important;
        margin-top: 0 !important;
    }

    .select2-selection__rendered::-webkit-scrollbar { display: none; }
    .btn-remove { cursor: pointer; color: #dc3545; font-size: 0.8rem; font-weight: bold; }
</style>

{{-- Pastikan Library Select2 Terpasang --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Tambah Section Baru
        $('#btnAddSection').click(function() {
            let field = `
                <div class="mb-3 input-group-judul mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label fw-semibold text-muted small mb-0">Nama Judul Baru</label>
                        <span class="btn-remove"><i class="bi bi-trash me-1"></i>Hapus</span>
                    </div>
                    <input type="text" name="judul_custom[]" class="form-control judul-input rounded-pill px-4 shadow-sm border-0 bg-light" 
                           placeholder="Ketik judul di sini...">
                </div>`;
            $('#judulContainer').append(field);
            updateDropdown();
        });

        // 2. Hapus Section
        $(document).on('click', '.btn-remove', function() {
            $(this).closest('.input-group-judul').remove();
            updateDropdown();
        });

        // 3. Sinkronisasi Dropdown Otomatis
        $(document).on('input', '.judul-input', function() {
            updateDropdown();
        });

        function updateDropdown() {
            let dropdown = $('#dropdownTargetSection');
            let currentVal = dropdown.val();
            dropdown.find('option.dynamic').remove();

            $('.judul-input').each(function() {
                let val = $(this).val().trim();
                if (val !== "") {
                    dropdown.append(`<option value="${val}" class="dynamic">${val}</option>`);
                }
            });
            dropdown.val(currentVal);
        }

        // 4. Inisialisasi Select2 AJAX yang Benar
        const $selectProduk = $('#produkSearch').select2({
        placeholder: "Ketik nama produk (contoh: Pena)...",
        minimumInputLength: 2, // Pencarian dimulai setelah mengetik 2 karakter
        width: '100%',
        ajax: {
            url: "{{ route('admin.produk.search_ajax') }}",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // Mengirim kata kunci pencarian ke Controller
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        // Pastikan kd_produk dan nama_produk sesuai dengan kolom di database Anda
                        return { id: item.kd_produk, text: item.nama_produk }
                    })
                };
            }
        }
    });

    // Perbaikan: Agar area bar bisa diklik di mana saja
    $('#clickableSearchArea').on('click', function() {
        $selectProduk.select2('open');
    });

    updateDropdown();
});
</script>
@endsection