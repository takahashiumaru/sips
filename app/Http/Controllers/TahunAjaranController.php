<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')
            ->orderByDesc('tahun_mulai')
            ->get();

        return view('tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun-ajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_akhir' => 'required|integer|min:2000|max:2100|gte:tahun_mulai',
        ]);

        $validated['is_aktif'] = $request->has('is_aktif');

        if ($validated['is_aktif']) {
            TahunAjaran::query()->update(['is_aktif' => false]);
        }

        TahunAjaran::create($validated);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:20|unique:tahun_ajaran,nama,' . $tahunAjaran->id,
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_akhir' => 'required|integer|min:2000|max:2100|gte:tahun_mulai',
        ]);

        $validated['is_aktif'] = $request->has('is_aktif');

        if ($validated['is_aktif']) {
            TahunAjaran::where('id', '!=', $tahunAjaran->id)->update(['is_aktif' => false]);
        }

        $tahunAjaran->update($validated);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->kelas()->count() > 0 || $tahunAjaran->tarifSpp()->count() > 0 || $tahunAjaran->tagihanSpp()->count() > 0) {
            return back()->with('error', 'Tahun ajaran ini tidak dapat dihapus karena masih digunakan oleh data lain.');
        }

        $tahunAjaran->delete();
        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    public function toggleStatus(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->is_aktif) {
            $tahunAjaran->update(['is_aktif' => false]);
        } else {
            TahunAjaran::query()->update(['is_aktif' => false]);
            $tahunAjaran->update(['is_aktif' => true]);
        }

        return back()->with('success', 'Status keaktifan tahun ajaran berhasil diubah.');
    }
}
