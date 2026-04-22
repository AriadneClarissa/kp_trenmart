@extends('layouts.app')

{{-- CSS tetap sama, saya tambahkan sedikit untuk list merk --}}
@push('styles')
<style>
    :root { --maroon: #800000; --light-bg: #f8f9fa; }
    .kat-link { font-weight: 500; color: #666; transition: 0.3s; padding-bottom: 5px; text-decoration: none; }
    .kat-link:hover { color: var(--maroon); }
    .kat-active { color: var(--maroon) !important; font-weight: bold; border-bottom: 2px solid var(--maroon); }
    .sidebar-card { border-radius: 12px; background: white; margin-bottom: 20px; border: 1px solid #eaeaea; box-shadow: 0 4px 12px rgba(0,0,0,0.08); overflow: hidden; }
    .sidebar-header { font-weight: bold; font-size: 1.1rem; border-bottom: 1px solid #eee; padding: 15px 20px; color: #333; }
    .sidebar-content { padding: 20px; }
    .merk-link { font-size: 14px; color: #555; text-decoration: none; display: block; padding: 5px 0; transition: 0.2s; }
    .merk-link:hover { color: var(--maroon); padding-left: 5px; }
    .merk-active { color: var(--maroon) !important; font-weight: bold; }
    .btn-kelola { width: 100%; border-radius: 20px; font-weight: 600; font-size: 14px; padding: 10px; margin-bottom: 10px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); text-decoration: none; display: block; text-align: center; transition: 0.3s; }
    .btn-kategori { background-color: #55bdff; color: white; }
    .btn-merk { background-color: #198754; color: white; }
    .rounded-pill-start { border-top-left-radius: 50px !important; border-bottom-left-radius: 50px !important; padding-left: 20px; }
    .rounded-pill-end { border-top-right-radius: 50px !important; border-bottom-right-radius: 50px !important; }
</style>
@endpush

@section('content')
{{-- 1. Navbar Kategori --}}
<div class="bg-white border-bottom py-3 mb-4 sticky-top shadow-sm" style="top: 70px; z-index: 1010;">
    <div class="container">
        <div class="d-flex gap-4">
            <a href="{{ route('katalog') }}" class="kat-link {{ !request('kategori') ? 'kat-active' : '' }}">Semua Produk</a>
            @foreach($kategori as $kat)
                <a href="{{ route('katalog', ['kategori' => $kat->kd_kategori]) }}" class="kat-link {{ request('kategori') == $kat->kd_kategori ? 'kat-active' : '' }}">
                    {{ $kat->nama_kategori }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="row">
        {{-- 2. Sidebar (Kiri) --}}
        <div class="col-md-3">
            <div class="sidebar-card shadow-sm border-0">
                <div class="sidebar-header">Merk</div>
                <div class="sidebar-content">
                    <div class="d-flex flex-column gap-1 mb-3">
                        {{-- Memastikan loop merk mengambil data terbaru dari database --}}
                        @foreach($merk as $m)
                            <a href="{{ route('katalog', ['kategori' => request('kategori'), 'merk' => $m->kd_merk]) }}" 
                               class="merk-link {{ request('merk') == $m->kd_merk ? 'merk-active' : '' }}">
                                {{ $m->nama_merk }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="sidebar-card shadow-sm border-0">
                        <div class="sidebar-header">Manajemen Katalog</div>
                        <div class="sidebar-content">
                            <div class="d-flex flex-column gap-2">
                                <a href="#" class="btn btn-kelola btn-kategori" data-bs-toggle="modal" data-bs-target="#modalKelolaKategori">Kelola Kategori</a>
                                <a href="#" class="btn btn-kelola btn-merk" data-bs-toggle="modal" data-bs-target="#modalKelolaMerk">Kelola Merk</a>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        </div>

        {{-- 3. Grid Produk (Kanan) --}}
        <div class="col-md-9">
            <div class="row g-4">
                @forelse($produk as $p)
                    <div class="col-md-4">
                         {{-- Masukkan komponen card produk Anda di sini --}}
                    </div>
                @empty
                    <div class="col-12 text-center p-5">
                        <i class="bi bi-box-seam text-muted fs-1"></i>
                        <p class="text-muted mt-3">Produk tidak tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- MODAL KELOLA KATEGORI --}}
<div class="modal fade" id="modalKelolaKategori" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Kelola Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formTambahKategori">
                    @csrf
                    <div class="input-group mb-4 shadow-sm border rounded-pill overflow-hidden">
                        <input type="text" id="inputNamaKategori" name="nama_kategori" class="form-control border-0 px-3" placeholder="Tambah kategori baru..." required>
                        <button class="btn btn-success border-0 px-4" type="submit"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </form>
                <div class="list-group border-0 rounded-3 shadow-sm overflow-hidden" id="containerListKategori">
                    @foreach($kategori as $kat)
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-light border-bottom">
                            <span class="fw-medium {{ $kat->is_hidden ? 'text-muted text-decoration-line-through' : 'text-dark' }}">
                                {{ $kat->nama_kategori }}
                            </span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-white border shadow-sm btn-toggle-visible-kat" data-id="{{ $kat->kd_kategori }}">
                                    <i class="bi {{ $kat->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                                <button class="btn btn-sm btn-white border shadow-sm text-secondary"><i class="bi bi-pencil-square"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 pb-4">
                <button class="btn btn-danger w-100 py-2 fw-bold" onclick="window.location.reload();" style="background-color: var(--maroon);">Selesai</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL KELOLA MERK --}}
<div class="modal fade" id="modalKelolaMerk" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Kelola Merk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formTambahMerk">
                    @csrf
                    <div class="input-group mb-4 shadow-sm border rounded-pill overflow-hidden">
                        <input type="text" id="inputNamaMerk" name="nama_merk" class="form-control border-0 px-3" placeholder="Tambah merk baru..." required>
                        <button class="btn btn-success border-0 px-4" type="submit" id="btnSubmitMerk"><i class="bi bi-plus-lg"></i></button>
                    </div>
                </form>
                <div class="list-group border-0 rounded-3 shadow-sm overflow-hidden" id="containerListMerk">
                    @foreach($merk as $m)
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-light border-bottom">
                            <span class="fw-medium {{ $m->is_hidden ? 'text-muted text-decoration-line-through' : 'text-dark' }}">
                                {{ $m->nama_merk }}
                            </span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-white border shadow-sm btn-toggle-visible" data-id="{{ $m->kd_merk }}">
                                    <i class="bi {{ $m->is_hidden ? 'bi-eye-slash-fill text-danger' : 'bi-eye-fill text-primary' }}"></i>
                                </button>
                                <button class="btn btn-sm btn-white border shadow-sm text-secondary"><i class="bi bi-pencil-square"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0 pb-4">
                <button class="btn btn-danger w-100 py-2 fw-bold" onclick="window.location.reload();" style="background-color: var(--maroon);">Selesai</button>
            </div>
        </div>
    </div>
</div>

@endsection {{-- TUTUP CONTENT DI SINI --}}

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle untuk MERK
    $(document).on('click', '.btn-toggle-visible', function(e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.data('id');

        $.ajax({
            url: "{{ url('merk/toggle') }}/" + id,
            method: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                // Langsung reload agar perubahan terlihat di Sidebar & List
                window.location.reload();
            },
            error: function() {
                alert('Gagal mengubah status merk. Cek Route atau Controller.');
            }
        });
    });

    // Toggle untuk KATEGORI
    $(document).on('click', '.btn-toggle-visible-kat', function(e) {
        e.preventDefault();
        let btn = $(this);
        let id = btn.data('id');

        $.ajax({
            url: "{{ url('kategori/toggle') }}/" + id,
            method: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(response) {
                window.location.reload();
            },
            error: function() {
                alert('Gagal mengubah status kategori.');
            }
        });
    });
});
</script>
@endpush