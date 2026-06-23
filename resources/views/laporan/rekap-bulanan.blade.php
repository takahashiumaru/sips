@extends('layouts.app')

@section('page_title', 'Rekap Bulanan SPP')
@section('page_subtitle', 'Tinjauan rekapitulasi data penagihan, penerimaan, dan persentase kelunasan bulanan')

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/60 shadow-sm overflow-hidden card-premium">
    <!-- Filter Section -->
    <div class="p-6 border-b border-blue-50/50 flex items-center justify-between">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider font-sans">Rekapitulasi Tahun {{ $tahun }}</h2>
        <form action="{{ route('laporan.rekap') }}" method="GET">
            <select name="tahun" onchange="this.form.submit()"
                class="block w-36 px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                @for ($y = date('Y') - 3; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-100/40 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-400/80 uppercase tracking-wider bg-blue-50/20">
                    <th class="px-6 py-4">Bulan</th>
                    <th class="px-6 py-4">Target Penagihan</th>
                    <th class="px-6 py-4">Realisasi Penerimaan</th>
                    <th class="px-6 py-4">Sisa Tunggakan</th>
                    <th class="px-6 py-4 text-center">Jumlah Tagihan</th>
                    <th class="px-6 py-4 text-center">Jumlah Lunas</th>
                    <th class="px-6 py-4 text-right">Tingkat Kelunasan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/30 text-slate-700">
                @php
                    $grandTotalTagihan = 0;
                    $grandTotalBayar = 0;
                    $grandCountTagihan = 0;
                    $grandCountLunas = 0;
                @endphp
                @foreach ($rekap as $r)
                    @php
                        $grandTotalTagihan += $r['total_tagihan'];
                        $grandTotalBayar += $r['total_bayar'];
                        $grandCountTagihan += $r['total'];
                        $grandCountLunas += $r['lunas'];
                        $sisa = $r['total_tagihan'] - $r['total_bayar'];
                    @endphp
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-800">
                            {{ \Carbon\Carbon::create()->month($r['bulan'])->translatedFormat('F') }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-500 font-mono">Rp {{ number_format($r['total_tagihan'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 font-mono">Rp {{ number_format($r['total_bayar'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-rose-600 font-mono">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center font-bold text-slate-500 font-mono">{{ $r['total'] }} item</td>
                        <td class="px-6 py-4 text-center font-bold text-slate-500 font-mono">{{ $r['lunas'] }} item</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                <span class="bg-blue-50/50 text-brand-light border border-blue-100 px-2 py-0.5 rounded-lg text-[9px] font-bold font-mono">{{ $r['persentase'] }}%</span>
                                <div class="w-12 bg-blue-100/60 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                    <div class="bg-brand-light h-1.5 rounded-full" style="width: {{ $r['persentase'] }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $grandSisa = $grandTotalTagihan - $grandTotalBayar;
                    $grandPersentase = $grandCountTagihan > 0 ? round(($grandCountLunas / $grandCountTagihan) * 100, 1) : 0;
                @endphp
                <tr class="bg-blue-50/30 font-bold text-xs text-slate-900 border-t border-blue-100/50">
                    <td class="px-6 py-4 font-bold text-slate-900">GRAND TOTAL</td>
                    <td class="px-6 py-4 font-bold font-mono">Rp {{ number_format($grandTotalTagihan, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-emerald-600 font-mono">Rp {{ number_format($grandTotalBayar, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-rose-600 font-mono">Rp {{ number_format($grandSisa, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-center font-bold font-mono">{{ $grandCountTagihan }} item</td>
                    <td class="px-6 py-4 text-center font-bold font-mono">{{ $grandCountLunas }} item</td>
                    <td class="px-6 py-4 text-right">
                        <span class="bg-brand-light text-white px-2.5 py-0.5 rounded-full text-[9px] font-bold font-mono shadow-md shadow-brand-light/10">{{ $grandPersentase }}%</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
