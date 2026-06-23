<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $tahunAktif = TahunAjaran::aktif()->first();
        $kelas = Kelas::with('tahunAjaran')
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('kelas.index', compact('kelas', 'tahunAjaran', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|in:7,8,9',
            'wali_kelas' => 'nullable|string|max:100',
        ]);

        Kelas::create($validated);

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|in:7,8,9',
            'wali_kelas' => 'nullable|string|max:100',
        ]);

        $kela->update($validated);

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        if ($kela->siswa()->count() > 0) {
            return back()->with('error', 'Kelas masih memiliki siswa terdaftar.');
        }

        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
