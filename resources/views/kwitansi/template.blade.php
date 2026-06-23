<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran SPP - {{ $kwitansi->nomor_kwitansi }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            line-height: 1.4;
        }
        
        .receipt-card {
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 24px;
            position: relative;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        
        .school-logo-cell {
            width: 8%;
            vertical-align: middle;
        }
        
        .school-info-cell {
            width: 60%;
            vertical-align: middle;
            padding-left: 10px;
        }
        
        .school-name {
            font-size: 14px;
            font-weight: 800;
            color: #1E3A8A;
            margin: 0;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }
        
        .school-details {
            font-size: 9px;
            color: #64748B;
            margin: 2px 0 0 0;
            font-weight: 500;
        }
        
        .receipt-title-cell {
            width: 32%;
            text-align: right;
            vertical-align: middle;
        }
        
        .receipt-label {
            font-size: 14px;
            font-weight: 800;
            color: #2563EB;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.05em;
        }
        
        .receipt-no-box {
            display: inline-block;
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 3px 8px;
            font-family: monospace;
            font-size: 9px;
            font-weight: bold;
            color: #475569;
            margin-top: 4px;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .details-table td {
            padding: 8px 0;
            vertical-align: middle;
            border-bottom: 1px dashed #E2E8F0;
        }
        
        .label-col {
            width: 25%;
            color: #64748B;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 8.5px;
            letter-spacing: 0.08em;
        }
        
        .value-col {
            width: 75%;
            font-size: 11px;
            color: #1E293B;
            font-weight: 600;
        }
        
        .value-col strong {
            color: #0F172A;
            font-weight: 800;
        }
        
        .amount-badge {
            background-color: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 800;
            color: #1E40AF;
            font-family: monospace;
            display: inline-block;
            letter-spacing: -0.01em;
        }
        
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .footer-table td {
            vertical-align: top;
        }
        
        .qr-col {
            width: 33%;
        }
        
        .amount-col {
            width: 34%;
            text-align: center;
            vertical-align: middle;
        }
        
        .sig-col {
            width: 33%;
            text-align: right;
        }
        
        .qr-container {
            display: inline-block;
            text-align: center;
        }
        
        .qr-container img {
            border: 1px solid #E2E8F0;
            padding: 3px;
            border-radius: 6px;
            background-color: #ffffff;
        }
        
        .qr-text {
            font-size: 7px;
            color: #94A3B8;
            margin: 4px 0 0 0;
            font-family: monospace;
            font-weight: bold;
            letter-spacing: 0.05em;
        }
        
        .signature-container {
            display: inline-block;
            text-align: center;
        }
        
        .sig-date {
            margin: 0;
            font-size: 9px;
            color: #64748B;
            font-weight: 500;
        }
        
        .sig-label {
            margin: 4px 0 0 0;
            font-weight: 700;
            font-size: 9px;
            color: #1E293B;
        }
        
        .sig-space {
            height: 48px;
        }
        
        .sig-name {
            font-weight: 700;
            color: #0F172A;
            border-bottom: 1px solid #94A3B8;
            padding-bottom: 1px;
            display: inline-block;
            font-size: 10px;
        }
        
        .sig-title {
            font-size: 8px;
            color: #64748B;
            margin: 3px 0 0 0;
            font-weight: 500;
        }
        
        .stamp-lunas {
            position: absolute;
            right: 25%;
            bottom: 28px;
            border: 3px double #10B981;
            color: #10B981;
            font-size: 13px;
            font-weight: 900;
            padding: 4px 14px;
            border-radius: 6px;
            transform: rotate(-15deg);
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 3px;
            background-color: rgba(255, 255, 255, 0.9);
            z-index: 10;
        }
        
        .logo-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: #EFF6FF;
            border: 1px solid #DBEAFE;
            text-align: center;
            vertical-align: middle;
            line-height: 32px;
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <!-- Stamp Lunas -->
        <div class="stamp-lunas">LUNAS</div>
        
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td class="school-logo-cell">
                    <div class="logo-box">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5" style="vertical-align: middle; display: inline-block; margin-top: 6px;">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6v12M15 9H11.5a2.5 2.5 0 0 0 0 5H13a2.5 2.5 0 0 1 0 5H9"></path>
                        </svg>
                    </div>
                </td>
                <td class="school-info-cell">
                    <h1 class="school-name">SMP NEGERI 1 KELAPA DUA</h1>
                    <p class="school-details">Jl. Pendidikan No. 45, Kecamatan Kelapa Dua, Tangerang · Telp: (021) 543210</p>
                </td>
                <td class="receipt-title-cell">
                    <h2 class="receipt-label">Kwitansi SPP</h2>
                    <div class="receipt-no-box">NO: {{ $kwitansi->nomor_kwitansi }}</div>
                </td>
            </tr>
        </table>

        <!-- Details -->
        <table class="details-table">
            <tr>
                <td class="label-col">Telah Diterima Dari</td>
                <td class="value-col">: &nbsp;<strong>{{ $siswa->nama_lengkap }}</strong> &nbsp;<span style="color: #64748B; font-weight: normal; font-size: 10px;">(NIS: {{ $siswa->nis }})</span></td>
            </tr>
            <tr>
                <td class="label-col">Kelas / Tingkat</td>
                <td class="value-col">: &nbsp;<strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong> &nbsp;<span style="color: #64748B; font-weight: normal; font-size: 10px;">(Tingkat {{ $siswa->kelas->tingkat ?? '-' }})</span></td>
            </tr>
            <tr>
                <td class="label-col">Untuk Pembayaran</td>
                <td class="value-col">: &nbsp;SPP Bulan 
                    @php
                        $bulanName = \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F');
                    @endphp
                    <strong>{{ $bulanName }} {{ $tagihan->tahun }}</strong>
                </td>
            </tr>
            <tr>
                <td class="label-col">Terbilang</td>
                <td class="value-col" style="font-style: italic; color: #475569; font-weight: 500;">: &nbsp;
                    @php
                        if (!function_exists('penyebut')) {
                            function penyebut($nilai) {
                                $nilai = abs($nilai);
                                $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
                                $temp = "";
                                if ($nilai < 12) {
                                    $temp = " " . $huruf[$nilai];
                                } else if ($nilai < 20) {
                                    $temp = penyebut($nilai - 10). " Belas";
                                } else if ($nilai < 100) {
                                    $temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
                                } else if ($nilai < 200) {
                                    $temp = " Seratus" . penyebut($nilai - 100);
                                } else if ($nilai < 1000) {
                                    $temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
                                } else if ($nilai < 2000) {
                                    $temp = " Seribu" . penyebut($nilai - 1000);
                                } else if ($nilai < 1000000) {
                                    $temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
                                } else if ($nilai < 1000000000) {
                                    $temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
                                }
                                return $temp;
                            }
                        }
                        
                        if (!function_exists('terbilang')) {
                            function terbilang($nilai) {
                                if($nilai<0) {
                                    $hasil = "Minus ". trim(penyebut($nilai));
                                } else {
                                    $hasil = trim(penyebut($nilai));
                                }
                                return $hasil . " Rupiah";
                            }
                        }
                    @endphp
                    "{{ terbilang($pembayaran->jumlah_bayar) }}"
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <table class="footer-table">
            <tr>
                <td class="qr-col">
                    <div class="qr-container">
                        <!-- QR Code verification for security (SVG format, no imagick needed) -->
                        <img src="data:image/svg+xml;base64, {!! base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(64)->errorCorrection('H')->generate($qrContent)) !!}" alt="QR Verifikasi" width="64" height="64">
                        <p class="qr-text">SCAN UNTUK VERIFIKASI KEASLIAN</p>
                    </div>
                </td>
                
                <td class="amount-col">
                    <div class="amount-badge">
                        Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                    </div>
                </td>
                
                <td class="sig-col">
                    <div class="signature-container">
                        <p class="sig-date">Tangerang, {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') }}</p>
                        <p class="sig-label">Penerima / Bendahara,</p>
                        <div class="sig-space"></div>
                        <span class="sig-name">{{ $pembayaran->dicatatOleh->name ?? 'Bendahara Sekolah' }}</span>
                        <p class="sig-title">NIP. 19820512 200904 2 003</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
