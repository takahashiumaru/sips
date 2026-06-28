@extends('layouts.app')

@section('page_title', 'Edit Tarif SPP')
@section('page_subtitle', 'Perbarui besaran biaya pembayaran SPP bulanan')

@section('content')
<div class="form-page-card bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Edit Tarif</h2>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tarif.update', $tarifSpp) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="form-grid-wide">
            <div>
                <label for="tahun_ajaran_id" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" id="tahun_ajaran_id" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @foreach ($tahunAjaran as $ta)
                        <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $tarifSpp->tahun_ajaran_id) == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }}{{ $ta->is_aktif ? ' - Aktif' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tingkat" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tingkat Kelas</label>
                <select name="tingkat" id="tingkat" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('tingkat', $tarifSpp->tingkat) == $i ? 'selected' : '' }}>Tingkat {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="form-grid-wide">
            <div>
                <label for="jumlah" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nominal Bulanan (IDR)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-blue-400 text-xs font-bold font-mono">Rp</span>
                    <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah', (int) $tarifSpp->jumlah) }}" required min="0" step="1"
                        class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-bold font-mono form-input-premium"
                        placeholder="Masukkan nominal">
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $tarifSpp->keterangan) }}"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Masukkan keterangan">
            </div>
        </div>

        <div class="form-action-bar">
            <a href="{{ route('tarif.index') }}" class="form-secondary-action">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Perbarui Tarif
            </button>
        </div>
    </form>
</div>
@endsection
