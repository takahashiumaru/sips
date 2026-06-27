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
            ->get()
            ->filter(fn(Siswa $siswa) => (float) $siswa->total_tunggakan > 0)
            ->sortByDesc(fn(Siswa $siswa) => (float) $siswa->total_tunggakan)
            ->take(5)
            ->values();

        // Tren penerimaan kas tahun ini: hanya pembayaran yang benar-benar terverifikasi.
        $now = now();
        $trenBulanan = Pembayaran::where('status_verifikasi', 'terverifikasi')
            ->whereBetween('tanggal_bayar', [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ])
            ->get(['tanggal_bayar', 'jumlah_bayar'])
            ->groupBy(fn(Pembayaran $pembayaran) => $pembayaran->tanggal_bayar->month)
            ->map(fn($payments) => $payments->sum(fn(Pembayaran $pembayaran) => (float) $pembayaran->jumlah_bayar))
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
