@extends('layouts.app')

@section('content')
<div class="container my-4 my-md-5">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 12px;">
            {{ session('success') }}
        </div>
    @endif

    @if($isAdminEditMode)
        <form action="{{ route('admin.tentang.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
                <div>
                    <h1 class="fw-bold mb-1" style="font-size: 2rem;">Halaman Tentang Kami</h1>
                    <p class="text-muted mb-0">Atur informasi toko, foto, lokasi, dan pengumuman yang tampil di website pelanggan.</p>
                </div>
                <a href="{{ route('tentang', ['preview' => 1]) }}" class="btn btn-outline-danger fw-semibold" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Halaman Pelanggan
                </a>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="bi bi-image me-2 text-danger"></i>Foto Banner Toko</h4>
                    <div class="border rounded-4 p-3" style="border-style: dashed !important;">
                        @if(!empty($data['tentang_banner']))
                            <img src="{{ asset('storage/' . $data['tentang_banner']) }}" alt="Banner Toko" class="img-fluid rounded-4 mb-3" style="max-height: 320px; width: 100%; object-fit: cover;">
                        @endif
                        <input type="file" class="form-control @error('tentang_banner') is-invalid @enderror" name="tentang_banner" accept="image/png,image/jpeg,image/jpg,image/webp">
                        <small class="text-muted">JPG, PNG, WEBP - Maks. 5 MB</small>
                        @error('tentang_banner')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Informasi Utama</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Toko</label>
                            <input type="text" name="tentang_nama_toko" class="form-control @error('tentang_nama_toko') is-invalid @enderror" value="{{ old('tentang_nama_toko', $data['tentang_nama_toko']) }}" required>
                            @error('tentang_nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tagline / Slogan</label>
                            <input type="text" name="tentang_tagline" class="form-control @error('tentang_tagline') is-invalid @enderror" value="{{ old('tentang_tagline', $data['tentang_tagline']) }}">
                            @error('tentang_tagline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Deskripsi Toko</label>
                            <textarea name="tentang_deskripsi" rows="4" class="form-control @error('tentang_deskripsi') is-invalid @enderror">{{ old('tentang_deskripsi', $data['tentang_deskripsi']) }}</textarea>
                            @error('tentang_deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Kontak & Lokasi</h4>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Toko</label>
                            <textarea name="tentang_alamat" rows="2" class="form-control @error('tentang_alamat') is-invalid @enderror">{{ old('tentang_alamat', $data['tentang_alamat']) }}</textarea>
                            @error('tentang_alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Link Google Maps (opsional)</label>
                            <input type="text" name="tentang_maps_link" class="form-control @error('tentang_maps_link') is-invalid @enderror" value="{{ old('tentang_maps_link', $data['tentang_maps_link']) }}" placeholder="https://maps.google.com/...">
                            <small class="text-muted">Jika diisi, lokasi ini diprioritaskan untuk tampilan peta.</small>
                            @error('tentang_maps_link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" name="tentang_telepon" class="form-control @error('tentang_telepon') is-invalid @enderror" value="{{ old('tentang_telepon', $data['tentang_telepon']) }}">
                            @error('tentang_telepon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="tentang_email" class="form-control @error('tentang_email') is-invalid @enderror" value="{{ old('tentang_email', $data['tentang_email']) }}">
                            @error('tentang_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Jam Operasional</label>
                            <input type="text" name="tentang_jam_operasional" class="form-control @error('tentang_jam_operasional') is-invalid @enderror" value="{{ old('tentang_jam_operasional', $data['tentang_jam_operasional']) }}">
                            @error('tentang_jam_operasional')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 18px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0">Fitur Unggulan</h4>
                        <button type="button" id="addFeatureBtn" class="btn btn-outline-danger fw-semibold">
                            <i class="bi bi-plus-lg me-1"></i> Tambah
                        </button>
                    </div>

                    <div id="featureContainer" class="d-flex flex-column gap-3">
                        @foreach($fiturUnggulan as $fitur)
                            <div class="feature-row border rounded-3 p-3 bg-light-subtle">
                                <div class="row g-2 align-items-start">
                                    <div class="col-md-2">
                                        <select class="form-select" name="feature_icon[]">
                                            @php
                                                $icons = ['shop' => 'Store', 'truck' => 'Truck', 'patch-check' => 'ShieldCheck', 'headset' => 'Headphones', 'bag-check' => 'Bag Check', 'stars' => 'Stars'];
                                            @endphp
                                            @foreach($icons as $value => $label)
                                                <option value="{{ $value }}" {{ ($fitur['icon'] ?? 'shop') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" name="feature_title[]" placeholder="Judul fitur" value="{{ $fitur['title'] ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="feature_description[]" placeholder="Deskripsi fitur" value="{{ $fitur['description'] ?? '' }}">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-link text-danger remove-feature" title="Hapus fitur">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn px-4 py-2 fw-semibold" style="background-color: #981b3f; color: #fff; border-radius: 12px;">
                    <i class="bi bi-floppy me-2"></i> Simpan Informasi Toko
                </button>
            </div>
        </form>
    @else
        <div class="text-center mb-5">
            @if(!empty($data['tentang_banner']))
                <img src="{{ asset('storage/' . $data['tentang_banner']) }}" alt="Banner {{ $data['tentang_nama_toko'] }}" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 360px; object-fit: cover;">
            @endif
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="fw-bold mb-2">{{ $data['tentang_nama_toko'] }}</h1>
                        <p class="text-danger fw-semibold mb-4">{{ $data['tentang_tagline'] }}</p>
                        <p class="text-muted mb-0" style="line-height: 1.8;">{{ $data['tentang_deskripsi'] }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 18px;">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Kontak & Lokasi</h4>
                        <ul class="list-unstyled mb-3">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2 text-danger"></i>{{ $data['tentang_alamat'] }}</li>
                            <li class="mb-2"><i class="bi bi-telephone me-2 text-danger"></i>{{ $data['tentang_telepon'] }}</li>
                            <li class="mb-2"><i class="bi bi-envelope me-2 text-danger"></i>{{ $data['tentang_email'] }}</li>
                            <li><i class="bi bi-clock me-2 text-danger"></i>{{ $data['tentang_jam_operasional'] }}</li>
                        </ul>
                        <div class="ratio ratio-16x9 rounded-3 overflow-hidden">
                            <iframe src="{{ $mapEmbedUrl }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="row g-3">
                @foreach($fiturUnggulan as $fitur)
                    <div class="col-md-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 42px; height: 42px; background-color: #fce7ef; color: #981b3f;">
                                    <i class="bi bi-{{ $fitur['icon'] ?? 'shop' }}"></i>
                                </div>
                                <h5 class="fw-bold" style="font-size: 1rem;">{{ $fitur['title'] ?? '' }}</h5>
                                <p class="text-muted mb-0" style="font-size: 0.92rem;">{{ $fitur['description'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
@if($isAdminEditMode)
<script>
(function () {
    const container = document.getElementById('featureContainer');
    const addButton = document.getElementById('addFeatureBtn');

    const createRow = () => {
        const row = document.createElement('div');
        row.className = 'feature-row border rounded-3 p-3 bg-light-subtle';
        row.innerHTML = `
            <div class="row g-2 align-items-start">
                <div class="col-md-2">
                    <select class="form-select" name="feature_icon[]">
                        <option value="shop">Store</option>
                        <option value="truck">Truck</option>
                        <option value="patch-check">ShieldCheck</option>
                        <option value="headset">Headphones</option>
                        <option value="bag-check">Bag Check</option>
                        <option value="stars">Stars</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="feature_title[]" placeholder="Judul fitur">
                </div>
                <div class="col-md-6">
                    <input type="text" class="form-control" name="feature_description[]" placeholder="Deskripsi fitur">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-link text-danger remove-feature" title="Hapus fitur">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
        return row;
    };

    addButton.addEventListener('click', function () {
        container.appendChild(createRow());
    });

    container.addEventListener('click', function (event) {
        const removeBtn = event.target.closest('.remove-feature');
        if (!removeBtn) {
            return;
        }

        const rows = container.querySelectorAll('.feature-row');
        if (rows.length <= 1) {
            return;
        }

        removeBtn.closest('.feature-row').remove();
    });
})();
</script>
@endif
@endpush