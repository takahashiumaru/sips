@extends('layouts.app')

@section('page_title', 'Edit Pengguna')
@section('page_subtitle', 'Perbarui informasi profil atau reset password akun pengguna')

@section('content')
<div class="form-page-card rounded-2xl p-6 shadow-sm border border-blue-100/60 bg-white card-premium">
    <div class="flex items-center justify-between border-b border-blue-100/50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Edit Pengguna</h2>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="form-grid-wide">
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Role Akses</label>
                <select name="role" id="role" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wali_murid" {{ old('role', $user->role) === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                    <option value="kepala_sekolah" {{ old('role', $user->role) === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>
        </div>

        <div class="form-grid-wide">
            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="email@sekolah.sch.id">
            </div>

            <div>
                <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">No. Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div class="space-y-4">
            <label for="avatar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Foto Profil</label>
            
            @if($user->avatar)
                <div class="flex items-center gap-4 p-3 bg-slate-50 dark:bg-slate-900 border border-blue-100/50 rounded-xl max-w-md">
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-xl object-cover border border-slate-200 dark:border-slate-800 shrink-0">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs font-bold text-slate-700">Foto profil saat ini</span>
                        <label class="inline-flex items-center gap-2 cursor-pointer mt-1">
                            <input type="checkbox" name="delete_avatar" id="delete_avatar" value="1" class="rounded text-brand-light focus:ring-brand-light/20 border-slate-300 w-4 h-4">
                            <span class="text-xs font-semibold text-red-600 hover:text-red-700">Hapus foto profil ini</span>
                        </label>
                    </div>
                </div>
            @endif

            <input type="file" name="avatar" id="avatar" accept="image/*"
                class="block w-full px-4 py-2 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold file:mr-4 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-brand-light hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-white">
            <p class="text-slate-400 text-[10px] mt-1.5">Format: JPEG, PNG, JPG, GIF, SVG. Ukuran maks: 2MB.</p>
        </div>

        <div class="border-t border-blue-100/50 pt-5 mt-5">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ganti Password (Opsional)</h3>
            <p class="text-slate-400 text-[10px] mb-4">Kosongkan kolom di bawah jika tidak ingin mengganti password pengguna.</p>
            
            <div class="form-grid-wide">
                <div>
                    <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
                    <input type="password" name="password" id="password"
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                        placeholder="Ulangi password">
                </div>
            </div>
        </div>

        <div class="form-action-bar">
            <a href="{{ route('users.index') }}" class="form-secondary-action">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
