<?php

namespace App\Http\Controllers;

use App\Models\Kwitansi;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KwitansiController extends Controller
{
    public function download(Pembayaran $pembayaran)
    {
        $pembayaran->load(['tagihan.siswa.kelas', 'dicatatOleh', 'kwitansi']);

        if (!$pembayaran->kwitansi) {
            // Auto-create kwitansi if it doesn't exist (e.g. for seeded or legacy data)
            $kwitansi = Kwitansi::create([
                'nomor_kwitansi' => Kwitansi::generateNomor(),
                'pembayaran_id' => $pembayaran->id,
                'dicetak_oleh' => auth()->id() ?? $pembayaran->dicatat_oleh,
                'dicetak_at' => now(),
            ]);
            $pembayaran->setRelation('kwitansi', $kwitansi);
        }

        $qrContent = route('kwitansi.verify', $pembayaran->kwitansi->nomor_kwitansi);

        $pdf = Pdf::loadView('kwitansi.template', [
            'pembayaran' => $pembayaran,
            'kwitansi' => $pembayaran->kwitansi,
            'siswa' => $pembayaran->tagihan->siswa,
            'tagihan' => $pembayaran->tagihan,
            'qrContent' => $qrContent,
        ]);

        $pdf->setPaper('a5', 'landscape');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('isHtml5ParserEnabled', true);

        return $pdf->download("Kwitansi-{$pembayaran->kwitansi->nomor_kwitansi}.pdf");
    }

    public function verify(string $nomor)
    {
        $kwitansi = Kwitansi::where('nomor_kwitansi', $nomor)->with(['pembayaran.tagihan.siswa'])->first();

        if (!$kwitansi) {
            return view('kwitansi.verify', ['valid' => false, 'nomor' => $nomor]);
        }

        return view('kwitansi.verify', [
            'valid' => true,
            'kwitansi' => $kwitansi,
            'nomor' => $nomor,
        ]);
    }
}
