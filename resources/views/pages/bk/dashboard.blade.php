@extends('layouts.app', ['title' => 'Dashboard BK'])

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
    .metric-card.card-univ::before { background: linear-gradient(90deg, #10b981, #34d399); }
    .metric-card.card-aktif::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
    .metric-card.card-lulus::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .metric-card.card-cuti::before { background: linear-gradient(90deg, #ef4444, #f87171); }

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
    .metric-icon.icon-alumni  { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #2563eb; }
    .metric-icon.icon-univ    { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #059669; }
    .metric-icon.icon-aktif   { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #7c3aed; }
    .metric-icon.icon-lulus   { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #d97706; }
    .metric-icon.icon-cuti    { background: linear-gradient(135deg, #fee2e2, #fecaca); color: #dc2626; }

    .dark .metric-icon.icon-alumni  { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .dark .metric-icon.icon-univ    { background: rgba(16,185,129,0.15); color: #34d399; }
    .dark .metric-icon.icon-aktif   { background: rgba(139,92,246,0.15); color: #a78bfa; }
    .dark .metric-icon.icon-lulus   { background: rgba(245,158,11,0.15); color: #fbbf24; }
    .dark .metric-icon.icon-cuti    { background: rgba(239,68,68,0.15); color: #f87171; }

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
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
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
    <x-common.page-breadcrumb pageTitle="Dashboard BK" />

    {{-- WELCOME BANNER --}}
    <div class="welcome-banner p-6 mb-6 anim-scale-blur delay-0">
        <div class="welcome-banner-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">
                    Selamat Datang, {{ auth()->user()->nama_lengkap ?? 'BK' }}! 👋
                </h2>
                <p class="mt-1.5 text-sm text-indigo-100/80 leading-relaxed max-w-xl">
                    Monitor perkembangan alumni yang melanjutkan pendidikan tinggi. Kelola data alumni, tracer study, dan laporan statistik.
                </p>
            </div>
            <a href="{{ route('bk.alumni-kuliah.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white text-sm font-semibold transition-all duration-200 whitespace-nowrap border border-white/10">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Kelola Alumni
            </a>
        </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5 md:gap-5 mb-6">
        {{-- Total Alumni --}}
        <div class="metric-card card-alumni border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-1">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Kuliah</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 anim-count" data-count="{{ $totalAlumniKuliah }}">{{ $totalAlumniKuliah }}</h4>
                </div>
                <div class="metric-icon icon-alumni">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25S6.5 28 12 28s10-4.745 10-10.75S17.5 6.253 12 6.253z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Total Universitas --}}
        <div class="metric-card card-univ border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Universitas</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 anim-count" data-count="{{ $totalUniversitas }}">{{ $totalUniversitas }}</h4>
                </div>
                <div class="metric-icon icon-univ">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>

        {{-- Alumni Aktif --}}
        <div class="metric-card card-aktif border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aktif</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-purple-600 dark:text-purple-400 anim-count" data-count="{{ $totalAktif }}">{{ $totalAktif }}</h4>
                </div>
                <div class="metric-icon icon-aktif">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Alumni Lulus --}}
        <div class="metric-card card-lulus border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-4">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lulus</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400 anim-count" data-count="{{ $totalLulus }}">{{ $totalLulus }}</h4>
                </div>
                <div class="metric-icon icon-lulus">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Alumni Cuti --}}
        <div class="metric-card card-cuti border border-gray-200/80 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-pop delay-5">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cuti</span>
                    <h4 class="mt-2 text-2xl font-extrabold text-red-600 dark:text-red-400 anim-count" data-count="{{ $totalCuti }}">{{ $totalCuti }}</h4>
                </div>
                <div class="metric-icon icon-cuti">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M6 15v.01M18 15v.01"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- INSIGHT PANEL --}}
    @if(count($insights) > 0)
        <div class="rounded-2xl border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm mb-6 anim-scale-blur delay-5">
            <div class="flex items-center gap-2.5 mb-4">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0114 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </span>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Insight Otomatis</h3>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500">Analisis cerdas berdasarkan data alumni terkini</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($insights as $index => $insight)
                    <div class="insight-card border border-gray-100 dark:border-gray-800 bg-gray-50/60 dark:bg-white/[0.02] flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-md bg-indigo-100 dark:bg-indigo-500/15 text-indigo-600 dark:text-indigo-400 font-bold text-[10px] mt-0.5">
                            {{ $index + 1 }}
                        </span>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                            {!! $insight !!}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- CHARTS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        {{-- Chart 1: Status Alumni (Donut) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-6">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #10b981, #34d399)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Proporsi Status Alumni</h3>
            </div>
            <div class="flex justify-center">
                <div id="statusChart" class="w-full max-w-[320px]"></div>
            </div>
        </div>

        {{-- Chart 2: Universitas (Bar) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-6">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #3b82f6, #60a5fa)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Alumni per Universitas</h3>
            </div>
            <div id="universChart" class="w-full"></div>
        </div>

        {{-- Chart 3: Tren Alumni (Area) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-7">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #8b5cf6, #c084fc)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Tren Alumni per Tahun Lulus</h3>
            </div>
            <div id="trendChart" class="w-full"></div>
        </div>

        {{-- Chart 4: Program Studi (Bar) --}}
        <div class="chart-panel border border-gray-200/80 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-sm anim-scale-blur delay-7">
            <div class="chart-panel-header">
                <span class="chart-panel-dot" style="background: linear-gradient(135deg, #f59e0b, #fbbf24)"></span>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">Program Studi Terbanyak</h3>
            </div>
            <div id="programChart" class="w-full"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridBorder = isDark ? '#1e293b' : '#f1f5f9';
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const fontFamily = 'Outfit, Inter, system-ui, sans-serif';

    // ──── 1. Status Donut Chart ────
    new ApexCharts(document.querySelector("#statusChart"), {
        chart: {
            type: 'donut',
            height: 300,
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: { enabled: true, delay: 150 }
            }
        },
        series: @json($statusChartData['series']),
        labels: @json($statusChartData['labels']),
        colors: ['#8b5cf6', '#f59e0b', '#ef4444'],
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

    // ──── 2. Universitas Bar Chart ────
    const universData = @json($universChartData['labels']).map((label, index) => {
        return { x: label, y: @json($universChartData['series'])[index] };
    });
    const barColors = ['#3b82f6', '#06b6d4', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444', '#ec4899', '#14b8a6'];
    new ApexCharts(document.querySelector("#universChart"), {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 600,
                animateGradually: { enabled: true, delay: 80 }
            }
        },
        series: [{
            name: 'Jumlah Alumni',
            data: universData
        }],
        xaxis: {
            type: 'numeric',
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: labelColor, fontSize: '11px' },
                formatter: v => Math.floor(v)
            }
        },
        yaxis: {
            labels: {
                maxWidth: 160,
                style: { colors: labelColor, fontSize: '11px', fontWeight: 500 }
            }
        },
        colors: barColors,
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 6,
                barHeight: '60%',
                distributed: true
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
            formatter: v => v,
            style: { fontSize: '10px', fontWeight: 600 },
            offsetX: 4
        }
    }).render();

    // ──── 3. Tren Area Chart ────
    new ApexCharts(document.querySelector("#trendChart"), {
        chart: {
            type: 'area',
            height: 280,
            toolbar: { show: false },
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                dynamicAnimation: { speed: 500 }
            }
        },
        series: [{
            name: 'Alumni Lulus',
            data: @json($trendChartData['series'])
        }],
        xaxis: {
            categories: @json($trendChartData['labels']),
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
        stroke: {
            curve: 'smooth',
            width: 3
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                type: 'vertical',
                opacityFrom: 0.4,
                opacityTo: 0.02,
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

    // ──── 4. Program Studi Bar ────
    const programData = @json($programChartData['labels']).map((label, index) => {
        return { x: label, y: @json($programChartData['series'])[index] };
    });
    new ApexCharts(document.querySelector("#programChart"), {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: fontFamily,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 600,
                animateGradually: { enabled: true, delay: 80 }
            }
        },
        series: [{
            name: 'Jumlah Alumni',
            data: programData
        }],
        xaxis: {
            type: 'numeric',
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: labelColor, fontSize: '11px' },
                formatter: v => Math.floor(v)
            }
        },
        yaxis: {
            labels: {
                maxWidth: 180,
                style: { colors: labelColor, fontSize: '11px', fontWeight: 500 }
            }
        },
        colors: ['#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#fef3c7', '#fef08a'],
        plotOptions: {
            bar: {
                horizontal: true,
                borderRadius: 6,
                barHeight: '65%',
                distributed: true
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
            formatter: v => v,
            style: { fontSize: '10px', fontWeight: 600 },
            offsetX: 4
        }
    }).render();

    // ──── Animated Counter ────
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
