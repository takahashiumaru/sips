@extends('layouts.app')

@section('page_title', 'Laporan Tunggakan')
@section('page_subtitle', 'Analisis dan tinjauan daftar siswa yang memiliki sisa tunggakan pembayaran SPP')

@section('actions')
<div class="flex flex-wrap items-center gap-2.5">
    <a href="{{ route('laporan.tunggakan.excel', request()->query()) }}" download data-pjax="false" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-emerald-600/10 btn-premium">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <span>Ekspor Excel</span>
    </a>
    <a href="{{ route('laporan.tunggakan.pdf', request()->query()) }}" download data-pjax="false" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
        </svg>
        <span>Ekspor PDF</span>
    </a>
</div>
@endsection

@section('content')
<!-- Ringkasan Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium arrears-student-summary">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Siswa Menunggak</span>
            <span class="text-xl font-black text-slate-900 block metric-value-blue">{{ number_format($ringkasan['total_siswa'], 0, ',', '.') }} orang</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Siswa aktif terhitung</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50/60 text-blue-600 flex items-center justify-center border border-blue-100/30 shrink-0 shadow-xs stat-icon-blue">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium arrears-amount-summary">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Nilai Tunggakan</span>
            <span class="text-xl font-black text-rose-600 block font-mono metric-value-rose">Rp {{ number_format($ringkasan['total_tunggakan'], 0, ',', '.') }}</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Akumulasi sisa tagihan</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100/50 shrink-0 shadow-xs stat-icon-rose">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium invoice-item-summary">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Item Tagihan</span>
            <span class="text-xl font-black text-slate-900 block metric-value-cyan">{{ number_format($ringkasan['total_tagihan'], 0, ',', '.') }} item</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Item tagihan belum lunas</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50/60 text-blue-600 flex items-center justify-center border border-blue-100/30 shrink-0 shadow-xs stat-icon-cyan">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden card-premium">
    <!-- Filter Section -->
    <div class="p-6 border-b border-slate-100/80">
        <form action="{{ route('laporan.tunggakan') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <select name="kelas_id" onchange="this.form.submit()"
                    class="block w-full sm:w-44 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>

                <select name="bulan" onchange="this.form.submit()"
                    class="block w-full sm:w-36 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (string) $filterBulan === (string) $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" onchange="this.form.submit()"
                    class="block w-full sm:w-32 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ (string) $filterTahun === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                @if(request('kelas_id') || request('bulan') || request('tahun'))
                    <a href="{{ route('laporan.tunggakan') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold shrink-0 transition-colors">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th data-i18n="table.nis">NIS</th>
                    <th data-i18n="table.studentName">Nama Siswa</th>
                    <th data-i18n="table.class">Kelas</th>
                    <th data-i18n="table.period">Periode</th>
                    <th data-i18n="table.billingAmount">Jumlah Tagihan</th>
                    <th data-i18n="table.amountPaid">Telah Dibayar</th>
                    <th data-i18n="table.remainingArrears">Sisa Tunggakan</th>
                    <th data-i18n="table.guardianName">Nama Wali</th>
                    <th class="text-right" data-i18n="table.guardianContact">Kontak Wali</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tunggakan as $t)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="font-bold font-mono text-slate-900 tracking-tight">
                            <div class="flex items-center gap-2">
                                <span>{{ $t->siswa->nis ?? '-' }}</span>
                                @if($t->siswa)
                                    <button onclick="copyToClipboard('{{ $t->siswa->nis }}', this)" class="text-slate-400 hover:text-brand-light copy-btn focus:outline-none p-1 rounded-md" title="Salin NIS">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                        <td class="font-bold text-slate-800">{{ $t->siswa->nama_lengkap ?? '-' }}</td>
                        <td class="font-semibold text-slate-550">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="font-bold text-slate-700">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($t->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $t->tahun }}
                        </td>
                        <td class="font-bold text-slate-500 font-mono">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="font-bold text-emerald-600 font-mono">Rp {{ number_format($t->total_dibayar, 0, ',', '.') }}</td>
                        <td class="font-bold text-red-600 font-mono">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="text-slate-500 font-semibold">{{ $t->siswa->waliMurid->name ?? '-' }}</td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($t->siswa && $t->siswa->waliMurid && $t->siswa->waliMurid->phone)
                                    @php
                                        $cleanPhone = preg_replace('/[^0-9]/', '', $t->siswa->waliMurid->phone);
                                        if (str_starts_with($cleanPhone, '0')) {
                                            $cleanPhone = '62' . substr($cleanPhone, 1);
                                        }
                                        $waMessage = rawurlencode("Halo Bapak/Ibu " . $t->siswa->waliMurid->name . ", kami dari MI Al-Haq menginformasikan bahwa terdapat tunggakan SPP untuk siswa " . $t->siswa->nama_lengkap . " (Kelas: " . ($t->siswa->kelas->nama_kelas ?? '-') . ") periode " . $bulanName . " " . $t->tahun . " sebesar Rp " . number_format($t->sisa_tagihan, 0, ',', '.') . ". Mohon segera menyelesaikan pembayaran. Terima kasih.");
                                        $waUrl = "https://wa.me/" . $cleanPhone . "?text=" . $waMessage;
                                    @endphp
                                    <a href="{{ $waUrl }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 border border-emerald-100 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 text-[10px] font-extrabold rounded-lg transition-all btn-premium shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.003 5.324 5.328 0 11.859 0c3.161.001 6.132 1.23 8.37 3.469 2.237 2.239 3.466 5.211 3.466 8.377-.003 6.538-5.328 11.86-11.859 11.86-2.004-.001-3.973-.51-5.729-1.479L0 24zm6.59-4.846c1.62.962 3.21 1.6 5.269 1.602 5.451 0 9.886-4.437 9.888-9.891.002-2.64-1.022-5.123-2.887-6.99-1.865-1.867-4.348-2.892-6.995-2.893-5.454 0-9.89 4.437-9.892 9.893-.001 2.083.547 4.116 1.585 5.897L2.482 21.52l4.165-1.091zM17.86 14c-.33-.164-1.94-.954-2.24-1.064-.3-.11-.52-.164-.74.164-.22.329-.85 1.064-1.04 1.283-.19.22-.38.247-.71.082-1.68-.83-2.9-1.46-3.86-3.11-.25-.436.25-.4.71-1.32.08-.164.04-.31-.02-.438-.06-.128-.52-1.25-.71-1.71-.19-.453-.38-.39-.52-.39-.136 0-.29-.012-.45-.012-.16 0-.42.06-.64.3-.22.24-.84.82-.84 2.01 0 1.185.86 2.33 1.04 2.58.19.24 1.7 2.6 4.11 3.64 1.43.62 2.29.68 3.11.56.92-.14 1.94-.79 2.21-1.52.27-.73.27-1.36.19-1.52-.08-.16-.3-.26-.63-.42z"/>
                                        </svg>
                                        WhatsApp
                                    </a>
                                @else
                                    <span class="text-slate-400 font-semibold">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 font-semibold" data-i18n="table.emptyArrearsReport">Luar biasa! Tidak ada data siswa menunggak untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($tunggakan->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $tunggakan->links() }}
        </div>
    @endif
</div>
@endsection
