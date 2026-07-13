@extends('layouts.app')

@section('page_title', 'Manajemen Pengguna')
@section('page_subtitle', 'Kelola hak akses, status, dan role akun pengguna sistem')

@section('actions')
<a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-brand-light/10 btn-premium">
    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
    </svg>
    <span>Tambah Pengguna</span>
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-blue-100/60 shadow-sm overflow-hidden card-premium">
    <!-- Filter Section -->
    <div class="p-6 border-b border-blue-50/50">
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="block w-full pl-9 pr-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all form-input-premium font-semibold"
                    placeholder="Cari nama atau email...">
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select name="role" onchange="this.form.submit()"
                    class="block w-full md:w-44 px-3.5 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-xs text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all font-semibold">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wali_murid" {{ request('role') == 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                    <option value="kepala_sekolah" {{ request('role') == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>

                @if(request('search') || request('role'))
                    <a href="{{ route('users.index') }}" class="text-xs text-blue-400 hover:text-blue-600 font-bold shrink-0">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-100/40 text-left">
            <thead>
                <tr class="text-[10px] font-bold text-blue-400/80 uppercase tracking-wider bg-blue-50/20">
                    <th class="px-6 py-4" data-i18n="table.fullName">Nama Lengkap</th>
                    <th class="px-6 py-4" data-i18n="table.email">Email</th>
                    <th class="px-6 py-4" data-i18n="table.role">Role</th>
                    <th class="px-6 py-4" data-i18n="table.phoneNumber">No. Telepon</th>
                    <th class="px-6 py-4" data-i18n="table.status">Status</th>
                    <th class="px-6 py-4" data-i18n="table.lastLogin">Login Terakhir</th>
                    <th class="px-6 py-4 text-right" data-i18n="table.action">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50/30 text-slate-700">
                @forelse ($users as $u)
                    <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-3">
                             @if($u->avatar)
                                <img src="{{ asset('storage/' . $u->avatar) }}" alt="{{ $u->name }}" class="w-8.5 h-8.5 rounded-xl object-cover border border-slate-100 dark:border-slate-800 shrink-0">
                            @else
                                <div class="w-8.5 h-8.5 rounded-xl bg-brand-50 text-brand-light font-bold flex items-center justify-center border border-brand-light/10 text-xs shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="truncate max-w-[160px]">{{ $u->name }}</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-semibold">{{ $u->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold capitalize bg-blue-50 text-blue-600 border border-blue-100/40">
                                {{ str_replace('_', ' ', $u->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-bold font-mono">{{ $u->phone ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('users.toggle', $u) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold {{ $u->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }} btn-premium">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $u->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-slate-400 font-bold font-mono">{{ $u->last_login ? $u->last_login->translatedFormat('d M Y, H:i') : '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('users.edit', $u) }}" class="p-2 bg-blue-50/30 border border-blue-100/60 text-blue-500 hover:text-white hover:bg-brand-light rounded-xl transition-all btn-premium action-btn-edit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menonaktifkan user ini?', 'Nonaktifkan Pengguna', 'danger')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-blue-50/30 border border-blue-100/60 text-slate-400 hover:text-red-600 hover:bg-red-50 hover:border-red-100 rounded-xl transition-all btn-premium action-btn-delete">
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
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400 font-semibold" data-i18n="table.emptyUsers">Tidak ada data pengguna ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if ($users->hasPages())
        <div class="px-6 py-4 border-t border-blue-100/40 bg-blue-50/10">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
