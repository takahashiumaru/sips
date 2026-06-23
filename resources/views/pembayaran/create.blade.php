@extends('layouts.app')

@section('page_title', 'Catat Pembayaran SPP')
@section('page_subtitle', 'Catat transaksi pembayaran langsung (tunai) atau transfer lunas untuk siswa')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl border border-blue-100/60 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-100/50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Catat Transaksi Pembayaran</h2>
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

    <form action="{{ route('pembayaran.store') }}" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">

        <div class="p-4 bg-blue-50/30 border border-blue-100/60 rounded-xl space-y-2.5 mb-2 text-xs">
            <div class="flex justify-between">
                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Nama Siswa</span>
                <span class="text-slate-800 font-bold">{{ $tagihan->siswa->nama_lengkap ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">NIS / Kelas</span>
                <span class="text-slate-800 font-bold font-mono">{{ $tagihan->siswa->nis ?? '-' }} / {{ $tagihan->siswa->kelas->nama_kelas ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Periode Tagihan</span>
                <span class="text-slate-800 font-bold">
                    @php
                        $bulanName = \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F');
                    @endphp
                    {{ $bulanName }} {{ $tagihan->tahun }}
                </span>
            </div>
            <div class="border-t border-blue-100/50 my-1 pt-2.5 flex justify-between font-bold">
                <span class="text-slate-500 font-bold uppercase text-[9px] tracking-wider">Jumlah Harus Dibayar</span>
                <span class="text-brand-light text-sm font-mono">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="jumlah_bayar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Jumlah Bayar (IDR)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 text-xs font-bold font-mono">Rp</span>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" value="{{ old('jumlah_bayar', $tagihan->sisa_tagihan) }}" required min="1" max="{{ $tagihan->sisa_tagihan }}" step="1"
                        class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-bold font-mono form-input-premium"
                        placeholder="Masukkan nominal bayar">
                </div>
            </div>

            <div>
                <label for="metode_bayar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Metode Pembayaran</label>
                <select name="metode_bayar" id="metode_bayar" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="tunai" {{ old('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai (Langsung / Cash)</option>
                    <option value="transfer" {{ old('metode_bayar') === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qris" {{ old('metode_bayar') === 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>
            </div>
        </div>

        <div>
            <label for="catatan_verifikasi" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan Penerimaan / Keterangan</label>
            <textarea name="catatan_verifikasi" id="catatan_verifikasi" rows="3"
                class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                placeholder="Tambahkan catatan seperti nama penyetor atau nomor referensi bank">{{ old('catatan_verifikasi') }}</textarea>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Simpan & Cetak Kwitansi
            </button>
        </div>
    </form>
</div>
@endsection
