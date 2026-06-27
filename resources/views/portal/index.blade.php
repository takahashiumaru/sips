@extends('layouts.app')

@section('page_title', 'Dashboard Wali Murid')
@section('page_subtitle', 'Selamat datang! Di sini Anda dapat memantau status tagihan SPP dan riwayat pembayaran putra-putri Anda.')

@section('content')
<!-- Wali stats summary -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-blue-100/60 shadow-sm flex items-center justify-between transition-all hover:shadow-md card-premium">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Tagihan Belum Lunas</span>
            <span class="text-xl font-black text-slate-900 mt-1 block">{{ $tagihanBelumLunas }} Periode</span>
            <span class="text-[10px] text-slate-400 font-bold mt-1 block uppercase tracking-wider">Segera selesaikan pembayaran sebelum jatuh tempo</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-blue-100/60 shadow-sm flex items-center justify-between transition-all hover:shadow-md card-premium">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block">Total Sisa Tunggakan</span>
            <span class="text-xl font-black text-red-600 mt-1 block font-mono">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
            <span class="text-[10px] text-slate-400 font-bold mt-1 block uppercase tracking-wider">Jumlah keseluruhan nominal yang belum dibayar</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Daftar Anak Terdaftar -->
<div class="bg-white rounded-2xl border border-blue-100/60 p-6 shadow-sm mb-8 card-premium">
    <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-6">Daftar Siswa (Putra/Putri Anda)</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($siswa as $s)
            <div class="border border-blue-100/60 bg-blue-50/30 p-5 rounded-2xl flex flex-col gap-4 relative overflow-hidden transition-all hover:shadow-sm hover:border-blue-200/80 card-premium">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-brand-light/10 text-brand-light font-bold text-lg flex items-center justify-center border border-brand-light/10 shrink-0">
                        {{ strtoupper(substr($s->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xs font-bold text-slate-800 truncate">{{ $s->nama_lengkap }}</h3>
                        <p class="text-[9px] text-slate-400 font-bold font-mono tracking-tight uppercase mt-0.5">NIS: {{ $s->nis }}</p>
                    </div>
                </div>

                <div class="border-t border-blue-100/50 pt-3.5 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Kelas Akademik</span>
                        <span class="text-slate-700 font-bold">{{ $s->kelas->nama_kelas ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Wali Kelas</span>
                        <span class="text-slate-700 font-bold">{{ $s->kelas->wali_kelas ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Tunggakan SPP</span>
                        <span class="text-red-600 font-bold font-mono">Rp {{ number_format($s->getTotalTunggakan(), 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('siswa.show', $s) }}" class="w-full mt-2 py-2 bg-white border border-blue-100 hover:border-brand-light/20 hover:bg-brand-50 hover:text-brand-light text-slate-600 text-[10px] font-bold rounded-xl transition-all text-center btn-premium">
                    Tinjau Riwayat SPP Siswa
                </a>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-slate-400 font-semibold">Belum ada data siswa terhubung dengan akun Anda. Hubungi Administrator.</div>
        @endforelse
    </div>
</div>

<!-- Informasi Rekening Bank Sekolah / Instruksi -->
<div class="bg-blue-50/30 border border-blue-100/60 rounded-2xl p-6">
    <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Panduan & Petunjuk Pembayaran</h3>
    <p class="text-xs text-blue-700 leading-relaxed mb-4">Pembayaran SPP dapat diselesaikan secara tunai melalui loket sekolah, atau melalui Transfer Bank ke Rekening Resmi Sekolah berikut:</p>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-xs">
        <div class="bg-white border border-blue-100 p-4 rounded-xl card-premium">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Nama Bank</span>
            <span class="text-xs font-bold text-slate-800 block mt-0.5">Bank Pembangunan Daerah (BJB)</span>
        </div>
        <div class="bg-white border border-blue-100 p-4 rounded-xl card-premium">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Nomor Rekening</span>
            <span class="text-xs font-bold font-mono text-slate-800 block mt-0.5">0011-2233-44556</span>
        </div>
        <div class="bg-white border border-blue-100 p-4 rounded-xl card-premium">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Atas Nama</span>
            <span class="text-xs font-bold text-slate-800 block mt-0.5">SD Negeri 1 Kelapa Dua</span>
        </div>
    </div>
    <p class="text-[10px] text-blue-600/80 font-bold uppercase mt-4 tracking-wider">*Setelah melakukan transfer, pastikan Anda masuk ke menu <strong>"Tagihan SPP Anak"</strong> dan mengupload bukti transfer untuk diverifikasi oleh pihak sekolah.</p>
</div>
@endsection
