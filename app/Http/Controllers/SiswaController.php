<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::with(['kelas', 'waliMurid'])
            ->search($request->search)
            ->when($request->kelas_id, fn($q, $v) => $q->where('kelas_id', $v))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->orderBy('nama_lengkap')
            ->paginate(20)
            ->withQueryString();

        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $waliMurid = User::where('role', 'wali_murid')->orderBy('name')->get();
        return view('siswa.create', compact('kelas', 'waliMurid'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis',
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_murid_id' => 'nullable|exists:users,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'waliMurid', 'tagihanSpp' => function ($q) {
            $q->orderByDesc('tahun')->orderByDesc('bulan');
        }]);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $waliMurid = User::where('role', 'wali_murid')->orderBy('name')->get();
        return view('siswa.edit', compact('siswa', 'kelas', 'waliMurid'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nis' => 'required|string|max:20|unique:siswa,nis,' . $siswa->id,
            'nama_lengkap' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
            'wali_murid_id' => 'nullable|exists:users,id',
            'status' => 'required|in:aktif,lulus,pindah,keluar',
        ]);

        $siswa->update($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('success', 'Data siswa berhasil dihapus.');
    }
}
