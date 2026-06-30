@extends('layouts.app', ['title' => 'Laporan BKK'])

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
        <x-common.page-breadcrumb pageTitle="Laporan BKK" />
    </div>

    <!-- Header & Print Action -->
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between no-print">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white/90">
                Laporan Bursa Kerja Khusus (BKK)
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Pantau metrik penyerapan alumni dan cetak laporan resmi.
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- Filter Tahun Lulus -->
            <form action="{{ route('bkk.laporan.index') }}" method="GET" class="flex gap-2 items-center">
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
        <h1 class="text-2xl font-extrabold uppercase">Laporan Bursa Kerja Khusus (BKK)</h1>
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
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 md:gap-5 mb-6 print:grid-cols-4 print:gap-4 print:mb-4">
        <!-- Alumni Bekerja -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Alumni Bekerja</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalAlumniBekerja }}</h4>
        </div>

        <!-- Perusahaan Mitra -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Perusahaan Mitra</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalMitra }}</h4>
        </div>

        <!-- Lowongan Kerja -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Lowongan Kerja</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalLoker }}</h4>
        </div>

        <!-- Lowongan Kerja -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider print:text-black">Perusahaan</span>
            <h4 class="mt-2 text-2xl font-extrabold text-gray-800 dark:text-white/90 print:text-black">{{ $totalMitra }}</h4>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 print:grid-cols-3 print:gap-4 print:mb-4">
        <!-- 1. Bidang Industri Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Sektor Industri Terpopuler</h3>
            <div class="space-y-4">
                @forelse($industriStats as $row)
                    @php
                        $percentage = $totalAlumniBekerja > 0 ? ($row->total / $totalAlumniBekerja) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->bidang_industri }}</span>
                            <span>{{ $row->total }} alumni ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-blue-600 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data industri.</p>
                @endforelse
            </div>
        </div>

        <!-- 2. Mitra Perekrut Terbanyak -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Mitra Perekrut Terbanyak</h3>
            <div class="space-y-4">
                @forelse($perusahaanStats as $row)
                    @php
                        $percentage = $totalAlumniBekerja > 0 ? ($row->total / $totalAlumniBekerja) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $row->perusahaan_nama }}</span>
                            <span>{{ $row->total }} alumni ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-emerald-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Belum ada data perekrut.</p>
                @endforelse
            </div>
        </div>

        <!-- 3. Status Pekerjaan Alumni -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
            <h3 class="text-sm font-bold text-gray-800 dark:text-white/90 mb-4 print:text-black">Status Pekerjaan Alumni</h3>
            <div class="space-y-4">
                @foreach($statusStats as $statusKey => $count)
                    @php
                        $percentage = $totalAlumniBekerja > 0 ? ($count / $totalAlumniBekerja) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1 print:text-black">
                            <span>{{ $statusKey }}</span>
                            <span>{{ $count }} org ({{ number_format($percentage, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 dark:bg-gray-800 print:bg-gray-200">
                            <div class="bg-amber-500 h-2 rounded-full print:bg-black" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Table of Working Alumni -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs print-card print:border print:border-gray-300">
        <div class="p-5 border-b border-gray-100 dark:border-gray-800 print:border-none print:p-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white/90 print:text-black print:text-sm">
                    Daftar Penyerapan Alumni Bekerja
                </h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 no-print">
                    Menampilkan daftar detail alumni yang telah terserap di dunia industri.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400 print:text-black">
                <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 print:bg-gray-100 print:text-black">
                    <tr>
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">Perusahaan</th>
                        <th class="px-6 py-4">Bidang Industri</th>
                        <th class="px-6 py-4">Jabatan</th>
                        <th class="px-6 py-4">Tanggal Masuk</th>
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
                            <td class="px-6 py-3.5 whitespace-nowrap font-semibold">
                                {{ $row->nama_perusahaan }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                {{ $row->bidang_industri }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-medium">{{ $row->jabatan }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap">{{ $row->tanggal_masuk->format('d M Y') }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap font-bold text-gray-800 dark:text-white/90 print:text-black">{{ $row->tahun_lulus }}</td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="no-print inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                                    @if($row->status_pekerjaan === 'Tetap') bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400
                                    @elseif($row->status_pekerjaan === 'Kontrak') bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400
                                    @else bg-gray-50 text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400
                                    @endif">
                                    {{ $row->status_pekerjaan }}
                                </span>
                                <span class="hidden print:inline font-medium text-xs">
                                    {{ $row->status_pekerjaan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-alumni-row">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni bekerja ditemukan.
                            </td>
                        </tr>
                    @endforelse

                    @if(count($alumniList) > 0)
                        <tr id="empty-alumni-row" style="display: none;">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400 dark:text-gray-500">
                                Tidak ada data alumni bekerja ditemukan untuk tahun yang dipilih.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="p-5 border-t border-gray-100 dark:border-gray-800 no-print">
            {{ $alumniList->links() }}
        </div>
    </div>
@endsection
