@extends('layouts.app', ['title' => 'Detail Alumni Bekerja'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Alumni Bekerja" />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="flex items-center gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
            <!-- Icon/Avatar -->
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">
                    {{ $alumni->nama_alumni }}
                </h3>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-0.5">
                    Alumni Lulusan Tahun <span class="font-bold text-gray-800 dark:text-white/80">{{ $alumni->tahun_lulus }}</span>
                </p>
            </div>
        </div>

        <div class="py-6 space-y-5">
            <!-- Grid 1: Perusahaan & Industri -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Perusahaan</span>
                    <div class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        @if($alumni->perusahaanMitra)
                            <a href="{{ route('perusahaan-mitra.show', $alumni->perusahaan_mitra_id) }}" class="text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
                                {{ $alumni->nama_perusahaan }}
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4"/></svg>
                            </a>
                        @else
                            {{ $alumni->nama_perusahaan }}
                            <span class="ml-1 text-[10px] font-normal text-gray-400 italic">(Non-Mitra)</span>
                        @endif
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Bidang Industri</span>
                    <span class="mt-1 inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $alumni->bidang_industri }}
                    </span>
                </div>
            </div>

            <!-- Grid 2: Jabatan & Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Jabatan / Posisi</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                        {{ $alumni->jabatan }}
                    </span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status Pekerjaan</span>
                    <span class="block mt-1">
                        @if($alumni->status_pekerjaan === 'Tetap')
                            <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                                Tetap
                            </span>
                        @elseif($alumni->status_pekerjaan === 'Kontrak')
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400">
                                Kontrak
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-gray-50 px-2.5 py-0.5 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400">
                                Magang / Apprentice
                            </span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Grid 3: Tanggal Masuk & Estimasi Gaji -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tanggal Mulai Kerja</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                        {{ $alumni->tanggal_masuk->format('d M Y') }}
                        <span class="text-xs text-gray-400 font-normal">
                            ({{ \Carbon\Carbon::parse($alumni->tanggal_masuk)->diffForHumans() }})
                        </span>
                    </span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Gaji Bulanan</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-semibold">
                        @if($alumni->gaji)
                            Rp {{ number_format($alumni->gaji, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-3">
            <a href="{{ route('alumni-bekerja.edit', $alumni->id) }}"
                class="flex-1 bg-yellow-500 hover:bg-yellow-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                Ubah Data
            </a>
            <a href="{{ route('alumni-bekerja.index') }}"
                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition">
                Kembali
            </a>
        </div>
    </div>
@endsection
