@extends('layouts.app')

@section('page_title', 'Tambah Pengguna')
@section('page_subtitle', 'Buat akun pengguna baru dengan role spesifik')

@section('content')
<div class="max-w-2xl rounded-2xl p-6 shadow-sm border border-blue-100/60 bg-white card-premium">
    <div class="flex items-center justify-between border-b border-blue-100/50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Pengguna Baru</h2>
        <a href="{{ route('users.index') }}" class="text-xs font-bold text-slate-400 hover:text-slate-600 transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
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

    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="name" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Masukkan nama lengkap">
            </div>

            <div>
                <label for="role" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Role Akses</label>
                <select name="role" id="role" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="">Pilih Role...</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="wali_murid" {{ old('role') === 'wali_murid' ? 'selected' : '' }}>Wali Murid</option>
                    <option value="kepala_sekolah" {{ old('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="email" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="email@sekolah.sch.id">
            </div>

            <div>
                <label for="phone" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">No. Telepon / WhatsApp</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Contoh: 081234567890">
            </div>
        </div>

        <div>
            <label for="avatar" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Profil (Opsional)</label>
            <input type="file" name="avatar" id="avatar" accept="image/*"
                class="block w-full px-4 py-2 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold file:mr-4 file:py-1.5 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-brand-light hover:file:bg-blue-100 dark:file:bg-slate-800 dark:file:text-white">
            <p class="text-slate-400 text-[10px] mt-1.5">Format: JPEG, PNG, JPG, GIF, SVG. Ukuran maks: 2MB.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-blue-100/50">
            <div>
                <label for="password" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" id="password" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Ulangi password">
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Tambah User
            </button>
        </div>
    </form>
</div>
@endsection
