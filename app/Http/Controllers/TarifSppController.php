<?php

namespace App\Http\Controllers;

use App\Models\TarifSpp;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TarifSppController extends Controller
{
    public function index()
    {
        $tahunAktif = TahunAjaran::aktif()->first();
        $tarif = TarifSpp::with('tahunAjaran')
            ->when($tahunAktif, fn($q) => $q->where('tahun_ajaran_id', $tahunAktif->id))
            ->orderBy('tingkat')
            ->get();
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();

        return view('tarif.index', compact('tarif', 'tahunAjaran', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat' => 'required|integer|in:7,8,9',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $exists = TarifSpp::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('tingkat', $validated['tingkat'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tarif untuk tingkat ini sudah ada pada tahun ajaran tersebut.');
        }

        $validated['created_by'] = auth()->id();
        TarifSpp::create($validated);

        return back()->with('success', 'Tarif SPP berhasil ditambahkan.');
    }

    public function update(Request $request, TarifSpp $tarifSpp)
    {
        $validated = $request->validate([
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $tarifSpp->update($validated);

        return back()->with('success', 'Tarif SPP berhasil diperbarui.');
    }
}
