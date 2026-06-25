@extends('layouts.auth')

@section('title', 'Masuk Akun')

@section('content')
<main class="auth-login-shell">
    <section class="auth-hero-panel">
        <div class="auth-hero-inner">
            <div class="auth-hero-copy-block">
                <!-- Premium Hero Brand -->
                <div class="auth-hero-brand">
                    <div class="auth-hero-logo-ring">
                        <img src="{{ asset('logo.png') }}" alt="Logo Tut Wuri Handayani" class="auth-hero-logo-img">
                    </div>
                    <div class="auth-hero-brand-text">
                        <span class="auth-hero-app-name">SIP<span class="auth-hero-app-accent">-</span>SPP</span>
                        <span class="auth-hero-app-sub">Sistem Informasi Pembayaran</span>
                    </div>
                </div>

                <div class="auth-badge">
                    <span data-i18n="login.badge">Sistem pembayaran sekolah</span>
                </div>
                <h1 class="auth-hero-title" data-i18n="login.heroTitle">Administrasi SPP jadi lebih rapi.</h1>
                <p class="auth-hero-copy" data-i18n="login.heroBody">Cek pembayaran masuk, tunggakan siswa, dan rekap bulanan dari satu ruang kerja yang rapi.</p>
            </div>
        </div>
    </section>

    <section class="auth-form-panel">
        <div class="auth-form-card">
            <div class="md:hidden mb-6">
                @include('partials.brand-mark', ['showText' => true, 'tone' => 'light', 'size' => 'lg'])
            </div>

            <div>
                <h2 class="auth-form-title" data-i18n="login.title">Masuk ke SIP-SPP</h2>
                <p class="auth-form-copy" data-i18n="login.subtitle">Pantau pembayaran SPP, tagihan, dan rekap sekolah dalam satu sistem.</p>
            </div>



            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="auth-label" data-i18n="login.email">Alamat Email</label>
                    <div class="auth-input-wrap">
                        <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.2A8.96 8.96 0 0112 21"></path>
                        </svg>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="auth-input"
                            placeholder="nama@sekolah.sch.id"
                            data-i18n-placeholder="login.emailPlaceholder">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-[10px] text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="auth-label" data-i18n="login.password">Kata Sandi</label>
                    <div class="auth-input-wrap">
                        <svg class="auth-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <input type="password" name="password" id="password" required
                            class="auth-input"
                            placeholder="Masukkan kata sandi"
                            data-i18n-placeholder="login.passwordPlaceholder">
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-[10px] text-red-500 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="relative flex items-center cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="sr-only peer">
                        <span class="auth-checkbox">
                            <svg class="h-3.5 w-3.5 text-white opacity-0 transition-opacity peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </span>
                        <span class="ml-2 text-[10px] font-bold uppercase tracking-widest text-slate-400" data-i18n="login.remember">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="auth-submit btn-premium">
                    <span data-i18n="login.submit">Masuk ke Aplikasi</span>
                </button>
            </form>

            <p class="mt-8 border-t border-slate-100 pt-6 text-center text-[10px] font-semibold uppercase tracking-wider text-slate-400" data-i18n="login.help">Butuh bantuan? Hubungi operator IT sekolah.</p>
        </div>
    </section>
</main>
@endsection
