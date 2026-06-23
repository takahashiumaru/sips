import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

const translations = {
    id: {
        'app.today': 'Hari ini',
        'app.search': 'Cari...',
        'app.notifications': 'Notifikasi Baru',
        'app.noNotifications': 'Tidak ada notifikasi baru',
        'app.new': 'Baru',
        'app.account': 'Akun Pengguna',
        'app.profile': 'Profil Saya',
        'app.logout': 'Keluar Aplikasi',
        'app.close': 'Tutup',
        'app.footer': 'SIP-SPP · SMP Negeri 1 Kelapa Dua. Hak Cipta Dilindungi.',
        'nav.system': 'Sistem & Pengguna',
        'nav.users': 'User Management',
        'nav.academic': 'Data Akademik',
        'nav.classes': 'Data Kelas',
        'nav.tariff': 'Tarif SPP',
        'nav.students': 'Data Siswa',
        'nav.finance': 'Keuangan & Transaksi',
        'nav.bills': 'Tagihan SPP',
        'nav.payments': 'Pembayaran SPP',
        'nav.reports': 'Laporan & Rekap',
        'nav.arrearsReport': 'Lap. Tunggakan',
        'nav.monthlyRecap': 'Rekap Bulanan',
        'nav.guardianPortal': 'Portal Wali Murid',
        'nav.guardianDashboard': 'Dashboard Wali',
        'nav.childBills': 'Tagihan Anak',
        'nav.paymentHistory': 'Riwayat Bayar',
        'dashboard.title': 'Dashboard Utama',
        'dashboard.subtitle': 'Tinjauan real-time status pembayaran SPP dan tunggakan siswa',
        'dashboard.activeStudents': 'Total Siswa Aktif',
        'dashboard.activeStudentsHelp': 'Siswa terdaftar aktif',
        'dashboard.paidThisMonth': 'Lunas Bulan Ini',
        'dashboard.paidRate': 'tingkat kelunasan',
        'dashboard.unpaidThisMonth': 'Belum Lunas Bulan Ini',
        'dashboard.unpaidHelp': 'Siswa belum membayar',
        'dashboard.totalArrears': 'Tunggakan Kumulatif',
        'dashboard.allYears': 'Semua tahun ajaran',
        'dashboard.paymentTrend': 'Tren Penerimaan Pembayaran',
        'dashboard.paymentTrendHelp': 'Akumulasi pembayaran lunas tahun ini',
        'dashboard.peak': 'Puncak',
        'dashboard.topArrears': 'Tunggakan Tertinggi',
        'dashboard.topArrearsHelp': 'Prioritas follow-up pembayaran',
        'dashboard.noArrears': 'Tidak ada tunggakan siswa.',
        'dashboard.latestTransactions': '10 Transaksi Pembayaran Terbaru',
        'dashboard.student': 'Siswa',
        'dashboard.class': 'Kelas',
        'dashboard.period': 'Periode Tagihan',
        'dashboard.amount': 'Jumlah Bayar',
        'dashboard.method': 'Metode',
        'dashboard.status': 'Status',
        'dashboard.transactionDate': 'Tanggal Transaksi',
        'dashboard.noTransactions': 'Belum ada data transaksi masuk.',
        'dashboard.paid': 'Lunas',
        'dashboard.pending': 'Pending',
        'dashboard.rejected': 'Ditolak',
        'login.title': 'Masuk ke SIP-SPP',
        'login.subtitle': 'Pantau pembayaran SPP, tagihan, dan rekap sekolah dalam satu sistem.',
        'login.heroTitle': 'Administrasi SPP jadi lebih rapi.',
        'login.heroBody': 'Cek pembayaran masuk, tunggakan siswa, dan rekap bulanan dari satu ruang kerja yang rapi.',
        'login.totalStudents': 'Siswa Aktif',
        'login.monitoring': 'Monitoring',
        'login.finance': 'SPP Digital',
        'login.email': 'Alamat Email',
        'login.emailPlaceholder': 'nama@sekolah.sch.id',
        'login.password': 'Kata Sandi',
        'login.passwordPlaceholder': 'Masukkan kata sandi',
        'login.remember': 'Ingat Saya',
        'login.submit': 'Masuk ke Aplikasi',
        'login.help': 'Butuh bantuan? Hubungi operator IT sekolah.',
        'login.badge': 'Sistem pembayaran sekolah'
    },
    en: {
        'app.today': 'Today',
        'app.search': 'Search...',
        'app.notifications': 'New Notifications',
        'app.noNotifications': 'No new notifications',
        'app.new': 'New',
        'app.account': 'User Account',
        'app.profile': 'My Profile',
        'app.logout': 'Sign Out',
        'app.close': 'Close',
        'app.footer': 'SIP-SPP · SMP Negeri 1 Kelapa Dua. All rights reserved.',
        'nav.system': 'System & Users',
        'nav.users': 'User Management',
        'nav.academic': 'Academic Data',
        'nav.classes': 'Class Data',
        'nav.tariff': 'SPP Tariffs',
        'nav.students': 'Student Data',
        'nav.finance': 'Finance & Transactions',
        'nav.bills': 'SPP Bills',
        'nav.payments': 'SPP Payments',
        'nav.reports': 'Reports & Recaps',
        'nav.arrearsReport': 'Arrears Report',
        'nav.monthlyRecap': 'Monthly Recap',
        'nav.guardianPortal': 'Parent Portal',
        'nav.guardianDashboard': 'Parent Dashboard',
        'nav.childBills': 'Child Bills',
        'nav.paymentHistory': 'Payment History',
        'dashboard.title': 'Main Dashboard',
        'dashboard.subtitle': 'Real-time overview of SPP payment status and student arrears',
        'dashboard.activeStudents': 'Active Students',
        'dashboard.activeStudentsHelp': 'Registered active students',
        'dashboard.paidThisMonth': 'Paid This Month',
        'dashboard.paidRate': 'completion rate',
        'dashboard.unpaidThisMonth': 'Unpaid This Month',
        'dashboard.unpaidHelp': 'Students awaiting payment',
        'dashboard.totalArrears': 'Cumulative Arrears',
        'dashboard.allYears': 'All academic years',
        'dashboard.paymentTrend': 'Payment Collection Trend',
        'dashboard.paymentTrendHelp': 'Completed payment total this year',
        'dashboard.peak': 'Peak',
        'dashboard.topArrears': 'Highest Arrears',
        'dashboard.topArrearsHelp': 'Priority payment follow-up',
        'dashboard.noArrears': 'No student arrears.',
        'dashboard.latestTransactions': '10 Latest Payment Transactions',
        'dashboard.student': 'Student',
        'dashboard.class': 'Class',
        'dashboard.period': 'Billing Period',
        'dashboard.amount': 'Paid Amount',
        'dashboard.method': 'Method',
        'dashboard.status': 'Status',
        'dashboard.transactionDate': 'Transaction Date',
        'dashboard.noTransactions': 'No transactions recorded yet.',
        'dashboard.paid': 'Paid',
        'dashboard.pending': 'Pending',
        'dashboard.rejected': 'Rejected',
        'login.title': 'Sign in to SIP-SPP',
        'login.subtitle': 'Track SPP payments, bills, and school recaps from one system.',
        'login.heroTitle': 'Cleaner SPP administration.',
        'login.heroBody': 'Review incoming payments, student arrears, and monthly recaps from one focused workspace.',
        'login.totalStudents': 'Active Students',
        'login.monitoring': 'Monitoring',
        'login.finance': 'Digital SPP',
        'login.email': 'Email Address',
        'login.emailPlaceholder': 'name@school.sch.id',
        'login.password': 'Password',
        'login.passwordPlaceholder': 'Enter your password',
        'login.remember': 'Remember Me',
        'login.submit': 'Sign In',
        'login.help': 'Need help? Contact the school IT operator.',
        'login.badge': 'School payment system'
    }
};

function getStoredPreference(key, fallback) {
    try {
        return localStorage.getItem(key) || fallback;
    } catch (error) {
        return fallback;
    }
}

function setStoredPreference(key, value) {
    try {
        localStorage.setItem(key, value);
    } catch (error) {
        // Storage can be unavailable in hardened browsers; keep UI state in DOM.
    }
}

function applyTheme(theme) {
    const nextTheme = theme === 'dark' ? 'dark' : 'light';
    document.documentElement.dataset.theme = nextTheme;
    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        const active = button.dataset.themeOption === nextTheme;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    setStoredPreference('sip_spp_theme', nextTheme);
}

function applyLanguage(lang) {
    const nextLang = lang === 'en' ? 'en' : 'id';
    const dict = translations[nextLang];
    document.documentElement.lang = nextLang;
    document.documentElement.dataset.lang = nextLang;

    document.querySelectorAll('[data-i18n]').forEach((node) => {
        const key = node.dataset.i18n;
        if (dict[key]) node.textContent = dict[key];
    });

    document.querySelectorAll('[data-i18n-placeholder]').forEach((node) => {
        const key = node.dataset.i18nPlaceholder;
        if (dict[key]) node.setAttribute('placeholder', dict[key]);
    });

    document.querySelectorAll('[data-i18n-title]').forEach((node) => {
        const key = node.dataset.i18nTitle;
        if (dict[key]) node.setAttribute('title', dict[key]);
    });

    document.querySelectorAll('[data-lang-option]').forEach((button) => {
        const active = button.dataset.langOption === nextLang;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });

    setStoredPreference('sip_spp_lang', nextLang);
}

// Expose preferences to window for PJAX re-run
window.applySipTheme = applyTheme;
window.applySipLanguage = applyLanguage;
window.getStoredPreference = getStoredPreference;

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getStoredPreference('sip_spp_theme', document.documentElement.dataset.theme || 'light'));
    applyLanguage(getStoredPreference('sip_spp_lang', document.documentElement.lang || 'id'));

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.addEventListener('click', () => applyTheme(button.dataset.themeOption));
    });

    document.querySelectorAll('[data-lang-option]').forEach((button) => {
        button.addEventListener('click', () => applyLanguage(button.dataset.langOption));
    });
});

Alpine.start();
