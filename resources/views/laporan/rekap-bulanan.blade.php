@extends('layouts.app')

@section('page_title', 'Rekap Bulanan SPP')
@section('page_subtitle', 'Tinjauan rekapitulasi data penagihan, penerimaan, dan persentase kelunasan bulanan')

@section('actions')
<div class="flex items-center gap-2.5">
    <a href="{{ route('laporan.rekap.excel', ['tahun' => $tahun]) }}" download data-pjax="false" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-600/10 btn-premium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span>Ekspor Excel</span>
    </a>
    <a href="{{ route('laporan.rekap.pdf', ['tahun' => $tahun]) }}" download data-pjax="false" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        <span>Ekspor PDF</span>
    </a>
</div>
@endsection

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
                            <div class="flex items-center justify-end gap-2.5 settlement-meter">
                                <span class="settlement-badge bg-blue-50/50 text-brand-light border border-blue-100 px-2 py-0.5 rounded-lg text-[9px] font-bold font-mono">{{ $r['persentase'] }}%</span>
                                <div class="settlement-track w-12 bg-blue-100/60 rounded-full h-1.5 overflow-hidden hidden sm:block">
                                    <div class="settlement-fill bg-brand-light h-1.5 rounded-full" style="width: {{ $r['persentase'] }}%"></div>
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
                <tr class="rekap-grand-total-row bg-blue-50/30 font-bold text-xs text-slate-900 border-t border-blue-100/50">
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
