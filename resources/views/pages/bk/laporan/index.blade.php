@extends('layouts.app', ['title' => 'Laporan BK'])

@push('styles')
<style>
    @media print {
        body {
            background-color: #fff !important;
            color: #000 !important;
        }
        #sidebar, #header, .no-print, .breadcrumb, form {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .print-card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            padding: 0 !important;
        }
        table {
            border-collapse: collapse !important;
            width: 100% !important;
        }
        th, td {
            border: 1px solid #ddd !important;
            padding: 8px !important;
            font-size: 11px !important;
        }
        th {
            background-color: #f3f4f6 !important;
            color: #000 !important;
        }
    }
</style>
@endpush

@section('content')
    <div class="breadcrumb no-print">
        <x-common.page-breadcrumb pageTitle="Laporan BK" />
    </div>

    <!-- Header & Cetak Action -->
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between no-print">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white/90">
                Laporan Bimbingan Konseling (BK)
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pantau metrik alumni kuliah, wirausaha, dan tracer study.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <!-- Filter Tahun Lulus -->
            <form action="{{ route('bk.laporan.index') }}" method="GET" class="flex gap-2 items-center">
                <select name="tahun_lulus" onchange="this.form.submit()"
                    class="dark:bg-dark-900 h-10 rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                    <option value="">Semua Tahun Lulus</option>
                    @foreach($tahunLulusList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunLulus == $tahun ? 'selected' : '' }}>Tahun Lulus {{ $tahun }}</option>
                    @endforeach
                </select>
            </form>

            <!-- Cetak Button -->
            <button onclick="window.print()"
                class="bg-brand-500 hover:bg-brand-600 shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Print-only Title Header -->
    <div class="hidden print:block text-center mb-6">
        <h1 class="text-2xl font-extrabold uppercase">Laporan Bimbingan Konseling (BK)</h1>
        <h2 class="text-lg font-bold mt-1">SMK Negeri Sim-MoU</h2>
        @if($tahunLulus)
            <p class="text-sm text-gray-600 mt-1">Tahun Lulus: {{ $tahunLulus }}</p>
        @else
            <p class="text-sm text-gray-600 mt-1">Semua Tahun Angkatan</p>
        @endif
        <p class="text-xs text-gray-400 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        <hr class="border-t-2 border-double border-black mt-4">
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-5 md:gap-5 mb-6 print:grid-cols-5 print:gap-4 print:mb-4">
        <!-- Alumni Kuliah -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Alumni Kuliah</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalAlumniKuliah }}</h4>
        </div>

        <!-- Alumni Wirausaha -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Alumni Wirausaha</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalAlumniWirausaha }}</h4>
        </div>

        <!-- Universitas -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Universitas</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalUniversitas }}</h4>
        </div>

        <!-- Program Studi -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Program Studi</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalProgramStudi }}</h4>
        </div>

        <!-- Tracer Study -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Tracer Study</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalTracerKuliah }}</h4>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6 print:grid-cols-4 print:gap-4 print:mb-4">
        <!-- 1. Universitas Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Universitas Terbanyak</h3>
            <div class="space-y-4">
                @forelse($universitasStats as $row)
                    @php
                        $percentage = $totalAlumniKuliah > 0 ? ($row->total / $totalAlumniKuliah) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->universitas->nama_universitas ?? 'N/A' }}</span>
                            <span>{{ $row->total }} alumni ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-blue-600 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data universitas.</p>
                @endforelse
            </div>
        </div>

        <!-- 2. Program Studi Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Program Studi Terbanyak</h3>
            <div class="space-y-4">
                @forelse($programStudiStats as $row)
                    @php
                        $percentage = $totalAlumniKuliah > 0 ? ($row->total / $totalAlumniKuliah) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->program_studi }}</span>
                            <span>{{ $row->total }} alumni ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-emerald-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data program studi.</p>
                @endforelse
            </div>
        </div>

        <!-- 3. Bidang Usaha Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Bidang Usaha Terbanyak</h3>
            <div class="space-y-4">
                @forelse($bidangUsahaStats as $row)
                    @php
                        $percentage = $totalAlumniWirausaha > 0 ? ($row->total / $totalAlumniWirausaha) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->bidang_usaha }}</span>
                            <span>{{ $row->total }} alumni ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-amber-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data bidang usaha.</p>
                @endforelse
            </div>
        </div>

        <!-- 4. Status Alumni Kuliah -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Status Alumni Kuliah</h3>
            <div class="space-y-4">
                @foreach($statusStats as $status => $count)
                    @php
                        $percentage = $totalAlumniKuliah > 0 ? ($count / $totalAlumniKuliah) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $status }}</span>
                            <span>{{ $count }} org ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-indigo-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Table of Alumni Kuliah -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 print:border-none print:p-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white/90 print:text-black print:text-sm">
                    Daftar Alumni Kuliah
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 no-print">
                    Menampilkan daftar detail alumni yang melanjutkan kuliah.
                </p>
            </div>
            <!-- Year filter buttons -->
            <div class="flex flex-wrap gap-1.5 no-print" id="tahun-alumni-filters">
                <button data-tahun="all"
                    class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 bg-brand-500 text-white border-brand-500 shadow-theme-xs">
                    Semua
                </button>
                @foreach($tahunLulusList as $tahun)
                    <button data-tahun="{{ $tahun }}"
                        class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 bg-transparent hover:bg-gray-50 dark:hover:bg-white/5">
                        {{ $tahun }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 print:text-black">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">NIS</th>
                        <th class="px-6 py-4">Universitas</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Tahun Lulus</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($alumniList as $row)
                        <tr class="alumni-row hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors print:hover:bg-transparent" data-tahun="{{ $row->tahun_lulus }}">
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black print:font-semibold">
                                {{ $row->nama_alumni }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->nis }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->universitas->nama_universitas ?? '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->program_studi }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black">{{ $row->tahun_lulus }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="no-print inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                    @if($row->status_alumni === 'aktif') bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400
                                    @elseif($row->status_alumni === 'lulus') bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400
                                    @elseif($row->status_alumni === 'cuti') bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400
                                    @else bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400
                                    @endif">
                                    {{ ucfirst($row->status_alumni) }}
                                </span>
                                <span class="hidden print:inline font-medium text-xs">
                                    {{ ucfirst($row->status_alumni) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-alumni-row">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni kuliah ditemukan.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($alumniList) > 0)
                        <tr id="empty-alumni-row" style="display: none;">
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni kuliah ditemukan untuk tahun yang dipilih.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Table of Alumni Wirausaha -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300 mt-6">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 print:border-none print:p-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white/90 print:text-black print:text-sm">
                    Daftar Alumni Wirausaha
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 no-print">
                    Menampilkan daftar detail alumni yang berwirausaha.
                </p>
            </div>
            <!-- Year filter buttons for wirausaha -->
            <div class="flex flex-wrap gap-1.5 no-print" id="tahun-wirausaha-filters">
                <button data-tahun="all"
                    class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 bg-brand-500 text-white border-brand-500 shadow-theme-xs">
                    Semua
                </button>
                @foreach($tahunLulusList as $tahun)
                    <button data-tahun="{{ $tahun }}"
                        class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 bg-transparent hover:bg-gray-50 dark:hover:bg-white/5">
                        {{ $tahun }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 print:text-black">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">Nama Usaha</th>
                        <th class="px-6 py-4">Bidang Usaha</th>
                        <th class="px-6 py-4">Lama Usaha</th>
                        <th class="px-6 py-4">Tahun Lulus</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($alumniWirausahaList as $row)
                        <tr class="wirausaha-row hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors print:hover:bg-transparent" data-tahun="{{ $row->tahun_lulus }}">
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black print:font-semibold">
                                {{ $row->nama_alumni }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->nama_usaha }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->bidang_usaha }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->lama_usaha }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black">{{ $row->tahun_lulus }}</td>
                        </tr>
                    @empty
                        <tr id="empty-wirausaha-row">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni wirausaha ditemukan.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($alumniWirausahaList) > 0)
                        <tr id="empty-wirausaha-row" style="display: none;">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni wirausaha ditemukan untuk tahun yang dipilih.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tracer Study Section (jika ada data) -->
    @if(count($tracerList) > 0)
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300 mt-6">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 print:border-none print:p-2">
            <h3 class="text-base font-bold text-gray-800 dark:text-white/90 print:text-black print:text-sm">
                Respon Tracer Study Terbaru
            </h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 no-print">
                Menampilkan {{ count($tracerList) }} respon tracer study terbaru.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 print:text-black">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4">Alumni</th>
                        <th class="px-6 py-4">Status Kuliah</th>
                        <th class="px-6 py-4">Kampus Tujuan</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4">Tanggal Respon</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @foreach($tracerList as $tracer)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors print:hover:bg-transparent">
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black print:font-semibold">
                                {{ $tracer->alumniKuliah?->nama_alumni ?? '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="no-print inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                    @if($tracer->status_kuliah === 'Masih Kuliah') bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400
                                    @elseif($tracer->status_kuliah === 'Lulus') bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400
                                    @else bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400
                                    @endif">
                                    {{ $tracer->status_kuliah }}
                                </span>
                                <span class="hidden print:inline font-medium text-xs">{{ $tracer->status_kuliah }}</span>
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $tracer->kampus_tujuan ?? '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $tracer->program_studi ?? '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $tracer->created_at->format('d M Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Alumni Kuliah Filters
        const alumniButtons = document.querySelectorAll('#tahun-alumni-filters .filter-btn');
        const alumniRows = document.querySelectorAll('.alumni-row');
        const emptyAlumniRow = document.getElementById('empty-alumni-row');

        alumniButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const selectedYear = this.getAttribute('data-tahun');

                alumniButtons.forEach(b => {
                    b.classList.remove('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                    b.classList.add('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');
                });

                this.classList.add('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                this.classList.remove('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');

                let shownCount = 0;
                alumniRows.forEach(row => {
                    const rowYear = row.getAttribute('data-tahun');
                    if (selectedYear === 'all' || rowYear === selectedYear) {
                        row.style.display = '';
                        shownCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyAlumniRow) {
                    emptyAlumniRow.style.display = shownCount === 0 ? '' : 'none';
                }
            });
        });

        // Wirausaha Filters
        const wirausahaButtons = document.querySelectorAll('#tahun-wirausaha-filters .filter-btn');
        const wirausahaRows = document.querySelectorAll('.wirausaha-row');
        const emptyWirausahaRow = document.getElementById('empty-wirausaha-row');

        wirausahaButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const selectedYear = this.getAttribute('data-tahun');

                wirausahaButtons.forEach(b => {
                    b.classList.remove('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                    b.classList.add('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');
                });

                this.classList.add('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                this.classList.remove('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');

                let shownCount = 0;
                wirausahaRows.forEach(row => {
                    const rowYear = row.getAttribute('data-tahun');
                    if (selectedYear === 'all' || rowYear === selectedYear) {
                        row.style.display = '';
                        shownCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyWirausahaRow) {
                    emptyWirausahaRow.style.display = shownCount === 0 ? '' : 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection
