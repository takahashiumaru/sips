<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | SIP-SPP</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}?v=2" type="image/png">
    <script>
        (() => {
            try {
                document.documentElement.dataset.theme = localStorage.getItem('sip_spp_theme') || 'light';
                document.documentElement.lang = localStorage.getItem('sip_spp_lang') || 'id';
            } catch (error) {
                document.documentElement.dataset.theme = 'light';
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.showAlert = function(type, title, message) {
            const isDark = document.documentElement.dataset.theme === 'dark';

            const icons = {
                success: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#10B981,#059669);box-shadow:0 8px 24px rgba(16,185,129,0.35)"><svg width="26" height="26" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>`,
                error: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#EF4444,#DC2626);box-shadow:0 8px 24px rgba(239,68,68,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>`,
                warning: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#F59E0B,#D97706);box-shadow:0 8px 24px rgba(245,158,11,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>`,
                info: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#3B82F6,#2563EB);box-shadow:0 8px 24px rgba(59,130,246,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>`
            };

            const timerColors = { success:'#10B981', error:'#EF4444', warning:'#F59E0B', info:'#3B82F6' };
            const autoClose = (type === 'error' && message && message.includes('<li>')) ? 4000 : 1500;

            Swal.fire({
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;gap:14px;padding:8px 0 0">
                        ${icons[type] || icons.info}
                        <div style="font-size:15px;font-weight:800;letter-spacing:-0.02em;color:${isDark ? '#f1f5f9' : '#0f172a'}">${title}</div>
                        <div style="font-size:12px;font-weight:500;color:${isDark ? '#94a3b8' : '#64748b'};line-height:1.6;max-width:280px">${message}</div>
                    </div>
                `,
                background: isDark ? '#0f172a' : '#ffffff',
                showConfirmButton: false,
                timer: autoClose,
                timerProgressBar: true,
                width: 340,
                padding: '24px 28px 20px',
                showClass: { popup: 'swal-premium-slide-in' },
                hideClass: { popup: 'swal-premium-slide-out' },
                customClass: {
                    popup: 'swal-premium-popup',
                    timerProgressBar: 'swal-premium-timer'
                },
                didOpen: (popup) => {
                    const bar = popup.querySelector('.swal2-timer-progress-bar');
                    if (bar) bar.style.background = timerColors[type] || timerColors.info;
                    popup.addEventListener('click', () => Swal.close());
                }
            });
        };

        window.showConfirm = function(title, message, type = 'warning', confirmText = 'Ya', cancelText = 'Batal') {
            const isDark = document.documentElement.dataset.theme === 'dark';

            const icons = {
                danger: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#EF4444,#DC2626);box-shadow:0 8px 24px rgba(239,68,68,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></div>`,
                warning: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#F59E0B,#D97706);box-shadow:0 8px 24px rgba(245,158,11,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></div>`,
                info: `<div style="width:56px;height:56px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto;background:linear-gradient(135deg,#3B82F6,#2563EB);box-shadow:0 8px 24px rgba(59,130,246,0.35)"><svg width="24" height="24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg></div>`
            };

            const confirmBtnClass = type === 'danger' ? 'btn-danger' : (type === 'info' ? 'btn-info' : 'btn-warning');

            return Swal.fire({
                html: `
                    <div style="display:flex;flex-direction:column;align-items:center;gap:14px;padding:8px 0 0">
                        ${icons[type] || icons.warning}
                        <div style="font-size:15px;font-weight:800;letter-spacing:-0.02em;color:${isDark ? '#f1f5f9' : '#0f172a'}">${title}</div>
                        <div style="font-size:12px;font-weight:500;color:${isDark ? '#94a3b8' : '#64748b'};line-height:1.6;max-width:280px;text-align:center">${message}</div>
                    </div>
                `,
                background: isDark ? '#0f172a' : '#ffffff',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                buttonsStyling: false,
                width: 340,
                padding: '24px 28px 20px',
                showClass: { popup: 'swal-premium-slide-in' },
                hideClass: { popup: 'swal-premium-slide-out' },
                customClass: {
                    popup: 'swal-premium-popup',
                    actions: 'swal-premium-actions',
                    confirmButton: `swal-premium-btn-confirm ${confirmBtnClass}`,
                    cancelButton: 'swal-premium-btn-cancel'
                }
            });
        };

        window.confirmSubmit = function(event, message, title = 'Apakah Anda yakin?', type = 'warning', confirmText = 'Ya', cancelText = 'Batal') {
            event.preventDefault();
            const form = event.currentTarget || event.target.closest('form');
            window.showConfirm(title, message, type, confirmText, cancelText).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        };
    </script>
</head>
@php
    $isMinimized = (request()->cookie('sidebar_minimized') ?? $_COOKIE['sidebar_minimized'] ?? 'false') === 'true';
@endphp
<body class="bg-[#F6F8FC] font-sans antialiased text-[#0F172A] selection:bg-brand-light selection:text-white {{ $isMinimized ? 'sidebar-is-minimized' : '' }}"
      x-data="{ sidebarOpen: false, profileDropdownOpen: false, notificationsOpen: false, sidebarMinimized: {{ $isMinimized ? 'true' : 'false' }}, toggleSidebar() { this.sidebarMinimized = !this.sidebarMinimized; window.applySipSidebarState?.(this.sidebarMinimized, true); } }"
      :style="{ '--sidebar-w': sidebarMinimized ? '84px' : '256px' }"
      style="--sidebar-w: {{ $isMinimized ? '84px' : '256px' }}">

    <!-- Sidebar & Backdrop -->
    <!-- Mobile Backdrop -->
    <div class="fixed inset-0 bg-blue-950/20 backdrop-blur-xs z-40 md:hidden transition-opacity duration-300"
         x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"></div>

    <!-- Fixed Full-Height Sidebar -->
    <aside class="sidebar-premium fixed top-0 bottom-0 left-0 z-40 w-64 md:w-[var(--sidebar-w)] transition-all duration-300 flex flex-col -translate-x-full md:translate-x-0"
           :class="{ 'translate-x-0': sidebarOpen }">
        
        <!-- Sidebar Brand Logo Section -->
        <div class="sidebar-brand h-16 flex items-center shrink-0 transition-all duration-300" :class="sidebarMinimized ? 'justify-center px-0' : 'px-5'">
            <a href="{{ auth()->user()->isWaliMurid() ? route('portal.index') : route('dashboard') }}" class="flex items-center gap-2.5 font-bold text-lg tracking-tight text-slate-800">
                @include('partials.brand-mark', ['showText' => true, 'tone' => 'dark'])
            </a>
        </div>

        <!-- Sidebar Collapse Toggle Button (Floating) -->
        <button data-sidebar-toggle
                @click="toggleSidebar()" 
                class="sidebar-toggle-btn absolute top-20 -right-3.5 w-7 h-7 rounded-full flex items-center justify-center cursor-pointer z-50 transition-all focus:outline-none focus:ring-4 focus:ring-blue-500/15 hidden md:flex"
                :title="sidebarMinimized ? 'Besarkan sidebar' : 'Minimize sidebar'">
            <svg class="sidebar-toggle-icon w-3.5 h-3.5 transition-transform duration-300" :class="sidebarMinimized ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Navigation Menu -->
        <nav class="sidebar-nav space-y-1 overflow-y-auto flex-1 pt-4 transition-all duration-300" :class="sidebarMinimized ? 'px-2 py-3' : 'p-3'">
            @if(auth()->user()->isAdmin() || auth()->user()->isBendahara() || auth()->user()->isKepalaSekolah())
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('dashboard') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Dashboard' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="dashboard.title">Dashboard</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div x-show="!sidebarMinimized" class="px-3.5 pt-5 pb-1 transition-all duration-200">
                    <span class="text-[9.5px] font-extrabold text-blue-600/90 uppercase tracking-wider block" data-i18n="nav.system">Sistem & Pengguna</span>
                </div>
                <a href="{{ route('users.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('users.*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Manajemen Pengguna' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.users">User Management</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isBendahara() || auth()->user()->isKepalaSekolah())
                <div x-show="!sidebarMinimized" class="px-3.5 pt-5 pb-1 transition-all duration-200">
                    <span class="text-[9.5px] font-extrabold text-blue-600/90 uppercase tracking-wider block" data-i18n="nav.academic">Data Akademik</span>
                </div>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('kelas.index') }}" 
                       class="flex items-center transition-all btn-premium group {{ request()->routeIs('kelas.index') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                       :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                       :title="sidebarMinimized ? 'Data Kelas' : ''">
                        <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.classes">Data Kelas</span>
                        <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endif

                <a href="{{ route('tarif.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('tarif.*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Tarif SPP' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.tariff">Tarif SPP</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('siswa.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('siswa.*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Data Siswa' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.students">Data Siswa</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isBendahara() || auth()->user()->isKepalaSekolah())
                <div x-show="!sidebarMinimized" class="px-3.5 pt-5 pb-1 transition-all duration-200">
                    <span class="text-[9.5px] font-extrabold text-blue-600/90 uppercase tracking-wider block" data-i18n="nav.finance">Keuangan & Transaksi</span>
                </div>
                <a href="{{ route('tagihan.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('tagihan.*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Tagihan SPP' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.bills">Tagihan SPP</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <a href="{{ route('pembayaran.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('pembayaran.*') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Pembayaran & Verifikasi' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.payments">Pembayaran SPP</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isBendahara() || auth()->user()->isKepalaSekolah())
                <div x-show="!sidebarMinimized" class="px-3.5 pt-5 pb-1 transition-all duration-200">
                    <span class="text-[9.5px] font-extrabold text-blue-600/90 uppercase tracking-wider block" data-i18n="nav.reports">Laporan & Rekap</span>
                </div>
                <a href="{{ route('laporan.tunggakan') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('laporan.tunggakan') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Laporan Tunggakan' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.arrearsReport">Lap. Tunggakan</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('laporan.rekap') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('laporan.rekap') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Rekap Bulanan' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.monthlyRecap">Rekap Bulanan</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif

            @if(auth()->user()->isWaliMurid())
                <div x-show="!sidebarMinimized" class="px-3.5 pt-5 pb-1 transition-all duration-200">
                    <span class="text-[9.5px] font-extrabold text-blue-600/90 uppercase tracking-wider block" data-i18n="nav.guardianPortal">Portal Wali Murid</span>
                </div>
                <a href="{{ route('portal.index') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('portal.index') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Dashboard Wali' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V11a1 1 0 00-1-1H5m11 4h2a2 2 0 012 2v3a2 2 0 01-2 2H6a2 2 0 01-2-2v-3a2 2 0 012-2h2m4.5-3a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.guardianDashboard">Dashboard Wali</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('portal.tagihan') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('portal.tagihan') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Tagihan SPP Anak' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.childBills">Tagihan Anak</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('portal.riwayat') }}" 
                   class="flex items-center transition-all btn-premium group {{ request()->routeIs('portal.riwayat') ? 'sidebar-active shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }} {{ $isMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5' }}"
                   :class="sidebarMinimized ? 'justify-center mx-0.5 py-2 px-0 rounded-xl' : 'mx-1 px-3 py-2 rounded-xl gap-2.5'"
                   :title="sidebarMinimized ? 'Riwayat Pembayaran' : ''">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-[12px] font-semibold" x-show="!sidebarMinimized" x-transition.opacity data-i18n="nav.paymentHistory">Riwayat Bayar</span>
                    <svg class="w-3 h-3 text-slate-300 ml-auto hidden" :class="!sidebarMinimized ? 'md:block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!sidebarMinimized">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            @endif
        </nav>
    </aside>

    <!-- Top Navbar next to sidebar -->
    <header class="bg-white/90 backdrop-blur-xl border-b border-slate-200/70 h-16 fixed top-0 right-0 z-20 flex items-center justify-between px-6 shadow-xs transition-all duration-300 left-0 md:left-[var(--sidebar-w)]">
        
        <div class="flex items-center gap-4">
            <!-- Sidebar toggle (Mobile only) -->
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-slate-50 text-slate-500 hover:text-slate-800 rounded-xl transition-colors md:hidden focus:outline-none focus:ring-2 focus:ring-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Date Display (Inspired by screenshot) -->
            <div class="hidden sm:flex flex-col text-left leading-none">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider" data-i18n="app.today">Hari ini</span>
                <span class="text-xs font-bold text-slate-700 mt-0.5">{{ \Carbon\Carbon::now()->translatedFormat('D, d M Y') }}</span>
            </div>

            <!-- Global search input in header (Inspired by screenshot) -->
            <div class="relative w-48 lg:w-60 hidden md:block ml-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" placeholder="Cari..." data-i18n-placeholder="app.search"
                    class="block w-full pl-9 pr-3 py-1.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-light/10 focus:border-brand-light transition-all font-semibold form-input-premium">
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="theme-switch hidden sm:inline-flex" aria-label="Theme">
                <button type="button" data-theme-option="light" class="theme-option active" title="Light mode" aria-label="Light mode">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36-6.36-.7.7M6.34 17.66l-.7.7m0-12.72.7.7m12.72 12.72.7-.7M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                    </svg>
                </button>
                <button type="button" data-theme-option="dark" class="theme-option" title="Dark mode" aria-label="Dark mode">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.8A8.5 8.5 0 1111.2 3a6.5 6.5 0 009.8 9.8z"></path>
                    </svg>
                </button>
            </div>

            <!-- Language Mock Segment (Inspired by screenshot) -->
            <div class="segmented-control shrink-0 hidden sm:inline-flex">
                <button type="button" data-lang-option="en" class="segmented-btn">EN</button>
                <button type="button" data-lang-option="id" class="segmented-btn active">ID</button>
            </div>

            <!-- Notifications Bell -->
            @php
                $notifs = auth()->user()->notifikasi()->where('is_read', false)->latest()->take(5)->get();
                $unreadCount = auth()->user()->notifikasi()->where('is_read', false)->count();
            @endphp
            <div class="relative" @click.away="notificationsOpen = false">
                <button @click="notificationsOpen = !notificationsOpen" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-all relative focus:outline-none focus:ring-2 focus:ring-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    @if($unreadCount > 0)
                        <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-red-500 text-[9px] font-bold text-white rounded-full flex items-center justify-center border-2 border-white">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>

                <!-- Notifications Dropdown -->
                <div x-show="notificationsOpen" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2.5 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-slate-800"
                    style="display: none;">
                    <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider" data-i18n="app.notifications">Notifikasi Baru</span>
                        @if($unreadCount > 0)
                            <span class="text-[10px] bg-brand-light/10 text-brand-light px-2 py-0.5 rounded-full font-bold">{{ $unreadCount }} <span data-i18n="app.new">Baru</span></span>
                        @endif
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @forelse($notifs as $n)
                            <div class="px-4 py-3 hover:bg-slate-50 border-b border-slate-50 last:border-b-0 transition-colors flex flex-col gap-0.5">
                                <span class="text-xs font-bold text-slate-800 leading-snug">{{ $n->judul }}</span>
                                <span class="text-xs text-slate-500 line-clamp-2 leading-relaxed">{{ $n->pesan }}</span>
                                <span class="text-[9px] text-slate-400 font-semibold mt-1">{{ $n->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="px-4 py-8 text-center text-slate-400 flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <span class="text-[11px] font-semibold text-slate-400" data-i18n="app.noNotifications">Tidak ada notifikasi baru</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" @click.away="profileDropdownOpen = false">
                <button @click="profileDropdownOpen = !profileDropdownOpen" class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 hover:bg-slate-50 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-slate-100">
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-8 h-8 rounded-xl object-cover border border-brand-light/15 shadow-xs shrink-0">
                    @else
                        <div class="w-8 h-8 rounded-xl bg-brand-light/10 text-brand-light font-bold flex items-center justify-center text-xs border border-brand-light/15 shadow-xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="hidden md:flex flex-col items-start leading-none text-left">
                        <span class="text-xs font-bold text-slate-700">{{ auth()->user()->name }}</span>
                        <span class="text-[9px] text-slate-400 font-extrabold capitalize mt-0.5 tracking-wider">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                    </div>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Profile Menu -->
                <div x-show="profileDropdownOpen" 
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2.5 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 text-slate-800"
                    style="display: none;">
                    <div class="px-4 py-2.5 border-b border-slate-100 bg-slate-50/50">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest" data-i18n="app.account">Akun Pengguna</p>
                        <p class="text-xs font-bold text-slate-800 truncate mt-0.5">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('profil') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        <svg class="w-[18px] h-[18px] text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span data-i18n="app.profile">Profil Saya</span>
                    </a>
                    
                    <div class="border-t border-slate-100 my-1"></div>
                    
                    <form action="{{ route('logout') }}" method="POST" class="w-full" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin keluar dari aplikasi?', 'Keluar Aplikasi', 'warning')">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors text-left">
                            <svg class="w-[18px] h-[18px] text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span data-i18n="app.logout">Keluar Aplikasi</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sign Out Icon Button next to dropdown (Inspired by screenshot) -->
            <form action="{{ route('logout') }}" method="POST" class="shrink-0 hidden sm:block" onsubmit="return window.confirmSubmit(event, 'Apakah Anda yakin ingin keluar dari aplikasi?', 'Keluar Aplikasi', 'warning')">
                @csrf
                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all focus:outline-none" title="Log Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="pt-16 min-h-screen flex flex-col transition-all duration-300 pl-0 md:pl-[var(--sidebar-w)]">
        <div class="flex-1 p-6 md:p-10 max-w-7xl w-full mx-auto">
            <!-- Toast notification messages using SweetAlert2 -->
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        window.showAlert('success', 'Berhasil', '{{ session('success') }}');
                    });
                </script>
            @endif

            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        window.showAlert('error', 'Kesalahan', '{{ session('error') }}');
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        let errorMsg = '<ul class="text-left list-disc list-inside space-y-1.5 text-xs font-semibold opacity-90">';
                        @foreach ($errors->all() as $error)
                            errorMsg += '<li>{{ $error }}</li>';
                        @endforeach
                        errorMsg += '</ul>';
                        window.showAlert('error', 'Validasi Gagal', errorMsg);
                    });
                </script>
            @endif

            <!-- Page header -->
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none" @if(request()->routeIs('dashboard')) data-i18n="dashboard.title" @endif>@yield('page_title', 'Dashboard')</h1>
                    <p class="text-slate-400 text-xs mt-2 font-semibold" @if(request()->routeIs('dashboard')) data-i18n="dashboard.subtitle" @endif>@yield('page_subtitle', 'Sistem Informasi Pembayaran SPP Sekolah')</p>
                </div>
                <div class="flex items-center gap-3">
                    @yield('actions')
                </div>
            </div>

            <!-- Yield content -->
            @yield('content')
        </div>

        <footer class="md:pl-0 border-t border-slate-100 py-6 text-center text-xs text-slate-400 font-bold bg-white mt-auto">
            <span>&copy; {{ date('Y') }} <span data-i18n="app.footer">SIP-SPP &middot; SMP Negeri 1 Kelapa Dua. Hak Cipta Dilindungi.</span></span>
        </footer>
    </main>

    <script>
        (() => {
            const applySidebarState = (minimized, fromToggle = false) => {
                document.body.classList.toggle('sidebar-is-minimized', minimized);
                document.body.style.setProperty('--sidebar-w', minimized ? '84px' : '256px');
                try {
                    localStorage.setItem('sidebar_minimized', minimized ? 'true' : 'false');
                    document.cookie = `sidebar_minimized=${minimized ? 'true' : 'false'}; path=/; max-age=31536000; SameSite=Lax`;
                } catch (error) {
                    // Keep the UI responsive even if storage is unavailable.
                }

                const button = document.querySelector('[data-sidebar-toggle]');
                if (button) {
                    button.setAttribute('aria-expanded', minimized ? 'false' : 'true');
                    button.setAttribute('title', minimized ? 'Besarkan sidebar' : 'Minimize sidebar');
                }
            };

            window.applySipSidebarState = applySidebarState;

            document.addEventListener('DOMContentLoaded', () => {
                const saved = (() => {
                    try {
                        return localStorage.getItem('sidebar_minimized');
                    } catch (error) {
                        return null;
                    }
                })();

                if (saved === 'true' || saved === 'false') {
                    applySidebarState(saved === 'true');
                } else {
                    applySidebarState(document.body.classList.contains('sidebar-is-minimized'));
                }

                // ========================================
                // PREMIUM PJAX (PUSHSTATE + AJAX) SYSTEM
                // ========================================
                
                // Create progress bar element
                const progressBar = document.createElement('div');
                progressBar.id = 'pjax-progress-bar';
                progressBar.style.cssText = 'position: fixed; top: 0; left: 0; height: 3px; background: linear-gradient(90deg, #3B82F6, #6366F1, #10B981); z-index: 99999; width: 0%; transition: width 0.3s ease, opacity 0.3s ease; opacity: 0; pointer-events: none;';
                document.body.appendChild(progressBar);

                let progressInterval;
                const startProgress = () => {
                    clearInterval(progressInterval);
                    progressBar.style.opacity = '1';
                    progressBar.style.width = '0%';
                    let width = 0;
                    progressInterval = setInterval(() => {
                        if (width < 85) {
                            width += Math.random() * 12;
                            progressBar.style.width = width + '%';
                        }
                    }, 150);
                };

                const endProgress = () => {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                    setTimeout(() => {
                        progressBar.style.opacity = '0';
                        setTimeout(() => {
                            progressBar.style.width = '0%';
                        }, 300);
                    }, 150);
                };                const executeScripts = (container) => {
                    const scripts = container.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        // Mock DOMContentLoaded during script execution to trigger instantly
                        const originalAddEventListener = document.addEventListener;
                        document.addEventListener = function(type, listener, options) {
                            if (type === 'DOMContentLoaded') {
                                try {
                                    listener();
                                } catch (e) {
                                    console.error('Error executing inline DOMContentLoaded script:', e);
                                }
                            } else {
                                originalAddEventListener.call(document, type, listener, options);
                            }
                        };

                        try {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        } catch (e) {
                            console.error('Error re-executing injected script tag:', e);
                        } finally {
                            document.addEventListener = originalAddEventListener;
                        }
                    });
                };

                const updateActiveSidebarLinks = (currentUrl) => {
                    try {
                        const sidebarLinks = document.querySelectorAll('.sidebar-premium nav a');
                        const cleanPath = new URL(currentUrl).pathname;
                        
                        sidebarLinks.forEach(link => {
                            const linkUrl = new URL(link.href);
                            const linkPath = linkUrl.pathname;
                            
                            let isActive = false;
                            if (linkPath === '/dashboard' || linkPath === '/' || linkPath.endsWith('/dashboard')) {
                                isActive = cleanPath === linkPath;
                            } else {
                                isActive = cleanPath === linkPath || cleanPath.startsWith(linkPath + '/');
                            }

                            if (isActive) {
                                link.classList.add('sidebar-active', 'shadow-sm');
                                link.classList.remove('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
                            } else {
                                link.classList.remove('sidebar-active', 'shadow-sm');
                                link.classList.add('text-slate-500', 'hover:bg-slate-50', 'hover:text-slate-800');
                            }
                        });
                    } catch (e) {
                        console.error('Error updating active states:', e);
                    }
                };

                const loadPage = async (url, pushToHistory = true) => {
                    startProgress();
                    try {
                        const response = await fetch(url, {
                            headers: {
                                'X-PJAX': 'true',
                            }
                        });
                        if (!response.ok) throw new Error('Network response was not OK');
                        
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Update page title
                        if (doc.querySelector('title')) {
                            document.title = doc.querySelector('title').innerText;
                        }

                        // Update main content container
                        const oldMain = document.querySelector('main');
                        const newMain = doc.querySelector('main');
                        if (oldMain && newMain) {
                            oldMain.innerHTML = newMain.innerHTML;
                            executeScripts(oldMain);
                        }

                        // Update browser URL and state
                        if (pushToHistory) {
                            history.pushState({ pjax: true }, '', url);
                        }

                        // Scroll back to top instantly
                        window.scrollTo({ top: 0, behavior: 'instant' });

                        // Update active state in sidebar
                        updateActiveSidebarLinks(url);

                        // Close mobile sidebar backdrop if open
                        if (window.Alpine) {
                            const bodyEl = document.querySelector('body');
                            if (bodyEl) {
                                try {
                                    window.Alpine.$data(bodyEl).sidebarOpen = false;
                                } catch (e) {}
                            }
                        }

                        // Translate new content if needed
                        if (window.applySipLanguage) {
                            const currentLang = window.getStoredPreference?.('sip_spp_lang', 'id') || 'id';
                            window.applySipLanguage(currentLang);
                        }
                        if (window.applySipTheme) {
                            const currentTheme = window.getStoredPreference?.('sip_spp_theme', 'light') || 'light';
                            window.applySipTheme(currentTheme);
                        }

                        // Trigger pjax:success custom event
                        document.dispatchEvent(new Event('pjax:success'));

                    } catch (error) {
                        console.error('PJAX navigation failed, falling back to full refresh:', error);
                        if (pushToHistory) {
                            window.location.href = url;
                        }
                    } finally {
                        endProgress();
                    }
                };
                // Intercept clicks on links
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a');
                    if (!link) return;

                    const href = link.getAttribute('href');
                    if (!href) return;

                    // Skip anchors, javascript, external links, targets, download, logout
                    if (
                        href.startsWith('#') ||
                        href.startsWith('javascript:') ||
                        link.getAttribute('target') === '_blank' ||
                        link.hasAttribute('download') ||
                        link.getAttribute('data-pjax') === 'false' ||
                        link.getAttribute('data-no-pjax') !== null
                    ) {
                        return;
                    }

                    try {
                        const url = new URL(link.href);
                        if (url.origin !== window.location.origin) return; // External link
                        
                        // Skip logout post link or other exceptions
                        if (url.pathname.endsWith('/logout') || url.pathname.includes('/logout')) return;

                        e.preventDefault();
                        loadPage(url.href);
                    } catch (err) {
                        // Let browser handle malformed links
                    }
                });

                // Handle back/forward actions
                window.addEventListener('popstate', (e) => {
                    loadPage(window.location.href, false);
                });
            });
        })();
    </script>

    <!-- Global copy helper logic -->
    <script>
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                }, 1500);
            }).catch(err => {
                console.error('Error copying text: ', err);
            });
        }
    </script>

</body>
</html>
