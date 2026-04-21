<div class="row">
    <div class="col-md-4">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
            <h6 class="fw-bold mb-3">Tambah Kategori Baru</h6>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small">Kode Kategori</label>
                    <input type="text" name="kd_kategori" class="form-control" placeholder="Contoh: K001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Buku Tulis" required>
                </div>
                <button type="submit" class="btn btn-success w-100 rounded-pill">Simpan Kategori</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 15px;">
            <h6 class="fw-bold mb-3">Daftar Kategori</h6>
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategori as $item)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $item->kd_kategori }}</span></td>
                        <td>{{ $item->nama_kategori }}</td>
                        <td class="text-center">
                            <form action="{{ route('kategori.destroy', $item->kd_kategori) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus kategori ini?')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>