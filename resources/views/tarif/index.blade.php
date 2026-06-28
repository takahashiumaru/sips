@extends('layouts.app')

@section('page_title', 'Tarif SPP')
@section('page_subtitle', 'Kelola besaran biaya pembayaran SPP bulanan per tingkat kelas')

@section('actions')
@if(auth()->user()->isAdmin())
<button @click="$dispatch('open-modal-add')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Tarif</span>
</button>
@endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/50 shadow-sm overflow-hidden card-premium" x-data="{ editMode: false, editForm: { id: '', jumlah: 0, keterangan: '' } }">
    <div class="p-6 border-b border-blue-50/50 flex items-center justify-between">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tarif Pembayaran SPP (Tahun Ajaran: {{ $tahunAktif->nama ?? '-' }})</h2>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-50 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                    <th class="px-6 py-4">Tingkat Kelas</th>
                    <th class="px-6 py-4">Tahun Ajaran</th>
                    <th class="px-6 py-4">Nominal Bulanan</th>
                    <th class="px-6 py-4">Keterangan</th>
                    @if(auth()->user()->isAdmin())
                        <th class="px-6 py-4 text-right">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/50 text-slate-700">
                @forelse ($tarif as $t)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">Tingkat {{ $t->tingkat }}</td>
                        <td class="px-6 py-4 text-slate-500 font-semibold">{{ $t->tahunAjaran->nama ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900 text-sm font-mono">Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-400 font-semibold">{{ $t->keterangan ?? '-' }}</td>
                        @if(auth()->user()->isAdmin())
                            <td class="px-6 py-4 text-right">
                                <button type="button" 
                                    @click="editMode = true; editForm = { id: '{{ $t->id }}', jumlah: '{{ $t->jumlah }}', keterangan: '{{ $t->keterangan }}' }; $dispatch('open-modal-edit')"
                                    class="p-2 bg-blue-50/40 border border-blue-100/30 text-blue-500 hover:text-brand-hover hover:bg-blue-50 hover:border-blue-100/50 rounded-xl transition-all btn-premium action-btn-edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-400 font-semibold">Tidak ada data tarif SPP untuk tahun ajaran aktif.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(auth()->user()->isAdmin())
        <!-- Modal Add Tarif -->
        <div x-data="{ open: false }" 
             @open-modal-add.window="open = true" 
             @close-modal-add.window="open = false"
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
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Tambah Tarif Baru</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('tarif.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="tahun_ajaran_id" value="{{ $tahunAktif->id ?? '' }}">

                    <div>
                        <label for="tingkat" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tingkat Kelas</label>
                        <select name="tingkat" id="tingkat" required
                            class="block w-full px-3 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}">Tingkat {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="jumlah" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nominal Bulanan (IDR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-blue-400 text-xs font-bold font-mono">Rp</span>
                            <input type="number" name="jumlah" id="jumlah" required min="0" step="1"
                                class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                                placeholder="Masukkan nominal, cth: 250000">
                        </div>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan"
                            class="block w-full px-3 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Contoh: SPP Reguler">
                    </div>

                    <div class="pt-3 flex justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 text-xs font-bold rounded-xl transition-colors btn-premium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-brand-light/10 btn-premium">Simpan Tarif</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Tarif -->
        <div x-data="{ open: false }" 
             @open-modal-edit.window="open = true" 
             @close-modal-edit.window="open = false"
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
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Perbarui Tarif SPP</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form :action="'{{ route('tarif.index') }}/' + editForm.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="edit_jumlah" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nominal Bulanan (IDR)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-blue-400 text-xs font-bold font-mono">Rp</span>
                            <input type="number" name="jumlah" id="edit_jumlah" required min="0" step="1" x-model="editForm.jumlah"
                                class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                                placeholder="Masukkan nominal">
                        </div>
                    </div>

                    <div>
                        <label for="edit_keterangan" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Keterangan</label>
                        <input type="text" name="keterangan" id="edit_keterangan" x-model="editForm.keterangan"
                            class="block w-full px-3 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Masukkan keterangan">
                    </div>

                    <div class="pt-3 flex justify-end gap-3">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 text-xs font-bold rounded-xl transition-colors btn-premium">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-colors shadow-md shadow-brand-light/10 btn-premium">Perbarui Tarif</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
