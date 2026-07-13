import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Chart = Chart;
window.Swal = Swal;

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
        'app.footer': 'SIP-SPP · MI Al-Haq. Hak Cipta Dilindungi.',
        'nav.system': 'Sistem & Pengguna',
        'nav.users': 'User Management',
        'nav.academic': 'Data Akademik',
        'nav.tahunAjaran': 'Tahun Ajaran',
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
        'login.badge': 'Sistem pembayaran sekolah',
        'table.academicYear': 'Tahun Ajaran',
        'table.academicYearName': 'Nama Tahun Ajaran',
        'table.startYear': 'Tahun Mulai',
        'table.endYear': 'Tahun Akhir',
        'table.status': 'Status',
        'table.action': 'Aksi',
        'table.level': 'Tingkat',
        'table.className': 'Nama Kelas',
        'table.classTeacher': 'Wali Kelas',
        'table.studentCount': 'Jumlah Siswa',
        'table.classLevel': 'Tingkat Kelas',
        'table.monthlyAmount': 'Nominal Bulanan',
        'table.description': 'Keterangan',
        'table.nis': 'NIS',
        'table.fullName': 'Nama Lengkap',
        'table.class': 'Kelas',
        'table.gender': 'L/P',
        'table.guardian': 'Wali Murid',
        'table.student': 'Siswa',
        'table.period': 'Periode',
        'table.billingAmount': 'Jumlah Tagihan',
        'table.amountPaid': 'Telah Dibayar',
        'table.remainingBill': 'Sisa Tagihan',
        'table.dueDate': 'Jatuh Tempo',
        'table.studentAndClass': 'Siswa & Kelas',
        'table.sppPeriod': 'Periode SPP',
        'table.method': 'Metode',
        'table.proof': 'Bukti',
        'table.recordedBy': 'Dicatat Oleh',
        'table.studentName': 'Nama Siswa',
        'table.remainingArrears': 'Sisa Tunggakan',
        'table.guardianName': 'Nama Wali',
        'table.guardianContact': 'Kontak Wali',
        'table.month': 'Bulan',
        'table.billingTarget': 'Target Penagihan',
        'table.receivedAmount': 'Realisasi Penerimaan',
        'table.billingCount': 'Jumlah Tagihan',
        'table.paidCount': 'Jumlah Lunas',
        'table.paymentRate': 'Tingkat Kelunasan',
        'table.email': 'Email',
        'table.role': 'Role',
        'table.phoneNumber': 'No. Telepon',
        'table.lastLogin': 'Login Terakhir',
        'table.paymentMethod': 'Metode Bayar',
        'table.paymentDate': 'Tanggal Pembayaran',
        'table.downloadReceipt': 'Unduh Tanda Bukti',
        'table.monthAndYear': 'Bulan & Tahun',
        'table.billingPeriod': 'Periode Tagihan',
        'table.emptyAcademicYears': 'Tidak ada data tahun ajaran ditemukan.',
        'table.emptyClasses': 'Tidak ada data kelas ditemukan untuk tahun ajaran yang dipilih.',
        'table.emptyTariffs': 'Tidak ada data tarif SPP untuk tahun ajaran aktif.',
        'table.emptyStudents': 'Tidak ada data siswa ditemukan.',
        'table.emptyStudentBillingHistory': 'Siswa ini belum memiliki data tagihan SPP.',
        'table.emptyBills': 'Tidak ada data tagihan ditemukan.',
        'table.emptyPayments': 'Tidak ada data transaksi pembayaran ditemukan.',
        'table.emptyArrearsReport': 'Luar biasa! Tidak ada data siswa menunggak untuk filter ini.',
        'table.emptyUsers': 'Tidak ada data pengguna ditemukan.',
        'table.emptyReceipts': 'Belum ada riwayat transaksi pembayaran masuk.',
        'table.emptyPortalBills': 'Tidak ada data tagihan SPP untuk putra / putri Anda.',
        'table.processing': 'Diproses'
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
        'app.footer': 'SIP-SPP · MI Al-Haq. All rights reserved.',
        'nav.system': 'System & Users',
        'nav.users': 'User Management',
        'nav.academic': 'Academic Data',
        'nav.tahunAjaran': 'Academic Year',
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
        'login.badge': 'School payment system',
        'table.academicYear': 'Academic Year',
        'table.academicYearName': 'Academic Year Name',
        'table.startYear': 'Start Year',
        'table.endYear': 'End Year',
        'table.status': 'Status',
        'table.action': 'Action',
        'table.level': 'Level',
        'table.className': 'Class Name',
        'table.classTeacher': 'Homeroom Teacher',
        'table.studentCount': 'Student Count',
        'table.classLevel': 'Class Level',
        'table.monthlyAmount': 'Monthly Amount',
        'table.description': 'Description',
        'table.nis': 'NIS',
        'table.fullName': 'Full Name',
        'table.class': 'Class',
        'table.gender': 'Gender',
        'table.guardian': 'Guardian',
        'table.student': 'Student',
        'table.period': 'Period',
        'table.billingAmount': 'Billing Amount',
        'table.amountPaid': 'Amount Paid',
        'table.remainingBill': 'Remaining Bill',
        'table.dueDate': 'Due Date',
        'table.studentAndClass': 'Student & Class',
        'table.sppPeriod': 'SPP Period',
        'table.method': 'Method',
        'table.proof': 'Proof',
        'table.recordedBy': 'Recorded By',
        'table.studentName': 'Student Name',
        'table.remainingArrears': 'Remaining Arrears',
        'table.guardianName': 'Guardian Name',
        'table.guardianContact': 'Guardian Contact',
        'table.month': 'Month',
        'table.billingTarget': 'Billing Target',
        'table.receivedAmount': 'Received Amount',
        'table.billingCount': 'Billing Count',
        'table.paidCount': 'Paid Count',
        'table.paymentRate': 'Payment Rate',
        'table.email': 'Email',
        'table.role': 'Role',
        'table.phoneNumber': 'Phone Number',
        'table.lastLogin': 'Last Login',
        'table.paymentMethod': 'Payment Method',
        'table.paymentDate': 'Payment Date',
        'table.downloadReceipt': 'Download Receipt',
        'table.monthAndYear': 'Month & Year',
        'table.billingPeriod': 'Billing Period',
        'table.emptyAcademicYears': 'No academic years found.',
        'table.emptyClasses': 'No class data found for the selected academic year.',
        'table.emptyTariffs': 'No SPP tariff data for the active academic year.',
        'table.emptyStudents': 'No student data found.',
        'table.emptyStudentBillingHistory': 'No SPP billing history for this student.',
        'table.emptyBills': 'No bills found.',
        'table.emptyPayments': 'No payment transactions found.',
        'table.emptyArrearsReport': 'Outstanding! No students in arrears for this filter.',
        'table.emptyUsers': 'No user data found.',
        'table.emptyReceipts': 'No incoming payment transactions record found.',
        'table.emptyPortalBills': 'No SPP billing data for your children.',
        'table.processing': 'Processing'
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
    const previousTheme = document.documentElement.dataset.theme;
    document.documentElement.dataset.theme = nextTheme;
    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        const active = button.dataset.themeOption === nextTheme;
        button.classList.toggle('active', active);
        button.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
    setStoredPreference('sip_spp_theme', nextTheme);
    if (previousTheme !== nextTheme) {
        window.dispatchEvent(new CustomEvent('sip-theme-change', {
            detail: { theme: nextTheme }
        }));
    }
}

function applyLanguage(lang) {
    const nextLang = lang === 'en' ? 'en' : 'id';
    const dict = translations[nextLang];
    const previousLang = document.documentElement.lang;
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
    if (previousLang !== nextLang) {
        window.dispatchEvent(new CustomEvent('sip-language-change', {
            detail: { lang: nextLang }
        }));
    }
}

// Expose preferences to window for PJAX re-run
window.applySipTheme = applyTheme;
window.applySipLanguage = applyLanguage;
window.getStoredPreference = getStoredPreference;

window.commandPaletteComponent = function commandPaletteComponent(menus = []) {
    return {
        open: false,
        search: '',
        selectedIndex: 0,
        menus,
        init() {
            this.$watch('open', (value) => {
                document.body.classList.toggle('overflow-hidden', value);
            });
            this.$watch('search', () => {
                this.selectedIndex = 0;
            });
        },
        openPalette() {
            this.open = true;
            this.search = '';
            this.selectedIndex = 0;
            setTimeout(() => {
                if (this.$refs.searchInput) {
                    this.$refs.searchInput.focus();
                }
            }, 50);
        },
        get filteredMenus() {
            if (!this.search) return this.menus;
            const query = this.search.toLowerCase();
            return this.menus.filter((menu) => {
                return menu.name.toLowerCase().includes(query)
                    || menu.category.toLowerCase().includes(query);
            });
        },
        selectNext() {
            if (this.selectedIndex < this.filteredMenus.length - 1) {
                this.selectedIndex++;
                this.scrollToSelected();
            }
        },
        selectPrev() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
                this.scrollToSelected();
            }
        },
        navigateSelected() {
            const item = this.filteredMenus[this.selectedIndex];
            if (!item) return;

            this.open = false;
            window.location.href = item.url;
        },
        scrollToSelected() {
            this.$nextTick(() => {
                const container = this.$refs.resultsContainer;
                if (!container) return;
                const selectedEl = container.querySelector('[data-selected]');
                if (selectedEl) {
                    selectedEl.scrollIntoView({ block: 'nearest' });
                }
            });
        },
    };
};

function setupSearchTriggers() {
    document.querySelectorAll('[data-open-search]').forEach((trigger) => {
        if (trigger.dataset.searchTriggerBound === 'true') return;
        trigger.dataset.searchTriggerBound = 'true';

        trigger.addEventListener('click', () => {
            window.dispatchEvent(new CustomEvent('open-search'));
        });
    });
}

function setupAutoSubmitSearch() {
    document.querySelectorAll('input[data-auto-submit-search]').forEach((input) => {
        if (input.dataset.autoSubmitBound === 'true') return;
        input.dataset.autoSubmitBound = 'true';

        let timer = null;
        let previousValue = input.value;

        const submitForm = () => {
            const form = input.closest('form');
            if (!form || input.value === previousValue) return;
            previousValue = input.value;

            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.submit();
            }
        };

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(submitForm, 550);
        });

        input.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;
            event.preventDefault();
            clearTimeout(timer);
            submitForm();
        });
    });
}

function setupMotionEnhancements() {
    const root = document.querySelector('[data-motion-root]');
    if (!root) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const selectors = [
        '.page-heading-motion',
        '.dashboard-stat-primary',
        '.dashboard-stat-card',
        '.dashboard-panel',
        '.card-premium',
        'form[data-filter-form]',
        'table',
        '.pagination-premium'
    ];

    const elements = Array.from(root.querySelectorAll(selectors.join(',')))
        .filter((element, index, list) => {
            if (element.closest('.modal-premium-backdrop')) return false;
            return list.indexOf(element) === index;
        });

    elements.forEach((element, index) => {
        element.classList.add('motion-reveal');
        const customDelay = element.dataset.revealDelay;
        element.style.setProperty('--reveal-delay', customDelay ? `${customDelay}ms` : `${Math.min(index, 8) * 45}ms`);
        if (prefersReducedMotion) {
            element.classList.add('is-visible');
        } else {
            element.classList.remove('is-visible');
        }
    });

    if (prefersReducedMotion) {
        root.querySelectorAll('.chart-shell').forEach((shell) => {
            shell.classList.add('is-chart-ready');
            shell.setAttribute('aria-busy', 'false');
        });
        return;
    }

    root.querySelectorAll('.chart-shell').forEach((shell) => {
        if (!shell.classList.contains('is-chart-ready')) {
            shell.setAttribute('aria-busy', 'true');
        }
    });

    if (!('IntersectionObserver' in window)) {
        requestAnimationFrame(() => {
            elements.forEach((element) => element.classList.add('is-visible'));
        });
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.08
    });

    elements.forEach((element) => {
        if (element.dataset.revealEager === 'true') {
            requestAnimationFrame(() => element.classList.add('is-visible'));
            return;
        }

        observer.observe(element);
    });

    window.setTimeout(() => {
        elements.forEach((element) => {
            element.classList.add('is-visible');
            observer.unobserve(element);
        });
    }, 900);
}

function initializeInteractiveControls() {
    setupSearchTriggers();
    setupAutoSubmitSearch();
    setupMotionEnhancements();
}

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getStoredPreference('sip_spp_theme', document.documentElement.dataset.theme || 'light'));
    applyLanguage(getStoredPreference('sip_spp_lang', document.documentElement.lang || 'id'));
    initializeInteractiveControls();

    document.querySelectorAll('[data-theme-option]').forEach((button) => {
        button.addEventListener('click', () => applyTheme(button.dataset.themeOption));
    });

    document.querySelectorAll('[data-lang-option]').forEach((button) => {
        button.addEventListener('click', () => applyLanguage(button.dataset.langOption));
    });
});

document.addEventListener('pjax:success', initializeInteractiveControls);

Alpine.start();
