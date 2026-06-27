@extends('layouts.app')

@section('page_title', 'Dashboard Utama')
@section('page_subtitle', 'Tinjauan real-time status pembayaran SPP dan tunggakan siswa')

@section('content')
@php
    $chartPeak = collect($trenData)->max();
    $paidRateWidth = min(100, max(0, (float) $persentaseLunas));
@endphp
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="p-6 rounded-2xl shadow-lg border border-blue-600/10 bg-gradient-to-br from-blue-600 via-blue-700 to-slate-900 text-white flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-xl shrink-0">
        <div class="space-y-1.5">
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-widest block" data-i18n="dashboard.activeStudents">Total Siswa Aktif</span>
            <span class="text-3xl font-black text-white leading-none tracking-tight block font-mono">{{ number_format($totalSiswa, 0, ',', '.') }}</span>
            <span class="text-[10px] font-bold text-blue-100/70 block" data-i18n="dashboard.activeStudentsHelp">Siswa terdaftar aktif</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-white/10 text-white flex items-center justify-center border border-white/20 shrink-0 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="dashboard-stat-card dashboard-stat-paid p-6 rounded-2xl shadow-sm border bg-white flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-md card-premium">
        <div class="space-y-2.5 min-w-0">
            <span class="dashboard-stat-label text-[10px] font-bold uppercase tracking-widest block" data-i18n="dashboard.paidThisMonth">Lunas Bulan Ini</span>
            <span class="dashboard-stat-value text-3xl font-black leading-none tracking-tight block font-mono">{{ number_format($lunasBulanIni, 0, ',', '.') }}</span>
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="dashboard-stat-chip dashboard-stat-chip-paid text-[9px] font-black px-2 py-0.5 rounded-lg">{{ $persentaseLunas }}%</span>
                <span class="dashboard-stat-help text-[10px] font-bold" data-i18n="dashboard.paidRate">tingkat kelunasan</span>
            </div>
            <div class="dashboard-stat-track" aria-hidden="true">
                <span class="dashboard-stat-fill" style="width: {{ $paidRateWidth }}%"></span>
            </div>
        </div>
        <div class="dashboard-stat-icon dashboard-stat-icon-paid w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="dashboard-stat-card dashboard-stat-unpaid p-6 rounded-2xl shadow-sm border bg-white flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-md card-premium">
        <div class="space-y-2.5 min-w-0">
            <span class="dashboard-stat-label text-[10px] font-bold uppercase tracking-widest block" data-i18n="dashboard.unpaidThisMonth">Belum Lunas Bulan Ini</span>
            <span class="dashboard-stat-value text-3xl font-black leading-none tracking-tight block font-mono">{{ number_format($menunggakBulanIni, 0, ',', '.') }}</span>
            <span class="dashboard-stat-help text-[10px] font-bold block" data-i18n="dashboard.unpaidHelp">Siswa belum membayar</span>
        </div>
        <div class="dashboard-stat-icon dashboard-stat-icon-unpaid w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="dashboard-stat-card dashboard-stat-arrears p-6 rounded-2xl shadow-sm border bg-white flex items-center justify-between transition-all duration-200 hover:-translate-y-1 hover:shadow-md card-premium">
        <div class="space-y-2.5 min-w-0">
            <span class="dashboard-stat-label text-[10px] font-bold uppercase tracking-widest block" data-i18n="dashboard.totalArrears">Tunggakan Kumulatif</span>
            <span class="dashboard-stat-value text-xl font-black tracking-tight block font-mono">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</span>
            <span class="dashboard-stat-help text-[10px] font-bold block" data-i18n="dashboard.allYears">Semua tahun ajaran</span>
        </div>
        <div class="dashboard-stat-icon dashboard-stat-icon-arrears w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Chart & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <!-- Chart Card -->
    <div class="lg:col-span-2 rounded-2xl p-6 dashboard-panel">
        <div class="dashboard-panel-heading mb-5 pb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xs font-bold text-slate-800 uppercase" data-i18n="dashboard.paymentTrend">Tren Penerimaan Pembayaran</h2>
                <p class="mt-1 text-[11px] font-semibold text-slate-400" data-i18n="dashboard.paymentTrendHelp">Akumulasi pembayaran lunas tahun ini</p>
            </div>
            <div class="flex items-center gap-4 self-start sm:self-auto flex-wrap">
                <div class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">
                    <span class="legend-color legend-current h-2.5 w-2.5 rounded-sm"></span>
                    <span>Bulan Ini</span>
                </div>
                <div class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                    <span class="legend-color legend-past h-2.5 w-2.5 rounded-sm"></span>
                    <span>Sebelumnya</span>
                </div>
                <div class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-300 dark:text-slate-600">
                    <span class="legend-color legend-future h-2.5 w-2.5 rounded-sm"></span>
                    <span>Mendatang</span>
                </div>
                <div class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-blue-500 dark:text-blue-400">
                    <span class="h-0.5 w-4 rounded-full bg-blue-500 dark:bg-blue-400"></span>
                    <span>Tren</span>
                </div>
            </div>
        </div>
        <div class="chart-shell h-[21rem] min-h-80">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>

    <!-- Top Arrears Students -->
    <div class="lg:col-span-1 rounded-2xl p-6 dashboard-panel flex flex-col">
        <div class="dashboard-panel-heading mb-5 pb-4">
            <h2 class="text-xs font-bold text-slate-800 uppercase" data-i18n="dashboard.topArrears">Tunggakan Tertinggi</h2>
            <p class="mt-1 text-[11px] font-semibold text-slate-400" data-i18n="dashboard.topArrearsHelp">Prioritas follow-up pembayaran</p>
        </div>
        <div class="flex-1 space-y-3">
            @forelse ($tunggakanTertinggi as $siswa)
                <div class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white/72 px-3 py-3 transition-all hover:border-blue-100 hover:bg-blue-50/35">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-900 text-[10px] font-black text-white shadow-sm">
                            {{ $loop->iteration }}
                        </span>
                        <div class="flex flex-col gap-0.5 min-w-0">
                            <span class="text-xs font-extrabold text-slate-800 truncate">{{ $siswa->nama_lengkap }}</span>
                            <span class="text-[9px] text-blue-500 font-bold uppercase tracking-wider">Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-rose-600 shrink-0 whitespace-nowrap bg-rose-50 border border-rose-100 px-2.5 py-1 rounded-xl font-mono">
                        Rp {{ number_format($siswa->total_tunggakan, 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center text-slate-400 gap-2">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-400" data-i18n="dashboard.noArrears">Tidak ada tunggakan siswa.</span>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="rounded-2xl p-6 dashboard-panel">
    <h2 class="dashboard-panel-heading text-xs font-bold text-slate-800 uppercase mb-6 pb-3" data-i18n="dashboard.latestTransactions">10 Transaksi Pembayaran Terbaru</h2>
    <div class="overflow-x-auto -mx-6">
        <div class="inline-block min-w-full align-middle px-6">
            <table class="min-w-full divide-y divide-blue-50 text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-blue-500/80 uppercase tracking-wider bg-blue-50/40">
                        <th class="px-6 py-4" data-i18n="dashboard.student">Siswa</th>
                        <th class="px-6 py-4" data-i18n="dashboard.class">Kelas</th>
                        <th class="px-6 py-4" data-i18n="dashboard.period">Periode Tagihan</th>
                        <th class="px-6 py-4" data-i18n="dashboard.amount">Jumlah Bayar</th>
                        <th class="px-6 py-4" data-i18n="dashboard.method">Metode</th>
                        <th class="px-6 py-4" data-i18n="dashboard.status">Status</th>
                        <th class="px-6 py-4 text-right" data-i18n="dashboard.transactionDate">Tanggal Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-blue-50/50 text-slate-700">
                    @forelse ($transaksiTerbaru as $t)
                        <tr class="text-xs hover:bg-blue-50/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 font-bold flex items-center justify-center text-blue-500 text-[10px] shrink-0 border border-blue-100/30">
                                    {{ strtoupper(substr($t->tagihan->siswa->nama_lengkap ?? '-', 0, 1)) }}
                                </div>
                                <span class="truncate max-w-[150px]">{{ $t->tagihan->siswa->nama_lengkap ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-500">{{ $t->tagihan->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-4 font-semibold text-slate-600">
                                @php
                                    $bulanName = \Carbon\Carbon::create()->month($t->tagihan->bulan)->translatedFormat('F');
                                @endphp
                                {{ $bulanName }} {{ $t->tagihan->tahun }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 font-mono">Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 font-bold text-slate-500 uppercase tracking-wider text-[10px]">{{ $t->metode_pembayaran }}</td>
                            <td class="px-6 py-4">
                                @if ($t->status_verifikasi === 'disetujui' || $t->status_verifikasi === 'terverifikasi')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span data-i18n="dashboard.paid">Lunas</span>
                                    </span>
                                @elseif ($t->status_verifikasi === 'menunggu_verifikasi' || $t->status_verifikasi === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        <span data-i18n="dashboard.pending">Pending</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        <span data-i18n="dashboard.rejected">Ditolak</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-slate-400 font-semibold font-mono">{{ $t->created_at->translatedFormat('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-400 gap-2 font-semibold">
                                <span data-i18n="dashboard.noTransactions">Tidak ada transaksi pembayaran terbaru.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .legend-current { background-color: #2563EB; }
    .legend-past { background-color: #CBD5E1; }
    .legend-future { background-color: #E2E8F0; }
    [data-theme='dark'] .legend-past { background-color: #334155; }
    [data-theme='dark'] .legend-future { background-color: #1E293B; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('paymentChart');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const chartData = @json($trenData).map((value) => Number(value || 0));
        const monthNow = {{ now()->month - 1 }};
        const maxValue = Math.max(...chartData, 0);

        const currencyFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        });

        const isDark = document.documentElement.dataset.theme === 'dark';

        // Trendline data: only up to current month, null for future months (line won't render there)
        const trendLineData = chartData.map((val, i) => i <= monthNow ? val : null);

        // Per-bar colors: current month = vibrant blue, past = soft slate, future = very faint
        const barColors = chartData.map((val, i) => {
            if (i === monthNow) return '#2563EB';
            if (i < monthNow)   return isDark ? '#334155' : '#CBD5E1';
            return isDark ? '#1E293B' : '#E2E8F0';
        });

        const barHoverColors = chartData.map((val, i) => {
            if (i === monthNow) return '#1D4ED8';
            if (i < monthNow)   return isDark ? '#475569' : '#94A3B8';
            return isDark ? '#334155' : '#CBD5E1';
        });

        // Floating value label plugin — shows value above current month bar
        const floatingLabelPlugin = {
            id: 'floatingLabel',
            afterDatasetsDraw(chart) {
                const meta = chart.getDatasetMeta(0);
                const bar = meta?.data?.[monthNow];
                if (!bar) return;
                const value = chartData[monthNow];
                if (!value) return;

                const { ctx: c } = chart;
                c.save();

                const text = currencyFormatter.format(value);
                c.font = "bold 10px 'Plus Jakarta Sans', sans-serif";
                const textWidth = c.measureText(text).width;
                const pillW = textWidth + 16;
                const pillH = 22;
                const pillX = bar.x - pillW / 2;
                const pillY = bar.y - pillH - 8;

                c.beginPath();
                c.roundRect(pillX, pillY, pillW, pillH, 6);
                c.fillStyle = isDark ? '#1E293B' : '#0F172A';
                c.fill();

                c.fillStyle = '#FFFFFF';
                c.textAlign = 'center';
                c.textBaseline = 'middle';
                c.fillText(text, bar.x, pillY + pillH / 2);

                c.beginPath();
                c.moveTo(bar.x - 4, pillY + pillH);
                c.lineTo(bar.x, pillY + pillH + 4);
                c.lineTo(bar.x + 4, pillY + pillH);
                c.closePath();
                c.fillStyle = isDark ? '#1E293B' : '#0F172A';
                c.fill();

                c.restore();
            }
        };

        // Premium trendline effects plugin — shadow, custom points, animated pulse
        let pulsePhase = 0;
        const trendEffectsPlugin = {
            id: 'trendEffects',
            beforeDatasetDraw(chart, args) {
                if (args.index !== 1) return;
                const { ctx: c } = chart;
                c.save();
                c.shadowColor = isDark ? 'rgba(59, 130, 246, 0.35)' : 'rgba(37, 99, 235, 0.28)';
                c.shadowBlur = 16;
                c.shadowOffsetX = 0;
                c.shadowOffsetY = 8;
            },
            afterDatasetDraw(chart, args) {
                if (args.index !== 1) return;
                const { ctx: c } = chart;
                c.restore(); 

                const meta = chart.getDatasetMeta(1);
                if (!meta || !meta.data) return;

                meta.data.forEach((pt, i) => {
                    if (trendLineData[i] === null || trendLineData[i] === undefined) return;

                    const x = pt.x;
                    const y = pt.y;
                    const accentColor = isDark ? '#3B82F6' : '#2563EB';

                    if (i === monthNow) {
                        const pulse = 0.4 + Math.sin(pulsePhase) * 0.25;
                        const ringRadius = 12 + Math.sin(pulsePhase) * 3;

                        c.save();
                        c.beginPath();
                        c.arc(x, y, ringRadius, 0, Math.PI * 2);
                        c.fillStyle = isDark
                            ? `rgba(59, 130, 246, ${pulse * 0.18})`
                            : `rgba(37, 99, 235, ${pulse * 0.15})`;
                        c.fill();
                        c.restore();

                        c.save();
                        c.beginPath();
                        c.arc(x, y, 7, 0, Math.PI * 2);
                        c.fillStyle = isDark
                            ? 'rgba(59, 130, 246, 0.15)'
                            : 'rgba(37, 99, 235, 0.12)';
                        c.fill();
                        c.restore();

                        c.save();
                        c.beginPath();
                        c.arc(x, y, 5, 0, Math.PI * 2);
                        c.fillStyle = accentColor;
                        c.fill();
                        c.restore();

                        c.save();
                        c.beginPath();
                        c.arc(x, y, 2.5, 0, Math.PI * 2);
                        c.fillStyle = '#FFFFFF';
                        c.fill();
                        c.restore();
                    } else {
                        c.save();

                        c.beginPath();
                        c.arc(x, y, 5, 0, Math.PI * 2);
                        c.fillStyle = isDark
                            ? 'rgba(59, 130, 246, 0.08)'
                            : 'rgba(37, 99, 235, 0.06)';
                        c.fill();

                        c.beginPath();
                        c.arc(x, y, 3, 0, Math.PI * 2);
                        c.fillStyle = isDark ? '#1E293B' : '#FFFFFF';
                        c.fill();
                        c.strokeStyle = accentColor;
                        c.lineWidth = 1.5;
                        c.stroke();

                        c.restore();
                    }
                });
            }
        };

        const pulseAnimationPlugin = {
            id: 'pulseAnimation',
            afterDraw(chart) {
                pulsePhase += 0.04;
                if (pulsePhase > Math.PI * 200) pulsePhase = 0;
                requestAnimationFrame(() => chart.draw());
            }
        };

        let cachedGradient = null;
        const createLineGradient = (chart) => {
            const area = chart.chartArea;
            if (!area) return 'rgba(37, 99, 235, 0.05)';
            if (cachedGradient) return cachedGradient;
            const gradient = chart.ctx.createLinearGradient(0, area.top, 0, area.bottom);
            if (isDark) {
                gradient.addColorStop(0,   'rgba(59, 130, 246, 0.28)');
                gradient.addColorStop(0.3, 'rgba(59, 130, 246, 0.14)');
                gradient.addColorStop(0.6, 'rgba(59, 130, 246, 0.05)');
                gradient.addColorStop(1,   'rgba(59, 130, 246, 0)');
            } else {
                gradient.addColorStop(0,   'rgba(37, 99, 235, 0.22)');
                gradient.addColorStop(0.3, 'rgba(37, 99, 235, 0.10)');
                gradient.addColorStop(0.6, 'rgba(37, 99, 235, 0.03)');
                gradient.addColorStop(1,   'rgba(37, 99, 235, 0)');
            }
            cachedGradient = gradient;
            return gradient;
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Penerimaan SPP',
                        data: chartData,
                        backgroundColor: barColors,
                        hoverBackgroundColor: barHoverColors,
                        borderWidth: 0,
                        borderRadius: {
                            topLeft: 6,
                            topRight: 6,
                            bottomLeft: 2,
                            bottomRight: 2
                        },
                        borderSkipped: false,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Tren Penerimaan',
                        data: trendLineData,
                        borderColor: isDark ? '#3B82F6' : '#2563EB',
                        borderWidth: 2.5,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: function(context) {
                            return createLineGradient(context.chart);
                        },
                        pointRadius: 0,
                        pointHoverRadius: 0,
                        pointHitRadius: 10,
                        spanGaps: false,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                animation: {
                    duration: 700,
                    easing: 'easeOutQuart',
                    delay: (context) => context.dataIndex * 40
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        padding: { top: 10, bottom: 10, left: 14, right: 14 },
                        backgroundColor: '#0F172A',
                        titleColor: '#94A3B8',
                        bodyColor: '#FFFFFF',
                        borderColor: 'rgba(255, 255, 255, 0.06)',
                        borderWidth: 1,
                        cornerRadius: 10,
                        caretPadding: 6,
                        caretSize: 5,
                        titleFont: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '600',
                            size: 10
                        },
                        bodyFont: {
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '700',
                            size: 13
                        },
                        displayColors: false,
                        filter: function(tooltipItem) {
                            return tooltipItem.datasetIndex === 0;
                        },
                        callbacks: {
                            title: function(items) {
                                const idx = items[0].dataIndex;
                                const suffix = idx === monthNow ? ' (Bulan Ini)' : '';
                                return items[0].label + suffix;
                            },
                            label: function(context) {
                                return currencyFormatter.format(context.parsed.y || 0);
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 36,
                        right: 4,
                        bottom: 0,
                        left: 0
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: maxValue > 0 ? maxValue * 1.22 : 1000000,
                        border: {
                            display: false
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.10)',
                            drawTicks: false
                        },
                        ticks: {
                            color: '#94A3B8',
                            padding: 12,
                            maxTicksLimit: 6,
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                size: 10,
                                weight: '600'
                            },
                            callback: function(value) {
                                if (value === 0) return 'Rp 0';
                                if (value >= 1000000) return 'Rp ' + (value / 1000000).toLocaleString('id-ID') + 'jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'rb';
                                return 'Rp ' + Number(value).toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: (context) => context.index === monthNow ? '#2563EB' : '#94A3B8',
                            padding: 8,
                            font: {
                                family: "'Plus Jakarta Sans', sans-serif",
                                size: 10.5,
                                weight: (context) => context.index === monthNow ? '800' : '500'
                            }
                        }
                    }
                }
            },
            plugins: [trendEffectsPlugin, floatingLabelPlugin, pulseAnimationPlugin]
        });
    });
</script>
@endsection
