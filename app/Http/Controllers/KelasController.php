<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $tahunAktif = TahunAjaran::aktif()->first();
        
        $selectedTahunId = $request->get('tahun_ajaran_id', $tahunAktif->id ?? null);
        $selectedTahun = $selectedTahunId && $selectedTahunId !== 'all' 
            ? $tahunAjaran->firstWhere('id', $selectedTahunId) 
            : null;

        $kelas = Kelas::with('tahunAjaran')
            ->when($selectedTahunId && $selectedTahunId !== 'all', fn($q) => $q->where('tahun_ajaran_id', $selectedTahunId))
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();

        return view('kelas.index', compact('kelas', 'tahunAjaran', 'tahunAktif', 'selectedTahun', 'selectedTahunId'));
    }

    public function create()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $tahunAktif = TahunAjaran::aktif()->first();

        return view('kelas.create', compact('tahunAjaran', 'tahunAktif'));
    }

    public function edit(Kelas $kela)
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();

        return view('kelas.edit', compact('kela', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|in:1,2,3,4,5,6',
            'wali_kelas' => 'nullable|string|max:100',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'nama_kelas' => 'required|string|max:10',
            'tingkat' => 'required|integer|in:1,2,3,4,5,6',
            'wali_kelas' => 'nullable|string|max:100',
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
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
