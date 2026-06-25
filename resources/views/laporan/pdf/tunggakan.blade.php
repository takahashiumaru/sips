<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tunggakan SPP</title>
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
    <div class="title">Laporan Tunggakan Pembayaran SPP</div>

    <!-- Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 12%;" class="text-center">NIS</th>
                <th style="width: 25%;">Nama Siswa</th>
                <th style="width: 10%;" class="text-center">Kelas</th>
                <th style="width: 13%;" class="text-center">Periode SPP</th>
                <th class="text-right">Nominal Tagihan</th>
                <th class="text-right">Telah Dibayar</th>
                <th class="text-right">Sisa Tunggakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tunggakan as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-bold" style="font-family: monospace;">{{ $t->siswa->nis ?? '-' }}</td>
                    <td class="font-bold">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                    <td class="text-center">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td class="text-center">
                        @php
                            $bulanName = \Carbon\Carbon::create()->month($t->bulan)->translatedFormat('F');
                        @endphp
                        {{ $bulanName }} {{ $t->tahun }}
                    </td>
                    <td class="text-right">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($t->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-right font-bold" style="color: #DC2626;">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">Tidak ada data tunggakan siswa.</td>
                </tr>
            @endforelse
            <tr style="background-color: #F8FAFC; font-weight: bold;">
                <td colspan="7" class="text-right">TOTAL TUNGGAKAN KUMULATIF:</td>
                <td class="text-right font-bold" style="color: #DC2626; font-size: 11px;">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</td>
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
