@extends('layouts.app', ['title' => 'Detail Perusahaan Mitra'])

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Perusahaan Mitra" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profil Utama Perusahaan -->
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="flex flex-col items-center text-center pb-6 border-b border-gray-100 dark:border-gray-800">
                    <!-- Avatar Placeholder or Logo -->
                    <div class="w-20 h-20 rounded-2xl bg-sky-100 dark:bg-sky-500/10 flex items-center justify-center text-sky-600 dark:text-sky-400 mb-4">
                        <svg class="h-10 w-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="10" width="20" height="11" rx="2"/><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white/90">
                        {{ $mitra->nama_perusahaan }}
                    </h3>
                    <span class="mt-1.5 inline-flex items-center rounded-md px-2.5 py-0.5 text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $mitra->bidang_industri }}
                    </span>
                </div>

                <div class="py-6 space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Alamat</span>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                            {{ $mitra->alamat }}
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email</span>
                            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium break-all">
                                {{ $mitra->email }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Telepon</span>
                            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                                {{ $mitra->nomor_telepon }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">PIC Kantor</span>
                            <span class="block mt-1 text-sm text-gray-600 dark:text-gray-300 font-medium">
                                {{ $mitra->pic }}
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status</span>
                            <span class="block mt-1">
                                @if ($mitra->status_aktif === 'Aktif')
                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>

                    @if($mitra->website)
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Website</span>
                            <a href="{{ $mitra->website }}" target="_blank" class="mt-1 inline-flex items-center gap-1 text-sm text-brand-600 hover:text-brand-800 dark:text-brand-400 dark:hover:text-brand-300 font-medium">
                                {{ $mitra->website }}
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    @endif

                    @if($mitra->deskripsi)
                        <div>
                            <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Deskripsi</span>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ $mitra->deskripsi }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center gap-2">
                    <a href="{{ route('perusahaan-mitra.edit', $mitra->id) }}"
                        class="flex-1 bg-yellow-500 hover:bg-yellow-600 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                        Ubah Data
                    </a>
                    <a href="{{ route('perusahaan-mitra.index') }}"
                        class="flex-1 border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Relasi Data (Alumni Bekerja & Lowongan Kerja) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- List Alumni Bekerja -->
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                        Alumni yang Bekerja di Sini ({{ count($mitra->alumniBekerja) }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">Nama Alumni</th>
                                <th class="px-6 py-3">Jabatan</th>
                                <th class="px-6 py-3">Tanggal Masuk</th>
                                <th class="px-6 py-3">Tahun Lulus</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                            @forelse ($mitra->alumniBekerja as $alumni)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                    <td class="px-6 py-3.5 whitespace-nowrap font-medium text-gray-800 dark:text-white/90">
                                        <a href="{{ route('alumni-bekerja.show', $alumni->id) }}" class="text-brand-600 hover:underline dark:text-brand-400">
                                            {{ $alumni->nama_alumni }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">{{ $alumni->jabatan }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">{{ $alumni->tanggal_masuk->format('d M Y') }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap font-semibold">{{ $alumni->tahun_lulus }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-850 dark:text-gray-250">
                                            {{ $alumni->status_pekerjaan }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500">
                                        Belum ada data alumni tersalurkan ke perusahaan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- List Lowongan Kerja -->
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-xs">
                <div class="p-5 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                        Lowongan Kerja dari Mitra Ini ({{ count($mitra->lowonganKerja) }})
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800 text-left text-sm text-gray-500 dark:text-gray-400">
                        <thead class="bg-gray-50 dark:bg-white/[0.02] text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            <tr>
                                <th class="px-6 py-3">Judul Lowongan</th>
                                <th class="px-6 py-3">Posisi</th>
                                <th class="px-6 py-3">Gaji</th>
                                <th class="px-6 py-3">Batas Akhir</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 bg-white dark:bg-transparent">
                            @forelse ($mitra->lowonganKerja as $loker)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.01] transition-colors">
                                    <td class="px-6 py-3.5 whitespace-nowrap font-medium text-gray-800 dark:text-white/90">
                                        <a href="{{ route('lowongan-kerja.show', $loker->id) }}" class="text-brand-600 hover:underline dark:text-brand-400">
                                            {{ $loker->judul }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">{{ $loker->posisi }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap text-xs">{{ $loker->gaji ?? '-' }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        {{ $loker->tanggal_tutup ? $loker->tanggal_tutup->format('d M Y') : 'Tidak ditentukan' }}
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        @if ($loker->status === 'Aktif')
                                            <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20">
                                                Tutup
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-400 dark:text-gray-500">
                                        Belum ada lowongan kerja dipublikasikan dari mitra ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
