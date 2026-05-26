@extends('layouts.app')

@section('content')
@include('admin._header', ['activePage' => $page === 'internal' ? 'internal_users' : 'users'])

<div class="container-fluid">
    <div class="mb-4">
        <h4 class="fw-bold ms-0">
            {{ $page === 'internal' ? 'Daftar Pengguna Internal' : 'Daftar Pengguna (Admin & Pelanggan)' }}
        </h4>
    </div>

    <div class="card shadow-sm w-100">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle w-100 mb-0" style="font-size: 0.95rem; table-layout: fixed;">
                    <thead class="table-light">
                        <tr>
                            <th class="py-1 px-2" style="width: 5%;">#</th>
                            <th class="py-1 px-2" style="width: 11%;">Kode Pelanggan</th>
                            <th class="py-1 px-2" style="width: 18%;">Nama</th>
                            <th class="py-1 px-2" style="width: 26%;">Email</th>
                            <th class="py-1 px-2" style="width: 12%;">Role</th>
                            <th class="py-1 px-2" style="width: 12%;">Jenis Pelanggan</th>
                            @if($page === 'internal')
                                <th class="py-1 px-2" style="width: 8%;">Status</th>
                                <th class="py-1 px-2" style="width: 10%;">Aksi</th>
                            @endif
                            <th class="py-1 px-2" style="width: 8%;">Tgl Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td class="py-1 px-2 text-truncate">{{ $u->id }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $u->kd_pelanggan ?? '-' }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $u->name }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $u->email }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $u->roleLabel() }}</td>
                            <td class="py-1 px-2 text-truncate">{{ $u->customer_type ?? '-' }}</td>
                            @if($page === 'internal')
                                <td class="py-1 px-2">
                                    @if($u->isActive())
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-1 px-2">
                                    @if(auth()->user()->isOwner() && $u->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle_active', $u->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $u->isActive() ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                onclick="return confirm('Yakin ingin {{ $u->isActive() ? 'menonaktifkan' : 'mengaktifkan' }} user ini?')">
                                                {{ $u->isActive() ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            @endif
                            <td class="py-1 px-2">{{ $u->created_at ? $u->created_at->format('d M Y') : '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
