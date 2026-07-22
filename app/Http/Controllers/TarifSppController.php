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

    public function create()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();
        $tahunAktif = TahunAjaran::aktif()->first();

        return view('tarif.create', compact('tahunAjaran', 'tahunAktif'));
    }

    public function edit(TarifSpp $tarifSpp)
    {
        $tahunAjaran = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('tahun_mulai')->get();

        return view('tarif.edit', compact('tarifSpp', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat' => 'required|integer|in:1,2,3,4,5,6',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $exists = TarifSpp::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('tingkat', $validated['tingkat'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Tarif untuk tingkat ini sudah ada pada tahun ajaran tersebut.');
        }

        $validated['created_by'] = auth()->id();
        TarifSpp::create($validated);

        return redirect()->route('tarif.index')->with('success', 'Tarif SPP berhasil ditambahkan.');
    }

    public function update(Request $request, TarifSpp $tarifSpp)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat' => 'required|integer|in:1,2,3,4,5,6',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $exists = TarifSpp::where('tahun_ajaran_id', $validated['tahun_ajaran_id'])
            ->where('tingkat', $validated['tingkat'])
            ->where('id', '!=', $tarifSpp->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Tarif untuk tingkat ini sudah ada pada tahun ajaran tersebut.');
        }

        $tarifSpp->update($validated);

        return redirect()->route('tarif.index')->with('success', 'Tarif SPP berhasil diperbarui.');
    }
}
