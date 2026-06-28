@extends('layouts.app')

@section('page_title', 'Tagihan SPP Anak')
@section('page_subtitle', 'Tinjau tagihan aktif putra-putri Anda dan selesaikan pembayaran via transfer')

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/60 shadow-sm overflow-hidden card-premium" x-data="{ 
    showUploadModal: false, 
    uploadActionUrl: '', 
    uploadStudentName: '', 
    uploadPeriod: '', 
    uploadAmount: '' 
}">
    <div class="p-6 border-b border-blue-50/50 flex items-center justify-between">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Tagihan SPP Putra / Putri Anda</h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-100/40 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-400/80 uppercase tracking-wider bg-blue-50/20">
                    <th class="px-6 py-4">Siswa</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Periode Tagihan</th>
                    <th class="px-6 py-4">Jumlah Tagihan</th>
                    <th class="px-6 py-4">Telah Dibayar</th>
                    <th class="px-6 py-4">Sisa Tagihan</th>
                    <th class="px-6 py-4">Jatuh Tempo</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/30 text-slate-700">
                @forelse ($tagihan as $t)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            <div class="w-8.5 h-8.5 rounded-xl bg-brand-50 text-brand-light font-bold flex items-center justify-center border border-brand-light/10 text-xs shrink-0">
                                {{ strtoupper(substr($t->siswa->nama_lengkap ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="truncate max-w-[140px]">{{ $t->siswa->nama_lengkap ?? '-' }}</span>
                                <span class="text-[9px] text-slate-400 font-bold font-mono uppercase tracking-tight">NIS: {{ $t->siswa->nis ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-500">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-850">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($t->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $t->tahun }}
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-500 font-mono">Rp {{ number_format($t->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 font-mono">Rp {{ number_format($t->total_dibayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 font-mono">Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-400 font-bold font-mono">{{ \Carbon\Carbon::parse($t->jatuh_tempo)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4">
                            @if ($t->status === 'lunas')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Lunas
                                </span>
                            @elseif ($t->status === 'menunggu_verifikasi')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Verifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100 animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Lunas
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if ($t->status === 'belum_bayar')
                                    <button type="button" 
                                        @click="
                                            uploadActionUrl = '{{ route('pembayaran.upload', $t) }}';
                                            uploadStudentName = '{{ $t->siswa->nama_lengkap ?? '-' }}';
                                            uploadPeriod = '{{ $bulanName }} {{ $t->tahun }}';
                                            uploadAmount = 'Rp {{ number_format($t->sisa_tagihan, 0, ',', '.') }}';
                                            showUploadModal = true;
                                        "
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-brand-light text-white text-[9px] font-bold rounded-xl hover:bg-brand-hover transition-all shadow-md shadow-brand-light/10 btn-premium">
                                        Upload Bukti
                                    </button>
                                @elseif ($t->status === 'lunas')
                                    @php
                                        $pembayaranLunas = $t->pembayaran()->where('status_verifikasi', 'terverifikasi')->latest()->first();
                                    @endphp
                                    @if ($pembayaranLunas)
                                        <a href="{{ route('kwitansi.download', $pembayaranLunas) }}" data-pjax="false" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50/30 border border-blue-100/60 text-blue-500 hover:text-white hover:bg-brand-light rounded-xl transition-all btn-premium">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            <span>Unduh Kwitansi</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="text-slate-400 font-bold uppercase text-[9px] tracking-wider px-2.5 py-1 bg-blue-50/30 border border-blue-100/60 rounded-xl">Diproses</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-slate-400 font-semibold">Tidak ada data tagihan SPP untuk putra / putri Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($tagihan->hasPages())
        <div class="px-6 py-4 border-t border-blue-100/40 bg-blue-50/10">
            {{ $tagihan->links() }}
        </div>
    @endif

    <!-- Upload Bukti Modal (Alpine.js) -->
    <div x-show="showUploadModal" 
         class="modal-premium-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/30 backdrop-blur-xs"
         style="display: none;"
         x-transition:enter="modal-backdrop-enter"
         x-transition:enter-start="modal-backdrop-enter-start"
         x-transition:enter-end="modal-backdrop-enter-end"
         x-transition:leave="modal-backdrop-leave"
         x-transition:leave-start="modal-backdrop-leave-start"
         x-transition:leave-end="modal-backdrop-leave-end">
        <div class="modal-premium-panel bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-blue-100/60" @click.away="showUploadModal = false">
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
