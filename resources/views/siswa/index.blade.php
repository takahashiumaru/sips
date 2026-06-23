@extends('layouts.app')

@section('page_title', 'Data Siswa')
@section('page_subtitle', 'Manajemen informasi data siswa, kelas, wali murid, dan status akademik')

@section('actions')
@if(auth()->user()->isAdmin())
<a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium animate-fade-in">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Siswa</span>
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden card-premium">
    <!-- Filter Section -->
    <div class="p-6 border-b border-slate-100/80">
        <form action="{{ route('siswa.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="relative w-full lg:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-9.5 pr-4 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all form-input-premium font-semibold"
                    placeholder="Cari NIS atau nama siswa...">
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <select name="kelas_id" onchange="this.form.submit()"
                    class="block w-full sm:w-44 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()"
                    class="block w-full sm:w-36 px-3.5 py-2 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light focus:bg-white transition-all font-semibold form-input-premium">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                    <option value="keluar" {{ request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                </select>

                @if(request('search') || request('kelas_id') || request('status'))
                    <a href="{{ route('siswa.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold shrink-0 transition-colors">Reset</a>
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
                    <th>Nama Lengkap</th>
                    <th>Kelas</th>
                    <th>L/P</th>
                    <th>Wali Murid</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="font-bold font-mono text-slate-900 tracking-tight">
                            <div class="flex items-center gap-2">
                                <span>{{ $s->nis }}</span>
                                <button onclick="copyToClipboard('{{ $s->nis }}', this)" class="text-slate-400 hover:text-brand-light copy-btn focus:outline-none p-1 rounded-md" title="Salin NIS">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="font-bold text-slate-800">{{ $s->nama_lengkap }}</td>
                        <td class="font-bold text-brand-hover">{{ $s->kelas->nama_kelas ?? '-' }}</td>
                        <td class="text-slate-500 font-semibold">{{ $s->jenis_kelamin }}</td>
                        <td class="text-slate-500 font-semibold">{{ $s->waliMurid->name ?? '-' }}</td>
                        <td>
                            @if ($s->status === 'aktif')
                                <span class="badge-pill-premium bg-emerald-50 text-emerald-700 border border-emerald-100/60">
                                    <span class="dot bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            @elseif ($s->status === 'lulus')
                                <span class="badge-pill-premium bg-blue-50 text-blue-700 border border-blue-100/60">
                                    <span class="dot bg-blue-500"></span>
                                    Lulus
                                </span>
                            @elseif ($s->status === 'pindah')
                                <span class="badge-pill-premium bg-amber-50 text-amber-700 border border-amber-100/60">
                                    <span class="dot bg-amber-500"></span>
                                    Pindah
                                </span>
                            @else
                                <span class="badge-pill-premium bg-rose-50 text-rose-700 border border-rose-100/60">
                                    <span class="dot bg-rose-500"></span>
                                    Keluar
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('siswa.show', $s) }}" class="p-2 bg-slate-50 border border-slate-200/60 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-all btn-premium shadow-xs" title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('siswa.edit', $s) }}" class="p-2 bg-slate-50 border border-slate-200/60 text-slate-500 hover:text-brand-hover hover:bg-blue-50/60 hover:border-blue-100 rounded-xl transition-all btn-premium shadow-xs" title="Ubah Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('siswa.destroy', $s) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus data siswa ini?', 'Hapus Data Siswa', 'danger')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-slate-50 border border-slate-200/60 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl transition-all btn-premium shadow-xs" title="Hapus Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 font-semibold">Tidak ada data siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($siswa->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $siswa->links() }}
        </div>
    @endif
</div>
@endsection
