<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RekapBulananExport;
use App\Exports\TunggakanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function tunggakan(Request $request)
    {
        $filterBulan = $request->has('bulan') ? $request->input('bulan') : now()->month;
        $filterTahun = $request->has('tahun') ? $request->input('tahun') : now()->year;

        $query = TagihanSpp::with(['siswa.kelas', 'siswa.waliMurid'])
            ->where('status', '!=', 'lunas')
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($filterBulan !== '', fn($q) => $q->where('bulan', $filterBulan))
            ->when($filterTahun !== '', fn($q) => $q->where('tahun', $filterTahun));

        $tunggakan = $query->orderByDesc(DB::raw('jumlah_tagihan - total_dibayar'))->paginate(20)->withQueryString();

        $ringkasan = [
            'total_siswa' => (clone $query)->select('siswa_id')->distinct()->count('siswa_id'),
            'total_tunggakan' => (clone $query)->sum(DB::raw('jumlah_tagihan - total_dibayar')),
            'total_tagihan' => (clone $query)->count(),
        ];

        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('laporan.tunggakan', compact('tunggakan', 'ringkasan', 'kelas', 'filterBulan', 'filterTahun'));
    }

    public function exportPdf(Request $request)
    {
        $filterBulan = $request->has('bulan') ? $request->input('bulan') : now()->month;
        $filterTahun = $request->has('tahun') ? $request->input('tahun') : now()->year;

        $tunggakan = TagihanSpp::with(['siswa.kelas', 'siswa.waliMurid'])
            ->where('status', '!=', 'lunas')
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($filterBulan !== '', fn($q) => $q->where('bulan', $filterBulan))
            ->when($filterTahun !== '', fn($q) => $q->where('tahun', $filterTahun))
            ->orderByDesc(DB::raw('jumlah_tagihan - total_dibayar'))
            ->get();

        $totalTunggakan = $tunggakan->sum(fn($t) => $t->jumlah_tagihan - $t->total_dibayar);

        $pdf = Pdf::loadView('laporan.pdf.tunggakan', compact('tunggakan', 'totalTunggakan'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Laporan-Tunggakan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filterBulan = $request->has('bulan') ? $request->input('bulan') : now()->month;
        $filterTahun = $request->has('tahun') ? $request->input('tahun') : now()->year;

        $tunggakan = TagihanSpp::with(['siswa.kelas', 'siswa.waliMurid'])
            ->where('status', '!=', 'lunas')
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($filterBulan !== '', fn($q) => $q->where('bulan', $filterBulan))
            ->when($filterTahun !== '', fn($q) => $q->where('tahun', $filterTahun))
            ->orderByDesc(DB::raw('jumlah_tagihan - total_dibayar'))
            ->get();

        return Excel::download(
            new TunggakanExport($tunggakan),
            'Laporan-Tunggakan-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    public function rekapBulanan(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        $rekap = $this->getRekapData($tahun);
        return view('laporan.rekap-bulanan', compact('rekap', 'tahun'));
    }

    public function exportRekapPdf(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        $rekap = $this->getRekapData($tahun);

        $pdf = Pdf::loadView('laporan.pdf.rekap-bulanan', compact('rekap', 'tahun'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('Rekap-Bulanan-SPP-' . $tahun . '-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportRekapExcel(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;
        $rekap = $this->getRekapData($tahun);

        return Excel::download(
            new \App\Exports\RekapBulananExport($rekap, $tahun),
            'Rekap-Bulanan-SPP-' . $tahun . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private function getRekapData(int $tahun): array
    {
        $tagihanPerBulan = TagihanSpp::where('tahun', $tahun)
            ->withSum([
                'pembayaran as pembayaran_terverifikasi_total' => fn($q) => $q->where('status_verifikasi', 'terverifikasi'),
            ], 'jumlah_bayar')
            ->get()
            ->groupBy('bulan');

        $rekap = [];
        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $tagihanBulan = $tagihanPerBulan->get($bulan, collect());
            $totalTagihan = $tagihanBulan->sum(fn(TagihanSpp $tagihan) => (float) $tagihan->jumlah_tagihan);
            $totalBayar = $tagihanBulan->sum(fn(TagihanSpp $tagihan) => (float) ($tagihan->pembayaran_terverifikasi_total ?? 0));
            $lunas = $tagihanBulan->filter(function (TagihanSpp $tagihan) {
                return (float) ($tagihan->pembayaran_terverifikasi_total ?? 0) >= (float) $tagihan->jumlah_tagihan;
            })->count();
            $total = $tagihanBulan->count();

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
        return $rekap;
    }
}
