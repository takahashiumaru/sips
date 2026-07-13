@extends('layouts.app')

@section('page_title', 'Tagihan SPP')
@section('page_subtitle', 'Kelola tagihan biaya pendidikan bulanan siswa secara massal maupun individu')

@section('actions')
@if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
<button @click="$dispatch('open-modal-generate')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50/50 border border-blue-100/40 hover:bg-blue-50 hover:text-brand-hover hover:border-blue-100/60 text-blue-600 text-xs font-bold rounded-xl transition-all shadow-sm btn-premium btn-generate-massal">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
    </svg>
    <span>Generate Massal</span>
</button>

<a href="{{ route('tagihan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Buat Manual</span>
</a>
@endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/50 shadow-sm overflow-hidden card-premium" x-data="{ }">
    <!-- Filter Section -->
    <div class="p-6 border-b border-blue-50/50">
        <form action="{{ route('tagihan.index') }}" method="GET" data-filter-form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-center">
            <!-- Search -->
            <div class="relative lg:col-span-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="search" name="search" value="{{ request('search') }}" data-auto-submit-search autocomplete="off"
                    class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all form-input-premium font-semibold"
                    placeholder="NIS / nama siswa...">
            </div>

            <!-- Kelas -->
            <div class="lg:col-span-1">
                <select name="kelas_id" onchange="this.form.submit()"
                    class="block w-full px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Kelas</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Bulan -->
            <div class="lg:col-span-1">
                <select name="bulan" onchange="this.form.submit()"
                    class="block w-full px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Bulan</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (string) $filterBulan === (string) $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Tahun -->
            <div class="lg:col-span-1">
                <select name="tahun" onchange="this.form.submit()"
                    class="block w-full px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                        <option value="{{ $y }}" {{ (string) $filterTahun === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <!-- Status -->
            <div class="lg:col-span-1 flex items-center gap-3">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Status</option>
                    <option value="belum_bayar" {{ request('status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>

                @if(request('search') || request('kelas_id') || request('bulan') || request('tahun') || request('status'))
                    <a href="{{ route('tagihan.index') }}" class="text-xs text-slate-400 hover:text-slate-600 font-bold shrink-0">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-50 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                    <th class="px-6 py-4" data-i18n="table.student">Siswa</th>
                    <th class="px-6 py-4" data-i18n="table.class">Kelas</th>
                    <th class="px-6 py-4" data-i18n="table.period">Periode</th>
                    <th class="px-6 py-4" data-i18n="table.billingAmount">Jumlah Tagihan</th>
                    <th class="px-6 py-4" data-i18n="table.amountPaid">Telah Dibayar</th>
                    <th class="px-6 py-4" data-i18n="table.remainingBill">Sisa Tagihan</th>
                    <th class="px-6 py-4" data-i18n="table.dueDate">Jatuh Tempo</th>
                    <th class="px-6 py-4" data-i18n="table.status">Status</th>
                    <th class="px-6 py-4 text-right" data-i18n="table.action">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/50 text-slate-700">
                @forelse ($tagihan as $t)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                            <div class="w-8.5 h-8.5 rounded-xl bg-blue-50 text-brand-hover font-bold flex items-center justify-center border border-blue-100/50 text-xs shrink-0">
                                {{ strtoupper(substr($t->siswa->nama_lengkap ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex flex-col gap-0.5">
                                <span class="truncate max-w-[140px]">{{ $t->siswa->nama_lengkap ?? '-' }}</span>
                                <span class="text-[9px] text-slate-400 font-bold font-mono uppercase tracking-tight">NIS: {{ $t->siswa->nis ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-slate-500">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800">
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
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Bayar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if($t->status !== 'lunas' && (auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah()))
                                    <a href="{{ route('pembayaran.create', $t) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-brand-light text-white text-[9px] font-bold rounded-xl hover:bg-brand-hover transition-all shadow-md shadow-brand-light/10 btn-premium">
                                        Bayar
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
                                    <a href="{{ route('tagihan.edit', $t) }}" class="p-2 bg-blue-50/40 border border-blue-100/30 text-blue-500 hover:text-brand-hover hover:bg-blue-50 hover:border-blue-100/50 rounded-xl transition-all btn-premium action-btn-edit">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <form action="{{ route('tagihan.destroy', $t) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus tagihan ini?', 'Hapus Tagihan', 'danger')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-blue-50/40 border border-blue-100/30 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl transition-all btn-premium action-btn-delete">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-8 text-center text-slate-400 font-semibold" data-i18n="table.emptyBills">Tidak ada data tagihan ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($tagihan->hasPages())
        <div class="px-6 py-4 border-t border-blue-50/50 bg-blue-50/10">
            {{ $tagihan->links() }}
        </div>
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isKepalaSekolah())
        <!-- Modal Generate Massal -->
        <div x-data="{ open: false }" 
             @open-modal-generate.window="open = true" 
             @close-modal-generate.window="open = false"
             x-show="open" 
             class="modal-premium-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 bg-blue-950/30 backdrop-blur-xs"
             style="display: none;"
             x-transition:enter="modal-backdrop-enter"
             x-transition:enter-start="modal-backdrop-enter-start"
             x-transition:enter-end="modal-backdrop-enter-end"
             x-transition:leave="modal-backdrop-leave"
             x-transition:leave-start="modal-backdrop-leave-start"
             x-transition:leave-end="modal-backdrop-leave-end">
            <div class="modal-premium-panel bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-blue-100/50" @click.away="open = false">
                <div class="flex items-center justify-between border-b border-blue-50 pb-3.5 mb-5">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Generate Tagihan Bulanan</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('tagihan.generate') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="gen_bulan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Bulan Periode</label>
                        <select name="bulan" id="gen_bulan" required
                            class="block w-full px-3 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="gen_tahun" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tahun Periode</label>
                        <select name="tahun" id="gen_tahun" required
                            class="block w-full px-3 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                            @for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="p-4 bg-blue-50 border border-blue-100/50 text-[10px] text-blue-700 rounded-2xl leading-relaxed font-bold">
                        Sistem akan otomatis membuat tagihan SPP untuk seluruh siswa berstatus "Aktif" sesuai tingkat kelas dan tarif SPP aktif pada bulan yang dipilih. Siswa yang sudah memiliki tagihan pada periode tersebut akan dilewati.
                    </div>

                    <div class="pt-3 flex justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 text-xs font-bold rounded-xl transition-colors btn-premium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-brand-light/10 btn-premium">Generate Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
