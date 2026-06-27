<?php

namespace App\Http\Controllers;

use App\Models\TagihanSpp;
use App\Models\TarifSpp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->search);
        $searchTerms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $filterBulan = $request->has('bulan') ? $request->input('bulan') : now()->month;
        $filterTahun = $request->has('tahun') ? $request->input('tahun') : now()->year;

        $tagihan = TagihanSpp::with(['siswa.kelas'])
            ->when($filterBulan !== '', fn($q) => $q->where('bulan', $filterBulan))
            ->when($filterTahun !== '', fn($q) => $q->where('tahun', $filterTahun))
            ->when($request->status, fn($q, $v) => $q->where('status', $v))
            ->when($request->kelas_id, fn($q, $v) => $q->whereHas('siswa', fn($sq) => $sq->where('kelas_id', $v)))
            ->when($search !== '', function ($q) use ($search, $searchTerms) {
                $q->whereHas('siswa', function ($sq) use ($search, $searchTerms) {
                    $sq->where(function ($student) use ($search, $searchTerms) {
                        $student->where('nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%")
                            ->orWhereHas('kelas', fn($kelas) => $kelas->where('nama_kelas', 'like', "%{$search}%"));

                        $student->orWhere(function ($name) use ($searchTerms) {
                            foreach ($searchTerms as $term) {
                                $name->where('nama_lengkap', 'like', "%{$term}%");
                            }
                        });
                    });
                });
            })
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->paginate(20)
            ->withQueryString();

        $kelas = \App\Models\Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('tagihan.index', compact('tagihan', 'kelas', 'filterBulan', 'filterTahun'));
    }

    public function create()
    {
        $siswa = Siswa::aktif()->with('kelas')->orderBy('nama_lengkap')->get();
        return view('tagihan.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $exists = TagihanSpp::where('siswa_id', $validated['siswa_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Tagihan untuk siswa ini bulan tersebut sudah ada.');
        }

        $tahunAjaran = TahunAjaran::aktif()->first();
        $validated['tahun_ajaran_id'] = $tahunAjaran->id;
        $validated['created_by'] = auth()->id();

        TagihanSpp::create($validated);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function generateMassal(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $tahunAjaran = TahunAjaran::aktif()->first();

        if (!$tahunAjaran) {
            return back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $tarifByTingkat = TarifSpp::where('tahun_ajaran_id', $tahunAjaran->id)
            ->get()
            ->keyBy('tingkat');
        $createdBy = auth()->id();
        $jatuhTempo = "{$tahun}-" . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '-10';
        $created = 0;
        $skipped = 0;

        Siswa::aktif()->with('kelas:id,tingkat')->chunkById(200, function ($siswaAktif) use ($bulan, $tahun, $tahunAjaran, $tarifByTingkat, $createdBy, $jatuhTempo, &$created, &$skipped) {
            $siswaIds = $siswaAktif->pluck('id');
            $existingSiswaIds = TagihanSpp::whereIn('siswa_id', $siswaIds)
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->pluck('siswa_id')
                ->all();
            $existingLookup = array_flip($existingSiswaIds);
            $now = now();
            $rows = [];

            foreach ($siswaAktif as $siswa) {
                if (isset($existingLookup[$siswa->id])) {
                    $skipped++;
                    continue;
                }

                $tarif = $tarifByTingkat->get($siswa->kelas->tingkat ?? null);
                if (!$tarif) {
                    $skipped++;
                    continue;
                }

                $rows[] = [
                    'siswa_id' => $siswa->id,
                    'tahun_ajaran_id' => $tahunAjaran->id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'jumlah_tagihan' => $tarif->jumlah,
                    'total_dibayar' => 0,
                    'status' => 'belum_bayar',
                    'jatuh_tempo' => $jatuhTempo,
                    'created_by' => $createdBy,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if ($rows !== []) {
                DB::transaction(fn() => TagihanSpp::insert($rows));
                $created += count($rows);
            }
        });

        return back()->with('success', "{$created} tagihan berhasil dibuat. {$skipped} di-skip (sudah ada/tanpa tarif).");
    }

    public function edit(TagihanSpp $tagihan)
    {
        $tagihan->load('siswa.kelas');
        return view('tagihan.edit', compact('tagihan'));
    }

    public function update(Request $request, TagihanSpp $tagihan)
    {
        $validated = $request->validate([
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $tagihan->update($validated);

        return redirect()->route('tagihan.index')->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(TagihanSpp $tagihan)
    {
        $tagihan->delete();
        return back()->with('success', 'Tagihan berhasil dihapus.');
    }
}
