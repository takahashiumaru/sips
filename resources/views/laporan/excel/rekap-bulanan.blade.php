<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; font-size: 14px; text-align: center;">MI AL-HAQ</th>
        </tr>
        <tr>
            <th colspan="7" style="font-style: italic; text-align: center;">REKAPITULASI PENERIMAAN SPP TAHUN {{ $tahun }}</th>
        </tr>
        <tr>
            <th colspan="7"></th>
        </tr>
        <tr style="background-color: #1E3A5F; color: #FFFFFF; font-weight: bold;">
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Bulan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Target Penagihan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Realisasi Penerimaan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Sisa Tunggakan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Jumlah Tagihan (Item)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Jumlah Lunas (Item)</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Tingkat Kelunasan (%)</th>
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
                <td style="border: 1px solid #000000; font-weight: bold;">{{ \Carbon\Carbon::create()->month($r['bulan'])->translatedFormat('F') }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $r['total_tagihan'] }}</td>
                <td style="border: 1px solid #000000; text-align: right; color: #059669;">{{ $r['total_bayar'] }}</td>
                <td style="border: 1px solid #000000; text-align: right; color: #DC2626;">{{ $sisa }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $r['total'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $r['lunas'] }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $r['persentase'] }}%</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        @php
            $grandSisa = $grandTotalTagihan - $grandTotalBayar;
            $grandPersentase = $grandCountTagihan > 0 ? round(($grandCountLunas / $grandCountTagihan) * 100, 1) : 0;
        @endphp
        <tr style="background-color: #F1F5F9; font-weight: bold;">
            <td style="border: 1px solid #000000; font-weight: bold;">GRAND TOTAL</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right;">{{ $grandTotalTagihan }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right; color: #059669;">{{ $grandTotalBayar }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right; color: #DC2626;">{{ $grandSisa }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $grandCountTagihan }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: center;">{{ $grandCountLunas }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right;">{{ $grandPersentase }}%</td>
        </tr>
    </tfoot>
</table>
