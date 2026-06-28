@extends('layouts.app')

@section('page_title', 'Edit Kelas')
@section('page_subtitle', 'Perbarui data kelas, tingkat akademik, dan wali kelas')

@section('content')
<div class="form-page-card bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Edit Kelas</h2>
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

    <form action="{{ route('kelas.update', $kela) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="form-grid-wide">
            <div>
                <label for="tahun_ajaran_id" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $kela->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }}{{ $ta->is_aktif ? ' - Aktif' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tingkat" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tingkat</label>
                <select name="tingkat" id="tingkat" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('tingkat', $kela->tingkat) == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-grid-wide">
            <div>
                <label for="nama_kelas" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Contoh: 1A atau 2B">
            </div>

            <div>
                <label for="wali_kelas" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Wali Kelas (Nama Guru)</label>
                <input type="text" name="wali_kelas" id="wali_kelas" value="{{ old('wali_kelas', $kela->wali_kelas) }}"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Masukkan nama wali kelas">
            </div>
        </div>

        <div class="form-action-bar">
            <a href="{{ route('kelas.index') }}" class="form-secondary-action">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Perbarui Kelas
            </button>
        </div>
    </form>
</div>
@endsection
