@extends('layouts.app', ['title' => 'Detail Alumni Wirausaha'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Alumni Wirausaha" />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="flex items-center gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
            <!-- Icon/Avatar -->
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.5a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75h-3.5a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
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
            <!-- Grid 1: Nama Alumni & Nama Usaha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama Alumni</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">{{ $alumni->nama_alumni }}</span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nama Usaha</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">{{ $alumni->nama_usaha }}</span>
                </div>
            </div>

            <!-- Grid 2: Bidang Usaha & Lama Usaha -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Bidang Usaha</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $alumni->bidang_usaha }}</span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Lama Usaha</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $alumni->lama_usaha }}</span>
                </div>
            </div>

            <!-- Grid 3: Tahun Lulus -->
            <div>
                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tahun Lulus</span>
                <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-bold">{{ $alumni->tahun_lulus }}</span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-3">
            <a href="{{ route('bk.alumni-wirausaha.edit', $alumni->id) }}"
                class="flex-1 bg-yellow-500 hover:bg-yellow-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                Ubah Data
            </a>
            <a href="{{ route('bk.alumni-wirausaha.index') }}"
                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition">
                Kembali
            </a>
        </div>
    </div>
@endsection
