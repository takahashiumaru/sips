<table>
    <thead>
        <tr>
            <th colspan="8" style="font-weight: bold; font-size: 14px; text-align: center;">MI AL-HAQ</th>
        </tr>
        <tr>
            <th colspan="8" style="font-style: italic; text-align: center;">LAPORAN TUNGGAKAN PEMBAYARAN SPP</th>
        </tr>
        <tr>
            <th colspan="8"></th>
        </tr>
        <tr style="background-color: #1E3A5F; color: #FFFFFF; font-weight: bold;">
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">No</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">NIS</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: left;">Nama Siswa</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Kelas</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: center;">Periode SPP</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Nominal Tagihan</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Telah Dibayar</th>
            <th style="font-weight: bold; border: 1px solid #000000; text-align: right;">Sisa Tunggakan</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalTunggakan = 0;
            $totalTagihan = 0;
            $totalBayar = 0;
        @endphp
        @foreach ($tunggakan as $index => $t)
            @php
                $sisa = $t->jumlah_tagihan - $t->total_dibayar;
                $totalTunggakan += $sisa;
                $totalTagihan += $t->jumlah_tagihan;
                $totalBayar += $t->total_dibayar;
                $bulanName = \Carbon\Carbon::create()->month($t->bulan)->translatedFormat('F');
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $t->siswa->nis ?? '-' }}</td>
                <td style="border: 1px solid #000000; font-weight: bold;">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $bulanName }} {{ $t->tahun }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $t->jumlah_tagihan }}</td>
                <td style="border: 1px solid #000000; text-align: right;">{{ $t->total_dibayar }}</td>
                <td style="border: 1px solid #000000; text-align: right; color: #DC2626; font-weight: bold;">{{ $sisa }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr style="background-color: #F1F5F9; font-weight: bold;">
            <td colspan="5" style="border: 1px solid #000000; font-weight: bold; text-align: right;">TOTAL TUNGGAKAN KUMULATIF</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right;">{{ $totalTagihan }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right;">{{ $totalBayar }}</td>
            <td style="border: 1px solid #000000; font-weight: bold; text-align: right; color: #DC2626; font-size: 11px;">{{ $totalTunggakan }}</td>
        </tr>
    </tfoot>
</table>
