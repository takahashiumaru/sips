<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function tunggakan(Request $request)
    {
        $query = TagihanSpp::with(['siswa.kelas', 'siswa.waliMurid'])
            ->where('status', '!=', 'lunas')
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($request->bulan, fn($q, $v) => $q->where('bulan', $v))
            ->when($request->tahun, fn($q, $v) => $q->where('tahun', $v));

        $tunggakan = $query->orderByDesc(DB::raw('jumlah_tagihan - total_dibayar'))->paginate(20)->withQueryString();

        $ringkasan = [
            'total_siswa' => (clone $query)->select('siswa_id')->distinct()->count('siswa_id'),
            'total_tunggakan' => (clone $query)->sum(DB::raw('jumlah_tagihan - total_dibayar')),
            'total_tagihan' => (clone $query)->count(),
        ];

        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('laporan.tunggakan', compact('tunggakan', 'ringkasan', 'kelas'));
    }

    public function exportPdf(Request $request)
    {
        $tunggakan = TagihanSpp::with(['siswa.kelas', 'siswa.waliMurid'])
            ->where('status', '!=', 'lunas')
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($request->bulan, fn($q, $v) => $q->where('bulan', $v))
            ->when($request->tahun, fn($q, $v) => $q->where('tahun', $v))
            ->orderByDesc(DB::raw('jumlah_tagihan - total_dibayar'))
            ->get();

        $totalTunggakan = $tunggakan->sum(fn($t) => $t->jumlah_tagihan - $t->total_dibayar);

        $pdf = Pdf::loadView('laporan.pdf.tunggakan', compact('tunggakan', 'totalTunggakan'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Tunggakan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function rekapBulanan(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $rekap = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $totalTagihan = TagihanSpp::where('bulan', $bulan)->where('tahun', $tahun)->sum('jumlah_tagihan');
            $totalBayar = TagihanSpp::where('bulan', $bulan)->where('tahun', $tahun)->sum('total_dibayar');
            $lunas = TagihanSpp::where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'lunas')->count();
            $total = TagihanSpp::where('bulan', $bulan)->where('tahun', $tahun)->count();

            $rekap[] = [
                'bulan' => $bulan,
                'nama_bulan' => ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$bulan],
                'total_tagihan' => $totalTagihan,
                'total_bayar' => $totalBayar,
                'lunas' => $lunas,
                'total' => $total,
                'persentase' => $total > 0 ? round(($lunas / $total) * 100, 1) : 0,
            ];
        }

        return view('laporan.rekap-bulanan', compact('rekap', 'tahun'));
    }
}
