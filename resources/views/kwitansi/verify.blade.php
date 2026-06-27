<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kwitansi SPP | SIP-SPP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans antialiased text-slate-800">
    <div class="w-full max-w-md bg-white rounded-3xl border border-slate-100 overflow-hidden card-premium shadow-2xl">
        
        <div class="bg-brand-primary p-6 text-center text-white relative">
            <h1 class="text-xs font-bold tracking-widest uppercase">Verifikasi Kwitansi Resmi</h1>
            <p class="text-[10px] text-slate-400 mt-1 uppercase font-bold tracking-wider">Sistem Informasi Pembayaran SPP (SIP-SPP)</p>
        </div>

        <div class="p-6 md:p-8">
            @if ($valid)
                <!-- SUCCESS STATE -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-base font-bold text-emerald-600 tracking-tight">Kwitansi Valid & Terverifikasi</h2>
                    <p class="text-slate-400 text-[10px] mt-1 font-semibold leading-relaxed">Dokumen ini merupakan tanda bukti pembayaran SPP sekolah yang sah</p>
                </div>

                <div class="mt-8 space-y-4 border-t border-slate-100 pt-6">
                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">No. Kwitansi</span>
                        <span class="text-slate-800 font-bold font-mono">{{ $kwitansi->nomor_kwitansi }}</span>
                    </div>

                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Nama Siswa</span>
                        <span class="text-slate-800 font-bold text-right">{{ $kwitansi->pembayaran->tagihan->siswa->nama_lengkap ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">NIS / Kelas</span>
                        <span class="text-slate-800 font-bold font-mono text-right">
                            {{ $kwitansi->pembayaran->tagihan->siswa->nis ?? '-' }} / {{ $kwitansi->pembayaran->tagihan->siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Periode SPP</span>
                        <span class="text-slate-800 font-bold text-right">
                            @php
                                $bulanName = \Carbon\Carbon::create()->month($kwitansi->pembayaran->tagihan->bulan)->translatedFormat('F');
                            @endphp
                            {{ $bulanName }} {{ $kwitansi->pembayaran->tagihan->tahun }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Jumlah Bayar</span>
                        <span class="text-brand-light font-bold text-sm font-mono text-right">
                            Rp {{ number_format($kwitansi->pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-start text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Tanggal Bayar</span>
                        <span class="text-slate-800 font-bold font-mono text-right">
                            {{ \Carbon\Carbon::parse($kwitansi->pembayaran->tanggal_bayar)->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold uppercase text-[9px] tracking-widest">Metode Bayar</span>
                        <span class="text-slate-800 font-bold uppercase tracking-wider text-[9px] bg-slate-50 border border-slate-200 px-2 py-0.5 rounded-lg">
                            {{ $kwitansi->pembayaran->metode_pembayaran }}
                        </span>
                    </div>
                </div>
            @else
                <!-- ERROR STATE -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 bg-rose-50 text-rose-600 border border-rose-100 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    
                    <h2 class="text-base font-bold text-rose-600 tracking-tight">Kwitansi Tidak Valid!</h2>
                    <p class="text-slate-400 text-[10px] mt-1 font-semibold leading-relaxed">Nomor kwitansi <span class="font-mono font-bold text-slate-600">{{ $nomor }}</span> tidak terdaftar dalam database kami.</p>
                </div>
            @endif

            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">SIP-SPP &middot; MI Al-Haq</p>
            </div>
        </div>
    </div>
</body>
</html>
