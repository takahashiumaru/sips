@extends('layouts.app')

@section('page_title', 'Tahun Ajaran')
@section('page_subtitle', 'Manajemen data tahun ajaran akademik dan status keaktifan')

@section('actions')
<a href="{{ route('tahun-ajaran.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Tahun Ajaran</span>
</a>
@endsection

@section('content')
@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden card-premium">
    <div class="p-6 border-b border-slate-100/80 flex items-center justify-between">
        <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest">Daftar Tahun Ajaran</h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th>Nama Tahun Ajaran</th>
                    <th>Tahun Mulai</th>
                    <th>Tahun Akhir</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tahunAjaran as $ta)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="font-bold text-slate-900">{{ $ta->nama }}</td>
                        <td class="text-slate-500 font-semibold font-mono">{{ $ta->tahun_mulai }}</td>
                        <td class="text-slate-500 font-semibold font-mono">{{ $ta->tahun_akhir }}</td>
                        <td>
                            @if ($ta->is_aktif)
                                <span class="badge-pill-premium bg-emerald-50 text-emerald-700 border border-emerald-100/60 font-semibold">
                                    <span class="dot bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <form action="{{ route('tahun-ajaran.toggle', $ta) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-200 hover:bg-brand-light hover:text-white hover:border-brand-light text-slate-500 text-[10px] font-extrabold rounded-lg transition-all btn-premium shadow-xs">
                                        Set Aktif
                                    </button>
                                </form>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('tahun-ajaran.edit', $ta) }}"
                                    class="p-2 bg-slate-50 border border-slate-200/60 text-slate-500 hover:text-brand-hover hover:bg-blue-50/60 hover:border-blue-100 rounded-xl transition-all btn-premium shadow-xs action-btn-edit" title="Ubah Data">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('tahun-ajaran.destroy', $ta) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus tahun ajaran ini?', 'Hapus Tahun Ajaran', 'danger')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-50 border border-slate-200/60 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl transition-all btn-premium shadow-xs action-btn-delete" title="Hapus Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data tahun ajaran ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
