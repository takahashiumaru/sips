@extends('layouts.app')

@section('page_title', 'Detail Siswa')
@section('page_subtitle', 'Tinjauan lengkap data pribadi, wali, dan riwayat pembayaran SPP siswa')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ showUploadModal: false, uploadActionUrl: '', uploadStudentName: '', uploadPeriod: '', uploadAmount: '' }">
    <!-- Left Column: Personal and Wali Info -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-2xl bg-blue-50 text-brand-hover font-bold text-2xl flex items-center justify-center border border-blue-100/50 mb-4">
                    {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                </div>
                <h2 class="text-sm font-bold text-slate-800 leading-tight">{{ $siswa->nama_lengkap }}</h2>
                <p class="text-slate-400 text-xs font-mono font-semibold mt-1">NIS: {{ $siswa->nis }}</p>
                <span class="mt-2.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-brand-hover border border-blue-100">
                    Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}
                </span>
            </div>

            <div class="mt-8 pt-6 border-t border-blue-50/50 space-y-4">
                <div>
                    <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Jenis Kelamin</span>
                    <span class="text-xs font-bold text-slate-700 mt-0.5 block">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Tanggal Lahir</span>
                    <span class="text-xs font-bold text-slate-700 mt-0.5 block font-mono">{{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Alamat</span>
                    <span class="text-xs font-semibold text-slate-700 mt-0.5 block leading-relaxed">{{ $siswa->alamat ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Status Akademik</span>
                    <div class="mt-1">
                        @if ($siswa->status === 'aktif')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        @elseif ($siswa->status === 'lulus')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Lulus
                            </span>
                        @elseif ($siswa->status === 'pindah')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Pindah
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Keluar
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Wali Murid Card -->
        <div class="bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-blue-50 pb-3.5 mb-4">Informasi Wali Murid</h3>
            @if($siswa->waliMurid)
                <div class="space-y-4">
                    <div>
                        <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Nama Orang Tua / Wali</span>
                        <span class="text-xs font-bold text-slate-700 mt-0.5 block">{{ $siswa->waliMurid->name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">Email</span>
                        <span class="text-xs font-bold text-slate-700 mt-0.5 block">{{ $siswa->waliMurid->email }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest">No. Telepon / WhatsApp</span>
                        <span class="text-xs font-bold text-slate-700 mt-0.5 block font-mono">{{ $siswa->waliMurid->phone ?? '-' }}</span>
                    </div>
                </div>
            @else
                <div class="py-4 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Wali murid belum dihubungkan</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Tagihan SPP History -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Total Tunggakan Summary -->
        <div class="bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm flex items-center justify-between card-premium">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Sisa Tunggakan SPP</span>
                <span class="text-xl font-black text-red-600 mt-1 block font-mono">Rp {{ number_format($siswa->getTotalTunggakan(), 0, ',', '.') }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Billing History List -->
        <div class="bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
            <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-6">Riwayat Tagihan SPP</h3>
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle px-6">
                    <table class="min-w-full divide-y divide-blue-50 text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                                <th class="py-3.5 px-4">Bulan & Tahun</th>
                                <th class="py-3.5 px-4">Jumlah Tagihan</th>
                                <th class="py-3.5 px-4">Telah Dibayar</th>
                                <th class="py-3.5 px-4">Sisa Tagihan</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-blue-50/50 text-slate-700">
                            @forelse ($siswa->tagihanSpp as $tagihan)
                                <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-slate-800">
                                        @php
                                            $bulanName = \Carbon\Carbon::create()->month($tagihan->bulan)->translatedFormat('F');
                                        @endphp
                                        {{ $bulanName }} {{ $tagihan->tahun }}
                                    </td>
                                    <td class="py-3.5 px-4 font-bold text-slate-500 font-mono">Rp {{ number_format($tagihan->jumlah_tagihan, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-emerald-600 font-mono">Rp {{ number_format($tagihan->total_dibayar, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900 font-mono">Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4">
                                        @if ($tagihan->status === 'lunas')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Lunas
                                            </span>
                                        @elseif ($tagihan->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Verifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Belum Lunas
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if ($tagihan->status === 'lunas')
                                                <!-- Download receipt if exists -->
                                                @php
                                                    $pembayaranLunas = $tagihan->pembayaran()->where('status_verifikasi', 'terverifikasi')->latest()->first();
                                                @endphp
                                                @if ($pembayaranLunas)
                                                    <a href="{{ route('kwitansi.download', $pembayaranLunas) }}" data-pjax="false" class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50/40 border border-blue-100/30 hover:bg-blue-50 hover:border-blue-100/50 text-blue-500 hover:text-slate-800 text-[9px] font-bold rounded-lg transition-colors btn-premium">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                        </svg>
                                                        <span>Kwitansi</span>
                                                    </a>
                                                @endif
                                            @elseif (auth()->user()->isWaliMurid())
                                                @if ($tagihan->status === 'menunggu_verifikasi')
                                                    <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider px-2.5 py-1 bg-blue-50/30 border border-blue-100/60 rounded-xl">Diproses</span>
                                                @else
                                                    <button type="button" 
                                                        @click="
                                                            uploadActionUrl = '{{ route('pembayaran.upload', $tagihan) }}';
                                                            uploadStudentName = '{{ $siswa->nama_lengkap }}';
                                                            uploadPeriod = '{{ $bulanName }} {{ $tagihan->tahun }}';
                                                            uploadAmount = 'Rp {{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}';
                                                            showUploadModal = true;
                                                        "
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-brand-light text-white text-[9px] font-bold rounded-xl hover:bg-brand-hover transition-all shadow-md shadow-brand-light/10 btn-premium">
                                                        Upload Bukti
                                                    </button>
                                                @endif
                                            @elseif (auth()->user()->isAdmin() || auth()->user()->isBendahara())
                                                <a href="{{ route('pembayaran.create', $tagihan) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-brand-light hover:bg-brand-hover text-white text-[9px] font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
                                                    Bayar
                                                 </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 px-4 text-center text-slate-400 font-semibold">Siswa ini belum memiliki data tagihan SPP.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Upload Bukti Modal (Alpine.js) -->
    <div x-show="showUploadModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/30 backdrop-blur-xs text-left"
         style="display: none;"
         x-transition.opacity>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-blue-100/60" @click.away="showUploadModal = false">
            <div class="flex items-center justify-between border-b border-blue-100/50 pb-3.5 mb-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider font-sans">Upload Bukti Transfer</h3>
                <button @click="showUploadModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form :action="uploadActionUrl" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="p-3.5 bg-blue-50/30 border border-blue-100/60 rounded-2xl space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Nama Siswa</span>
                        <span class="text-slate-800 font-bold" x-text="uploadStudentName"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Periode</span>
                        <span class="text-slate-800 font-bold" x-text="uploadPeriod"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider">Nominal Transfer</span>
                        <span class="text-brand-light font-bold font-mono text-[13px]" x-text="uploadAmount"></span>
                    </div>
                </div>

                <div>
                    <label for="bukti_transfer" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Pilih File Foto Bukti Transfer</label>
                    <input type="file" name="bukti_transfer" id="bukti_transfer" required accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-light hover:file:bg-brand-100 transition-colors">
                    <p class="text-[9px] text-slate-400 mt-2 font-bold uppercase tracking-wider">Format berkas: JPG, JPEG, PNG. Ukuran maksimal: 5 MB.</p>
                </div>

                <div class="pt-3 flex justify-end gap-3">
                    <button type="button" @click="showUploadModal = false" class="px-4 py-2 bg-blue-50/30 hover:bg-blue-100/50 border border-blue-100/60 text-blue-500 text-xs font-bold rounded-xl transition-colors btn-premium">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-brand-light/10 btn-premium">Kirim Bukti</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
