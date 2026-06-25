@extends('layouts.app', ['title' => 'Detail Alumni Kuliah'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Alumni Kuliah" />

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs max-w-2xl">
        <div class="flex items-center gap-4 pb-6 border-b border-gray-100 dark:border-gray-800">
            <!-- Icon/Avatar -->
            <div class="w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 6.253v13m0-13C6.228 6.253 2 10.541 2 15.877c0 5.336 4.228 9.624 10 9.624c5.772 0 10-4.288 10-9.624c0-5.336-4.228-9.624-10-9.624z"/>
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
            <!-- Grid 1: NIS & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">NIS</span>
                    <span class="block mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">{{ $alumni->nis }}</span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300">
                        @if($alumni->email_alumni)
                            <a href="mailto:{{ $alumni->email_alumni }}" class="text-brand-600 dark:text-brand-400 hover:underline">
                                {{ $alumni->email_alumni }}
                            </a>
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>

            <!-- Grid 2: Nomor Telepon & Tahun Lulus -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Nomor Telepon</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                        @if($alumni->nomor_telepon)
                            <a href="tel:{{ $alumni->nomor_telepon }}" class="text-brand-600 dark:text-brand-400 hover:underline">
                                {{ $alumni->nomor_telepon }}
                            </a>
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Tahun Lulus</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-bold">{{ $alumni->tahun_lulus }}</span>
                </div>
            </div>

            <!-- Grid 3: Universitas & Program Studi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Universitas</span>
                    <div class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        @if($alumni->universitas)
                            <a href="{{ route('bk.universitas.show', $alumni->universitas->id) }}" class="text-brand-600 dark:text-brand-400 hover:underline inline-flex items-center gap-1">
                                {{ $alumni->universitas->nama_universitas }}
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4"/></svg>
                            </a>
                        @else
                            -
                        @endif
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Program Studi</span>
                    <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">{{ $alumni->program_studi }}</span>
                </div>
            </div>

            <!-- Status -->
            <div>
                <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-2">Status Kuliah</span>
                <span class="inline-block">
                    @if($alumni->status_alumni === 'aktif')
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400">
                            Aktif
                        </span>
                    @elseif($alumni->status_alumni === 'lulus')
                        <span class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-sm font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400">
                            Lulus
                        </span>
                    @elseif($alumni->status_alumni === 'cuti')
                        <span class="inline-flex items-center rounded-md bg-yellow-50 px-3 py-1 text-sm font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400">
                            Cuti
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-md bg-gray-50 px-3 py-1 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-600/20 dark:bg-gray-500/10 dark:text-gray-400">
                            Belum Terdata
                        </span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-3">
            <a href="{{ route('bk.alumni-kuliah.edit', $alumni->id) }}"
                class="flex-1 bg-yellow-500 hover:bg-yellow-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                Ubah Data
            </a>
            <a href="{{ route('bk.alumni-kuliah.index') }}"
                class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition">
                Kembali
            </a>
        </div>
    </div>
@endsection
