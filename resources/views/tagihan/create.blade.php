@extends('layouts.app')

@section('page_title', 'Buat Tagihan SPP')
@section('page_subtitle', 'Masukkan tagihan manual untuk siswa individu')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Buat Tagihan</h2>
        <a href="{{ route('tagihan.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
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

    <form action="{{ route('tagihan.store') }}" method="POST" class="space-y-5" x-data="{ 
        siswa: @js($siswa),
        selectedSiswaId: '',
        jumlahTagihan: 0,
        updateTarif() {
            const found = this.siswa.find(s => s.id == this.selectedSiswaId);
            if (found && found.kelas) {
                // Default SPP recommendation
                this.jumlahTagihan = 250000; 
            }
        }
    }">
        @csrf

        <div>
            <label for="siswa_id" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Pilih Siswa</label>
            <select name="siswa_id" id="siswa_id" required x-model="selectedSiswaId" @change="updateTarif()"
                class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                <option value="">Pilih Siswa...</option>
                @foreach ($siswa as $s)
                    <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                        {{ $s->nama_lengkap }} (NIS: {{ $s->nis }} - Kelas: {{ $s->kelas->nama_kelas ?? '-' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="bulan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Bulan Periode</label>
                <select name="bulan" id="bulan" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('bulan', date('m')) == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="tahun" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Periode</label>
                <select name="tahun" id="tahun" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ old('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="jumlah_tagihan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nominal Tagihan (IDR)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-blue-400 text-xs font-bold font-mono">Rp</span>
                    <input type="number" name="jumlah_tagihan" id="jumlah_tagihan" required min="0" step="1" x-model="jumlahTagihan"
                        class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-bold font-mono form-input-premium"
                        placeholder="Masukkan nominal tagihan">
                </div>
            </div>

            <div>
                <label for="jatuh_tempo" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tanggal Jatuh Tempo</label>
                <input type="date" name="jatuh_tempo" id="jatuh_tempo" value="{{ old('jatuh_tempo', date('Y-m-10')) }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium">
            </div>
        </div>

        <div>
            <label for="catatan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Catatan Keterangan (Opsional)</label>
            <textarea name="catatan" id="catatan" rows="2"
                class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                placeholder="Tambahkan catatan jika diperlukan">{{ old('catatan') }}</textarea>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Buat Tagihan
            </button>
        </div>
    </form>
</div>
@endsection
