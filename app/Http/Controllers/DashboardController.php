<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TagihanSpp;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        $totalSiswa = Siswa::aktif()->count();

        $tagihanBulanIni = TagihanSpp::where('bulan', $bulanIni)->where('tahun', $tahunIni);
        $lunasBulanIni = (clone $tagihanBulanIni)->where('status', 'lunas')->count();
        $menunggakBulanIni = (clone $tagihanBulanIni)->where('status', 'belum_bayar')->count();
        $totalTagihanBulanIni = (clone $tagihanBulanIni)->count();

        $persentaseLunas = $totalTagihanBulanIni > 0
            ? round(($lunasBulanIni / $totalTagihanBulanIni) * 100, 1) : 0;

        $totalTunggakan = TagihanSpp::where('status', '!=', 'lunas')
            ->sum(DB::raw('jumlah_tagihan - total_dibayar'));

        $transaksiTerbaru = Pembayaran::with(['tagihan.siswa.kelas'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $tunggakanTertinggi = Siswa::aktif()
            ->with('kelas')
            ->withSum(['tagihanSpp as total_tunggakan' => function ($q) {
                $q->where('status', '!=', 'lunas');
            }], DB::raw('jumlah_tagihan - total_dibayar'))
            ->having('total_tunggakan', '>', 0)
            ->orderByDesc('total_tunggakan')
            ->limit(5)
            ->get();

        // Tren bulanan (tahun ini)
        $trenBulanan = TagihanSpp::where('tahun', $tahunIni)
            ->where('status', 'lunas')
            ->select('bulan', DB::raw('SUM(total_dibayar) as total'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $trenData = [];
        for ($i = 1; $i <= 12; $i++) {
            $trenData[] = $trenBulanan[$i] ?? 0;
        }

        return view('dashboard.index', compact(
            'totalSiswa',
            'lunasBulanIni',
            'menunggakBulanIni',
            'persentaseLunas',
            'totalTunggakan',
            'transaksiTerbaru',
            'tunggakanTertinggi',
            'trenData',
        ));
    }
}
