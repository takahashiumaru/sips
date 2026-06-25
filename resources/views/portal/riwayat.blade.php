@extends('layouts.app')

@section('page_title', 'Riwayat Pembayaran Anda')
@section('page_subtitle', 'Tinjau riwayat transaksi pembayaran SPP putra-putri Anda beserta status verifikasinya')

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/60 shadow-sm overflow-hidden card-premium">
    <div class="p-6 border-b border-blue-50/50 flex items-center justify-between">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tabel Riwayat Pembayaran Masuk</h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-100/40 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-400/80 uppercase tracking-wider bg-blue-50/20">
                    <th class="px-6 py-4">Siswa</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Periode SPP</th>
                    <th class="px-6 py-4">Jumlah Bayar</th>
                    <th class="px-6 py-4">Metode Bayar</th>
                    <th class="px-6 py-4">Tanggal Pembayaran</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Unduh Tanda Bukti</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/30 text-slate-700">
                @forelse ($pembayaran as $p)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            <div class="w-8.5 h-8.5 rounded-xl bg-brand-50 text-brand-light font-bold flex items-center justify-center border border-brand-light/10 text-xs shrink-0">
                                {{ strtoupper(substr($p->tagihan->siswa->nama_lengkap ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="truncate max-w-[140px]">{{ $p->tagihan->siswa->nama_lengkap ?? '-' }}</span>
                                <span class="text-[9px] text-slate-400 font-bold font-mono uppercase tracking-tight">NIS: {{ $p->tagihan->siswa->nis ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-500">{{ $p->tagihan->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($p->tagihan->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $p->tagihan->tahun }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900 font-mono">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-500 uppercase tracking-wider text-[9px] font-bold">{{ $p->metode_pembayaran }}</td>
                        <td class="px-6 py-4 text-slate-400 font-bold font-mono">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            @if ($p->status_verifikasi === 'terverifikasi')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Disetujui
                                </span>
                            @elseif ($p->status_verifikasi === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
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
                            @if ($p->status_verifikasi === 'terverifikasi')
                                <a href="{{ route('kwitansi.download', $p) }}" data-pjax="false" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50/30 border border-blue-100/60 text-blue-500 hover:text-white hover:bg-brand-light rounded-xl transition-all btn-premium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    <span>Unduh Kwitansi</span>
                                </a>
                            @else
                                <span class="text-slate-400 font-semibold tracking-wider text-[9px] uppercase">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-slate-400 font-semibold">Belum ada riwayat transaksi pembayaran masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($pembayaran->hasPages())
        <div class="px-6 py-4 border-t border-blue-100/40 bg-blue-50/10">
            {{ $pembayaran->links() }}
        </div>
    @endif
</div>
@endsection
