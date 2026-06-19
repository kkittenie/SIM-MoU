@extends('layouts.app', ['title' => 'Dashboard BKK'])

@push('styles')
<style>
    /* ═══════════════════════════════════════════════ */
    /*  MODERN ENTRANCE ANIMATIONS                    */
    /* ═══════════════════════════════════════════════ */
    @keyframes scaleBlurIn {
        0% {
            opacity: 0;
            transform: scale(0.92) translateY(16px);
            filter: blur(6px);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0);
            filter: blur(0);
        }
    }

    @keyframes slideInRight {
        0% {
            opacity: 0;
            transform: translateX(30px);
            filter: blur(4px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
            filter: blur(0);
        }
    }

    @keyframes popIn {
        0% {
            opacity: 0;
            transform: scale(0.6);
        }
        60% {
            transform: scale(1.04);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes countUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .anim-scale-blur {
        opacity: 0;
        animation: scaleBlurIn 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .anim-slide-right {
        opacity: 0;
        animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .anim-pop {
        opacity: 0;
        animation: popIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    .anim-count {
        animation: countUp 0.4s ease-out forwards;
    }

    /* Staggered delays */
    .delay-0 { animation-delay: 0ms; }
    .delay-1 { animation-delay: 60ms; }
    .delay-2 { animation-delay: 120ms; }
    .delay-3 { animation-delay: 180ms; }
    .delay-4 { animation-delay: 240ms; }
    .delay-5 { animation-delay: 350ms; }
    .delay-6 { animation-delay: 450ms; }
    .delay-7 { animation-delay: 550ms; }

    /* ═══════════════════════════════════════════════ */
    /*  METRIC CARDS                                   */
    /* ═══════════════════════════════════════════════ */
    .metric-card {
        position: relative;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        overflow: hidden;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px -8px rgba(0,0,0,0.12);
    }
    .dark .metric-card:hover {
        box-shadow: 0 12px 32px -8px rgba(0,0,0,0.4);
    }
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 1rem 1rem 0 0;
    }
    .metric-card.card-alumni::before { background: linear-gradient(90deg, #3b82f6, #60a5fa); }
    .metric-card.card-mitra::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .metric-card.card-loker::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .metric-card.card-tracer::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

    /* Icon container with gradient bg */
    .metric-icon {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .metric-icon.icon-alumni   { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .metric-icon.icon-mitra    { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
    .metric-icon.icon-loker    { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .metric-icon.icon-tracer   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }

    .dark .metric-icon.icon-alumni   { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .metric-icon.icon-mitra    { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .metric-icon.icon-loker    { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .metric-icon.icon-tracer   { background: rgba(245,158,11,0.15); color: #fbbf24; }

    /* ═══════════════════════════════════════════════ */
    /*  CHART PANELS                                   */
    /* ═══════════════════════════════════════════════ */
    .chart-panel {
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
    }
    .chart-panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px -6px rgba(0,0,0,0.1);
    }
    .dark .chart-panel:hover {
        box-shadow: 0 8px 24px -6px rgba(0,0,0,0.35);
    }
    .chart-panel-header {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 1rem;
    }
    .chart-panel-dot {
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* ═══════════════════════════════════════════════ */
    /*  INSIGHT CARDS                                  */
    /* ═══════════════════════════════════════════════ */
    .insight-card {
        border-radius: 0.75rem;
        padding: 1rem 1.125rem;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }
    .insight-card:hover {
        transform: translateX(3px);
    }

    /* ═══════════════════════════════════════════════ */
    /*  WELCOME BANNER                                 */
    /* ═══════════════════════════════════════════════ */
    .welcome-banner {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #075985 100%);
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.12) 0%, transparent 60%),
                    radial-gradient(ellipse at 20% 80%, rgba(255,255,255,0.08) 0%, transparent 50%);
        pointer-events: none;
    }
    .welcome-banner-pattern {
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
</style>
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Dashboard BKK" />

    {{-- ════════════════════════════════════════════════ --}}
    {{--  WELCOME BANNER                                 --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="welcome-banner p-6 mb-6 anim-scale-blur delay-0">
        <div class="welcome-banner-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">
                    Dashboard Bursa Kerja Khusus (BKK) 👋
                </h2>
                <p class="mt-1.5 text-sm text-sky-100/90 leading-relaxed max-w-xl">
                    Selamat datang, {{ auth()->user()->name }}. Kelola data penyerapan alumni bekerja, perusahaan mitra kerja sama, publikasikan lowongan kerja, dan pantau hasil Tracer Study dengan mudah.
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('alumni-bekerja.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-sky-700 text-sm font-semibold transition-all duration-200 hover:bg-sky-50 shadow-xs border border-transparent whitespace-nowrap">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Kerja Alumni
                </a>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{--  SUMMARY METRIC CARDS                           --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 md:gap-5 mb-6">
        {{-- Alumni Bekerja --}}
        <div class="metric-card card-alumni border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Bekerja</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 anim-count" data-count="{{ $totalAlumniBekerja }}">{{ $totalAlumniBekerja }}</h4>
                </div>
                <div class="metric-icon icon-alumni">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
            </div>
        </div>

        {{-- Perusahaan Mitra --}}
        <div class="metric-card card-mitra border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Perusahaan Mitra</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-green-600 dark:text-green-400 anim-count" data-count="{{ $totalPerusahaanMitra }}">{{ $totalPerusahaanMitra }}</h4>
                </div>
                <div class="metric-icon icon-mitra">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="10" width="20" height="11" rx="2"/><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/></svg>
                </div>
            </div>
        </div>

        {{-- Lowongan Kerja Aktif --}}
        <div class="metric-card card-loker border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loker Aktif</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-purple-600 dark:text-purple-400 anim-count" data-count="{{ $totalLowonganKerja }}">{{ $totalLowonganKerja }}</h4>
                </div>
                <div class="metric-icon icon-loker">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                </div>
            </div>
        </div>

        {{-- Tracer Study Respon --}}
        <div class="metric-card card-tracer border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Respon Tracer</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400 anim-count" data-count="{{ $totalTracerStudy }}">{{ $totalTracerStudy }}</h4>
                </div>
                <div class="metric-icon icon-tracer">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════ --}}
    {{--  INSIGHT PANEL                                  --}}
    {{-- ════════════════════════════════════════════════ --}}
    @if(count($insights) > 0)
        <div class="rounded-2xl border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm mb-6 anim-scale-blur delay-5">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-indigo-500 text-white shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </span>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Wawasan Otomatis (Insights)</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Analisis cerdas berdasarkan keterserapan alumni BKK</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($insights as $index => $insight)
                    <div class="insight-card border border-gray-100 dark:border-gray-800 bg-gray-50/60 dark:bg-white/[0.02] flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-md bg-sky-100 dark:bg-sky-500/15 text-sky-600 dark:text-sky-400 font-bold text-[10px] mt-0.5">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                            {{ $insight }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ════════════════════════════════════════════════ --}}
    {{--  CHARTS GRID                                    --}}
    {{-- ════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        {{-- Chart 1: Persentase Alumni Bekerja (Donut) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-6">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #3b82f6, #60a5fa)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Status Responden Alumni (Tracer Study)</h3>
            </div>
            <div class="flex justify-center">
                <div id="statusChart" class="w-full max-w-[320px]"></div>
            </div>
        </div>

        {{-- Chart 2: Perusahaan yang Paling Banyak Merekrut (Horizontal Bar) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-6">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #10b981, #34d399)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Top Mitra Perekrut Alumni Terbanyak</h3>
            </div>
            <div id="perusahaanChart" class="w-full"></div>
        </div>

        {{-- Chart 3: Bidang Industri Terbanyak (Donut) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-7">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #f59e0b, #fbbf24)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Penyebaran Berdasarkan Sektor Industri</h3>
            </div>
            <div class="flex justify-center">
                <div id="industriChart" class="w-full max-w-[320px]"></div>
            </div>
        </div>

        {{-- Chart 4: Penyerapan Alumni per Tahun (Area) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-7">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #8b5cf6, #c084fc)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Grafik Penyerapan Alumni per Tahun Lulus</h3>
            </div>
            <div id="penyerapanChart" class="w-full"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ──── Theme Configuration ────
    const isDark = document.documentElement.classList.contains('dark');
    const gridBorder = isDark ? '#1e293b' : '#f1f5f9';
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const fontFamily = 'Outfit, Inter, system-ui, sans-serif';

    // ──── 1. Status Alumni (Donut) ────
    new ApexCharts(document.querySelector("#statusChart"), {
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            }
        },
        series: @json($statusChartData['series']),
        labels: @json($statusChartData['labels']),
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom',
            fontSize: '12px',
            fontWeight: 500,
            labels: { colors: labelColor },
            markers: { size: 6, offsetX: -3 }
        },
        stroke: { show: true, width: 3, colors: [isDark ? '#111827' : '#ffffff'] },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: { fontSize: '13px', fontWeight: 600, color: labelColor },
                        value: {
                            fontSize: '22px',
                            fontWeight: 700,
                            color: isDark ? '#e2e8f0' : '#1e293b',
                            formatter: v => v
                        },
                        total: {
                            show: true,
                            label: 'Total Alumni',
                            color: labelColor,
                            fontSize: '12px',
                            fontWeight: 500,
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false }
    }).render();

    // ──── 2. Perusahaan yang Paling Banyak Merekrut (Bar) ────
    const perusahaanLabels = @json($perusahaanChartData['labels']);
    const perusahaanSeries = @json($perusahaanChartData['series']);
    
    new ApexCharts(document.querySelector("#perusahaanChart"), {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 600,
            }
        },
        series: [{
            name: 'Jumlah Alumni',
            data: perusahaanSeries
        }],
        xaxis: {
            categories: perusahaanLabels,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: labelColor, fontSize: '11px' }
            }
        },
        yaxis: {
            labels: {
                style: { colors: labelColor, fontSize: '11px', fontWeight: 500 },
                formatter: v => Math.floor(v)
            }
        },
        colors: ['#10b981'],
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 6,
                barHeight: '50%',
                distributed: false
            }
        },
        legend: { show: false },
        grid: {
            borderColor: gridBorder,
            strokeDashArray: 4,
            padding: { left: 15, right: 25 }
        },
        dataLabels: {
            enabled: true,
            formatter: v => v + ' org',
            style: { fontSize: '10px', fontWeight: 600 },
            offsetX: 4
        }
    }).render();

    // ──── 3. Bidang Industri Terbanyak (Donut) ────
    new ApexCharts(document.querySelector("#industriChart"), {
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            }
        },
        series: @json($industriChartData['series']),
        labels: @json($industriChartData['labels']),
        colors: ['#f59e0b', '#3b82f6', '#10b981', '#8b5cf6', '#ec4899', '#06b6d4'],
        legend: {
            position: 'bottom',
            fontSize: '11px',
            fontWeight: 500,
            labels: { colors: labelColor },
            markers: { size: 5, offsetX: -3 }
        },
        stroke: { show: true, width: 3, colors: [isDark ? '#111827' : '#ffffff'] },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: { fontSize: '12px', fontWeight: 600, color: labelColor },
                        value: {
                            fontSize: '20px',
                            fontWeight: 700,
                            color: isDark ? '#e2e8f0' : '#1e293b',
                            formatter: v => v
                        },
                        total: {
                            show: true,
                            label: 'Jumlah Pekerja',
                            color: labelColor,
                            fontSize: '11px',
                            fontWeight: 500,
                            formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false }
    }).render();

    // ──── 4. Penyerapan Alumni per Tahun (Area) ────
    new ApexCharts(document.querySelector("#penyerapanChart"), {
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
            }
        },
        series: [{
            name: 'Alumni Terserap',
            data: @json($penyerapanChartData['series'])
        }],
        xaxis: {
            categories: @json($penyerapanChartData['labels']),
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { style: { colors: labelColor, fontSize: '11px', fontWeight: 500 } }
        },
        yaxis: {
            labels: {
                style: { colors: labelColor, fontSize: '11px' },
                formatter: v => Math.floor(v)
            }
        },
        colors: ['#8b5cf6'],
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                type: 'vertical',
                stops: [0, 100],
                colorStops: [
                    { offset: 0, color: '#8b5cf6', opacity: 0.35 },
                    { offset: 100, color: '#8b5cf6', opacity: 0.02 }
                ]
            }
        },
        markers: {
            size: 4,
            colors: ['#8b5cf6'],
            strokeColors: isDark ? '#111827' : '#ffffff',
            strokeWidth: 2,
            hover: { size: 6 }
        },
        grid: { borderColor: gridBorder, strokeDashArray: 4 }
    }).render();

    // ──── Animated Counters ────
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.getAttribute('data-count'), 10);
        if (isNaN(target) || target === 0) return;
        const duration = 1200;
        const start = performance.now();
        const step = ts => {
            const progress = Math.min((ts - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target;
        };
        el.textContent = '0';
        requestAnimationFrame(step);
    });
});
</script>
@endpush
