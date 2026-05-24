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
        }
        table th, table td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
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
            Periode: {{ \Carbon\Carbon::parse(request('start', now()->startOfMonth()))->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse(request('end', now()))->translatedFormat('d F Y') }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No.</th>
                <th>Tanggal/Waktu Selesai</th>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Isi Pesanan</th>
                <th class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            {{-- Contoh Looping Data (Sesuaikan dengan milikmu) --}}
            {{-- @forelse($orders as $index => $order)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $order->completed_at }}</td>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'Umum' }}</td>
                    <td>{{ $order->items_description }}</td>
                    <td class="text-right">{{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            @empty --}}
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada pesanan selesai pada periode ini.</td>
                </tr>
            {{-- @endforelse --}}
        </tbody>
        {{-- TFOOT: Grand Total --}}
        <tfoot>
            <tr>
                <td colspan="5" class="text-right" style="font-weight: bold;">Grand Total</td>
                <td class="text-right" style="font-weight: bold;">Rp 0</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 40px; width: 100%; text-align: right;">
        <p style="margin-bottom: 70px;">Mengetahui,</p>
        <p style="font-weight: bold;">Pemilik Trenmart</p>
    </div>
</body>
</html>