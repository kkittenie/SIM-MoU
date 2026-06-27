@extends('layouts.app', ['title' => 'Dashboard BK'])

@push('styles')
<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }
    .anim-up { opacity: 0; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .anim-scale { opacity: 0; animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    .d-0 { animation-delay: 0ms; }
    .d-1 { animation-delay: 60ms; }
    .d-2 { animation-delay: 120ms; }
    .d-3 { animation-delay: 180ms; }
    .d-4 { animation-delay: 240ms; }
    .d-5 { animation-delay: 320ms; }
    .d-6 { animation-delay: 400ms; }
    .d-7 { animation-delay: 480ms; }

    .stat-card {
        position: relative; background: #ffffff; border: 1px solid #E4E7EC;
        border-radius: 14px; padding: 1.25rem 1.5rem; overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px -6px rgba(91,182,255,0.12); }
    .dark .stat-card { background: rgba(255,255,255,0.03); border-color: #1e293b; }
    .dark .stat-card:hover { box-shadow: 0 8px 24px -6px rgba(0,0,0,0.3); }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
    .stat-card.accent-blue::before   { background: #5BB6FF; }
    .stat-card.accent-green::before  { background: #22C55E; }
    .stat-card.accent-indigo::before { background: #6366f1; }
    .stat-card.accent-amber::before  { background: #F59E0B; }
    .stat-card.accent-red::before    { background: #EF4444; }

    .stat-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-icon.bg-blue   { background: #EAF5FF; color: #2278D8; }
    .stat-icon.bg-green  { background: #F0FDF4; color: #16A34A; }
    .stat-icon.bg-indigo { background: #EEF2FF; color: #4F46E5; }
    .stat-icon.bg-amber  { background: #FFFBEB; color: #D97706; }
    .stat-icon.bg-red    { background: #FEF2F2; color: #DC2626; }
    .dark .stat-icon.bg-blue   { background: rgba(91,182,255,0.12); color: #5BB6FF; }
    .dark .stat-icon.bg-green  { background: rgba(34,197,94,0.12); color: #4ADE80; }
    .dark .stat-icon.bg-indigo { background: rgba(99,102,241,0.12); color: #818CF8; }
    .dark .stat-icon.bg-amber  { background: rgba(245,158,11,0.12); color: #FBBF24; }
    .dark .stat-icon.bg-red    { background: rgba(239,68,68,0.12); color: #F87171; }

    .dash-panel {
        background: #ffffff; border: 1px solid #E4E7EC; border-radius: 14px;
        padding: 1.25rem; transition: box-shadow 0.25s ease;
    }
    .dash-panel:hover { box-shadow: 0 4px 16px -4px rgba(91,182,255,0.08); }
    .dark .dash-panel { background: rgba(255,255,255,0.03); border-color: #1e293b; }
    .dash-panel-title {
        font-size: 14px; font-weight: 600; color: #1E293B;
        display: flex; align-items: center; gap: 8px; margin-bottom: 16px;
    }
    .dark .dash-panel-title { color: #e2e8f0; }
    .dash-panel-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }

    .welcome-header {
        position: relative; border-radius: 14px; overflow: hidden;
        background: linear-gradient(135deg, #2278D8 0%, #399EF2 50%, #5BB6FF 100%);
    }
    .welcome-header::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 60%),
                    radial-gradient(ellipse at 20% 80%, rgba(255,255,255,0.06) 0%, transparent 50%);
        pointer-events: none;
    }
    .welcome-header-pattern {
        position: absolute; inset: 0;
        background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                          linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 32px 32px; pointer-events: none;
    }

    .insight-item {
        border-radius: 10px; padding: 0.875rem 1rem; background: #F8FBFF;
        border: 1px solid #E4E7EC; transition: transform 0.2s ease;
    }
    .insight-item:hover { transform: translateX(2px); }
    .dark .insight-item { background: rgba(255,255,255,0.02); border-color: #1e293b; }
</style>
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Dashboard BK" />

    {{-- WELCOME HEADER --}}
    <div class="welcome-header p-6 mb-6 anim-up d-0">
        <div class="welcome-header-pattern"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-white tracking-tight">
                    Selamat datang, {{ auth()->user()->name ?? 'BK User' }}
                </h1>
                <p class="mt-1.5 text-sm text-blue-100/80 leading-relaxed max-w-xl">
                    Monitor perkembangan alumni yang melanjutkan kuliah dan wirausaha. Kelola data alumni kuliah, alumni wirausaha, tracer study, dan laporan statistik.
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('bk.alumni-kuliah.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white text-sm font-semibold transition-all duration-200 whitespace-nowrap border border-white/10">
                    Data Kuliah
                </a>
                <a href="{{ route('bk.alumni-wirausaha.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur-sm text-white text-sm font-semibold transition-all duration-200 whitespace-nowrap border border-white/10">
                    Data Wirausaha
                </a>
            </div>
        </div>
    </div>

    {{-- METRIC CARDS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-5 mb-6">
        <div class="stat-card accent-blue anim-scale d-1">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Kuliah</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90 tabular-nums" data-count="{{ $totalAlumniKuliah }}">{{ $totalAlumniKuliah }}</h4>
                </div>
                <div class="stat-icon bg-blue">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                </div>
            </div>
        </div>
        <div class="stat-card accent-amber anim-scale d-2">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alumni Wirausaha</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90 tabular-nums" data-count="{{ $totalAlumniWirausaha }}">{{ $totalAlumniWirausaha }}</h4>
                </div>
                <div class="stat-icon bg-amber">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>
        <div class="stat-card accent-green anim-scale d-3">
            <div class="flex items-center justify-between">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Universitas Terdaftar</span>
                    <h4 class="mt-2 text-2xl font-bold text-gray-800 dark:text-white/90 tabular-nums" data-count="{{ $totalUniversitas }}">{{ $totalUniversitas }}</h4>
                </div>
                <div class="stat-icon bg-green">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- INSIGHTS --}}
    @if(count($insights) > 0)
        <div class="dash-panel mb-6 anim-up d-4">
            <div class="dash-panel-title">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-brand-500 text-white">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                </div>
                <div>
                    <span>Insight Otomatis</span>
                    <span class="text-[11px] font-normal text-gray-400 dark:text-gray-500 ml-2">Analisis data alumni</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($insights as $index => $insight)
                    <div class="insight-item flex items-start gap-3">
                        <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-md bg-brand-50 dark:bg-brand-500/15 text-brand-600 dark:text-brand-400 font-bold text-[10px] mt-0.5">{{ $index + 1 }}</span>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium">{!! $insight !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
        <div class="dash-panel anim-up d-5">
            <div class="dash-panel-title">
                <span class="dash-panel-dot" style="background: #22C55E"></span>
                Persentase Penyaluran BK (Kuliah vs Wirausaha)
            </div>
            <div class="flex justify-center"><div id="distribChart" class="w-full max-w-[320px]"></div></div>
        </div>
        <div class="dash-panel anim-up d-5">
            <div class="dash-panel-title">
                <span class="dash-panel-dot" style="background: #5BB6FF"></span>
                Kampus Tujuan Terbanyak
            </div>
            <div id="universChart" class="w-full"></div>
        </div>
        <div class="dash-panel anim-up d-6">
            <div class="dash-panel-title">
                <span class="dash-panel-dot" style="background: #F59E0B"></span>
                Bidang Usaha Terbanyak
            </div>
            <div id="wirausahaChart" class="w-full"></div>
        </div>
        <div class="dash-panel anim-up d-6">
            <div class="dash-panel-title">
                <span class="dash-panel-dot" style="background: #399EF2"></span>
                Tren Alumni Kuliah per Tahun Lulus
            </div>
            <div id="trendChart" class="w-full"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const gridBorder = isDark ? '#1e293b' : '#f1f5f9';
    const labelColor = isDark ? '#94a3b8' : '#64748b';
    const fontFamily = "'Plus Jakarta Sans', system-ui, sans-serif";
    const palette = ['#399EF2', '#F59E0B', '#22C55E', '#6366f1', '#EF4444', '#06b6d4', '#ec4899'];

    // 1. Donut Distribusi
    new ApexCharts(document.querySelector("#distribChart"), {
        chart: { type: 'donut', height: 300, fontFamily, animations: { enabled: true, easing: 'easeinout', speed: 800 } },
        series: @json($distribChartData['series']),
        labels: @json($distribChartData['labels']),
        colors: ['#399EF2', '#F59E0B'],
        legend: { position: 'bottom', fontSize: '12px', fontWeight: 500, labels: { colors: labelColor }, markers: { size: 6, offsetX: -3 } },
        stroke: { show: true, width: 3, colors: [isDark ? '#111827' : '#ffffff'] },
        plotOptions: { pie: { donut: { size: '72%', labels: { show: true, name: { fontSize: '13px', fontWeight: 600, color: labelColor }, value: { fontSize: '22px', fontWeight: 700, color: isDark ? '#e2e8f0' : '#1e293b' }, total: { show: true, label: 'Total Alumni', color: labelColor, fontSize: '12px', fontWeight: 500 } } } } },
        dataLabels: { enabled: false }
    }).render();

    // 2. Bar Kampus Tujuan Terbanyak
    const universData = @json($universChartData['labels']).map((label, index) => ({ x: label, y: @json($universChartData['series'])[index] }));
    new ApexCharts(document.querySelector("#universChart"), {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily },
        series: [{ name: 'Jumlah Alumni', data: universData }],
        xaxis: { type: 'numeric', axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: labelColor, fontSize: '11px' }, formatter: v => Math.floor(v) } },
        yaxis: { labels: { maxWidth: 160, style: { colors: labelColor, fontSize: '11px', fontWeight: 500 } } },
        colors: ['#399EF2'],
        plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '60%', distributed: true } },
        legend: { show: false },
        grid: { borderColor: gridBorder, strokeDashArray: 4 },
        dataLabels: { enabled: true, formatter: v => v, style: { fontSize: '10px', fontWeight: 600 }, offsetX: 4 }
    }).render();

    // 3. Bar Bidang Usaha Terbanyak
    const wirausahaData = @json($wirausahaChartData['labels']).map((label, index) => ({ x: label, y: @json($wirausahaChartData['series'])[index] }));
    new ApexCharts(document.querySelector("#wirausahaChart"), {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily },
        series: [{ name: 'Jumlah Alumni', data: wirausahaData }],
        xaxis: { type: 'numeric', axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: labelColor, fontSize: '11px' }, formatter: v => Math.floor(v) } },
        yaxis: { labels: { maxWidth: 160, style: { colors: labelColor, fontSize: '11px', fontWeight: 500 } } },
        colors: ['#F59E0B'],
        plotOptions: { bar: { horizontal: true, borderRadius: 6, barHeight: '60%', distributed: true } },
        legend: { show: false },
        grid: { borderColor: gridBorder, strokeDashArray: 4 },
        dataLabels: { enabled: true, formatter: v => v, style: { fontSize: '10px', fontWeight: 600 }, offsetX: 4 }
    }).render();

    // 4. Area Tren Alumni Kuliah
    new ApexCharts(document.querySelector("#trendChart"), {
        chart: { type: 'area', height: 280, toolbar: { show: false }, fontFamily },
        series: [{ name: 'Alumni Kuliah', data: @json($trendChartData['series']) }],
        xaxis: { categories: @json($trendChartData['labels']), axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { colors: labelColor, fontSize: '11px', fontWeight: 500 } } },
        yaxis: { labels: { style: { colors: labelColor, fontSize: '11px' }, formatter: v => Math.floor(v) } },
        colors: ['#399EF2'],
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, type: 'vertical', opacityFrom: 0.3, opacityTo: 0.02 } },
        markers: { size: 4, colors: ['#399EF2'], strokeWidth: 2 },
        grid: { borderColor: gridBorder, strokeDashArray: 4 }
    }).render();

    // Counter animations
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.getAttribute('data-count'), 10);
        if (isNaN(target) || target === 0) return;
        const duration = 1000, start = performance.now();
        const step = ts => {
            const progress = Math.min((ts - start) / duration, 1);
            el.textContent = Math.floor(progress * target);
            if (progress < 1) requestAnimationFrame(step); else el.textContent = target;
        };
        el.textContent = '0'; requestAnimationFrame(step);
    });
});
</script>
@endpush
