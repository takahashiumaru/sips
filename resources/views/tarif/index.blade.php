@extends('layouts.app')

@section('page_title', 'Tarif SPP')
@section('page_subtitle', 'Kelola besaran biaya pembayaran SPP bulanan per tingkat kelas')

@section('actions')
@if(auth()->user()->isAdmin())
<a href="{{ route('tarif.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Tarif</span>
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/50 shadow-sm overflow-hidden card-premium">
    <div class="p-6 border-b border-blue-50/50 flex items-center justify-between">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tarif Pembayaran SPP (Tahun Ajaran: {{ $tahunAktif->nama ?? '-' }})</h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-50 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                    <th class="px-6 py-4">Tingkat Kelas</th>
                    <th class="px-6 py-4">Tahun Ajaran</th>
                    <th class="px-6 py-4">Nominal Bulanan</th>
                    <th class="px-6 py-4">Keterangan</th>
                    @if(auth()->user()->isAdmin())
                        <th class="px-6 py-4 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/50 text-slate-700">
                @forelse ($tarif as $t)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">Tingkat {{ $t->tingkat }}</td>
                        <td class="px-6 py-4 text-slate-500 font-semibold">{{ $t->tahunAjaran->nama ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm font-mono">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-400 font-semibold">{{ $t->keterangan ?? '-' }}</td>
                        @if(auth()->user()->isAdmin())
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tarif.edit', $t) }}"
                                    class="p-2 bg-blue-50/40 border border-blue-100/30 text-blue-500 hover:text-brand-hover hover:bg-blue-50 hover:border-blue-100/50 rounded-xl transition-all btn-premium action-btn-edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-semibold">Tidak ada data tarif SPP untuk tahun ajaran aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
