@extends('layouts.app')

@section('page_title', 'Laporan Tunggakan')
@section('page_subtitle', 'Analisis dan tinjauan daftar siswa yang memiliki sisa tunggakan pembayaran SPP')

@section('actions')
<a href="{{ route('laporan.tunggakan.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
    </svg>
    <span>Ekspor PDF</span>
</a>
@endsection

@section('content')
<!-- Ringkasan Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Siswa Menunggak</span>
            <span class="text-xl font-black text-slate-900 block">{{ number_format($ringkasan['total_siswa'], 0, ',', '.') }} orang</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Siswa aktif terhitung</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50/60 text-blue-600 flex items-center justify-center border border-blue-100/30 shrink-0 shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Nilai Tunggakan</span>
            <span class="text-xl font-black text-rose-600 block font-mono">Rp {{ number_format($ringkasan['total_tunggakan'], 0, ',', '.') }}</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Akumulasi sisa tagihan</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100/50 shrink-0 shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs flex items-center justify-between card-premium">
        <div class="space-y-1">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Total Item Tagihan</span>
            <span class="text-xl font-black text-slate-900 block">{{ number_format($ringkasan['total_tagihan'], 0, ',', '.') }} item</span>
            <span class="text-[9px] font-semibold text-slate-400 block">Item tagihan belum lunas</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50/60 text-blue-600 flex items-center justify-center border border-blue-100/30 shrink-0 shadow-xs">
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
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" onchange="this.form.submit()"
                    class="block w-full sm:w-32 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
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
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Periode</th>
                    <th>Jumlah Tagihan</th>
                    <th>Telah Dibayar</th>
                    <th>Sisa Tunggakan</th>
                    <th>Nama Wali</th>
                    <th class="text-right">Kontak Wali</th>
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
                        <td class="font-bold text-slate-500">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="font-bold text-slate-800">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($t->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $t->tahun }}
                        </td>
                        <td class="font-bold text-slate-500 font-mono">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="font-bold text-emerald-600 font-mono">Rp {{ number_format($t->total_dibayar, 0, ',', '.') }}</td>
                        <td class="font-bold text-rose-600 font-mono">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="text-slate-500 font-semibold">{{ $t->siswa->waliMurid->name ?? '-' }}</td>
                        <td>
                            <div class="flex items-center justify-end">
                                @if($t->siswa->waliMurid && $t->siswa->waliMurid->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $t->siswa->waliMurid->phone) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100/70 font-bold text-[9px] rounded-xl transition-all border border-emerald-100/60 btn-premium shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>WhatsApp</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 font-semibold">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 font-semibold">Luar biasa! Tidak ada data siswa menunggak untuk filter ini.</td>
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
