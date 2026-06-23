<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\TagihanSpp;
use App\Models\Kwitansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $pembayaran = Pembayaran::with(['tagihan.siswa.kelas', 'dicatatOleh'])
            ->when($request->status, fn($q, $v) => $q->where('status_verifikasi', $v))
            ->when($request->metode, fn($q, $v) => $q->where('metode_bayar', $v))
            ->when($request->search, fn($q, $s) => $q->whereHas('tagihan.siswa', fn($sq) => $sq->where('nama_lengkap', 'like', "%{$s}%")))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create(TagihanSpp $tagihan)
    {
        $tagihan->load('siswa.kelas');
        return view('pembayaran.create', compact('tagihan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tagihan_id' => 'required|exists:tagihan_spp,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'metode_bayar' => 'required|in:tunai,transfer,qris',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $tagihan = TagihanSpp::findOrFail($validated['tagihan_id']);

        $pembayaran = Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'jumlah_bayar' => $validated['jumlah_bayar'],
            'tanggal_bayar' => now(),
            'metode_bayar' => $validated['metode_bayar'],
            'status_verifikasi' => 'terverifikasi',
            'dicatat_oleh' => auth()->id(),
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_at' => now(),
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
        ]);

        // Update tagihan
        $totalBayar = $tagihan->pembayaran()->where('status_verifikasi', 'terverifikasi')->sum('jumlah_bayar');
        $tagihan->update([
            'total_dibayar' => $totalBayar,
            'status' => $totalBayar >= $tagihan->jumlah_tagihan ? 'lunas' : 'belum_bayar',
        ]);

        // Auto-generate kwitansi
        Kwitansi::create([
            'nomor_kwitansi' => Kwitansi::generateNomor(),
            'pembayaran_id' => $pembayaran->id,
            'dicetak_oleh' => auth()->id(),
            'dicetak_at' => now(),
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dicatat dan kwitansi dibuat.');
    }

    public function uploadBukti(Request $request, TagihanSpp $tagihan)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::create([
            'tagihan_id' => $tagihan->id,
            'jumlah_bayar' => $tagihan->sisa_tagihan,
            'tanggal_bayar' => now(),
            'metode_bayar' => 'transfer',
            'bukti_transfer' => $path,
            'status_verifikasi' => 'pending',
            'dicatat_oleh' => auth()->id(),
        ]);

        $tagihan->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Bukti transfer berhasil diupload. Menunggu verifikasi bendahara.');
    }

    public function verifikasi(Request $request, Pembayaran $pembayaran)
    {
        $validated = $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $pembayaran->update([
            'status_verifikasi' => $validated['status'],
            'catatan_verifikasi' => $validated['catatan_verifikasi'],
            'diverifikasi_oleh' => auth()->id(),
            'diverifikasi_at' => now(),
        ]);

        $tagihan = $pembayaran->tagihan;

        if ($validated['status'] === 'terverifikasi') {
            $totalBayar = $tagihan->pembayaran()->where('status_verifikasi', 'terverifikasi')->sum('jumlah_bayar');
            $tagihan->update([
                'total_dibayar' => $totalBayar,
                'status' => $totalBayar >= $tagihan->jumlah_tagihan ? 'lunas' : 'belum_bayar',
            ]);

            // Auto-generate kwitansi
            if (!$pembayaran->kwitansi) {
                Kwitansi::create([
                    'nomor_kwitansi' => Kwitansi::generateNomor(),
                    'pembayaran_id' => $pembayaran->id,
                    'dicetak_oleh' => auth()->id(),
                    'dicetak_at' => now(),
                ]);
            }
        } else {
            // Ditolak — kembalikan status tagihan
            $tagihan->update(['status' => 'belum_bayar']);
        }

        $statusLabel = $validated['status'] === 'terverifikasi' ? 'diverifikasi' : 'ditolak';
        return back()->with('success', "Pembayaran berhasil {$statusLabel}.");
    }
}
