<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Laporan Penjualan' }}</title>
    <style>
        @page {
            margin: 18mm 14mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111827;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .header .period {
            margin-top: 6px;
            font-size: 12px;
        }

        .meta {
            width: 100%;
            border-bottom: 2px solid #222;
            margin-bottom: 14px;
            padding-bottom: 8px;
        }

        .meta td {
            vertical-align: top;
        }

        .meta .right {
            text-align: right;
            white-space: nowrap;
        }

        .company-name {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        table.report thead th {
            background: #efefef;
            border: 1px solid #d1d5db;
            padding: 8px 6px;
            font-size: 10.5px;
            text-align: left;
        }

        table.report td {
            border: 1px solid #e5e7eb;
            padding: 8px 6px;
            vertical-align: top;
        }

        table.report tfoot th {
            border: 1px solid #d1d5db;
            background: #e5e7eb;
            padding: 8px 6px;
        }

        .num {
            width: 34px;
            text-align: center;
        }

        .order-no {
            width: 120px;
        }

        .date {
            width: 128px;
            white-space: nowrap;
        }

        .customer {
            width: 110px;
        }

        .total {
            width: 110px;
            text-align: right;
            white-space: nowrap;
        }

        .items ul {
            margin: 0;
            padding-left: 16px;
        }

        .items li {
            margin: 0 0 2px 0;
        }

        .signature {
            margin-top: 28px;
            width: 100%;
        }

        .signature .box {
            width: 220px;
            margin-left: auto;
            text-align: left;
        }

        .muted {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Penjualan</h1>
        <div class="period">{{ $period }}</div>
    </div>

    <table class="meta">
        <tr>
            <td>
                <div class="company-name">Trenmart</div>
                Jl. Pasar Baru No. 123<br>
                Telp: (021) 000-0000
            </td>
            <td class="right muted">Dicetak: {{ $generated_at->format('d M Y H:i') }}</td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th class="num">No.</th>
                <th class="date">Tanggal/Waktu Selesai</th>
                <th class="order-no">No. Pesanan</th>
                <th class="customer">Pelanggan</th>
                <th>Isi Pesanan</th>
                <th class="total">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $idx = 1; $grand = 0; @endphp
            @forelse($orders as $o)
                @php
                    $grand += $o->total;
                    $completedAt = $o->completed_at ?? $o->updated_at;
                @endphp
                <tr>
                    <td class="num">{{ $idx++ }}</td>
                    <td class="date">{{ $completedAt ? $completedAt->format('d M Y H:i') : '-' }}</td>
                    <td class="order-no">#{{ $o->order_number }}</td>
                    <td class="customer">{{ $o->user?->name ?? 'Guest' }}</td>
                    <td class="items">
                        <ul>
                            @forelse($o->items as $item)
                                <li>{{ $item->produk?->nama_produk ?? $item->kd_produk }} x {{ $item->quantity }}</li>
                            @empty
                                <li>-</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="total">{{ number_format($o->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding: 18px 6px;">Tidak ada pesanan selesai pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="total" style="text-align:right;">Grand Total</th>
                <th class="total">Rp {{ number_format($grand, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <table class="signature">
        <tr>
            <td>
                Mengetahui,<br><br><br>
                Pemilik Trenmart
            </td>
        </tr>
    </table>
</body>
</html>