<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Bulanan Penerimaan SPP - {{ $tahun }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #1E3A5F;
            padding-bottom: 10px;
            margin-bottom: 15px;
            text-align: center;
        }
        .school-name {
            font-size: 16px;
            font-weight: bold;
            color: #1E3A5F;
            text-transform: uppercase;
            margin: 0;
        }
        .school-details {
            font-size: 10px;
            color: #666666;
            margin: 3px 0 0 0;
        }
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 15px 0;
            color: #1E3A5F;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .report-table th {
            background-color: #1E3A5F;
            color: #FFFFFF;
            font-weight: bold;
            padding: 8px 6px;
            font-size: 9px;
            text-transform: uppercase;
            border: 1px solid #1E3A5F;
        }
        .report-table td {
            padding: 6px;
            border: 1px solid #E2E8F0;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .sig-space {
            height: 60px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1 class="school-name">SD NEGERI 1 KELAPA DUA</h1>
        <p class="school-details">Jl. Pendidikan No. 45, Kecamatan Kelapa Dua, Tangerang · Telp: (021) 543210</p>
    </div>

    <!-- Title -->
    <div class="title">Rekapitulasi Bulanan Penerimaan SPP Tahun {{ $tahun }}</div>

    <!-- Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 15%;">Bulan</th>
                <th class="text-right" style="width: 16%;">Target Penagihan</th>
                <th class="text-right" style="width: 16%;">Realisasi Penerimaan</th>
                <th class="text-right" style="width: 16%;">Sisa Tunggakan</th>
                <th class="text-center" style="width: 13%;">Jumlah Tagihan</th>
                <th class="text-center" style="width: 13%;">Jumlah Lunas</th>
                <th class="text-right" style="width: 11%;">Tingkat Kelunasan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalTagihan = 0;
                $grandTotalBayar = 0;
                $grandCountTagihan = 0;
                $grandCountLunas = 0;
            @endphp
            @foreach ($rekap as $r)
                @php
                    $grandTotalTagihan += $r['total_tagihan'];
                    $grandTotalBayar += $r['total_bayar'];
                    $grandCountTagihan += $r['total'];
                    $grandCountLunas += $r['lunas'];
                    $sisa = $r['total_tagihan'] - $r['total_bayar'];
                @endphp
                <tr>
                    <td class="font-bold">{{ \Carbon\Carbon::create()->month($r['bulan'])->translatedFormat('F') }}</td>
                    <td class="text-right font-bold" style="font-family: monospace;">Rp {{ number_format($r['total_tagihan'], 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="font-family: monospace; color: #059669;">Rp {{ number_format($r['total_bayar'], 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="font-family: monospace; color: #DC2626;">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                    <td class="text-center font-bold" style="font-family: monospace;">{{ $r['total'] }} item</td>
                    <td class="text-center font-bold" style="font-family: monospace;">{{ $r['lunas'] }} item</td>
                    <td class="text-right font-bold" style="font-family: monospace;">{{ $r['persentase'] }}%</td>
                </tr>
            @endforeach
            
            @php
                $grandSisa = $grandTotalTagihan - $grandTotalBayar;
                $grandPersentase = $grandCountTagihan > 0 ? round(($grandCountLunas / $grandCountTagihan) * 100, 1) : 0;
            @endphp
            <tr style="background-color: #F8FAFC; font-weight: bold;">
                <td class="font-bold">GRAND TOTAL</td>
                <td class="text-right font-bold" style="font-family: monospace;">Rp {{ number_format($grandTotalTagihan, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="font-family: monospace; color: #059669;">Rp {{ number_format($grandTotalBayar, 0, ',', '.') }}</td>
                <td class="text-right font-bold" style="font-family: monospace; color: #DC2626;">Rp {{ number_format($grandSisa, 0, ',', '.') }}</td>
                <td class="text-center font-bold" style="font-family: monospace;">{{ $grandCountTagihan }} item</td>
                <td class="text-center font-bold" style="font-family: monospace;">{{ $grandCountLunas }} item</td>
                <td class="text-right font-bold" style="font-family: monospace; color: #1E3A5F; font-size: 11px;">{{ $grandPersentase }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Signature -->
    <div class="footer">
        <p>Tangerang, {{ now()->translatedFormat('d F Y') }}</p>
        <p style="font-weight: bold; margin-bottom: 5px;">Kepala Sekolah,</p>
        <div class="sig-space"></div>
        <p style="font-weight: bold; text-decoration: underline; margin: 0;">Dr. H. Ahmad Fauzi, M.Pd.</p>
        <p style="margin: 2px 0 0 0; color: #666666;">NIP. 19740815 200212 1 002</p>
    </div>
</body>
</html>
