@extends('layouts.app')

@section('page_title', 'Ubah Tahun Ajaran')
@section('page_subtitle', 'Perbarui data tahun ajaran akademik dan status keaktifannya')

@section('content')
<div class="form-page-card bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Edit Tahun Ajaran</h2>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tahun-ajaran.update', $tahunAjaran) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="form-grid-wide">
            <div>
                <label for="nama" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nama Tahun Ajaran</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $tahunAjaran->nama) }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Contoh: 2024/2025">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="tahun_mulai" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Mulai</label>
                    <input type="number" name="tahun_mulai" id="tahun_mulai" value="{{ old('tahun_mulai', $tahunAjaran->tahun_mulai) }}" required
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Contoh: 2024">
                </div>
                <div>
                    <label for="tahun_akhir" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Akhir</label>
                    <input type="number" name="tahun_akhir" id="tahun_akhir" value="{{ old('tahun_akhir', $tahunAjaran->tahun_akhir) }}" required
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Contoh: 2025">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 py-2 px-1">
            <input type="checkbox" name="is_aktif" id="is_aktif" value="1" {{ old('is_aktif', $tahunAjaran->is_aktif) ? 'checked' : '' }}
                class="w-4.5 h-4.5 text-brand-light border-slate-300 rounded-md focus:ring-brand-light/30 transition-all cursor-pointer">
            <label for="is_aktif" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Jadikan sebagai Tahun Ajaran Aktif</label>
        </div>

        <div class="form-action-bar">
            <a href="{{ route('tahun-ajaran.index') }}" class="form-secondary-action">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Perbarui Tahun Ajaran
            </button>
        </div>
    </form>
</div>
@endsection
