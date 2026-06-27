@extends('layouts.app')

@section('page_title', 'Pembayaran & Verifikasi')
@section('page_subtitle', 'Tinjau riwayat pembayaran masuk dan kelola verifikasi bukti transfer wali murid')

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/50 shadow-sm overflow-hidden card-premium" x-data="{ 
    showVerifyModal: false, 
    verifyActionUrl: '', 
    verifyStudentName: '', 
    verifyAmount: '', 
    verifyImage: '' 
}">
    <!-- Filter Section -->
    <div class="p-6 border-b border-blue-50/50">
        <form action="{{ route('pembayaran.index') }}" method="GET" data-filter-form class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <div class="relative w-full lg:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" name="search" value="{{ request('search') }}" data-auto-submit-search autocomplete="off"
                    class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all form-input-premium font-semibold"
                    placeholder="Cari nama / NIS siswa...">
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full sm:w-44 px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Status Verifikasi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                    <option value="terverifikasi" {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi (Disetujui)</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>

                <select name="metode" onchange="this.form.submit()"
                    class="block w-full sm:w-36 px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Metode</option>
                    <option value="tunai" {{ request('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                    <option value="transfer" {{ request('metode') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                    <option value="qris" {{ request('metode') == 'qris' ? 'selected' : '' }}>QRIS</option>
                </select>

                @if(request('search') || request('status') || request('metode'))
                    <a href="{{ route('pembayaran.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold shrink-0">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-50 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                    <th class="px-6 py-4">Siswa & Kelas</th>
                    <th class="px-6 py-4">Periode SPP</th>
                    <th class="px-6 py-4">Jumlah Bayar</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Bukti</th>
                    <th class="px-6 py-4">Dicatat Oleh</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/50 text-slate-700">
                @forelse ($pembayaran as $p)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            <div class="w-8.5 h-8.5 rounded-xl bg-blue-50 text-brand-hover font-bold flex items-center justify-center border border-blue-100/50 text-xs shrink-0">
                                {{ strtoupper(substr($p->tagihan->siswa->nama_lengkap ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="truncate max-w-[140px]">{{ $p->tagihan->siswa->nama_lengkap ?? '-' }}</span>
                                <span class="text-[9px] text-slate-400 font-bold uppercase">Kelas: {{ $p->tagihan->siswa->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-600">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($p->tagihan->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $p->tagihan->tahun }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 font-mono">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-500 uppercase tracking-wider text-[9px] font-bold">{{ $p->metode_pembayaran }}</td>
                        <td class="px-6 py-4">
                            @if($p->bukti_transfer)
                                <a href="{{ asset('storage/' . $p->bukti_transfer) }}" target="_blank" class="text-xs font-bold text-brand-hover hover:underline flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Lihat Bukti
                                </a>
                            @else
                                <span class="text-slate-400 font-medium">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-semibold">{{ $p->dicatatOleh->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if ($p->status_verifikasi === 'terverifikasi')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                            @elseif ($p->status_verifikasi === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($p->status_verifikasi === 'pending' && (auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah()))
                                    <button type="button" 
                                        @click="
                                            verifyActionUrl = '{{ route('pembayaran.verifikasi', $p) }}';
                                            verifyStudentName = '{{ $p->tagihan->siswa->nama_lengkap ?? '-' }}';
                                            verifyAmount = 'Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}';
                                            verifyImage = '{{ $p->bukti_transfer ? asset('storage/' . $p->bukti_transfer) : '' }}';
                                            showVerifyModal = true;
                                        "
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-brand-light text-white text-[9px] font-bold rounded-xl hover:bg-brand-hover transition-all shadow-md shadow-brand-light/10 btn-premium">
                                        Verifikasi
                                    </button>
                                @endif

                                @if ($p->status_verifikasi === 'terverifikasi')
                                    <a href="{{ route('kwitansi.download', $p) }}" data-pjax="false" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50/40 border border-blue-100/30 text-blue-500 hover:text-slate-800 text-[9px] font-bold rounded-xl transition-all btn-premium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Kwitansi
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 font-semibold">Tidak ada data transaksi pembayaran ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($pembayaran->hasPages())
        <div class="px-6 py-4 border-t border-blue-50/50 bg-blue-50/10">
            {{ $pembayaran->links() }}
        </div>
    @endif

    <!-- Verifikasi Modal (Alpine.js) -->
    <div x-show="showVerifyModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/30 backdrop-blur-xs"
         style="display: none;"
         x-transition.opacity>
         <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-blue-100/50 flex flex-col max-h-[90vh]" @click.away="showVerifyModal = false">
            <div class="flex items-center justify-between border-b border-blue-50 pb-3.5 mb-5 shrink-0">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Verifikasi Bukti Pembayaran</h3>
                <button @click="showVerifyModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                <div class="p-3.5 bg-blue-50/20 border border-blue-100/50 rounded-2xl space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-blue-500/80 font-bold uppercase text-[9px] tracking-wider">Nama Siswa</span>
                        <span class="text-slate-800 font-bold" x-text="verifyStudentName"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-blue-500/80 font-bold uppercase text-[9px] tracking-wider">Nominal Transfer</span>
                        <span class="text-slate-800 font-bold font-mono" x-text="verifyAmount"></span>
                    </div>
                </div>

                <div x-show="verifyImage">
                    <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Lampiran Bukti Transfer</span>
                    <div class="border border-blue-100/50 rounded-2xl overflow-hidden bg-slate-100 flex items-center justify-center p-2">
                        <img :src="verifyImage" alt="Bukti Transfer" class="max-h-60 max-w-full object-contain rounded-xl">
                    </div>
                </div>
            </div>

            <form :action="verifyActionUrl" method="POST" class="pt-4 border-t border-blue-50 shrink-0 space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="catatan_verifikasi" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Catatan Verifikasi (Opsional)</label>
                    <textarea name="catatan_verifikasi" id="catatan_verifikasi" rows="2"
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Tambahkan alasan jika menolak, atau catatan persetujuan"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="showVerifyModal = false" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 text-xs font-bold rounded-xl transition-colors btn-premium">Batal</button>
                    <button type="submit" name="status" value="ditolak" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-red-100 btn-premium">Tolak</button>
                    <button type="submit" name="status" value="terverifikasi" class="px-4 py-2 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-brand-light/10 btn-premium">Setujui & Verifikasi</button>
                </div>
            </form>
         </div>
    </div>
</div>
@endsection
