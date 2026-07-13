@extends('layouts.app')

@section('page_title', 'Data Kelas')
@section('page_subtitle', 'Manajemen data kelas, tingkat akademik, dan wali kelas')

@section('actions')
<a href="{{ route('kelas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Kelas</span>
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden card-premium">
    <div class="p-6 border-b border-slate-100/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h2 class="text-xs font-extrabold text-slate-800 uppercase tracking-widest">
            Daftar Kelas (Tahun Ajaran: {{ $selectedTahunId === 'all' ? 'Semua' : ($selectedTahun->nama ?? '-') }})
        </h2>
        
        <form action="{{ route('kelas.index') }}" method="GET" class="flex items-center gap-2">
            <label for="tahun_ajaran_id" class="text-[10px] font-bold text-slate-400 uppercase tracking-wider shrink-0">Tahun Ajaran</label>
            <select name="tahun_ajaran_id" id="tahun_ajaran_id" onchange="this.form.submit()"
                class="block w-full sm:w-44 px-3 py-1.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                <option value="all" {{ $selectedTahunId === 'all' ? 'selected' : '' }}>Semua</option>
                @foreach ($tahunAjaran as $ta)
                    <option value="{{ $ta->id }}" {{ $selectedTahunId == $ta->id ? 'selected' : '' }}>
                        {{ $ta->nama }}{{ $ta->is_aktif ? ' (Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="table-premium">
            <thead>
                <tr>
                    <th data-i18n="table.level">Tingkat</th>
                    <th data-i18n="table.className">Nama Kelas</th>
                    <th data-i18n="table.classTeacher">Wali Kelas</th>
                    <th data-i18n="table.academicYear">Tahun Ajaran</th>
                    <th data-i18n="table.studentCount">Jumlah Siswa</th>
                    <th class="text-right" data-i18n="table.action">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelas as $k)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="font-bold text-slate-900">Tingkat {{ $k->tingkat }}</td>
                        <td class="font-bold text-brand-hover text-sm">{{ $k->nama_kelas }}</td>
                        <td class="text-slate-500 font-semibold">{{ $k->wali_kelas ?? '-' }}</td>
                        <td class="text-slate-400 font-medium font-mono text-[11px]">{{ $k->tahunAjaran->nama ?? '-' }}</td>
                        <td>
                            <span class="badge-pill-premium bg-blue-50 text-blue-700 border border-blue-100/60 font-mono">
                                <span class="dot bg-blue-500"></span>
                                {{ $k->siswa()->count() }} Siswa
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('kelas.edit', $k) }}"
                                    class="p-2 bg-slate-50 border border-slate-200/60 text-slate-500 hover:text-brand-hover hover:bg-blue-50/60 hover:border-blue-100 rounded-xl transition-all btn-premium shadow-xs action-btn-edit" title="Ubah Data">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('kelas.destroy', $k) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus kelas ini?', 'Hapus Kelas', 'danger')" class="inline">
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
                        <td colspan="6" class="py-8 text-center text-slate-400 font-semibold" data-i18n="table.emptyClasses">Tidak ada data kelas ditemukan untuk tahun ajaran yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
