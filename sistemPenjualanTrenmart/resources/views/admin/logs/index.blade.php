@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
        <h1 class="mb-0">Log Aktivitas Internal</h1>
        <a href="{{ route('beranda') }}" class="btn btn-outline-secondary">
            <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Waktu</th>
                        <th>Pelaku</th>
                        <th>Aksi</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->created_at->copy()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $log->actor ? $log->actor->name . ' (' . $log->actor->email . ')' : 'System' }}</td>
                        <td>{{ $log->action }}</td>
                        <td style="max-width:420px;overflow-wrap:break-word">{{ $log->details }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada log</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
