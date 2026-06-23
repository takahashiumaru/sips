@extends('layouts.app')

@section('page_title', 'Profil Pengguna')
@section('page_subtitle', 'Kelola informasi profil dan ubah kata sandi akun Anda')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Info Card -->
    <div class="lg:col-span-1 rounded-2xl p-6 shadow-sm border border-blue-100/60 bg-white card-premium">
        <div class="flex flex-col items-center text-center">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-brand-light/10 shadow-sm mb-4">
                <form action="{{ route('profil.avatar.delete') }}" method="POST" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin menghapus foto profil ini?', 'Hapus Foto Profil', 'danger')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[10px] font-bold text-red-600 hover:text-red-700 transition-colors uppercase tracking-widest flex items-center gap-1.5 mb-4 cursor-pointer bg-red-50 dark:bg-red-950/20 px-3 py-1.5 rounded-lg border border-red-100 dark:border-red-900/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus Foto
                    </button>
                </form>
            @else
                <div class="w-20 h-20 rounded-2xl bg-brand-50 text-brand-light border-2 border-brand-light/10 font-black text-2xl flex items-center justify-center mb-4">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            
            <h2 class="text-base font-extrabold text-slate-800 tracking-tight">{{ $user->name }}</h2>
            <span class="px-2.5 py-0.5 text-[9px] font-bold text-brand-light bg-brand-50 border border-brand-light/10 rounded-full capitalize mt-2 inline-block">
                {{ str_replace('_', ' ', $user->role) }}
            </span>
            <p class="text-slate-400 text-xs mt-1.5 font-medium">{{ $user->email }}</p>
        </div>

        <div class="mt-8 pt-6 border-t border-blue-100/50 space-y-4">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. Telepon</span>
                <span class="text-xs font-bold text-slate-700 mt-1 block font-mono">{{ $user->phone ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status Akun</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }} mt-1">
                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Login Terakhir</span>
                <span class="text-xs font-bold text-slate-700 mt-1 block font-mono">{{ $user->last_login ? $user->last_login->translatedFormat('d F Y H:i') : '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Edit Profile & Password Area -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Edit Profile Card -->
        <div class="rounded-2xl p-6 shadow-sm border border-blue-100/60 bg-white card-premium">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-blue-100/50 pb-4 mb-6">Perbarui Profil</h2>

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Nama Lengkap">
                    </div>

                    <div>
                        <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                            class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Nomor Telepon">
                    </div>
                </div>

                <div>
                    <label for="avatar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Profil Baru (Opsional)</label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full px-4 py-2 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold file:mr-4 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-brand-light hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-white">
                    <p class="text-slate-400 text-[10px] mt-1.5">Format: JPEG, PNG, JPG, GIF, SVG. Ukuran maks: 2MB.</p>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                        Perbarui Profil
                    </button>
                </div>
            </form>
        </div>

        <!-- Edit Password Card -->
        <div class="rounded-2xl p-6 shadow-sm border border-blue-100/60 bg-white card-premium">
            <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-blue-100/50 pb-4 mb-6">Ubah Kata Sandi</h2>

            <form action="{{ route('profil.password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kata Sandi Sekarang</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Masukkan kata sandi saat ini">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kata Sandi Baru</label>
                        <input type="password" name="password" id="password" required
                            class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                            placeholder="Ulangi kata sandi baru">
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
