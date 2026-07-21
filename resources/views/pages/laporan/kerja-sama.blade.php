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
            <!-- Ekspor Excel Button -->
            <a href="{{ route('laporan-kerja-sama.export-excel', request()->all()) }}" 
                class="bg-green-600 hover:bg-green-700 shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-white transition whitespace-nowrap">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Ekspor Excel
            </a>

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

    <!-- Filters Form -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs mb-6 no-print">
        <div class="p-5">
            <form action="{{ route('laporan-kerja-sama.index') }}" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <!-- Search Input -->
                <div class="relative">
                    <span class="absolute -translate-y-1/2 pointer-events-none left-4 top-1/2">
                        <svg class="fill-gray-400 dark:fill-gray-500" width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" />
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari mitra, PIC, MoU..."
                        class="dark:bg-dark-900 h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-800 dark:bg-white/3 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800" />
                </div>

                <!-- Jenis/Kategori Mitra -->
                <div>
                    <select name="jenis_mitra" onchange="this.form.submit()"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Kategori Mitra</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->nama }}" {{ $jenisMitra === $cat->nama ? 'selected' : '' }}>
                                {{ $cat->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tahun MoU -->
                <div>
                    <select name="year" onchange="this.form.submit()"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Tahun Mulai</option>
                        @foreach ($tahunList as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                Tahun Mulai {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Program Keahlian / Jurusan -->
                <div>
                    <select name="program_keahlian_id" onchange="this.form.submit()"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Jurusan</option>
                        @foreach ($programKeahlians as $pk)
                            <option value="{{ $pk->id }}" {{ $programKeahlianId == $pk->id ? 'selected' : '' }}>
                                {{ $pk->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <select name="status" onchange="this.form.submit()"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-3 py-2 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Semua Status MoU</option>
                        <option value="Aktif" {{ $status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Akan Berakhir" {{ $status === 'Akan Berakhir' ? 'selected' : '' }}>Akan Berakhir (<= 6 bulan)</option>
                        <option value="Berakhir" {{ $status === 'Berakhir' ? 'selected' : '' }}>Berakhir</option>
                    </select>
                </div>
            </form>
            
            @if ($search || $jenisMitra || $status || $year || $programKeahlianId)
                <div class="mt-4 flex justify-start">
                    <a href="{{ route('laporan-kerja-sama.index') }}"
                        class="bg-gray-100 hover:bg-gray-200 dark:bg-white/5 dark:hover:bg-white/10 h-10 text-gray-700 dark:text-white/95 rounded-lg px-4 py-2 text-sm font-medium transition inline-flex items-center justify-center whitespace-nowrap">
                        Reset Filter
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Print-only Title Header -->
    <div class="hidden print:block text-center mb-6">
        <h1 class="text-2xl font-extrabold uppercase">Laporan Kerja Sama dan MoU</h1>
        <h2 class="text-lg font-bold mt-1">SMK Negeri 1 Cirebon</h2>
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
        
        <!-- Pagination Links -->
        <div class="p-5 border-t border-gray-100 dark:border-gray-800 no-print">
            {{ $kerjaSamaList->links() }}
        </div>
    </div>
@endsection
