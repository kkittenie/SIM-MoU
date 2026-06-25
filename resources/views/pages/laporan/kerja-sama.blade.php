@extends('layouts.app', ['title' => 'Laporan Kerja Sama'])

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
        <x-common.page-breadcrumb pageTitle="Laporan Kerja Sama" />
    </div>

    <!-- Header & Print Action -->
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between no-print">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white/90">
                Laporan Kerja Sama & MoU
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pantau metrik kerja sama sekolah dengan mitra dan cetak laporan resmi.
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Filter Tahun Mulai -->
            <form action="{{ route('laporan-kerja-sama.index') }}" method="GET" class="flex gap-2 items-center">
                <select name="year" onchange="this.form.submit()"
                    class="dark:bg-dark-900 h-10 rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:focus:border-brand-800">
                    <option value="">Semua Tahun Mulai</option>
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $year == $t ? 'selected' : '' }}>Tahun Mulai {{ $t }}</option>
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
        <h1 class="text-2xl font-extrabold uppercase">Laporan Kerja Sama dan MoU</h1>
        <h2 class="text-lg font-bold mt-1">SMK Negeri Sim-MoU</h2>
        @if($year)
            <p class="text-sm text-gray-600 mt-1">Tahun Mulai Kerja Sama: {{ $year }}</p>
        @else
            <p class="text-sm text-gray-600 mt-1">Semua Tahun Kerja Sama</p>
        @endif
        <p class="text-xs text-gray-400 mt-1">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
        <hr class="border-t-2 border-double border-black mt-4">
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 md:gap-5 mb-6 print:grid-cols-4 print:gap-4 print:mb-4">
        <!-- Total Kerja Sama -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Total Kerja Sama</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalKerjaSama }}</h4>
        </div>

        <!-- Kerja Sama Aktif -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Aktif</span>
            <h4 class="mt-2 text-2xl font-extrabold text-green-600 dark:text-green-400 print:text-black">{{ $totalAktif }}</h4>
        </div>

        <!-- Kerja Sama Akan Berakhir -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Akan Berakhir</span>
            <h4 class="mt-2 text-2xl font-extrabold text-yellow-600 dark:text-yellow-400 print:text-black">{{ $totalAkanBerakhir }}</h4>
        </div>

        <!-- Kerja Sama Berakhir -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Berakhir</span>
            <h4 class="mt-2 text-2xl font-extrabold text-red-600 dark:text-red-400 print:text-black">{{ $totalExpired }}</h4>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 print:grid-cols-3 print:gap-4 print:mb-4">
        <!-- 1. Kategori Mitra Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Kategori Mitra</h3>
            <div class="space-y-4">
                @forelse($kategoriStats as $row)
                    @php
                        $percentage = $totalKerjaSama > 0 ? ($row->total / $totalKerjaSama) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->jenis_mitra }}</span>
                            <span>{{ $row->total }} mitra ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-blue-600 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data kategori.</p>
                @endforelse
            </div>
        </div>

        <!-- 2. Penyebaran Status Kerja Sama -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Status Kerja Sama</h3>
            <div class="space-y-4">
                @foreach($statusStats as $statusKey => $count)
                    @php
                        $percentage = $totalKerjaSama > 0 ? ($count / $totalKerjaSama) * 100 : 0;
                        $colorClass = 'bg-emerald-500';
                        if ($statusKey === 'Akan Berakhir') {
                            $colorClass = 'bg-amber-500';
                        } elseif ($statusKey === 'Berakhir') {
                            $colorClass = 'bg-rose-500';
                        }
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $statusKey }}</span>
                            <span>{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="{{ $colorClass }} h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Tren Mulai Kerja Sama per Tahun -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Tren Pertumbuhan Kerja Sama</h3>
            <div class="space-y-4">
                @forelse($trenStats as $row)
                    @php
                        $maxTotal = $trenStats->max('total');
                        $percentage = $maxTotal > 0 ? ($row->total / $maxTotal) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>Tahun {{ $row->year }}</span>
                            <span>{{ $row->total }} kerja sama</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-indigo-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data tren tahunan.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Detailed Table of Cooperation -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 print:border-none print:p-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white/90 print:text-black print:text-sm">
                    Daftar Detail Kerja Sama & MoU
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 no-print">
                    Menampilkan daftar lengkap dokumen MoU beserta masa berlakunya.
                </p>
            </div>
            <!-- Year filter buttons -->
            <div class="flex flex-wrap gap-1.5 no-print" id="tahun-kerja-sama-filters">
                <button data-tahun="all" 
                    class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 bg-brand-500 text-white border-brand-500 shadow-theme-xs">
                    Semua
                </button>
                @foreach($tahunList as $t)
                    <button data-tahun="{{ $t }}" 
                        class="filter-btn px-3 py-1.5 text-xs font-semibold rounded-lg border transition-all duration-200 border-gray-200 dark:border-gray-800 text-gray-600 dark:text-gray-400 bg-transparent hover:bg-gray-50 dark:hover:bg-white/5">
                        {{ $t }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 print:text-black">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4">Nama Mitra</th>
                        <th class="px-6 py-4">Kategori/Jenis</th>
                        <th class="px-6 py-4">Nomor MoU</th>
                        <th class="px-6 py-4">PIC Mitra</th>
                        <th class="px-6 py-4">Masa Berlaku</th>
                        <th class="px-6 py-4">Tahun Mulai</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                    @forelse ($kerjaSamaList as $row)
                        <tr class="kerja-sama-row hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors print:hover:bg-transparent" data-tahun="{{ $row->tanggal_mulai ? $row->tanggal_mulai->format('Y') : '' }}">
                            <td class="px-6 py-3.5 whitespace-normal font-bold text-gray-800 dark:text-white/90 print:text-black print:font-semibold">
                                {{ $row->nama_mitra }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->jenis_mitra }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-normal text-xs font-medium">
                                {{ $row->nomor_mou }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-normal">{{ $row->pic }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap text-xs">
                                {{ $row->tanggal_mulai ? $row->tanggal_mulai->format('d/m/Y') : '-' }} s.d.
                                {{ $row->tanggal_berakhir ? $row->tanggal_berakhir->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black">
                                {{ $row->tanggal_mulai ? $row->tanggal_mulai->format('Y') : '-' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="no-print inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                    @if($row->status === 'Aktif') bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400
                                    @elseif($row->status === 'Akan Berakhir') bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400
                                    @else bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400
                                    @endif">
                                    {{ $row->status }}
                                </span>
                                <span class="hidden print:inline font-medium text-xs">
                                    {{ $row->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-kerja-sama-row">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data kerja sama ditemukan.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($kerjaSamaList) > 0)
                        <tr id="empty-kerja-sama-row" style="display: none;">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data kerja sama ditemukan untuk tahun yang dipilih.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('#tahun-kerja-sama-filters .filter-btn');
        const rows = document.querySelectorAll('.kerja-sama-row');
        const emptyRow = document.getElementById('empty-kerja-sama-row');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const selectedYear = this.getAttribute('data-tahun');

                // Update active button styling
                buttons.forEach(b => {
                    b.classList.remove('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                    b.classList.add('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');
                });

                this.classList.add('bg-brand-500', 'text-white', 'border-brand-500', 'shadow-theme-xs');
                this.classList.remove('border-gray-200', 'dark:border-gray-800', 'text-gray-600', 'dark:text-gray-400', 'bg-transparent', 'hover:bg-gray-50', 'dark:hover:bg-white/5');

                let shownCount = 0;

                rows.forEach(row => {
                    const rowYear = row.getAttribute('data-tahun');
                    if (selectedYear === 'all' || rowYear === selectedYear) {
                        row.style.display = '';
                        shownCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyRow) {
                    if (shownCount === 0) {
                        emptyRow.style.display = '';
                    } else {
                        emptyRow.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection
