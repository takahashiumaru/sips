<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\TagihanSpp;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PortalWaliController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = $user->siswa()->with('kelas')->get();

        $tagihanBelumLunas = TagihanSpp::whereIn('siswa_id', $siswa->pluck('id'))
            ->where('status', '!=', 'lunas')
            ->count();

        $totalTunggakan = TagihanSpp::whereIn('siswa_id', $siswa->pluck('id'))
            ->where('status', '!=', 'lunas')
            ->selectRaw('SUM(jumlah_tagihan - total_dibayar) as total')
            ->value('total') ?? 0;

        return view('portal.index', compact('siswa', 'tagihanBelumLunas', 'totalTunggakan'));
    }

    public function tagihan()
    {
        $user = auth()->user();
        $siswaIds = $user->siswa()->pluck('id');

        $tagihan = TagihanSpp::with(['siswa.kelas'])
            ->whereIn('siswa_id', $siswaIds)
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(20);

        return view('portal.tagihan', compact('tagihan'));
    }

    public function riwayat()
    {
        $user = auth()->user();
        $siswaIds = $user->siswa()->pluck('id');

        $pembayaran = Pembayaran::with(['tagihan.siswa.kelas', 'kwitansi'])
            ->whereHas('tagihan', fn($q) => $q->whereIn('siswa_id', $siswaIds))
            ->orderByDesc('tanggal_bayar')
            ->paginate(20);

        return view('portal.riwayat', compact('pembayaran'));
    }
}
