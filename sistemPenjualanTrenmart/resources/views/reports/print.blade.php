<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        /* 1. KUNCI UNTUK JARAK TEPI (MARGIN) SAAT PRINT/PDF */
        @page {
            size: A4 landscape; /* Hapus 'landscape' jika ingin potret */
            margin: 2cm; /* Memberikan jarak tepi 2cm di semua sisi kertas */
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px; /* Jarak tambahan agar rapi saat dilihat di browser */
            color: #333;
            font-size: 12px;
        }

        /* 2. STYLE UNTUK HEADER PERUSAHAAN */
        .kop-surat {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
        }
        .kop-surat h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 3px 0;
            font-size: 13px;
        }

        /* Style Tabel Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        table th, table td {
            border: 1px solid #999;
            padding: 9px;
            text-align: left;
            vertical-align: top;
        }
        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .items-list {
            margin: 0;
            padding-left: 18px;
            font-size: 13px;
            line-height: 1.35;
        }
        .items-list li {
            margin: 0 0 4px 0;
        }
        .price-list {
            font-size: 13px;
            line-height: 1.35;
        }
        .order-code {
            word-break: break-word;
        }
        .narrow-col {
            font-size: 11px;
            line-height: 1.25;
        }
        .wide-col {
            font-size: 12px;
            line-height: 1.35;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1>PT TREN ABADI STATIONERI</h1>
        <p>Jalan Jenderal Ahmad Yani, Tangga Takat, Kota Palembang</p>
        <p>Telp. 0859-3522-7778 &nbsp;|&nbsp; Email: Trenabadistationeri@gmail.com</p>
    </div>

    <div class="judul-laporan">
        LAPORAN PENJUALAN<br>
        <span style="font-weight: normal; font-size: 12px;">
            Periode: {{ $period }}
        </span>
    </div>

    @php
        $grandTotal = (float) $orders->sum('total');
    @endphp

    <table>
        <colgroup>
            <col style="width: 10mm;">
            <col style="width: 25mm;">
            <col style="width: 30mm;">
            <col style="width: 25mm;">
            <col style="width: 25mm;">
            <col style="width: 65mm;">
            <col style="width: 55mm;">
            <col style="width: 11mm;">
            <col style="width: 11mm;">
        </colgroup>
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th>Tanggal/Waktu Selesai</th>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Metode Pembayaran</th>
                <th>Isi Pesanan</th>
                <th>Harga Item</th>
                <th class="text-right">Ongkir (Rp)</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
                @php
                    $finishedAt = $order->completed_at ?? $order->stock_deducted_at ?? $order->updated_at;
                    $items = $order->items
                        ->map(function ($item) {
                            $name = $item->produk->nama_produk ?? ($item->kd_produk ?? 'Produk');
                            $qty = (int) ($item->quantity ?? 0);
                            $unitPrice = (float) ($item->price ?? 0);
                            $lineTotal = $qty * $unitPrice;

                            return [
                                'description' => $name . ' x' . $qty,
                                'price' => 'Rp ' . number_format($lineTotal, 0, ',', '.') . ' (' . $qty . ' x Rp ' . number_format($unitPrice, 0, ',', '.') . ')',
                            ];
                        })
                        ->values();
                @endphp
                <tr>
                    <td class="text-center narrow-col">{{ $index + 1 }}</td>
                    <td class="narrow-col">{{ $finishedAt ? \Carbon\Carbon::parse($finishedAt)->format('d-m-Y H:i') : '-' }}</td>
                    <td class="order-code narrow-col">{{ $order->order_number }}</td>
                    <td class="narrow-col">{{ $order->user->name ?? '-' }}</td>
                    <td class="narrow-col">{{ $order->paymentMethod->name ?? '-' }}</td>
                    <td class="wide-col">
                        @if($items->isNotEmpty())
                            <ul class="items-list">
                                @foreach($items as $entry)
                                    <li>{{ $entry['description'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td class="wide-col">
                        @if($items->isNotEmpty())
                            <ul class="items-list price-list">
                                @foreach($items as $entry)
                                    <li>{{ $entry['price'] }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-right narrow-col">{{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right narrow-col">{{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Tidak ada pesanan selesai pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        {{-- TFOOT: Grand Total --}}
        <tfoot>
            <tr>
                <td colspan="8" class="text-right" style="font-weight: bold;">Grand Total</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; width: 100%; text-align: right;">
        <p style="margin-bottom: 70px;">Mengetahui,</p>
        <p style="font-weight: bold;">Pemilik Trenmart</p>
    </div>
</body>
</html>