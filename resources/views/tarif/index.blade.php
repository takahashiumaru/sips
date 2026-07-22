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
        <table class="table-premium min-w-[760px]">
            <thead>
                <tr>
                    <th data-i18n="table.classLevel">Tingkat Kelas</th>
                    <th data-i18n="table.academicYear">Tahun Ajaran</th>
                    <th data-i18n="table.monthlyAmount">Nominal Bulanan</th>
                    <th data-i18n="table.description">Keterangan</th>
                    @if(auth()->user()->isAdmin())
                        <th class="w-24 text-right" data-i18n="table.action">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($tarif as $t)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="font-bold text-slate-900">Tingkat {{ $t->tingkat }}</td>
                        <td class="text-slate-500 font-semibold">{{ $t->tahunAjaran->nama ?? '-' }}</td>
                        <td class="font-bold text-slate-900 text-sm font-mono">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="text-slate-400 font-semibold">{{ $t->keterangan ?? '-' }}</td>
                        @if(auth()->user()->isAdmin())
                            <td class="w-24">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tarif.edit', $t) }}"
                                        class="inline-flex h-9 w-9 items-center justify-center bg-blue-50/40 border border-blue-100/30 text-blue-500 hover:text-brand-hover hover:bg-blue-50 hover:border-blue-100/50 rounded-xl transition-all btn-premium action-btn-edit"
                                        title="Ubah Tarif">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('tarif.destroy', $t) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus tarif SPP ini?', 'Hapus Tarif SPP', 'danger')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="inline-flex h-9 w-9 items-center justify-center bg-blue-50/40 border border-blue-100/30 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl transition-all btn-premium action-btn-delete"
                                            title="Hapus Tarif">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="py-8 text-center text-slate-400 font-semibold" data-i18n="table.emptyTariffs">Tidak ada data tarif SPP untuk tahun ajaran aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
