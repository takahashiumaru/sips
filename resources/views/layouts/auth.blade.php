<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Login') | SIP-SPP</title>
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
                timerProgressBar: false,
                width: 340,
                padding: '24px 28px 20px',
                showClass: { popup: 'swal-premium-slide-in' },
                hideClass: { popup: 'swal-premium-slide-out' },
                customClass: {
                    popup: 'swal-premium-popup'
                },
                didOpen: (popup) => {
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
<body class="auth-body min-h-screen font-sans antialiased selection:bg-brand-light selection:text-white">
    <div class="auth-controls">
        <div class="theme-switch" aria-label="Theme">
            <button type="button" data-theme-option="light" class="theme-option active" title="Light mode" aria-label="Light mode">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.36-6.36-.7.7M6.34 17.66l-.7.7m0-12.72.7.7m12.72 12.72.7-.7M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>
            <button type="button" data-theme-option="dark" class="theme-option" title="Dark mode" aria-label="Dark mode">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.8A8.5 8.5 0 1111.2 3a6.5 6.5 0 009.8 9.8z"/>
                </svg>
            </button>
        </div>
        <div class="segmented-control auth-lang-control">
            <button type="button" data-lang-option="en" class="segmented-btn">EN</button>
            <button type="button" data-lang-option="id" class="segmented-btn active">ID</button>
        </div>
    </div>

    @yield('content')

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
</body>
</html>
