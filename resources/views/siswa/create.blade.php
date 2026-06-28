@extends('layouts.app')

@section('page_title', 'Tambah Siswa')
@section('page_subtitle', 'Masukkan data akademik dan personal siswa baru')

@section('content')
<div class="form-page-card bg-white rounded-2xl border border-blue-100/50 p-6 shadow-sm card-premium">
    <div class="flex items-center justify-between border-b border-blue-50 pb-4 mb-6">
        <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Form Siswa Baru</h2>
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

    <form action="{{ route('siswa.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="form-grid-wide">
            <div>
                <label for="nis" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">NIS (Nomor Induk Siswa)</label>
                <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-bold font-mono form-input-premium"
                    placeholder="Masukkan NIS unik">
            </div>

            <div>
                <label for="nama_lengkap" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Nama Lengkap Siswa</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                    placeholder="Masukkan nama lengkap siswa">
            </div>
        </div>

        <div class="form-grid-wide">
            <div>
                <label for="kelas_id" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Kelas Akademik</label>
                <select name="kelas_id" id="kelas_id" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="">Pilih Kelas...</option>
                    @foreach ($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                            Kelas {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="wali_murid_id" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Akun Wali Murid (Orang Tua)</label>
                <select name="wali_murid_id" id="wali_murid_id"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="">Hubungkan dengan Wali Murid...</option>
                    @foreach ($waliMurid as $w)
                        <option value="{{ $w->id }}" {{ old('wali_murid_id') == $w->id ? 'selected' : '' }}>
                            {{ $w->name }} ({{ $w->email }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-grid-wide">
            <div>
                <label for="jenis_kelamin" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                </select>
            </div>

            <div>
                <label for="tanggal_lahir" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                    class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium">
            </div>
        </div>

        <div>
            <label for="status" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Status Siswa</label>
            <select name="status" id="status" required
                class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-700 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold">
                <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="lulus" {{ old('status') === 'lulus' ? 'selected' : '' }}>Lulus</option>
                <option value="pindah" {{ old('status') === 'pindah' ? 'selected' : '' }}>Pindah</option>
                <option value="keluar" {{ old('status') === 'keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>

        <div>
            <label for="alamat" class="block text-[10px] font-bold text-blue-500/80 uppercase tracking-widest mb-2">Alamat Rumah Lengkap</label>
            <textarea name="alamat" id="alamat" rows="3"
                class="block w-full px-4 py-2.5 bg-blue-50/30 border border-blue-100/60 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/20 focus:border-brand-light focus:bg-white transition-all text-xs font-semibold form-input-premium"
                placeholder="Masukkan alamat tinggal siswa saat ini">{{ old('alamat') }}</textarea>
        </div>

        <div class="form-action-bar">
            <a href="{{ route('siswa.index') }}" class="form-secondary-action">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button type="submit"
                class="px-5 py-2.5 bg-brand-light hover:bg-brand-hover text-white text-xs font-bold rounded-xl transition-all duration-200 shadow-md shadow-brand-light/10 btn-premium">
                Simpan Data Siswa
            </button>
        </div>
    </form>
</div>
@endsection
